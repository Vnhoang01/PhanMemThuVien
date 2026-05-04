<?php

namespace App\Http\Controllers;

use App\Models\BookDetail;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\LoanSlipDetail;

class BookDetailController extends Controller
{
    public function index()
    {
        $bookDetails = BookDetail::with('book')->get();
        return view('book_details.index', compact('bookDetails'));
    }

    public function create()
    {
        $books = Book::all();
        return view('book_details.create', compact('books'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
        ]);

        DB::transaction(function () use ($request, &$barcode) {

            $book = Book::findOrFail($request->book_id);

            $last = BookDetail::where('book_id', $book->id)
                ->orderByDesc('id')
                ->first();

            $nextNumber = $last ? ((int)substr($last->barcode, -4)) + 1 : 1;

            $barcode = $book->book_code . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

            BookDetail::create([
                'book_id' => $book->id,
                'barcode' => $barcode,
                'status' => 'available'
            ]);

            $book->increment('total_quantity');
        });

        return back()->with('success', 'Thêm thành công: ' . $barcode);
    }


    public function edit($id)
    {
        $bookDetail = BookDetail::findOrFail($id);
        $books = Book::all();
        return view('book_details.edit', compact('bookDetail', 'books'));
    }

    public function updateStatus(Request $request)
    {
        $detail = BookDetail::findOrFail($request->id);

        if ($detail->loanSlipDetails()->where('status', 'borrowed')->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Sách đang được mượn'
            ]);
        }

        $detail->update(['status' => $request->status]);

        LoanSlipDetail::where('book_detail_id', $detail->id)
            ->update([
                'status' => $request->status,
                'fine_amount' => match ($request->status) {
                    'lost' => 100000,
                    'damaged' => 50000,
                    default => 0
                }
            ]);

        $book = $detail->book;

        $book->available_quantity = $book->bookDetails()
            ->where('status', 'available')
            ->count();

        $book->total_quantity = $book->bookDetails()->count();

        $book->status = $book->available_quantity > 0 ? 'available' : 'out_of_stock';

        $book->save();

        return response()->json([
            'success' => true
        ]);
    }

    // Xóa bản
    public function destroy($id)
    {
        $detail = BookDetail::findOrFail($id);

        //Không cho xoá nếu đang mượn
        if ($detail->status === 'borrowed') {
            return response()->json([
                'success' => false,
                'message' => 'Sách đang được mượn, không thể xoá!'
            ]);
        }

        $book = $detail->book;

        $detail->delete();

        //Chỉ giảm tổng sách
        $book->decrement('total_quantity');

        return response()->json([
            'success' => true,
            'message' => 'Xoá thành công!',
            'total_quantity' => $book->total_quantity,
            'available_quantity' => $book->available_quantity
        ]);
    }
}

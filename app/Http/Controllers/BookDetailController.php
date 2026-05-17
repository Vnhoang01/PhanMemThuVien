<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookDetail;
use App\Models\LoanSlipDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookDetailController extends Controller
{
    // =========================
    // LIST
    // =========================
    public function index()
    {
        $bookDetails = BookDetail::with('book')
            ->latest()
            ->get();

        return view(
            'book_details.index',
            compact('bookDetails')
        );
    }

    // =========================
    // CREATE
    // =========================
    public function create()
    {
        $books = Book::all();

        return view(
            'book_details.create',
            compact('books')
        );
    }

    // =========================
    // STORE
    // =========================
    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'quantity' => 'required|integer|min:1'
        ]);

        DB::transaction(function () use ($request, &$barcodes) {

            $book = Book::findOrFail($request->book_id);

            $barcodes = [];

            // Lấy số lượng hiện tại
            $currentCount = $book->details()->count();

            // Tạo nhiều bản vật lí
            for ($i = 1; $i <= $request->quantity; $i++) {

                $number = $currentCount + $i;

                $barcode =
                    $book->book_code .
                    '-' .
                    str_pad($number, 4, '0', STR_PAD_LEFT);

                BookDetail::create([
                    'book_id' => $book->id,
                    'barcode' => $barcode,
                    'status' => 'available'
                ]);

                $barcodes[] = $barcode;
            }

            // Đồng bộ số lượng
            $book->total_quantity =
                $book->details()->count();

            $book->available_quantity =
                $book->details()
                    ->where('status', 'available')
                    ->count();

            $book->status =
                $book->available_quantity > 0
                    ? 'available'
                    : 'out_of_stock';

            $book->save();
        });

        return back()->with(
            'success',
            'Thêm thành công: ' . implode(', ', $barcodes)
        );
    }

    // =========================
    // EDIT
    // =========================
    public function edit($id)
    {
        $bookDetail = BookDetail::findOrFail($id);

        $books = Book::all();

        return view(
            'book_details.edit',
            compact('bookDetail', 'books')
        );
    }

    // =========================
    // UPDATE STATUS AJAX
    // =========================
    public function updateStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:book_details,id',
            'status' => 'required|in:available,borrowed,damaged,lost'
        ]);

        $detail = BookDetail::findOrFail($request->id);

        // Không cho đổi khi đang mượn
        if (
            $detail->loanSlipDetails()
                ->where('status', 'borrowed')
                ->exists()
            &&
            $request->status != 'borrowed'
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Sách đang được mượn'
            ]);
        }

        // Update trạng thái sách
        $detail->update([
            'status' => $request->status
        ]);

        // Update phiếu mượn
        LoanSlipDetail::where(
            'book_detail_id',
            $detail->id
        )->update([
            'status' => $request->status,

            'fine_amount' => match ($request->status) {

                'lost' => 100000,

                'damaged' => 50000,

                default => 0
            }
        ]);

        // Đồng bộ book
        $book = $detail->book;

        $book->total_quantity =
            $book->details()->count();

        $book->available_quantity =
            $book->details()
                ->where('status', 'available')
                ->count();

        $book->status =
            $book->available_quantity > 0
                ? 'available'
                : 'out_of_stock';

        $book->save();

        return response()->json([
            'success' => true,

            'total_quantity' =>
                $book->total_quantity,

            'available_quantity' =>
                $book->available_quantity,

            'book_status' =>
                $book->status
        ]);
    }

    // =========================
    // DELETE
    // =========================
    public function destroy($id)
    {
        $detail = BookDetail::findOrFail($id);

        // Không cho xoá khi đang mượn
        if ($detail->status === 'borrowed') {

            return response()->json([
                'success' => false,
                'message' =>
                    'Sách đang được mượn, không thể xoá!'
            ]);
        }

        DB::transaction(function () use ($detail, &$book) {

            $book = $detail->book;

            $detail->delete();

            // Đồng bộ số lượng
            $book->total_quantity =
                $book->details()->count();

            $book->available_quantity =
                $book->details()
                    ->where('status', 'available')
                    ->count();

            $book->status =
                $book->available_quantity > 0
                    ? 'available'
                    : 'out_of_stock';

            $book->save();
        });

        return response()->json([
            'success' => true,

            'message' => 'Xoá thành công!',

            'total_quantity' =>
                $book->total_quantity,

            'available_quantity' =>
                $book->available_quantity
        ]);
    }

    // =========================
    // UNUSED
    // =========================
    public function show(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }
}

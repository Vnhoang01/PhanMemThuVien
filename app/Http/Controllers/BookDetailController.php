<?php

namespace App\Http\Controllers;

use App\Models\BookDetail;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookDetailController extends Controller
{
    public function index()
    {
        $bookDetails = BookDetail::with('book')
            ->where('status', 'available') // chỉ lấy sách còn
            ->get();
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

            // Lấy book trước
            $book = Book::findOrFail($request->book_id);

            // Tạo bản sao
            $bookDetail = BookDetail::create([
                'name' => $book->name,
                'status' => 'available',
                'book_id' => $request->book_id,
            ]);

            // Tự động tạo barcode
            $barcode = '#' . str_pad($bookDetail->id, 3, '0', STR_PAD_LEFT);

            $bookDetail->update([
                'barcode' => $barcode
            ]);

            // Cập nhập số lượng sách sau khi tạo bản sao
            $book->increment('total_quantity');
            $book->increment('available_quantity');
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
        $book = $detail->book;

        DB::transaction(function () use ($request, $detail, $book) {

            // 🔥 Nếu trạng thái cũ là available thì giảm
            if ($detail->status === 'available') {
                $book->decrement('available_quantity');
            }

            // 🔥 Nếu trạng thái mới là available thì tăng
            if ($request->status === 'available') {
                $book->increment('available_quantity');
            }

            // 🔥 Update status
            $detail->update([
                'status' => $request->status
            ]);
        });

        return response()->json([
            'success' => true,
            'available_quantity' => $book->available_quantity
        ]);
    }

    // Xóa bản
    public function destroyAjax($id)
    {
        $bookDetail = BookDetail::findOrFail($id);
        $book = $bookDetail->book;

        DB::transaction(function () use ($bookDetail, $book) {

            if ($bookDetail->status === 'available') {
                $book->decrement('available_quantity');
            }

            $book->decrement('total_quantity');

            $bookDetail->delete();
        });

        return response()->json([
            'success' => true,
            'available_quantity' => $book->available_quantity,
            'total_quantity' => $book->total_quantity
        ]);
    }
}

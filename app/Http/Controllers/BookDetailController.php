<?php

namespace App\Http\Controllers;

use App\Models\BookDetail;
use App\Models\Book;
use Illuminate\Http\Request;

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
            'barcode' => 'required|unique:book_details',
            'name' => 'required',
            'book_id' => 'required|exists:books,id',
        ]);

        $bookDetail = BookDetail::create([
            'barcode' => $request->barcode,
            'name' => $request->name,
            'status' => 'available',
            'book_id' => $request->book_id,
        ]);

        // 👉 cập nhật số lượng sách
        $book = Book::find($request->book_id);
        $book->increment('total_quantity');
        $book->increment('available_quantity');

        // 👉 nếu gọi từ AJAX (modal)
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $bookDetail
            ]);
        }

        return back()->with('success', 'Thêm thành công');
    }

    public function edit($id)
    {
        $bookDetail = BookDetail::findOrFail($id);
        $books = Book::all();
        return view('book_details.edit', compact('bookDetail', 'books'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'barcode' => 'required|unique:book_details,barcode,' . $id,
            'name' => 'required',
        ]);

        $bookDetail = BookDetail::findOrFail($id);

        $bookDetail->update([
            'barcode' => $request->barcode,
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return back()->with('success', 'Cập nhật thành công');
    }

    public function destroy($id)
    {
        $bookDetail = BookDetail::findOrFail($id);

        // ❗ cập nhật lại số lượng
        $book = $bookDetail->book;

        if ($bookDetail->status == 'available') {
            $book->decrement('available_quantity');
        }

        $book->decrement('total_quantity');

        $bookDetail->delete();

        return back()->with('success', 'Xoá thành công');
    }
}

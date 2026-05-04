<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookDetail;
use App\Models\Category;
use App\Models\Publisher;
use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookController extends Controller
{
    // =========================
    // LIST
    // =========================
    public function index()
    {
        $books = Book::withCount([
            'details',
            'details as available' => fn($q) => $q->where('status', 'available'),
            'details as borrowed' => fn($q) => $q->where('status', 'borrowed'),
            'details as damaged' => fn($q) => $q->where('status', 'damaged'),
            'details as lost' => fn($q) => $q->where('status', 'lost'),
        ])->get();

        return view('books.index', compact('books'));
    }

    // =========================
    // CREATE
    // =========================
    public function create()
    {
        $authors = Author::all();
        $categories = Category::all();
        $publishers = Publisher::all();

        return view('books.create', compact('authors', 'categories', 'publishers'));
    }

    // =========================
    // STORE
    // =========================
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'isbn' => [
                'nullable',
                'string',
                'max:20',
                'unique:books,isbn',
                'regex:/^[0-9\-Xx]+$/'
            ],
            'year_of_publication' => 'nullable|integer|min:1900|max:' . date('Y'),
            'author_id' => 'required|exists:authors,id',
            'category_id' => 'required|exists:categories,id',
            'publisher_id' => 'required|exists:publishers,id',
        ]);

        DB::transaction(function () use ($request) {

            $book = Book::create([
                'book_code' => $this->generateBookCode(),
                'isbn' => $this->normalizeIsbn($request->isbn),
                'name' => $request->name,
                'year_of_publication' => $request->year_of_publication,
                'author_id' => $request->author_id,
                'category_id' => $request->category_id,
                'publisher_id' => $request->publisher_id,
                'status' => 'available',
                'description' => $request->description,
            ]);

            $total = $request->total_quantity ?? 0;

            for ($i = 0; $i < $total; $i++) {

                $barcode = $book->book_code . '-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT);

                BookDetail::create([
                    'book_id' => $book->id,
                    'barcode' => $barcode,
                    'status' => 'available'
                ]);
            }
        });

        return redirect()->route('books.index')->with('success', 'Thêm thành công');
    }

    // =========================
    // EDIT
    // =========================
    public function edit(Book $book)
    {
        $authors = Author::all();
        $categories = Category::all();
        $publishers = Publisher::all();

        return view('books.edit', compact('book', 'authors', 'categories', 'publishers'));
    }

    // =========================
    // UPDATE
    // =========================
    public function update(Request $request, Book $book)
    {
        $request->validate([
            'name' => 'required',
            'isbn' => [
                'nullable',
                'string',
                'max:20',
                'unique:books,isbn,' . $book->id,
                'regex:/^[0-9\-Xx]+$/'
            ],
            'year_of_publication' => 'nullable|integer|min:1900|max:' . date('Y'),
            'author_id' => 'required|exists:authors,id',
            'category_id' => 'required|exists:categories,id',
            'publisher_id' => 'required|exists:publishers,id',
            'description' => 'nullable'
        ]);

        $book->update([
            'isbn' => $this->normalizeIsbn($request->isbn),
            'name' => $request->name,
            'year_of_publication' => $request->year_of_publication,
            'author_id' => $request->author_id,
            'category_id' => $request->category_id,
            'publisher_id' => $request->publisher_id,
            'description' => $request->description,
        ]);

        return redirect()->route('books.index')->with('success', 'Cập nhật thành công');
    }

    // =========================
    // DELETE
    // =========================
    public function destroy(Book $book)
    {
        DB::transaction(function () use ($book) {
            $book->details()->delete();
            $book->delete();
        });

        return redirect()->route('books.index')->with('success', 'Xóa thành công');
    }

    // =========================
    // GENERATE BOOK CODE
    // =========================
    private function generateBookCode()
    {
        do {
            $code = 'BOOK-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (Book::where('book_code', $code)->exists());

        return $code;
    }

    // =========================
    // NORMALIZE ISBN
    // =========================
    private function normalizeIsbn($isbn)
    {
        return $isbn ? str_replace(['-', ' '], '', $isbn) : null;
    }
}

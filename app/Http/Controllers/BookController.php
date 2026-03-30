<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\Publisher;
use Illuminate\Http\Request;
use App\Models\Author;

class BookController extends Controller
{

    public function index()
    {
        $books = Book::with(['author','category','publisher'])->get();
        return view('books.index', compact('books'));
    }

    public function create()
    {
        $authors = Author::all();
        $categories = Category::all();
        $publishers = Publisher::all();

        return view('books.create', compact('authors', 'categories','publishers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'year_of_publication' => 'required|numeric',
            'total_quantity' => 'required|numeric',
            'available_quantity' => 'required|numeric',
            'category_id' => 'required',
            'publisher_id' => 'required'
        ]);

        Book::create($request->all());

        return redirect()->route('books.index')->with('success', 'Thêm thành công');
    }

    public function edit(Book $book)
    {
        $authors = Author::all();
        $categories = Category::all();
        $publishers = Publisher::all();

        return view('books.edit', compact(
            'book',
            'authors',
            'categories',
            'publishers'
        ));
    }

    public function update(Request $request, Book $book)
    {
        $request->validate([
            'name' => 'required',
            'year_of_publication' => 'required|numeric',
            'total_quantity' => 'required|numeric',
            'available_quantity' => 'required|numeric',
        ]);

        $book->update($request->all());

        return redirect()->route('books.index')->with('success', 'Cập nhật thành công');
    }

    public function destroy(Book $book)
    {
        $book->delete();
        return redirect()->route('books.index')->with('success', 'Xóa thành công');
    }
}

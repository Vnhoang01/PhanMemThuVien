<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{

    public function index(Request $request)
    {
        $query = Author::query();

        // Tìm kiếm
        if ($request->keyword) {
            $keyword = $request->keyword;

            $query->where('name', 'like', '%' . $keyword . '%');
        }

        // Phân trang
        $authors = $query
            ->orderBy('id', 'desc')
            ->paginate(3)
            ->withQueryString();

        return view('authors.index', compact('authors'));
    }

    public function create()
    {
        return view('authors.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);

        Author::create($request->all());
        return redirect()->route('authors.index')->with('success', 'Thêm thành công');
    }

    public function edit(Author $author)
    {
        return view('authors.edit', compact('author'));
    }

    public function update(Request $request, Author $author)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $author->update($request->all());
        return redirect()->route('authors.index')->with('success', 'Cập nhật thành công');
    }

    public function destroy(Author $author)
    {
        $author->delete();
        return redirect()->route('authors.index')->with('success', 'Xóa thành công');
    }
}

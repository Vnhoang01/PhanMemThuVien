<?php

namespace App\Http\Controllers;

use App\Models\Publisher;
use Illuminate\Http\Request;

class PublisherController extends Controller
{
    public function index(Request $request)
    {
        $query = Publisher::query();

        // Tìm kiếm
        if ($request->keyword) {
            $keyword = $request->keyword;

            $query->where('name', 'like', '%' . $keyword . '%');
        }

        // Phân trang
        $publishers = $query
            ->orderBy('id', 'desc')
            ->paginate(3)
            ->withQueryString();

        return view('publishers.index', compact('publishers'));
    }

    public function create()
    {
        return view('publishers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'address' => 'nullable|max:255',
            'phone_number' => 'nullable|max:20'
        ]);

        Publisher::create($request->all());

        return redirect()->route('publishers.index')->with('success', 'Thêm thành công');
    }

    public function edit(Publisher $publisher)
    {
        return view('publishers.edit', compact('publisher'));
    }

    public function update(Request $request, Publisher $publisher)
    {
        $request->validate([
            'name' => 'required|max:255',
            'address' => 'nullable|max:255',
            'phone_number' => 'nullable|max:20'
        ]);

        $publisher->update($request->all());

        return redirect()->route('publishers.index')->with('success', 'Cập nhật thành công');
    }

    public function destroy(Publisher $publisher)
    {
        $publisher->delete();

        return redirect()->route('publishers.index')->with('success', 'Xóa thành công');
    }
}

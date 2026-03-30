<?php

namespace App\Http\Controllers;

use App\Models\Major;
use Illuminate\Http\Request;

class MajorController extends Controller
{
    public function index()
    {
        $majors = Major::all();
        return view('majors.index', compact('majors'));
    }

    public function create()
    {
        return view('majors.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable'
        ]);

        Major::create($request->all());

        return redirect()->route('majors.index')->with('success', 'Thêm thành công');
    }

    public function edit(Major $major)
    {
        return view('majors.edit', compact('major'));
    }

    public function update(Request $request, Major $major)
    {
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable'
        ]);

        $major->update($request->all());

        return redirect()->route('majors.index')->with('success', 'Cập nhật thành công');
    }

    public function destroy(Major $major)
    {
        $major->delete();

        return redirect()->route('majors.index')->with('success', 'Xóa thành công');
    }
}

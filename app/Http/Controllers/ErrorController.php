<?php

namespace App\Http\Controllers;

use App\Models\Error;
use Illuminate\Http\Request;

class ErrorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $errors = Error::latest()->get();
        return view('errors.index', compact('errors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('errors.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:errors,name',
            'fine_amount' => 'required|numeric|min:0',
        ]);

        Error::create($request->all());

        return redirect()->route('errors.index')
            ->with('success', 'Thêm thành công');
    }

    /**
     * Display the specified resource.
     */
    public function show(Error $error)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Error $error)
    {
        return view('errors.edit', compact('error'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Error $error)
    {
        $request->validate([
            'name' => 'required|unique:errors,name,' . $error->id,
            'fine_amount' => 'required|numeric|min:0',
        ]);

        $error->update($request->all());

        return redirect()->route('errors.index')
            ->with('success', 'Cập nhật thành công');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Error $error)
    {
        $error->delete();

        return redirect()->route('errors.index')
            ->with('success', 'Xóa thành công');
    }
}

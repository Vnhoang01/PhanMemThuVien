<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\Major;
use Illuminate\Http\Request;

class ClassesController extends Controller
{

    public function index()
    {
        $classes = Classes::with('major')->get();
        return view('classes.index', compact('classes'));
    }

    public function create()
    {
        $majors = Major::all();
        return view('classes.create', compact('majors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'major_id' => 'required'
        ]);

        Classes::create([
            'name' => $request->name,
            'course_year' => $request->course_year,
            'major_id' => $request->major_id
        ]);

        return redirect()->route('classes.index');
    }

    public function edit(Classes $class)
    {
        $majors = Major::all();
        return view('classes.edit', compact('class','majors'));
    }

    public function update(Request $request, Classes $class)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $class->update([
            'name' => $request->name,
            'course_year' => $request->course_year,
            'major_id' => $request->major_id
        ]);

        return redirect()->route('classes.index');
    }

    public function destroy(Classes $class)
    {
        $class->delete();
        return redirect()->route('classes.index');
    }
}

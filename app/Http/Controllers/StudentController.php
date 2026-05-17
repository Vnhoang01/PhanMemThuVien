<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Student;
use App\Models\Classes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::with('class')->get();
        return view('students.index', compact('students'));
    }

    public function create()
    {
        $classes = Classes::all();
        return view('students.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:students,email',
            'password' => 'nullable|min:6',
            'class_id' => 'required',
            'date_of_birth' => [
                'required',
                'date',
                'after:1999-12-31', // sinh sau năm 1999
                'before_or_equal:' . now()->subYears(18)->format('Y-m-d'), // ≥ 18 tuổi
            ],
        ], [
            'date_of_birth.after' => 'Phải sinh sau năm 1999',
            'date_of_birth.before_or_equal' => 'Sinh viên phải đủ 18 tuổi',
        ]);

        // Tránh tạo mã trùng
        do {
            $code = $this->generateStudentCode($request->class_id);
        } while (Student::where('student_code', $code)->exists());

        Student::create([
            'student_code' => $this->generateStudentCode(), //  AUTO
            'name' => $request->name,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'class_id' => $request->class_id,
            'email' => $request->email,
            'password' => $request->password
                ? Hash::make($request->password)
                : Hash::make('123456'),
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'status' => $request->status ?? 'active',
        ]);

        return redirect()->route('students.index')->with('success', 'Thêm thành công');
    }

    public function edit(Student $student)
    {
        $classes = Classes::all();
        return view('students.edit', compact('student', 'classes'));
    }

    public function update(Request $request, Student $student)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email',
            'phone_number' => 'nullable|max:20'
        ]);

        $student->update($request->all());

        return redirect()->route('students.index')->with('success', 'Cập nhật thành công');
    }

    public function destroy(Student $student)
    {
        $student->delete();

        return redirect()->route('students.index')->with('success', 'Xóa thành công');
    }

    private function generateStudentCode()
    {
        $last = Student::orderBy('id', 'desc')->first();

        $number = $last ? $last->id + 1 : 1;

        return 'SV' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    public function resetPassword($id)
    {
        $student = Student::findOrFail($id);

        $student->update([
            'password' => Hash::make('123456')
        ]);

        return redirect()->route('students.index')
            ->with('success',
                'Đã reset mật khẩu cho: '
                . $student->student_code . ' - '
                . $student->name
            );
    }

}

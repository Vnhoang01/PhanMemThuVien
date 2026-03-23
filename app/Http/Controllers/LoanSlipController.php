<?php

namespace App\Http\Controllers;

use App\Models\LoanSlip;
use App\Models\LoanSlipDetail;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Student;
use App\Models\Book;

class LoanSlipController extends Controller
{
    public function index()
    {
        $loanSlips = LoanSlip::with('details.book','student','admin')->get();
        return view('loan_slips.index', compact('loanSlips'));
    }

    public function create()
    {
        $admins = Admin::all();
        $students = Student::all();
        $books = Book::all();

        return view('loan_slips.create', compact('admins','students','books'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'admin_id' => 'required',
            'student_id' => 'required',
            'books' => 'required|array'
        ]);

        $loan = LoanSlip::create([
            'admin_id' => $request->admin_id,
            'student_id' => $request->student_id,
            'start_date' => now(),
            'due_date' => now()->addDays(14),
            'status' => 'borrowed'
        ]);

        // 🔥 Lưu nhiều sách
        foreach ($request->books as $bookId) {
            LoanSlipDetail::create([
                'loan_slip_id' => $loan->id,
                'book_id' => $bookId
            ]);
        }

        return redirect()->route('loan_slips.index')
            ->with('success','Thêm phiếu mượn thành công');
    }



    public function edit(LoanSlip $loanSlip)
    {
        $loan = $loanSlip;
        $admins = Admin::all();
        $students = Student::all();
        $books = Book::all();

        return view('loan_slips.edit', compact('loan','loanSlip','admins','students','books'));
    }

    public function update(Request $request, LoanSlip $loanSlip)
    {
        $request->validate([
            'admin_id' => 'required',
            'student_id' => 'required',
            'books' => 'required|array'
        ]);

        $loanSlip->update([
            'admin_id' => $request->admin_id,
            'student_id' => $request->student_id,
        ]);

        $loanSlip->details()->delete();


        foreach ($request->books as $bookId) {
            LoanSlipDetail::create([
                'loan_slip_id' => $loanSlip->id,
                'book_id' => $bookId
            ]);
        }

        return redirect()->route('loan_slips.index')
            ->with('success','Cập nhật thành công');
    }

    public function destroy(LoanSlip $loanSlip)
    {
        $loanSlip->details()->delete();
        $loanSlip->delete();

        return redirect()->route('loan_slips.index')
            ->with('success','Xóa thành công');
    }
}

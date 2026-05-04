<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Classes;
use App\Models\Error;
use App\Models\LoanSlip;
use App\Models\LoanSlipDetail;
use App\Models\Student;
use App\Models\BookDetail;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LoanSlipController extends Controller
{
    public function index()
    {
        $loanSlips = LoanSlip::with(
            'details.bookDetail.book',
            'student.class.major',
            'admin'
        )->latest()->get();

        return view('loan_slips.index', compact('loanSlips'));
    }

    public function getBookDetails($bookId)
    {
        $details = BookDetail::with('book')
            ->where('book_id', $bookId)
            ->where('status', 'available')
            ->get();

        return response()->json($details);
    }

    public function create()
    {
        $students = Student::all();
        $books = Book::all();
        $classes = Classes::all();

        $bookDetails = BookDetail::with('book')
            ->where('status', 'available')
            ->get();

        return view('loan_slips.create', compact(
            'students',
            'bookDetails',
            'books',
            'classes'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required',
            'book_details' => 'required|array',
        ]);

        // ✔ chỉ check đang mượn (borrowing)
        $hasBorrowing = LoanSlip::where('student_id', $request->student_id)
            ->where('status', 'borrowing')
            ->exists();

        if ($hasBorrowing) {
            return back()->with('error', 'Sinh viên đang có phiếu mượn chưa trả!');
        }

        DB::transaction(function () use ($request) {

            $loanSlip = LoanSlip::create([
                'admin_id' => Auth::id(),
                'student_id' => $request->student_id,
                'start_date' => now(),
                'due_date' => now()->addDays(14),
                'status' => 'borrowing',
                'total_quantity' => 0,
                'total_fine' => 0
            ]);

            $total = 0;

            foreach ($request->book_details as $id) {

                $detail = BookDetail::findOrFail($id);

                if ($detail->status != 'available') {
                    throw new \Exception('Sách không khả dụng');
                }

                LoanSlipDetail::create([
                    'loan_slip_id' => $loanSlip->id,
                    'book_detail_id' => $id,
                    'status' => 'borrowing'
                ]);

                $detail->update(['status' => 'borrowing']);

                $total++;
            }

            $loanSlip->update([
                'total_quantity' => $total
            ]);
        });

        return redirect()->route('loan_slips.index')
            ->with('success', 'Thêm thành công');
    }

    public function edit(LoanSlip $loanSlip)
    {
        $admins = Admin::all();
        $students = Student::all();
        $bookDetails = BookDetail::with('book')->get();

        return view('loan_slips.edit', compact(
            'loanSlip',
            'students',
            'bookDetails',
            'admins'
        ));
    }

    public function update(Request $request, LoanSlip $loanSlip)
    {
        $request->validate([
            'student_id' => 'required',
            'book_details' => 'required|array',
        ]);

        DB::transaction(function () use ($request, $loanSlip) {

            $old = $loanSlip->details()->pluck('book_detail_id')->toArray();
            $new = $request->book_details;

            $loanSlip->update([
                'student_id' => $request->student_id,
                'start_date' => $request->start_date,
                'due_date' => $request->due_date,
            ]);

            // REMOVE
            $removed = array_diff($old, $new);

            foreach ($removed as $id) {
                $detail = BookDetail::find($id);
                if ($detail) {
                    $detail->update(['status' => 'available']);
                }
            }

            LoanSlipDetail::where('loan_slip_id', $loanSlip->id)
                ->whereIn('book_detail_id', $removed)
                ->delete();

            // ADD
            $added = array_diff($new, $old);

            foreach ($added as $id) {

                $detail = BookDetail::findOrFail($id);

                if ($detail->status != 'available') {
                    throw new \Exception('Sách không khả dụng');
                }

                LoanSlipDetail::create([
                    'loan_slip_id' => $loanSlip->id,
                    'book_detail_id' => $id,
                    'status' => 'borrowing'
                ]);

                $detail->update(['status' => 'borrowing']);
            }

            $loanSlip->update([
                'total_quantity' => count($new)
            ]);
        });

        return redirect()->route('loan_slips.index')
            ->with('success', 'Cập nhật thành công');
    }

    public function destroy(LoanSlip $loanSlip)
    {
        DB::transaction(function () use ($loanSlip) {

            foreach ($loanSlip->details as $detail) {

                if ($detail->status == 'borrowing') {
                    $detail->bookDetail->update(['status' => 'available']);
                }
            }

            $loanSlip->details()->delete();
            $loanSlip->delete();
        });

        return redirect()->route('loan_slips.index')
            ->with('success', 'Xóa thành công');
    }

    public function showReturn($id)
    {
        $loan = LoanSlip::with('details.bookDetail.book')->findOrFail($id);
        $errors = Error::all();

        return view('loan_slips.return', compact('loan', 'errors'));
    }

    public function returnAll(Request $request, $id)
    {
        $loan = LoanSlip::with('details.bookDetail')->findOrFail($id);

        DB::transaction(function () use ($loan, $request) {

            $totalFine = 0;

            foreach ($loan->details as $detail) {

                $detail->errors()->detach();

                $errorIds = $request->errors[$detail->id] ?? [];

                foreach ($errorIds as $errorId) {

                    $error = Error::find($errorId);
                    if (!$error) continue;

                    $detail->errors()->attach($errorId, [
                        'fine_amount' => $error->fine_amount
                    ]);

                    $totalFine += $error->fine_amount;
                }

                $detail->update([
                    'status' => 'returned'
                ]);

                $detail->bookDetail->update([
                    'status' => 'available'
                ]);
            }

            $loan->update([
                'status' => 'returned',
                'return_date' => now(),
                'total_fine' => $totalFine
            ]);
        });

        return redirect()->route('loan_slips.index')
            ->with('success', 'Trả sách thành công!');
    }
}

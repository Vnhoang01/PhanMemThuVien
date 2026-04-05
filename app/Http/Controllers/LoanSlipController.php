<?php

namespace App\Http\Controllers;

use App\Models\LoanSlip;
use App\Models\LoanSlipDetail;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Student;
use App\Models\BookDetail;
use Illuminate\Support\Facades\DB;

class LoanSlipController extends Controller
{
    public function index()
    {
        $loanSlips = LoanSlip::with('details.bookDetail.book','student.class.major','admin')->get();
        return view('loan_slips.index', compact('loanSlips'));
    }

    public function create()
    {
        $admins = Admin::all();
        $students = Student::all();
        $bookDetails = BookDetail::with('book')
            ->where('status','available')
            ->get();

        return view('loan_slips.create', compact('admins','students','bookDetails'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'admin_id' => 'required',
            'student_id' => 'required',
            'book_details' => 'required|array',
            'book_details.*' => 'exists:book_details,id'
        ]);

        DB::transaction(function () use ($request) {

            $loan = LoanSlip::create([
                'admin_id' => $request->admin_id,
                'student_id' => $request->student_id,
                'start_date' => now(),
                'due_date' => now()->addDays(14),
                'status' => 'borrowed',
                'total_quantity' => 0,
                'total_fine' => 0
            ]);

            $totalQuantity = 0;

            foreach ($request->book_details as $detailId) {

                $detail = BookDetail::findOrFail($detailId);

                // tạo chi tiết phiếu mượn
                LoanSlipDetail::create([
                    'loan_slip_id' => $loan->id,
                    'book_detail_id' => $detailId,
                    'status' => 'borrowed'
                ]);

                // cập nhật trạng thái bản sách
                $detail->update(['status' => 'borrowed']);

                // trừ số lượng sách
                $detail->book->decrement('available_quantity');

                $totalQuantity++;
            }

            $loan->update([
                'total_quantity' => $totalQuantity
            ]);
        });

        return redirect()->route('loan_slips.index')
            ->with('success','Thêm thành công');
    }

    public function edit(LoanSlip $loanSlip)
    {
        $admins = Admin::all();
        $students = Student::all();
        $bookDetails = BookDetail::with('book')->get();

        return view('loan_slips.edit', compact(
            'loanSlip',
            'admins',
            'students',
            'bookDetails'
        ));
    }

    public function update(Request $request, LoanSlip $loanSlip)
    {
        $request->validate([
            'admin_id' => 'required',
            'student_id' => 'required',
            'book_details' => 'required|array',
            'book_details.*' => 'exists:book_details,id'
        ]);

        DB::transaction(function () use ($request, $loanSlip) {

            $loanSlip->update([
                'admin_id' => $request->admin_id,
                'student_id' => $request->student_id,
                'start_date' => $request->start_date,
                'due_date' => $request->due_date,
                'status' => $request->status,
                'return_date' => $request->status == 'returned'
                    ? ($request->return_date ?? now())
                    : null,
            ]);

            // danh sách cũ
            $oldDetails = $loanSlip->details()->pluck('book_detail_id')->toArray();

            // danh sách mới
            $newDetails = $request->book_details;

            // SÁCH BỊ BỎ → TRẢ LẠI
            $removed = array_diff($oldDetails, $newDetails);
            foreach ($removed as $detailId) {
                $detail = BookDetail::find($detailId);

                if ($detail) {
                    $detail->update(['status' => 'available']);
                    $detail->book->increment('available_quantity');
                }
            }

            //Xóa recond cũ trong DB
            LoanSlipDetail::where('loan_slip_id', $loanSlip->id)
                ->whereIn('book_detail_id', $removed)
                ->delete();

            // SÁCH MỚI THÊM
            $added = array_diff($newDetails, $oldDetails);
            foreach ($added as $detailId) {

                $detail = BookDetail::findOrFail($detailId);

                LoanSlipDetail::create([
                    'loan_slip_id' => $loanSlip->id,
                    'book_detail_id' => $detailId,
                    'status' => 'borrowed'
                ]);

                $detail->update(['status' => 'borrowed']);
                $detail->book->decrement('available_quantity');
            }

            // cập nhật tổng
            $loanSlip->update([
                'total_quantity' => count($newDetails)
            ]);
        });

        return redirect()->route('loan_slips.index')
            ->with('success','Cập nhật thành công');
    }

    public function destroy(LoanSlip $loanSlip)
    {
        foreach ($loanSlip->details as $detail) {

            if ($detail->status == 'borrowed') {
                $bookDetail = $detail->bookDetail;

                $bookDetail->update(['status' => 'available']);
                $bookDetail->book->increment('available_quantity');
            }
        }

        $loanSlip->details()->delete();
        $loanSlip->delete();

        return redirect()->route('loan_slips.index')
            ->with('success','Xóa thành công');
    }
}

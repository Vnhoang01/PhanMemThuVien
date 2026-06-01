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
    public function index(Request $request)
    {
        $query = LoanSlip::with(
            'details.bookDetail.book',
            'details.errors',
            'student.class.major',
            'admin'
        );

        // Tìm kiếm
        if ($request->keyword) {

            $keyword = $request->keyword;

            $query->where(function ($q) use ($keyword) {

                // Sinh viên
                $q->orWhereHas('student', function ($student) use ($keyword) {
                    $student->where('name', 'like', "%{$keyword}%")
                        ->orWhere('student_code', 'like', "%{$keyword}%");
                });

                // Người duyệt
                $q->orWhereHas('admin', function ($admin) use ($keyword) {
                    $admin->where('name', 'like', "%{$keyword}%");
                });
                // Trạng thái
                $statusMap = [
                    'đang duyệt'     => 'pending',
                    'đang mượn'      => 'borrowing',
                    'quá hạn'        => 'overdue',
                    'đã trả'         => 'returned',
                    'đã hủy duyệt'   => 'rejected',
                ];

                $status = $statusMap[mb_strtolower(trim($keyword), 'UTF-8')] ?? null;

                if ($status) {
                    $q->orWhere('status', $status);
                }
            });
        }

        $loanSlips = $query
            ->orderByRaw("
        CASE status
            WHEN 'pending' THEN 1
            WHEN 'borrowing' THEN 2
            WHEN 'overdue' THEN 3
            WHEN 'returned' THEN 4
            WHEN 'rejected' THEN 5
            ELSE 6
        END
    ")
            ->latest('created_at')
            ->paginate(3)
            ->withQueryString();

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

        $bookDetails = $request->book_details;

        $bookIds = BookDetail::whereIn('id', $bookDetails)
            ->pluck('book_id')
            ->toArray();

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

                $detail->update([
                    'status' => 'borrowed'
                ]);

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

                $detail->update([
                    'status' => 'borrowed'
                ]);
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
        // Không được xóa phiếu đang mượn
        if ($loanSlip->status != 'returned') {

            return redirect()->route('loan_slips.index')
                ->with('error', 'Không được xóa phiếu đang mượn!');
        }

        DB::transaction(function () use ($loanSlip) {

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

                $hasProblem = false;

                foreach ($errorIds as $errorId) {

                    $error = Error::find($errorId);

                    if (!$error) continue;

                    $detail->errors()->attach($errorId, [
                        'fine_amount' => $error->fine_amount
                    ]);

                    $totalFine += $error->fine_amount;

                    $hasProblem = true;

                    // MẤT SÁCH
                    if (str_contains(strtolower($error->name), 'mất')) {

                        $detail->bookDetail->update([
                            'status' => 'lost'
                        ]);
                    }

                    // HỎNG / RÁCH
                    elseif (
                        str_contains(strtolower($error->name), 'rách') ||
                        str_contains(strtolower($error->name), 'hỏng')
                    ) {

                        $detail->bookDetail->update([
                            'status' => 'damaged'
                        ]);
                    }
                }

                // KHÔNG CÓ LỖI
                if (!$hasProblem) {

                    $detail->bookDetail->update([
                        'status' => 'available'
                    ]);

                    $detail->update([
                        'status' => 'returned'
                    ]);
                }

                // CÓ LỖI
                else {

                    // nếu chỉ trễ hạn mà chưa đổi status
                    if ($detail->bookDetail->status == 'borrowed') {

                        $detail->bookDetail->update([
                            'status' => 'available'
                        ]);
                    }

                    $detail->update([
                        'status' => 'problem'
                    ]);
                }
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

    public function approve($id)
    {
        $loan = LoanSlip::with('details.bookDetail')->findOrFail($id);

        if ($loan->status !== 'pending') {
            return back()->with('error', 'Phiếu không hợp lệ để duyệt!');
        }

        DB::beginTransaction();

        foreach ($loan->details as $detail) {

            $bookDetail = BookDetail::where('id', $detail->book_detail_id)
                ->lockForUpdate()
                ->first();

            if ($bookDetail->status !== 'available') {
                DB::rollBack();
                return back()->with('error',
                    'Sách mã ' . $bookDetail->barcode . ' đã được người khác mượn!');
            }

            // lock sách lại
            $bookDetail->update([
                'status' => 'borrowed'
            ]);
        }

        $loan->update([
            'status' => 'borrowing',
            'admin_id' => auth()->id(),
        ]);

        DB::commit();

        return back()->with('success', 'Duyệt phiếu thành công!');
    }

    public function cancelApprove($id)
    {
        $loan = LoanSlip::findOrFail($id);

        // chỉ được hủy khi đang chờ duyệt
        if ($loan->status !== 'pending') {
            return back()->with('error', 'Chỉ có thể hủy khi phiếu đang chờ duyệt!');
        }

        $loan->update([
            'status' => 'rejected',
            'admin_id' => auth()->id()
        ]);

        return back()->with('success', 'Đã hủy duyệt phiếu mượn!');
    }
}

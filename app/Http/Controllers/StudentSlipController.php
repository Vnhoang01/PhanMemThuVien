<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookDetail;
use App\Models\LoanSlip;
use App\Models\LoanSlipDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StudentSlipController extends Controller
{
    // =========================
    // HIỂN THỊ PHIẾU MƯỢN
    // =========================
    public function index()
    {
        $slip = session()->get('borrow_slip', []);

        return view('login_student.slip', compact('slip'));
    }

    // =========================
    // THÊM SÁCH VÀO PHIẾU
    // id = book_detail_id
    // =========================
    public function add($id)
    {
        $detail = BookDetail::with([
            'book.author',
            'book.category'
        ])->findOrFail($id);

        // chỉ cho mượn sách available
        if ($detail->status != 'available') {

            return back()->with(
                'error',
                'Quyển sách này hiện không có sẵn!'
            );
        }

        $book = $detail->book;

        $slip = session()->get('borrow_slip', []);

        // kiểm tra đã tồn tại chưa
        if (isset($slip[$detail->id])) {

            return back()->with(
                'error',
                'Sách đã tồn tại trong phiếu mượn!'
            );
        }

        // lưu vào session
        $slip[$detail->id] = [

            // book detail
            'id' => $detail->id,

            // book
            'book_id' => $book->id,

            'book_code' => $book->book_code,

            'barcode' => $detail->barcode,

            'isbn' => $book->isbn,

            'name' => $book->name,

            'author' => $book->author?->name,

            'category' => $book->category?->name,

        ];

        session()->put('borrow_slip', $slip);

        return redirect()
            ->route('student.slip')
            ->with(
                'success',
                'Đã thêm sách vào phiếu mượn!'
            );
    }

    // =========================
    // XÓA 1 SÁCH
    // =========================
    public function remove($id)
    {
        $slip = session()->get('borrow_slip', []);

        unset($slip[$id]);

        session()->put('borrow_slip', $slip);

        return back()->with(
            'success',
            'Đã xóa sách khỏi phiếu mượn!'
        );
    }

    // =========================
    // XÓA TOÀN BỘ
    // =========================
    public function clear()
    {
        session()->forget('borrow_slip');

        return back()->with(
            'success',
            'Đã xóa toàn bộ phiếu mượn!'
        );
    }

    // =========================
    // GỬI PHIẾU MƯỢN
    // =========================
    public function submit(Request $request)
    {
        $student = Auth::guard('student')->user();

        $request->validate([

            'return_date' => [
                'required',
                'date',
                'after:today'
            ]

        ], [

            'return_date.required' => 'Vui lòng chọn ngày trả',
            'return_date.after' => 'Ngày trả phải sau hôm nay'

        ]);

        $slip = session()->get('borrow_slip', []);

        if (count($slip) <= 0) {

            return back()->with(
                'error',
                'Phiếu mượn đang trống!'
            );
        }

        // check sinh viên đang có phiếu chưa trả
        $exists = LoanSlip::where(
            'student_id',
            $student->id
        )
            ->whereIn('status', [
                'pending',
                'borrowing'
            ])
            ->exists();

        if ($exists) {

            return back()->with(
                'error',
                'Bạn đang có phiếu mượn chưa trả!'
            );
        }

        DB::transaction(function () use (
            $student,
            $request,
            $slip
        ) {

            // tạo phiếu mượn
            $loanSlip = LoanSlip::create([

                'student_id' => $student->id,

                'admin_id' => null,

                'start_date' => Carbon::now(),

                'due_date' => $request->return_date,

                'status' => 'pending',

                'total_fine' => 0

            ]);

            foreach ($slip as $item) {

                // lấy đúng book_detail
                $detail = BookDetail::where(
                    'id',
                    $item['id']
                )
                    ->where('status', 'available')
                    ->first();

                // nếu sách không còn available
                if (!$detail) {
                    continue;
                }

                // tạo chi tiết phiếu
                LoanSlipDetail::create([

                    'loan_slip_id' => $loanSlip->id,

                    'book_detail_id' => $detail->id,

                    'status' => 'borrowing'

                ]);

                // KHÔNG update status ở đây
                // admin duyệt mới đổi sang borrowing
            }
        });

        // xóa session
        session()->forget('borrow_slip');

        return redirect()
            ->route('student.slip')
            ->with(
                'success',
                'Đăng ký mượn sách thành công! Vui lòng chờ quản trị viên duyệt.'
            );
    }
}

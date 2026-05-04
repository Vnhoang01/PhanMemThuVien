<?php

namespace App\Http\Controllers;

use App\Models\LoanSlipDetail;
use App\Models\BookDetail;
use Illuminate\Http\Request;

class LoanSlipDetailController extends Controller
{
    public function index()
    {
        $loanSlipDetails = LoanSlipDetail::with('loanSlip','bookDetail.book')->get();

        return view('loanSlipDetail.index', compact('loanSlipDetails'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'loan_slip_id' => 'required|exists:loan_slips,id',
            'book_detail_id' => 'required|exists:book_details,id'
        ]);

        $detail = BookDetail::findOrFail($request->book_detail_id);

        // Không cho mượn nếu đã bị mượn
        if ($detail->status != 'available') {
            return back()->with('error','Sách này không khả dụng');
        }

        // tạo chi tiết
        LoanSlipDetail::create([
            'loan_slip_id' => $request->loan_slip_id,
            'book_detail_id' => $request->book_detail_id,
            'status' => 'borrowed'
        ]);

        // cập nhật trạng thái
        $detail->update(['status' => 'borrowed']);
        $detail->book->decrement('available_quantity');

        return back()->with('success','Thêm thành công');
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:book_details,id',
            'status' => 'required|in:available,damaged,lost'
        ]);

        $bookDetail = BookDetail::findOrFail($request->id);

        // Cập nhật status bản sao
        $bookDetail->status = $request->status;
        $bookDetail->save();

        // 🔥 Đồng bộ tất cả LoanSlipDetail đang mượn bản này
        LoanSlipDetail::where('book_detail_id', $bookDetail->id)
            ->where('status', 'borrowed')
            ->update(['status' => $request->status]);

        // Cập nhật số lượng còn sách
        $book = $bookDetail->book;
        $availableQuantity = $book->bookDetails()->where('status', 'available')->count();
        $totalQuantity = $book->bookDetails()->count();

        return response()->json([
            'success' => true,
            'available_quantity' => $availableQuantity,
            'total_quantity' => $totalQuantity
        ]);
    }

    public function destroy(LoanSlipDetail $loanSlipDetail)
    {
        $detail = $loanSlipDetail->bookDetail;

        // trả lại sách nếu đang mượn
        if ($loanSlipDetail->status == 'borrowed') {
            $detail->update(['status' => 'available']);
            $detail->book->increment('available_quantity');
        }

        $loanSlipDetail->delete();

        return back()->with('success','Xóa thành công');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookDetail;
use App\Models\LoanSlip;
use App\Models\Error;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // =========================
        // THÁNG HIỆN TẠI
        // =========================
        $startOfMonth = Carbon::now()->startOfMonth();

        $endOfMonth = Carbon::now()->endOfMonth();

        // =========================
        // THỐNG KÊ TỔNG QUAN
        // =========================
        $totalBooks = Book::sum('total_quantity');

        $availableBooks = BookDetail::where(
            'status',
            'available'
        )->count();

        $totalBorrowed = LoanSlip::where(
            'status',
            'borrowing'
        )->count();

        $totalPending = LoanSlip::where(
            'status',
            'pending'
        )->count();

        // =========================
        // THỐNG KÊ TÌNH TRẠNG SÁCH
        // =========================
        $damagedBooks = BookDetail::where(
            'status',
            'damaged'
        )->count();

        $lostBooks = BookDetail::where(
            'status',
            'lost'
        )->count();

        $goodBooks = BookDetail::where(
            'status',
            'available'
        )->count();

        $topBooks = Book::select(
            'books.id',
            'books.name',
            'books.image',
            DB::raw('COUNT(loan_slip_details.id) as total_borrow')
        )
            ->join(
                'book_details',
                'books.id',
                '=',
                'book_details.book_id'
            )
            ->join(
                'loan_slip_details',
                'book_details.id',
                '=',
                'loan_slip_details.book_detail_id'
            )
            ->join(
                'loan_slips',
                'loan_slip_details.loan_slip_id',
                '=',
                'loan_slips.id'
            )
            ->whereBetween(
                'loan_slips.created_at',
                [$startOfMonth, $endOfMonth]
            )
            ->groupBy(
                'books.id',
                'books.name',
                'books.image'
            )
            ->orderByDesc('total_borrow')
            ->take(3)
            ->get();

        // =========================
        // TOP LỖI NHIỀU NHẤT
        // THEO THÁNG HIỆN TẠI
        // =========================
        $topErrors = Error::select(
            'errors.id',
            'errors.name',

            DB::raw('COUNT(loan_slip_detail_error.id) as total_error')
        )
            ->join(
                'loan_slip_detail_error',
                'errors.id',
                '=',
                'loan_slip_detail_error.error_id'
            )
            ->join(
                'loan_slip_details',
                'loan_slip_detail_error.loan_slip_detail_id',
                '=',
                'loan_slip_details.id'
            )
            ->join(
                'loan_slips',
                'loan_slip_details.loan_slip_id',
                '=',
                'loan_slips.id'
            )

            ->whereBetween(
                'loan_slips.created_at',
                [$startOfMonth, $endOfMonth]
            )

            ->groupBy(
                'errors.id',
                'errors.name'
            )

            ->orderByDesc('total_error')

            ->take(3)

            ->get();

        $topStudents = DB::table('loan_slips')
            ->join(
                'students',
                'loan_slips.student_id',
                '=',
                'students.id'
            )
            ->select(
                'students.id',
                'students.student_code',
                'students.name',
                DB::raw('COUNT(loan_slips.id) as total_borrow')
            )

            ->whereBetween(
                'loan_slips.created_at',
                [$startOfMonth, $endOfMonth]
            )

            ->groupBy(
                'students.id',
                'students.student_code',
                'students.name'
            )

            ->orderByDesc('total_borrow')

            ->take(3)

            ->get();

        return view('dashboard', compact(
            'totalBooks',
            'availableBooks',
            'totalBorrowed',
            'totalPending',
            'damagedBooks',
            'lostBooks',
            'goodBooks',
            'topBooks',
            'topErrors',
            'topStudents'
        ));
    }

    // =========================
    // CHI TIẾT THỐNG KÊ SÁCH
    // =========================
    public function detail($id)
    {
        $book = Book::with('details')->findOrFail($id);

        $available = $book->details->where('status', 'available')->count();
        $damaged = $book->details->where('status', 'damaged')->count();
        $lost = $book->details->where('status', 'lost')->count();

        $borrowed = DB::table('loan_slip_details')
            ->join('book_details', 'loan_slip_details.book_detail_id', '=', 'book_details.id')
            ->join('loan_slips', 'loan_slip_details.loan_slip_id', '=', 'loan_slips.id')
            ->where('book_details.book_id', $id)
            ->where('loan_slips.status', 'borrowing')
            ->count();

        return response()->json([
            'book' => $book,
            'statistics' => [
                'total' => $book->details->count(),
                'available' => $available,
                'damaged' => $damaged,
                'lost' => $lost,
                'borrowed' => $borrowed,
            ]
        ]);
    }

    public function errorDetail($id)
    {
        $error = Error::findOrFail($id);

        $books = DB::table('loan_slip_detail_error')
            ->join(
                'loan_slip_details',
                'loan_slip_detail_error.loan_slip_detail_id',
                '=',
                'loan_slip_details.id'
            )
            ->join(
                'book_details',
                'loan_slip_details.book_detail_id',
                '=',
                'book_details.id'
            )
            ->join(
                'books',
                'book_details.book_id',
                '=',
                'books.id'
            )
            ->where(
                'loan_slip_detail_error.error_id',
                $id
            )
            ->select(
                'books.id',
                'books.name',
                'books.image',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy(
                'books.id',
                'books.name',
                'books.image'
            )
            ->orderByDesc('total')
            ->get();

        return response()->json([
            'error' => $error,
            'books' => $books
        ]);
    }
}

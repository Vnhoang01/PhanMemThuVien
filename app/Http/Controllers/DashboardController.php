<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookDetail;
use App\Models\Student;
use App\Models\LoanSlip;
use App\Models\Error;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // =========================
        // THỐNG KÊ TỔNG QUAN
        // =========================
        $totalBooks = Book::sum('total_quantity');

        $totalStudents = Student::count();

        $totalBorrowed = LoanSlip::where(
            'status',
            'borrowing'
        )->count();

        // Tổng sách còn trong thư viện
        $availableBooks = BookDetail::where(
            'status',
            'available'
        )->count();

        // =========================
        // TOP SÁCH ĐƯỢC MƯỢN
        // =========================
        $topBooks = Book::select(
            'books.id',
            'books.name',
            DB::raw('COUNT(loan_slip_details.id) as total_borrow')
        )
            ->join('book_details', 'books.id', '=', 'book_details.book_id')
            ->join('loan_slip_details', 'book_details.id', '=', 'loan_slip_details.book_detail_id')
            ->groupBy('books.id', 'books.name')
            ->orderByDesc('total_borrow')
            ->take(3)
            ->get();

        // =========================
        // TOP LỖI NHIỀU NHẤT
        // =========================
        $topErrors = Error::select(
            'errors.id',
            'errors.name',
            DB::raw('COUNT(loan_slip_detail_error.id) as total_error')
        )
            ->join('loan_slip_detail_error', 'errors.id', '=', 'loan_slip_detail_error.error_id')
            ->groupBy('errors.id', 'errors.name')
            ->orderByDesc('total_error')
            ->take(3)
            ->get();

        return view('dashboard', compact(
            'totalBooks',
            'totalStudents',
            'totalBorrowed',
            'availableBooks',
            'topBooks',
            'topErrors'
        ));
    }
}

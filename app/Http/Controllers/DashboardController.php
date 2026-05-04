<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookDetail;
use App\Models\Student;
use App\Models\LoanSlip;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBooks = Book::sum('total_quantity');
        $totalStudents = Student::count();
        $totalBorrowed = LoanSlip::count();

        // Tổng sách hiện còn trong thư viện
        $availableBooks = BookDetail::where('status', 'available')->count();

        return view('dashboard', compact(
            'totalBooks',
            'totalStudents',
            'totalBorrowed',
            'availableBooks'
        ));
    }
}

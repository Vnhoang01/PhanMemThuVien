<?php

namespace App\Http\Controllers;

use App\Models\Book;
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
        $availableBooks = Book::sum('available_quantity');

        return view('dashboard', compact(
            'totalBooks',
            'totalStudents',
            'totalBorrowed',
            'availableBooks'
        ));
    }
}

<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BookDetailController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ClassesController;
use App\Http\Controllers\ErrorController;
use App\Http\Controllers\LoanSlipController;
use App\Http\Controllers\LoanSlipDetailController;
use App\Http\Controllers\MajorController;
use App\Http\Controllers\PublisherController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminAuthController;

Route::get('/', function () {
    return redirect()->route('admin.login');
});

Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login');

Route::middleware('auth:admin')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('admins', AdminController::class);
    Route::resource('majors', MajorController::class);
    Route::resource('classes',ClassesController::class );
    Route::resource('students', StudentController::class);
    Route::post('students/{id}/reset-password',
        [StudentController::class, 'resetPassword']
    )->name('students.resetPassword');
    Route::resource('categories', CategoryController::class);
    Route::resource('authors', AuthorController::class);
    Route::resource('publishers', PublisherController::class);
    Route::resource('books', BookController::class);
    Route::resource('book_details', BookDetailController::class);
    Route::resource('loan_slips', LoanSlipController::class);
    Route::get('/loan-slips/{id}/return', [LoanSlipController::class, 'showReturn'])
        ->name('loan_slips.return.form');
    Route::post('/loan-slips/{id}/return', [LoanSlipController::class, 'returnAll'])
        ->name('loan_slips.return');
    Route::resource('loan_slip_details', LoanSlipDetailController::class);
    Route::delete('book-details/{id}', [BookDetailController::class, 'destroy'])
        ->name('book_details.destroy');
    Route::post('/book-details/update-status', [BookDetailController::class, 'updateStatus']);
    Route::get('books/{id}/details', [LoanSlipController::class, 'getBookDetails']);
    Route::resource('errors', ErrorController::class);
});

Route::get('admin/login', [AdminAuthController::class,'showLogin'])->name('admin.login');
Route::post('admin/login', [AdminAuthController::class,'login'])->name('admin.login.post');
Route::post('admin/logout', [AdminAuthController::class,'logout'])->name('admin.logout');

// Student auth routes
Route::get('student/login', [StudentAuthController::class, 'showLogin'])->name('login_student.login');
Route::post('student/login', [StudentAuthController::class, 'login'])->name('login_student.login.post');
Route::post('student/logout', [StudentAuthController::class, 'logout'])->name('student.logout');

// Frontpage after student login
Route::get('frontpage', function () {
    return view('layouts.frontpage');
})->name('layouts.frontpage');

Route::get('student/login', [StudentAuthController::class,'showLogin'])->name('student.login');
Route::post('student/login', [StudentAuthController::class,'login'])->name('student.login.post');
Route::post('student/logout', [StudentAuthController::class,'logout'])->name('student.logout');


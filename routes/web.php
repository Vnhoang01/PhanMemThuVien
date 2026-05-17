<?php

use Illuminate\Support\Facades\Route;
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
use App\Http\Controllers\StudentAuthController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminAuthController;

/*
|--------------------------------------------------------------------------
| Redirect root
|--------------------------------------------------------------------------
*/
Route::get('/', function () {

    // Admin
    if (auth('admin')->check()) {
        return redirect()->route('dashboard');
    }

    // Student
    if (auth('student')->check()) {
        return redirect()->route('student.profile');
    }

    // Chưa đăng nhập
    return redirect()->route('student.login');
});

/*
|--------------------------------------------------------------------------
| ADMIN AUTH
|--------------------------------------------------------------------------
*/
Route::get('admin/login', [AdminAuthController::class,'showLogin'])->name('admin.login');
Route::post('admin/login', [AdminAuthController::class,'login'])->name('admin.login.post');
Route::post('admin/logout', [AdminAuthController::class,'logout'])->name('admin.logout');

/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/
Route::middleware('auth:admin')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('admins', AdminController::class);
    Route::resource('majors', MajorController::class);
    Route::resource('classes', ClassesController::class);
    Route::resource('students', StudentController::class);

    Route::post('students/{id}/reset-password',
        [StudentController::class, 'resetPassword']
    )->name('students.resetPassword');

    Route::resource('categories', CategoryController::class);
    Route::resource('authors', AuthorController::class);
    Route::resource('publishers', PublisherController::class);
    Route::resource('books', BookController::class);
    Route::get('/books/{id}/details', [BookController::class, 'details']);
    Route::resource('book_details', BookDetailController::class);

    Route::resource('loan_slips', LoanSlipController::class);
    Route::resource('loan_slip_details', LoanSlipDetailController::class);

    Route::post('/loan-slips/{id}/approve',
        [LoanSlipController::class, 'approve'])
        ->name('loan_slips.approve');

    Route::get('/loan-slips/{id}/return', [LoanSlipController::class, 'showReturn'])
        ->name('loan_slips.return.form');

    Route::post('/loan-slips/{id}/return', [LoanSlipController::class, 'returnAll'])
        ->name('loan_slips.return');

    Route::post('/book-details/update-status', [BookDetailController::class, 'updateStatus']);

    Route::resource('errors', ErrorController::class);
});

/*
|--------------------------------------------------------------------------
| STUDENT AUTH
|--------------------------------------------------------------------------
*/
Route::get('student/login', [StudentAuthController::class,'showLogin'])->name('student.login');
Route::post('student/login', [StudentAuthController::class,'login'])->name('student.login.post');
Route::post('student/logout', [StudentAuthController::class,'logout'])->name('student.logout');

/*
|--------------------------------------------------------------------------
| STUDENT
|--------------------------------------------------------------------------
*/
Route::middleware('auth:student')->group(function () {

    // PROFILE
    Route::get(
        'student/profile',
        [StudentAuthController::class, 'showProfile']
    )->name('login_student.profile');

    Route::post(
        'student/profile',
        [StudentAuthController::class, 'updateProfile']
    )->name('student.profile.update');

    Route::get('/student/book/{id}', [StudentAuthController::class, 'detail'])
        ->name('login_student.detail');

    // BORROW BOOK
    Route::get(
        'student/borrow',
        [StudentAuthController::class, 'showBorrow']
    )->name('student.borrow');

    Route::post(
        'student/borrow',
        [StudentAuthController::class, 'borrow']
    )->name('student.borrow.submit');

});


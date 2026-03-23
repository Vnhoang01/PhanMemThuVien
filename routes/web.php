<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ClassesController;
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
    Route::resource('categories', CategoryController::class);
    Route::resource('authors', AuthorController::class);
    Route::resource('publishers', PublisherController::class);
    Route::resource('books', BookController::class);
    Route::resource('loan_slips', LoanSlipController::class);
    Route::resource('loan_slip_details', LoanSlipDetailController::class);
});

Route::get('admin/login', [AdminAuthController::class,'showLogin'])->name('admin.login');
Route::post('admin/login', [AdminAuthController::class,'login'])->name('admin.login.post');
Route::post('admin/logout', [AdminAuthController::class,'logout'])->name('admin.logout');




<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Book;
use App\Models\LoanSlip;
use App\Models\LoanSlipDetail;
use Carbon\Carbon;

class StudentAuthController extends Controller
{
    public function showLogin()
    {
        return view('login_student.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::guard('student')->attempt($credentials)) {

            // QUAN TRỌNG
            $request->session()->regenerate();

            $student = Auth::guard('student')->user();

            return redirect()->route('student.borrow');
        }

        return back()->with('error', 'Sai email hoặc password');
    }

    public function logout(Request $request)
    {
        Auth::guard('student')->logout();

        // QUAN TRỌNG
        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('student.login');
    }

    public function showBorrow()
    {
        $books = Book::with([
            'author',
            'category',
            'details'
        ])
            ->withCount([
                'details as available_quantity' => function ($query) {
                    $query->where('status', 'available');
                }
            ])
            ->having('available_quantity', '>', 0)
            ->get();

        return view('login_student.home', compact('books'));
    }

    public function borrow(Request $request)
    {
        $student = Auth::guard('student')->user();

        $request->validate([
            'book_ids' => 'required|array|min:1',
            'book_ids.*' => 'exists:books,id',
            'return_date' => 'required|date|after:today',
        ]);


        // Create loan slip
        $loanSlip = LoanSlip::create([
            'student_id' => $student->id,
            'start_date' => Carbon::now(),
            'end_date' => $request->return_date,
            'status' => 'borrowed',
            'total_books' => count($request->book_ids),
        ]);

        // Create loan slip details and update book copies
        foreach ($request->book_ids as $bookId) {

            $book = Book::find($bookId);

            if (!$book) continue;

            $detail = $book->details()
                ->where('status', 'available')
                ->first();

            if ($detail) {

                LoanSlipDetail::create([
                    'loan_slip_id' => $loanSlip->id,
                    'book_id' => $bookId,
                    'fee_amount' => 0,
                    'status' => 'borrowed',
                ]);

                $detail->update([
                    'status' => 'borrowed'
                ]);
            }
        }

        return redirect()->route('student.borrow')->with('success', 'Mượn sách thành công!');
    }

    public function showProfile()
    {
        $student = Auth::guard('student')->user();

        return view('login_student.profile', compact('student'));
    }

    public function updateProfile(Request $request)
    {
        $student = Auth::guard('student')->user();

        $request->validate([

            // NAME
            'name' => [
                'required',
                'string',
                'min:2',
                'max:255'
            ],

            // EMAIL
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:students,email,' . $student->id
            ],

            // PHONE
            'phone_number' => [
                'required',
                'regex:/^(0)[0-9]{9}$/'
            ],

            // ADDRESS
            'address' => [
                'required',
                'string',
                'min:5',
                'max:255'
            ],

            // PASSWORD
            'current_password' => [
                'nullable',
                'required_with:password'
            ],

            'password' => [
                'nullable',
                'required_with:current_password',
                'min:6',
                'confirmed'
            ],

            'password_confirmation' => [
                'nullable',
                'required_with:password'
            ]

        ], [

            // NAME
            'name.required' => 'Vui lòng nhập họ tên',
            'name.min' => 'Họ tên phải từ 2 ký tự trở lên',
            'name.max' => 'Họ tên quá dài',

            // EMAIL
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không đúng định dạng',
            'email.unique' => 'Email đã tồn tại',

            // PHONE
            'phone_number.required' => 'Vui lòng nhập số điện thoại',
            'phone_number.regex' => 'Số điện thoại không hợp lệ',

            // ADDRESS
            'address.required' => 'Vui lòng nhập địa chỉ',
            'address.min' => 'Địa chỉ quá ngắn',

            // PASSWORD
            'current_password.required_with' => 'Vui lòng nhập mật khẩu hiện tại',

            'password.required_with' => 'Vui lòng nhập mật khẩu mới',
            'password.min' => 'Mật khẩu mới phải từ 6 ký tự',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp',

            'password_confirmation.required_with' => 'Vui lòng xác nhận mật khẩu mới',
        ]);

        // CHECK CURRENT PASSWORD
        if ($request->filled('password')) {

            if (!Hash::check($request->current_password, $student->password)) {

                return back()
                    ->withErrors([
                        'current_password' => 'Mật khẩu hiện tại không đúng'
                    ])
                    ->withInput();
            }

            $student->password = Hash::make($request->password);
        }

        // UPDATE
        $student->name = $request->name;
        $student->email = $request->email;
        $student->phone_number = $request->phone_number;
        $student->address = $request->address;

        $student->save();

        return redirect()
            ->route('login_student.profile')
            ->with('success', 'Cập nhật thông tin thành công!');
    }

    public function detail($id)
    {
        $book = Book::with(['author', 'category', 'details'])
            ->findOrFail($id);

        return view('login_student.detail', compact('book'));
    }
}

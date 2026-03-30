<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentAuthController extends Controller
{
    public function showLogin()
    {
        return view('login_student.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email','password');

        if (Auth::guard('student')->attempt($credentials)) {
            return redirect()->route('layouts.frontpage');
        }

        return back()->with('error','Sai email hoặc password');
    }

    public function logout()
    {
        Auth::guard('student')->logout();
        return redirect()->route('login_student.login');
    }
}

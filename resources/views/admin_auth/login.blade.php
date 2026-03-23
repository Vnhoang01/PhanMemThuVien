@extends('layouts.auth')

@section('content')

    <h2>Admin Login</h2>

    @if(session('error'))
        <p style="color:red">{{ session('error') }}</p>
    @endif

    <form method="POST" action="{{ route('admin.login.post') }}">
        @csrf

        Email: <input type="email" name="email" class="form-control"><br><br>
        Password: <input type="password" name="password" class="form-control"><br><br>

        <button type="submit" class="btn btn-primary w-100">Đăng nhập</button>

    </form>

@endsection

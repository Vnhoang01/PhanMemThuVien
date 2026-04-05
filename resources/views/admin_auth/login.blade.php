@extends('layouts.auth')

@section('content')

    <div class="d-flex align-items-center justify-content-center vh-100 bg-light">
        <div class="card shadow-lg border-0" style="width: 400px; border-radius: 12px;">

            <div class="card-body p-4">

                <h4 class="text-center mb-4 fw-bold">
                    🔐 Đăng nhập hệ thống
                </h4>

                {{-- Hiển thị lỗi --}}
                @if(session('error'))
                    <div class="alert alert-danger text-center">
                        {{ session('error') }}
                    </div>
                @endif

                {{-- Kiểm tra, báo cáo lỗi --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <div>• {{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.login.post') }}">
                    @csrf

                    {{-- Email --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email"
                               name="email"
                               class="form-control form-control-lg"
                               value="{{ old('email') }}"
                               placeholder="admin@email.com"
                               required>
                    </div>

                    {{-- Mật khẩu --}}
                    <div class="mb-3 position-relative">
                        <label class="form-label fw-semibold">Mật khẩu</label>

                        <input type="password"
                               name="password"
                               id="password"
                               class="form-control form-control-lg"
                               placeholder="••••••••"
                               required>

                        <span onclick="togglePassword()"
                              style="position:absolute; right:15px; top:40px; cursor:pointer;">
                                <i id="eyeIcon" class="fa-solid fa-eye"></i>
                        </span>
                    </div>

                    {{-- Nút bấm --}}
                    <button type="submit" class="btn btn-primary w-100 btn-lg">
                        Đăng nhập
                    </button>
                </form>

            </div>
        </div>
    </div>

    {{-- JavaScript --}}
    <script>
        function togglePassword() {
            let input = document.getElementById("password");
            let icon = document.getElementById("eyeIcon");

            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                input.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        }
    </script>

@endsection

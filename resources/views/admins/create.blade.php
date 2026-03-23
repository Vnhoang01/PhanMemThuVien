@extends('layouts.master')

@section('content')

    <div class="container mt-4">

        <h2 class="fw-bold mb-4">➕ Thêm quản trị viên</h2>

        <!-- Hiển thị lỗi -->
        @if ($errors->any())
            <div class="alert alert-danger shadow-sm">
                <ul class="mb-0">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body">

                <form action="{{ route('admins.store') }}" method="POST">
                    @csrf

                    <div class="row g-3">

                        <!-- Tên -->
                        <div class="col-md-6">
                            <label class="form-label">Tên</label>
                            <input type="text" name="name"
                                   value="{{ old('name') }}"
                                   class="form-control"
                                   placeholder="Nhập tên">
                        </div>

                        <!-- Email -->
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email"
                                   value="{{ old('email') }}"
                                   class="form-control"
                                   placeholder="Nhập email">
                        </div>

                        <!-- Mật khẩu -->
                        <div class="col-md-6">
                            <label class="form-label">Mật khẩu</label>
                            <input type="password" name="password"
                                   class="form-control"
                                   placeholder="Nhập mật khẩu">
                        </div>

                        <!-- SĐT -->
                        <div class="col-md-6">
                            <label class="form-label">Số điện thoại</label>
                            <input type="text" name="phone_number"
                                   value="{{ old('phone_number') }}"
                                   class="form-control"
                                   placeholder="Nhập số điện thoại">
                        </div>

                        <!-- Vai trò -->
                        <div class="col-md-6">
                            <label class="form-label">Vai trò</label>
                            <select name="role" class="form-select">
                                <option value="">-- Chọn vai trò --</option>
                                <option value="admin">Quản trị viên</option>
                                <option value="staff">Thủ thư</option>
                            </select>
                        </div>

                    </div>

                    <!-- Buttons -->
                    <div class="mt-4 d-flex gap-2">
                        <button class="btn btn-success">
                            <i class="bi bi-save"></i> Lưu
                        </button>

                        <a href="{{ route('admins.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Quay lại
                        </a>
                    </div>

                </form>

            </div>
        </div>

    </div>

@endsection

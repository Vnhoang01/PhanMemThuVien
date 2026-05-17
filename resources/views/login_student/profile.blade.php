@extends('layouts.layout_student')

@section('title', 'Thông tin cá nhân')

@section('content')

    <div class="container py-4">

        <div class="card border-0 shadow-lg rounded-4">

            <div class="card-body p-4 p-lg-5">

                <!-- HEADER -->
                <div class="d-flex justify-content-between align-items-center mb-4">

                    <div>

                        <h2 class="fw-bold mb-1">
                            Thông tin cá nhân
                        </h2>

                        <p class="text-muted mb-0">
                            Quản lý thông tin tài khoản sinh viên
                        </p>

                    </div>

                    <button class="btn btn-primary rounded-3 px-4"
                            data-bs-toggle="modal"
                            data-bs-target="#editProfileModal">

                        ✏️ Chỉnh sửa

                    </button>

                </div>

                <!-- SUCCESS -->
                @if(session('success'))

                    <div class="alert alert-success rounded-3">
                        {{ session('success') }}
                    </div>

                @endif

                <!-- PROFILE INFO -->
                <div class="row g-4">

                    <div class="col-md-6">

                        <div class="profile-box">

                            <label>Họ và tên</label>

                            <div>
                                {{ $student->name }}
                            </div>

                        </div>

                    </div>

                    <div class="col-md-6">

                        <div class="profile-box">

                            <label>Email</label>

                            <div>
                                {{ $student->email }}
                            </div>

                        </div>

                    </div>

                    <div class="col-md-6">

                        <div class="profile-box">

                            <label>Lớp</label>

                            <div>
                                {{ $student->class->name ?? '' }}
                            </div>

                        </div>

                    </div>

                    <div class="col-md-6">

                        <div class="profile-box">

                            <label>Chuyên ngành</label>

                            <div>
                                {{ $student->class->major->name ?? '' }}
                            </div>

                        </div>

                    </div>

                    <div class="col-md-6">

                        <div class="profile-box">

                            <label>Số điện thoại</label>

                            <div>
                                {{ $student->phone_number ?: 'Chưa cập nhật' }}
                            </div>

                        </div>

                    </div>

                    <div class="col-md-6">

                        <div class="profile-box">

                            <label>Địa chỉ</label>

                            <div>
                                {{ $student->address ?: 'Chưa cập nhật' }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- MODAL -->
    <div class="modal fade"
         id="editProfileModal"
         tabindex="-1"
         aria-hidden="true">

        <div class="modal-dialog modal-lg modal-dialog-centered">

            <div class="modal-content border-0 rounded-4">

                <div class="modal-header border-0 pb-0">

                    <h4 class="fw-bold">
                        Cập nhật thông tin
                    </h4>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body p-4">

                    <!-- ERROR -->
                    @if($errors->any())

                        <div class="alert alert-danger">

                            <ul class="mb-0">

                                @foreach($errors->all() as $error)

                                    <li>{{ $error }}</li>

                                @endforeach

                            </ul>

                        </div>

                    @endif

                    <!-- FORM -->
                    <form method="POST"
                          action="{{ route('student.profile.update') }}">

                        @csrf

                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Tên
                                </label>

                                <input type="text"
                                       name="name"
                                       class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name', $student->name) }}"
                                       required>

                                @error('name')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                                @enderror

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Email
                                </label>

                                <input type="email"
                                       name="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email', $student->email) }}"
                                       required>

                                @error('email')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                                @enderror

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Số điện thoại
                                </label>

                                <input type="text"
                                       name="phone_number"
                                       class="form-control"
                                       value="{{ old('phone_number', $student->phone_number) }}">

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Địa chỉ
                                </label>

                                <input type="text"
                                       name="address"
                                       class="form-control"
                                       value="{{ old('address', $student->address) }}">
                            </div>

                        </div>

                        <hr class="my-4">

                        <!-- PASSWORD -->
                        <h5 class="fw-bold mb-3">
                            Đổi mật khẩu
                        </h5>

                        <div class="mb-3">

                            <label class="form-label">
                                Mật khẩu hiện tại
                            </label>

                            <input type="password"
                                   name="current_password"
                                   class="form-control">

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Mật khẩu mới
                            </label>

                            <input type="password"
                                   name="password"
                                   class="form-control">

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Xác nhận mật khẩu mới
                            </label>

                            <input type="password"
                                   name="password_confirmation"
                                   class="form-control">

                        </div>

                        <!-- BUTTON -->
                        <div class="text-end mt-4">

                            <button type="button"
                                    class="btn btn-light rounded-3 px-4"
                                    data-bs-dismiss="modal">

                                Đóng

                            </button>

                            <button type="submit"
                                    class="btn btn-primary rounded-3 px-4">

                                Lưu thay đổi

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

    <style>

        .profile-box{
            background:#f8fafc;
            border:1px solid #e2e8f0;
            border-radius:16px;
            padding:20px;
            height:100%;
        }

        .profile-box label{
            display:block;
            margin-bottom:8px;
            color:#64748b;
            font-size:14px;
            font-weight:700;
        }

        .profile-box div{
            font-size:16px;
            font-weight:600;
            color:#0f172a;
        }

        .modal-content{
            box-shadow:0 20px 50px rgba(0,0,0,0.15);
        }

        .form-control{
            border-radius:12px;
            padding:12px 14px;
        }

        .form-control:focus{
            box-shadow:none;
            border-color:#2563eb;
        }

        .card{
            overflow:hidden;
        }

    </style>

@endsection

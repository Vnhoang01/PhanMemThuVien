@extends('layouts.master')

@section('content')

    <div class="container mt-4">

        <h2 class="fw-bold mb-4">✏️ Cập nhật sinh viên</h2>

        {{-- Hiển thị lỗi --}}
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

                <form action="{{ route('students.update', $student->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">

                        {{-- Name --}}
                        <div class="col-md-6">
                            <label class="form-label">Tên</label>
                            <input type="text" name="name"
                                   value="{{ old('name', $student->name) }}"
                                   class="form-control @error('name') is-invalid @enderror">

                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- DOB --}}
                        <div class="col-md-6">
                            <label class="form-label">Ngày sinh</label>
                            <input type="date" name="date_of_birth"
                                   value="{{ old('date_of_birth', $student->date_of_birth) }}"
                                   class="form-control @error('date_of_birth') is-invalid @enderror">

                            @error('date_of_birth')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Gender --}}
                        <div class="col-md-6">
                            <label class="form-label">Giới tính</label>
                            <select name="gender"
                                    class="form-select @error('gender') is-invalid @enderror">
                                <option value="Male" {{ old('gender', $student->gender)=='Male'?'selected':'' }}>Nam
                                </option>
                                <option value="Female" {{ old('gender', $student->gender)=='Female'?'selected':'' }}>
                                    Nữ
                                </option>
                            </select>

                            @error('gender')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Class --}}
                        <div class="col-md-6">
                            <label class="form-label">Lớp</label>
                            <select name="class_id"
                                    class="form-select @error('class_id') is-invalid @enderror">

                                <option value="">-- Chọn lớp --</option>

                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}"
                                        {{ old('class_id', $student->class_id) == $class->id ? 'selected' : '' }}>
                                        {{ $class->name }}
                                    </option>
                                @endforeach

                            </select>

                            @error('class_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email"
                                   value="{{ old('email', $student->email) }}"
                                   class="form-control @error('email') is-invalid @enderror">

                            @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Phone --}}
                        <div class="col-md-6">
                            <label class="form-label">SĐT</label>
                            <input type="text" name="phone_number"
                                   value="{{ old('phone_number', $student->phone_number) }}"
                                   class="form-control">
                        </div>

                        {{-- Address --}}
                        <div class="col-md-6">
                            <label class="form-label">Địa chỉ</label>
                            <input type="text" name="address"
                                   value="{{ old('address', $student->address) }}"
                                   class="form-control">
                        </div>

                        {{-- Status --}}
                        <div class="col-md-6">
                            <label class="form-label">Trạng thái</label>
                            <select name="status" class="form-select">
                                <option value="active" {{ old('status', $student->status)=='active'?'selected':'' }}>
                                    Hoạt động
                                </option>
                                <option
                                    value="inactive" {{ old('status', $student->status)=='inactive'?'selected':'' }}>
                                    Ngừng
                                </option>
                            </select>
                        </div>

                    </div>

                    {{-- Buttons --}}
                    <div class="mt-4 d-flex gap-2">
                        <button class="btn btn-success">
                            <i class="bi bi-save"></i> Cập nhật
                        </button>

                        <a href="{{ route('students.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Quay lại
                        </a>
                    </div>

                </form>

            </div>
        </div>

    </div>

@endsection

@extends('layouts.master')

@section('content')

    <div class="container mt-4">

        <h2 class="fw-bold mb-4">✏️ Cập nhật lớp</h2>

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

                <form action="{{ route('classes.update', $class->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">

                        {{-- Name --}}
                        <div class="col-md-6">
                            <label class="form-label">Tên lớp</label>
                            <input type="text"
                                   name="name"
                                   value="{{ old('name', $class->name) }}"
                                   class="form-control @error('name') is-invalid @enderror"
                                   placeholder="Nhập tên lớp">

                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Course Year --}}
                        <div class="col-md-6">
                            <label class="form-label">Niên khóa</label>
                            <input type="text"
                                   name="course_year"
                                   value="{{ old('course_year', $class->course_year) }}"
                                   class="form-control @error('course_year') is-invalid @enderror"
                                   placeholder="VD: 2023-2027">

                            @error('course_year')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Major --}}
                        <div class="col-12">
                            <label class="form-label">Chuyên ngành</label>
                            <select name="major_id"
                                    class="form-select @error('major_id') is-invalid @enderror">

                                <option value="">-- Chọn chuyên ngành --</option>

                                @foreach($majors as $major)
                                    <option value="{{ $major->id }}"
                                        {{ old('major_id', $class->major_id) == $major->id ? 'selected' : '' }}>
                                        {{ $major->name }}
                                    </option>
                                @endforeach

                            </select>

                            @error('major_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                    {{-- Buttons --}}
                    <div class="mt-4 d-flex gap-2">
                        <button class="btn btn-success">
                            <i class="bi bi-save"></i> Cập nhật
                        </button>

                        <a href="{{ route('classes.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Quay lại
                        </a>
                    </div>

                </form>

            </div>
        </div>

    </div>

@endsection

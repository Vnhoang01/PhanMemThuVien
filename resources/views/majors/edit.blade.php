@extends('layouts.master')

@section('content')

    <div class="container mt-4">

        <h2 class="fw-bold mb-4">✏️ Cập nhật ngành</h2>

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

                <form action="{{ route('majors.update', $major->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">

                        <!-- Name -->
                        <div class="col-md-6">
                            <label class="form-label">Tên ngành</label>
                            <input type="text"
                                   name="name"
                                   value="{{ old('name', $major->name) }}"
                                   class="form-control"
                                   placeholder="Nhập tên ngành">

                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="col-12">
                            <label class="form-label mt-3">Mô tả ngành</label>
                            <textarea name="description"
                                      class="form-control"
                                      rows="4"
                                      placeholder="Nhập mô tả ngành...">{{ old('description', $major->description) }}</textarea>

                            @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                    <!-- Buttons -->
                    <div class="mt-4 d-flex gap-2">
                        <button class="btn btn-success">
                            <i class="bi bi-save"></i> Cập nhật
                        </button>

                        <a href="{{ route('majors.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Quay lại
                        </a>
                    </div>

                </form>

            </div>
        </div>

    </div>

@endsection

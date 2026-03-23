@extends('layouts.master')

@section('content')

    <div class="container mt-4">

        <h2 class="fw-bold mb-4">✏️ Cập nhật tác giả</h2>

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

                <form action="{{ route('authors.update',$author->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">

                        <!-- Tên -->
                        <div class="col-md-6">
                            <label class="form-label">Tên tác giả</label>
                            <input type="text" name="name"
                                   value="{{ old('name', $author->name) }}"
                                   class="form-control">
                        </div>

                        <!-- Ngày sinh -->
                        <div class="col-md-6">
                            <label class="form-label">Ngày sinh</label>
                            <input type="date" name="date_of_birth"
                                   value="{{ old('date_of_birth', \Carbon\Carbon::parse($author->date_of_birth)->format('Y-m-d')) }}"
                                   class="form-control">
                        </div>

                        <!-- Mô tả -->
                        <div class="col-12">
                            <label class="form-label">Mô tả</label>
                            <textarea name="description"
                                      class="form-control"
                                      rows="4">{{ old('description', $author->description) }}</textarea>
                        </div>

                    </div>

                    <!-- Buttons -->
                    <div class="mt-4 d-flex gap-2">
                        <button class="btn btn-success">
                            <i class="bi bi-save"></i> Cập nhật
                        </button>

                        <a href="{{ route('authors.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Quay lại
                        </a>
                    </div>

                </form>

            </div>
        </div>

    </div>

@endsection

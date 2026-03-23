@extends('layouts.master')

@section('content')

    <div class="container mt-4">

        <h2 class="fw-bold mb-4">✏️ Cập nhật thể loại</h2>

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

                <form action="{{ route('categories.update',$category->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">

                        <!-- Tên -->
                        <div class="col-md-6">
                            <label class="form-label">Tên thể loại</label>
                            <input type="text" name="name"
                                   value="{{ old('name', $category->name) }}"
                                   class="form-control"
                                   placeholder="Nhập tên thể loại">
                        </div>

                        <!-- Mô tả -->
                        <div class="col-12">
                            <label class="form-label">Mô tả</label>
                            <textarea name="description"
                                      class="form-control"
                                      rows="4"
                                      placeholder="Nhập mô tả...">{{ old('description', $category->description) }}</textarea>
                        </div>

                    </div>

                    <!-- Buttons -->
                    <div class="mt-4 d-flex gap-2">
                        <button class="btn btn-success">
                            <i class="bi bi-save"></i> Cập nhật
                        </button>

                        <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Quay lại
                        </a>
                    </div>

                </form>

            </div>
        </div>

    </div>

@endsection

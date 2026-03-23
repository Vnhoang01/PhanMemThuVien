@extends('layouts.master')

@section('content')

    <div class="container mt-4">

        <h2 class="fw-bold mb-4">➕ Thêm sách</h2>

        <div class="card shadow-sm">
            <div class="card-body">

                <form action="{{ route('books.store') }}" method="POST">
                    @csrf

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label">Tên sách</label>
                            <input type="text" name="name" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Năm xuất bản</label>
                            <input type="number" name="year_of_publication" class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Tác giả</label>
                            <select name="author_id" class="form-select" required>
                                <option value="" disabled selected hidden>-- Chọn tác giả --</option>
                                @foreach($authors as $author)
                                    <option value="{{ $author->id }}">{{ $author->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Thể loại</label>
                            <select name="category_id" class="form-select" required>
                                <option value="" disabled selected hidden>-- Chọn thể loại --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Nhà xuất bản</label>
                            <select name="publisher_id" class="form-select" required>
                                <option value="" disabled selected hidden>-- Chọn nhà xuất bản --</option>
                                @foreach($publishers as $publisher)
                                    <option value="{{ $publisher->id }}">{{ $publisher->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Tổng số lượng</label>
                            <input type="number" name="total_quantity" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Số lượng còn</label>
                            <input type="number" name="available_quantity" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Trạng thái</label>
                            <select name="status" class="form-select">
                                <option value="Còn sách">Còn sách</option>
                                <option value="Hết sách">Hết sách</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Mô tả</label>
                            <textarea name="description" class="form-control" rows="3"></textarea>
                        </div>

                    </div>

                    <div class="mt-4 d-flex gap-2">
                        <button class="btn btn-success">
                            <i class="bi bi-save"></i> Lưu
                        </button>

                        <a href="{{ route('books.index') }}" class="btn btn-secondary">
                            ← Quay lại
                        </a>
                    </div>

                </form>

            </div>
        </div>

    </div>

@endsection

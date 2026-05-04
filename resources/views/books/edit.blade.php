@extends('layouts.master')

@section('content')

    <div class="container mt-4">

        <h2 class="fw-bold mb-4">✏️ Cập nhật sách</h2>

        <div class="card shadow-sm">
            <div class="card-body">

                <form action="{{ route('books.update',$book->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- =========================
                        ROW 1: BOOK CODE + ISBN
                    ========================= --}}
                    <div class="row g-3 align-items-end">

                        <div class="col-md-6">
                            <label>Mã sách</label>
                            <input type="text" class="form-control bg-light fw-bold text-primary"
                                   value="{{ $book->book_code }}" disabled>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">ISBN</label>
                            <input type="text" class="form-control bg-light"
                                   value="{{ $book->isbn ?? 'Chưa có ISBN' }}"
                                   disabled>
                        </div>

                    </div>

                    {{-- =========================
                        ROW 2: NAME + YEAR
                    ========================= --}}
                    <div class="row g-3 mt-1">

                        <div class="col-md-6">
                            <label class="form-label">Tên sách</label>
                            <input type="text" name="name" class="form-control"
                                   value="{{ old('name', $book->name) }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Năm xuất bản</label>
                            <input type="number" name="year_of_publication"
                                   class="form-control"
                                   value="{{ old('year_of_publication', $book->year_of_publication) }}">
                        </div>

                    </div>

                    {{-- =========================
                        ROW 3: AUTHOR + CATEGORY
                    ========================= --}}
                    <div class="row g-3 mt-1">

                        <div class="col-md-6">
                            <label class="form-label">Tác giả</label>
                            <select name="author_id" class="form-select">
                                @foreach($authors as $author)
                                    <option value="{{ $author->id }}"
                                        {{ old('author_id', $book->author_id) == $author->id ? 'selected' : '' }}>
                                        {{ $author->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Thể loại</label>
                            <select name="category_id" class="form-select">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id', $book->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    {{-- =========================
                        ROW 4: PUBLISHER
                    ========================= --}}
                    <div class="row g-3 mt-1">

                        <div class="col-md-6">
                            <label class="form-label">Nhà xuất bản</label>
                            <select name="publisher_id" class="form-select">
                                @foreach($publishers as $publisher)
                                    <option value="{{ $publisher->id }}"
                                        {{ old('publisher_id', $book->publisher_id) == $publisher->id ? 'selected' : '' }}>
                                        {{ $publisher->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    {{-- =========================
                        ROW 5: STATISTICS
                    ========================= --}}
                    <div class="row g-3 mt-1">

                        <div class="col-md-4">
                            <label class="form-label">Tổng số lượng</label>
                            <input type="text" class="form-control bg-light"
                                   value="{{ $book->details->count() }}" disabled>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Số lượng còn</label>
                            <input type="text" class="form-control bg-light"
                                   value="{{ $book->available_quantity }}" disabled>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Trạng thái</label>
                            <input type="text" class="form-control bg-light"
                                   value="{{ $book->available_quantity > 0 ? 'Còn sách' : 'Hết sách' }}"
                                   disabled>
                        </div>

                    </div>

                    {{-- =========================
                        DESCRIPTION
                    ========================= --}}
                    <div class="mt-3">
                        <label class="form-label">Mô tả</label>
                        <textarea name="description" class="form-control" rows="4">{{ old('description', $book->description) }}</textarea>
                    </div>

                    {{-- =========================
                        BUTTON
                    ========================= --}}
                    <div class="mt-4 d-flex gap-2">
                        <button class="btn btn-success">
                            <i class="bi bi-save"></i> Cập nhật
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

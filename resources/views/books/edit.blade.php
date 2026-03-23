@extends('layouts.master')

@section('content')

    <div class="container mt-4">

        <h2 class="fw-bold mb-4">✏️ Cập nhật sách</h2>

        <div class="card shadow-sm">
            <div class="card-body">

                <form action="{{ route('books.update',$book->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label">Tên sách</label>
                            <input type="text" name="name" class="form-control"
                                   value="{{ $book->name }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Năm xuất bản</label>
                            <input type="number" name="year_of_publication"
                                   class="form-control"
                                   value="{{ $book->year_of_publication }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Tác giả</label>
                            <select name="author_id" class="form-select">
                                @foreach($authors as $author)
                                    <option value="{{ $author->id }}"
                                        {{ $book->author_id == $author->id ? 'selected' : '' }}>
                                        {{ $author->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Thể loại</label>
                            <select name="category_id" class="form-select">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ $book->category_id == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Nhà xuất bản</label>
                            <select name="publisher_id" class="form-select">
                                @foreach($publishers as $publisher)
                                    <option value="{{ $publisher->id }}"
                                        {{ $book->publisher_id == $publisher->id ? 'selected' : '' }}>
                                        {{ $publisher->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Tổng số lượng</label>
                            <input type="number" name="total_quantity"
                                   class="form-control"
                                   value="{{ $book->total_quantity }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Số lượng còn</label>
                            <input type="number" name="available_quantity"
                                   class="form-control"
                                   value="{{ $book->available_quantity }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Trạng thái</label>
                            <select name="status" class="form-select">
                                <option value="Còn sách" {{ $book->status=='Còn sách'?'selected':'' }}>
                                    Còn sách
                                </option>
                                <option value="Hết sách" {{ $book->status=='Hết sách'?'selected':'' }}>
                                    Hết sách
                                </option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Mô tả</label>
                            <textarea name="description" class="form-control">{{ $book->description }}</textarea>
                        </div>

                    </div>

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

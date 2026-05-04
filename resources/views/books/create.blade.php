@extends('layouts.master')

@section('content')

    <div class="container mt-4">

        <h2 class="fw-bold mb-4">➕ Thêm sách</h2>

        {{-- ERROR --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body">

                <form action="{{ route('books.store') }}" method="POST">
                    @csrf

                    {{-- ================== THÔNG TIN SÁCH ================== --}}
                    <h5 class="mb-3 text-primary">📘 Thông tin sách</h5>

                    <div class="row g-3">

                        <div class="col-md-12">
                            <label class="form-label">Mã sách</label>
                            <input type="text" class="form-control" value="Tự động tạo" disabled>
                        </div>

                        {{-- ISBN --}}
                        <div class="col-md-6">
                            <label class="form-label">ISBN</label>
                            <input type="text"
                                   name="isbn"
                                   class="form-control"
                                   value="{{ old('isbn') }}"
                                   placeholder="VD: 9786041234567">

                            {{--<button type="button"
                                    id="fetchIsbn"
                                    class="btn btn-sm btn-info mt-2">
                                🔎 Lấy thông tin từ ISBN
                            </button>--}}
                        </div>

                        {{-- TÊN SÁCH --}}
                        <div class="col-md-6">
                            <label class="form-label">Tên sách</label>
                            <input type="text"
                                   name="name"
                                   class="form-control"
                                   value="{{ old('name') }}"
                                   required>
                        </div>

                        {{-- NĂM --}}
                        <div class="col-md-6">
                            <label class="form-label">Năm xuất bản</label>
                            <input type="number"
                                   name="year_of_publication"
                                   class="form-control"
                                   value="{{ old('year_of_publication') }}">
                        </div>

                    </div>

                    {{-- ================== PHÂN LOẠI ================== --}}
                    <h5 class="mt-4 mb-3 text-primary">📂 Phân loại</h5>

                    <div class="row g-3">

                        {{-- AUTHOR --}}
                        <div class="col-md-4">
                            <label class="form-label">Tác giả</label>
                            <select name="author_id" class="form-select" required>
                                <option value="">-- Chọn tác giả --</option>
                                @foreach($authors as $author)
                                    <option value="{{ $author->id }}"
                                        {{ old('author_id') == $author->id ? 'selected' : '' }}>
                                        {{ $author->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- CATEGORY --}}
                        <div class="col-md-4">
                            <label class="form-label">Thể loại</label>
                            <select name="category_id" class="form-select" required>
                                <option value="">-- Chọn thể loại --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- PUBLISHER --}}
                        <div class="col-md-4">
                            <label class="form-label">Nhà xuất bản</label>
                            <select name="publisher_id" class="form-select" required>
                                <option value="">-- Chọn NXB --</option>
                                @foreach($publishers as $publisher)
                                    <option value="{{ $publisher->id }}"
                                        {{ old('publisher_id') == $publisher->id ? 'selected' : '' }}>
                                        {{ $publisher->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    {{-- ================== MÔ TẢ ================== --}}
                    <div class="mt-4">
                        <label class="form-label">Mô tả</label>
                        <textarea name="description"
                                  class="form-control"
                                  rows="4">{{ old('description') }}</textarea>
                    </div>

                    {{-- ================== BUTTON ================== --}}
                    <div class="mt-4 d-flex gap-2">
                        <button class="btn btn-success">
                            💾 Lưu
                        </button>

                        <a href="{{ route('books.index') }}" class="btn btn-secondary">
                            ← Quay lại
                        </a>
                    </div>

                </form>

            </div>
        </div>

    </div>

    {{-- ================== JS ISBN ================== --}}
    <script>
        document.getElementById('fetchIsbn').addEventListener('click', async function () {

            let isbnInput = document.querySelector('input[name="isbn"]');
            let isbn = isbnInput.value.replace(/[-\s]/g, '');

            if (!isbn) {
                alert('Vui lòng nhập ISBN');
                return;
            }

            try {

                // =========================
                // 1. GOOGLE BOOKS
                // =========================
                let res = await fetch(`https://www.googleapis.com/books/v1/volumes?q=isbn:${isbn}`);
                let data = await res.json();

                let book = null;

                if (data.items && data.items.length > 0) {
                    book = data.items[0].volumeInfo;
                }

                // =========================
                // 2. OPEN LIBRARY (fallback)
                // =========================
                if (!book) {
                    let res2 = await fetch(`https://openlibrary.org/api/books?bibkeys=ISBN:${isbn}&format=json&jscmd=data`);
                    let data2 = await res2.json();

                    let olBook = data2[`ISBN:${isbn}`];

                    if (olBook) {
                        book = {
                            title: olBook.title,
                            publishedDate: olBook.publish_date
                        };
                    }
                }

                // =========================
                // 3. KHÔNG TÌM THẤY
                // =========================
                if (!book) {
                    alert('Không tìm thấy sách từ ISBN này.\nBạn vui lòng nhập thủ công.');
                    return;
                }

                // =========================
                // 4. FILL DATA
                // =========================
                document.querySelector('input[name="name"]').value = book.title || '';

                if (book.publishedDate) {
                    document.querySelector('input[name="year_of_publication"]').value =
                        book.publishedDate.substring(0, 4);
                }

            } catch (err) {
                console.error(err);
                alert('Lỗi khi lấy dữ liệu ISBN');
            }

        });

        // =========================
        // CLEAN ISBN INPUT
        // =========================
        document.querySelector('input[name="isbn"]').addEventListener('input', function () {
            this.value = this.value.replace(/[^0-9Xx-]/g, '');
        });
    </script>

@endsection

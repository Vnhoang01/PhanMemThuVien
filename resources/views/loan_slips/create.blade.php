@extends('layouts.master')

@section('content')

    <div class="container mt-4">

        <h2 class="fw-bold mb-4">📄 Thêm phiếu mượn</h2>

        {{-- Errors --}}
        @if(session('error'))
            <div class="alert alert-danger shadow-sm">
                ❌ {{ session('error') }}
            </div>
        @endif
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

                <form action="{{ route('loan_slips.store') }}" method="POST">
                    @csrf

                    <div class="row g-3">

                        {{-- Người duyệt (auto login) --}}
                        <div class="col-md-6">
                            <label class="form-label">Người duyệt</label>
                            <input type="text"
                                   class="form-control"
                                   value="{{ auth()->user()->name }}"
                                   disabled>
                        </div>

                        {{-- CLASS --}}
                        <div class="col-md-6">
                            <label class="form-label">Lớp</label>
                            <select id="classSelect" class="form-select">
                                <option value="">-- Chọn lớp --</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Ngày mượn --}}
                        <div class="col-md-6">
                            <label class="form-label">Ngày mượn</label>
                            <input type="date" name="start_date"
                                   value="{{ old('start_date', date('Y-m-d')) }}"
                                   class="form-control @error('start_date') is-invalid @enderror" required>

                            @error('start_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- STUDENT --}}
                        <div class="col-md-6">
                            <label class="form-label">Sinh viên</label>
                            <select name="student_id" id="studentSelect" class="form-select" required>
                                <option value="">-- Chọn sinh viên --</option>

                                @foreach($students as $student)
                                    <option value="{{ $student->id }}" data-class="{{ $student->class_id }}">
                                        {{ $student->name }} - {{ $student->student_code }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Chọn sách --}}
                        <div class="col-md-6">
                            <label class="form-label">Chọn sách</label>
                            <select id="bookSelect" class="form-select">
                                <option value="" disabled selected>-- Chọn sách --</option>

                                @foreach($books as $book)
                                    <option value="{{ $book->id }}">
                                        {{ $book->name }} -
                                        {{ $book->book_code }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Hiển thị bản vật lý --}}
                        <div class="col-12">
                            <label class="form-label">Chọn bản sách</label>
                            <div class="d-flex gap-2 align-items-end">
                                <select id="bookDetailSelect" class="form-select"></select>

                                <button type="button" class="btn btn-primary px-3" style="height: 38px;"
                                        onclick="addBook()">
                                    Thêm
                                </button>
                            </div>
                        </div>

                        <div class="col-12 mt-3">
                            <label class="form-label">Danh sách sách đã chọn</label>

                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>Sách</th>
                                    <th>Thao tác</th>
                                </tr>
                                </thead>
                                <tbody id="selectedBooks"></tbody>
                            </table>
                        </div>

                        <div id="hiddenInputs"></div>

                    </div>

                    {{-- Buttons --}}
                    <div class="mt-4 d-flex gap-2">
                        <button class="btn btn-success">
                            <i class="bi bi-save"></i> Lưu
                        </button>

                        <a href="{{ route('loan_slips.index') }}" class="btn btn-secondary">
                            ← Quay lại
                        </a>
                    </div>

                </form>

            </div>
        </div>

    </div>

    {{-- JS --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const bookSelect = document.getElementById('bookSelect');
            const detailSelect = document.getElementById('bookDetailSelect');
            const tableBody = document.getElementById('selectedBooks');
            const hiddenInputs = document.getElementById('hiddenInputs');

            let selected = [];

            // =============================
            // LOAD BOOK DETAILS
            // =============================
            bookSelect.addEventListener('change', function () {

                let bookId = this.value;

                detailSelect.innerHTML = '';
                selected = []; // reset khi đổi sách

                if (!bookId) return;

                fetch(`/books/${bookId}/details`)
                    .then(res => {
                        if (!res.ok) throw new Error('HTTP ' + res.status);
                        return res.json();
                    })
                    .then(data => {

                        if (!data || data.length === 0) {
                            detailSelect.innerHTML = `<option disabled>Không còn sách</option>`;
                            return;
                        }

                        data.forEach((item, index) => {

                            let option = document.createElement('option');

                            option.value = item.id;
                            option.textContent = `${item.book.name} - 🔖 ${item.barcode}`;
                            option.dataset.book = item.book_id;

                            if (index === 0) option.selected = true;

                            detailSelect.appendChild(option);
                        });

                    })
                    .catch(err => {
                        console.error(err);
                        detailSelect.innerHTML = `<option disabled>Lỗi tải dữ liệu</option>`;
                    });
            });

            // =============================
            // ADD BOOK
            // =============================
            window.addBook = function () {

                if (detailSelect.selectedIndex === -1) {
                    alert('Không còn bản sách để chọn');
                    return;
                }

                let selectedOption = detailSelect.options[detailSelect.selectedIndex];

                let id = selectedOption.value;
                let text = selectedOption.text;
                let bookId = selectedOption.dataset.book;

                if (selected.includes(id)) {
                    alert('Đã chọn bản này');
                    return;
                }

                selected.push(id);

                // =============================
                // KHÓA TOÀN BỘ BẢN CÙNG SÁCH
                // =============================
                Array.from(detailSelect.options).forEach(opt => {
                    if (opt.dataset.book === bookId) {
                        opt.hidden = true;
                        opt.disabled = true;
                    }
                });

                // disable luôn sách ở dropdown chính
                let mainOption = bookSelect.querySelector(`option[value="${bookId}"]`);
                if (mainOption) mainOption.disabled = true;

                // chọn lại option khác
                let firstVisible = Array.from(detailSelect.options).find(opt => !opt.hidden);
                if (firstVisible) {
                    firstVisible.selected = true;
                } else {
                    detailSelect.selectedIndex = -1;
                }

                // =============================
                // TABLE ROW
                // =============================
                let row = document.createElement('tr');
                row.id = `row_${id}`;
                row.dataset.book = bookId;

                row.innerHTML = `
            <td>${text}</td>
            <td>
                <button type="button" class="btn btn-danger btn-sm">
                    Xóa
                </button>
            </td>
        `;

                row.querySelector('button').addEventListener('click', function () {
                    removeBook(id);
                });

                tableBody.appendChild(row);

                // =============================
                // HIDDEN INPUT
                // =============================
                let input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'book_details[]';
                input.value = id;
                input.id = `input_${id}`;

                hiddenInputs.appendChild(input);
            }

            // =============================
            // REMOVE BOOK
            // =============================
            window.removeBook = function (id) {

                let row = document.getElementById('row_' + id);
                if (!row) return;

                let bookId = row.dataset.book;

                selected = selected.filter(i => i != id);

                row.remove();

                let input = document.getElementById('input_' + id);
                if (input) input.remove();

                // =============================
                // MỞ LẠI TOÀN BỘ BẢN SÁCH
                // =============================
                Array.from(detailSelect.options).forEach(opt => {
                    if (opt.dataset.book === bookId) {
                        opt.hidden = false;
                        opt.disabled = false;
                    }
                });

                // mở lại dropdown chính
                let mainOption = bookSelect.querySelector(`option[value="${bookId}"]`);
                if (mainOption) mainOption.disabled = false;

                // chọn lại option đầu
                let firstVisible = Array.from(detailSelect.options).find(opt => !opt.hidden);
                if (firstVisible) firstVisible.selected = true;
            }

        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const classSelect = document.getElementById('classSelect');
            const studentSelect = document.getElementById('studentSelect');

            // Ẩn hết sinh viên ban đầu
            Array.from(studentSelect.options).forEach(opt => {
                if (opt.value) opt.hidden = true;
            });

            classSelect.addEventListener('change', function () {

                let classId = this.value;

                studentSelect.value = "";

                Array.from(studentSelect.options).forEach(opt => {

                    if (!opt.value) return;

                    opt.hidden = (opt.dataset.class != classId);

                });

            });

        });
    </script>

@endsection

@extends('layouts.master')

@section('content')

    <div class="container mt-4">

        <h2 class="fw-bold mb-4">📄 Thêm phiếu mượn</h2>

        {{-- Errors --}}
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

                        {{-- Admin --}}
                        <div class="col-md-6">
                            <label class="form-label">Admin</label>
                            <select name="admin_id"
                                    class="form-select @error('admin_id') is-invalid @enderror" required>

                                <option value="" disabled
                                    {{ old('admin_id') ? '' : 'selected' }}>
                                    -- Chọn admin --
                                </option>

                                @foreach($admins as $admin)
                                    <option value="{{ $admin->id }}"
                                        {{ old('admin_id') == $admin->id ? 'selected' : '' }}>
                                        {{ $admin->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('admin_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Student --}}
                        <div class="col-md-6">
                            <label class="form-label">Sinh viên</label>
                            <select name="student_id"
                                    class="form-select @error('student_id') is-invalid @enderror" required>

                                <option value="" disabled
                                    {{ old('student_id') ? '' : 'selected' }}>
                                    -- Chọn sinh viên --
                                </option>

                                @foreach($students as $student)
                                    <option value="{{ $student->id }}"
                                        {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                        {{ $student->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('student_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Dates --}}
                        <div class="col-md-6">
                            <label class="form-label">Ngày mượn</label>
                            <input type="date" name="start_date"
                                   value="{{ old('start_date', date('Y-m-d')) }}"
                                   class="form-control @error('start_date') is-invalid @enderror" required>
                            @error('start_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Hạn trả</label>
                            <input type="date" name="due_date"
                                   value="{{ old('due_date') }}"
                                   class="form-control @error('due_date') is-invalid @enderror">
                            @error('due_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Status --}}
                        <div class="col-md-6">
                            <label class="form-label">Trạng thái</label>
                            <select name="status" class="form-select">
                                <option value="borrowed">Đang mượn</option>
                            </select>
                        </div>

                        {{-- Books --}}
                        <div class="col-12">
                            <label class="form-label">Chọn bản sách</label>

                            <select name="book_details[]" multiple class="form-select" id="bookSelect">

                                @foreach($bookDetails as $detail)
                                    <option value="{{ $detail->id }}"
                                            data-book-id="{{ $detail->book_id }}">
                                        📘 {{ $detail->book->name }} | Mã: {{ $detail->barcode }}
                                    </option>
                                @endforeach

                            </select>

                            <small class="text-muted">
                                Mỗi dòng là 1 cuốn sách cụ thể (barcode)
                            </small>
                        </div>

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


    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const select = document.getElementById('bookSelect');

            // 1. Click lại để bỏ chọn
            select.addEventListener('mousedown', function (e) {

                if (e.target.tagName === 'OPTION') {

                    e.preventDefault();

                    // toggle chọn / bỏ chọn
                    e.target.selected = !e.target.selected;

                    // trigger change để chạy logic disable
                    select.dispatchEvent(new Event('change'));
                }
            });

            // 2. Disable các sách còn lại
            select.addEventListener('change', function () {

                let selectedOptions = [...this.selectedOptions];

                // reset tất cả
                [...this.options].forEach(opt => {
                    opt.disabled = false;
                });

                // disable các option cùng book_id
                selectedOptions.forEach(selected => {

                    let bookId = selected.dataset.bookId;

                    [...select.options].forEach(opt => {

                        if (opt.dataset.bookId === bookId && !opt.selected) {
                            opt.disabled = true;
                        }

                    });
                });
            });

        });
    </script>

@endsection

@extends('layouts.master')

@section('content')

    <div class="container mt-4">

        <h2 class="fw-bold mb-4">✏️ Cập nhật phiếu mượn</h2>

        {{-- Hiển thị lỗi validation --}}
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

                <form action="{{ route('loan_slips.update', $loan->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">

                        {{-- Admin --}}
                        <div class="col-md-6">
                            <label class="form-label">Người duyệt</label>
                            <select name="admin_id" class="form-select @error('admin_id') is-invalid @enderror">
                                <option value="">-- Chọn admin --</option>
                                @foreach($admins as $admin)
                                    <option value="{{ $admin->id }}"
                                        {{ old('admin_id', $loan->admin_id) == $admin->id ? 'selected' : '' }}>
                                        {{ $admin->name }}
                                        @if($admin->role == 'admin')
                                            (Quản trị)
                                        @else (Thủ thư) @endif
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
                            <select name="student_id" class="form-select @error('student_id') is-invalid @enderror">
                                <option value="">-- Chọn sinh viên --</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}"
                                        {{ old('student_id', $loan->student_id) == $student->id ? 'selected' : '' }}>
                                        {{ $student->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('student_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Start Date --}}
                        <div class="col-md-4">
                            <label class="form-label">Ngày mượn</label>
                            <input type="date" name="start_date"
                                   value="{{ old('start_date', $loan->start_date) }}"
                                   class="form-control @error('start_date') is-invalid @enderror">
                            @error('start_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Due Date --}}
                        <div class="col-md-4">
                            <label class="form-label">Hạn trả</label>
                            <input type="date" name="due_date"
                                   value="{{ old('due_date', $loan->due_date) }}"
                                   class="form-control @error('due_date') is-invalid @enderror">
                            @error('due_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Return Date --}}
                        <div class="col-md-4">
                            <label class="form-label">Ngày trả</label>
                            <input type="date" name="return_date"
                                   value="{{ old('return_date', $loan->return_date) }}"
                                   class="form-control @error('return_date') is-invalid @enderror">
                            @error('return_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Status --}}
                        <div class="col-md-6">
                            <label class="form-label">Trạng thái</label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror">
                                <option value="borrowed" {{ old('status', $loan->status)=='borrowed'?'selected':'' }}>
                                    Đang mượn
                                </option>
                                <option value="returned" {{ old('status', $loan->status)=='returned'?'selected':'' }}>Đã
                                    trả
                                </option>
                                <option value="overdue" {{ old('status', $loan->status)=='overdue'?'selected':'' }}>Quá
                                    hạn
                                </option>
                            </select>
                            @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label">Chọn sách</label>

                            <select name="books[]" multiple
                                    class="form-select @error('books') is-invalid @enderror"
                                    size="6">

                                @foreach($books as $book)
                                    <option value="{{ $book->id }}"
                                        {{ in_array($book->id, $loan->details->pluck('book_id')->toArray()) ? 'selected' : '' }}>
                                        {{ $book->name }}
                                    </option>
                                @endforeach

                            </select>

                            <small class="text-muted">Giữ Ctrl để chọn nhiều sách</small>
                        </div>

                    </div>

                    {{-- Buttons --}}
                    <div class="mt-4 d-flex gap-2">
                        <button class="btn btn-success">
                            <i class="bi bi-save"></i> Cập nhật
                        </button>

                        <a href="{{ route('loan_slips.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Quay lại
                        </a>
                    </div>

                </form>

            </div>
        </div>

    </div>

@endsection

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
                                    class="form-select @error('admin_id') is-invalid @enderror">
                                <option value="">-- Chọn admin --</option>
                                @foreach($admins as $admin)
                                    <option value="{{ $admin->id }}"
                                        {{ old('admin_id') == $admin->id ? 'selected' : '' }}>
                                        {{ $admin->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Student --}}
                        <div class="col-md-6">
                            <label class="form-label">Sinh viên</label>
                            <select name="student_id"
                                    class="form-select @error('student_id') is-invalid @enderror">
                                <option value="">-- Chọn sinh viên --</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}"
                                        {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                        {{ $student->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Dates --}}
                        <div class="col-md-6">
                            <label class="form-label">Ngày mượn</label>
                            <input type="date" name="start_date"
                                   value="{{ old('start_date') }}"
                                   class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Hạn trả</label>
                            <input type="date" name="due_date"
                                   value="{{ old('due_date') }}"
                                   class="form-control">
                        </div>

                        {{-- Status --}}
                        <div class="col-md-6">
                            <label class="form-label">Trạng thái</label>
                            <select name="status" class="form-select">
                                <option value="borrowed" {{ old('status')=='borrowed'?'selected':'' }}>Đang mượn
                                </option>
                            </select>
                        </div>

                        {{-- Books--}}
                        <div class="col-12">
                            <label class="form-label">Chọn sách</label>

                            <select name="books[]" multiple
                                    class="form-select @error('books') is-invalid @enderror"
                                    size="6">

                                @foreach($books as $book)
                                    <option value="{{ $book->id }}">
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

@endsection

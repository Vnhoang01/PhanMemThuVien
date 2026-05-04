@extends('layouts.master')

@section('content')

    <div class="container">

        <h2 class="fw-bold mb-4">✏️ Sửa lỗi</h2>

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

                <form action="{{ route('errors.update', $error->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Tên lỗi</label>
                        <input type="text"
                               name="name"
                               value="{{ old('name', $error->name) }}"
                               class="form-control @error('name') is-invalid @enderror"
                               required>

                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tiền phạt</label>
                        <input type="number"
                               name="fine_amount"
                               value="{{ old('fine_amount', $error->fine_amount) }}"
                               class="form-control @error('fine_amount') is-invalid @enderror"
                               required>

                        @error('fine_amount')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button class="btn btn-success">
                            <i class="bi bi-save"></i> Cập nhật
                        </button>

                        <a href="{{ route('errors.index') }}" class="btn btn-secondary">
                            ← Quay lại
                        </a>
                    </div>

                </form>

            </div>
        </div>

    </div>

@endsection

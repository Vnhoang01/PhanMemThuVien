@extends('layouts.master')

@section('content')

    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="fw-bold">⚠️ Danh sách lỗi</h2>

            <a href="{{ route('errors.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Thêm lỗi
            </a>
        </div>

        {{-- Thông báo --}}
        @if(session('success'))
            <div class="alert alert-success shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body p-0">

                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Tên lỗi</th>
                        <th>Tiền phạt</th>
                        <th>Hành động</th>
                    </tr>
                    </thead>

                    <tbody>
                    @forelse($errors as $e)
                        <tr>
                            <td>#{{ $loop->iteration }}</td>

                            <td>
                                <span class="fw-semibold">{{ $e->name }}</span>
                            </td>

                            <td class="text-danger fw-bold">
                                {{ number_format($e->fine_amount) }} ₫
                            </td>

                            <td>
                                {{-- Sửa --}}
                                <a href="{{ route('errors.edit', $e->id) }}"
                                   class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                {{-- Xóa --}}
                                <form action="{{ route('errors.destroy', $e->id) }}"
                                      method="POST"
                                      class="d-inline">
                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-sm btn-danger"
                                            onclick="return confirm('Xóa lỗi này?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">
                                Không có dữ liệu
                            </td>
                        </tr>
                    @endforelse
                    </tbody>

                </table>

            </div>
        </div>

    </div>

@endsection

@extends('layouts.master')

@section('content')

    <div class="container mt-4">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="fw-bold">🏫 Danh sách lớp</h2>

            <a href="{{ route('classes.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Thêm lớp
            </a>
        </div>

        <!-- Thông báo -->
        @if(session('success'))
            <div class="alert alert-success shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body p-0">

                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Tên lớp</th>
                        <th>Niên khóa</th>
                        <th>Chuyên ngành</th>
                        <th width="180">Hành động</th>
                    </tr>
                    </thead>

                    <tbody>
                    @forelse($classes as $class)
                        <tr>
                            <td class="text-muted">#{{ $loop->iteration }}</td>
                            <td>{{ $class->name }}</td>
                            <td>{{ $class->course_year }}</td>
                            <td>{{ $class->major->name ?? '---' }}</td>

                            <td>
                                <a href="{{ route('classes.edit',$class->id) }}"
                                   class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                <form action="{{ route('classes.destroy',$class->id) }}"
                                      method="POST"
                                      class="d-inline">
                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-sm btn-danger"
                                            onclick="return confirm('Bạn có chắc muốn xóa?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">
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

@extends('layouts.master')

@section('content')

    <div class="container mt-4">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="fw-bold">👨‍💼 Danh sách quản trị viên</h2>

            <a href="{{ route('admins.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Thêm quản trị viên
            </a>
        </div>

        <!-- Thông báo -->
        @if(session('success'))
            <div class="alert alert-success shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <!-- Search -->
        <div class="card mb-3 shadow-sm">
            <div class="card-body">
                <form method="GET" action="{{ route('admins.index') }}">
                    <div class="row g-2">
                        <div class="col-md-10">
                            <input type="text" name="keyword" value="{{ request('keyword') }}"
                                   placeholder="🔍 Tìm kiếm"
                                   class="form-control">
                        </div>
                        <div class="col-md-2 d-grid">
                            <button class="btn btn-dark">
                                <i class="bi bi-search"></i> Tìm
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white p-3 rounded shadow-sm">
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-hover align-middle">

                        <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Tên</th>
                            <th>Email</th>
                            <th>SĐT</th>
                            <th>Vai trò</th>
                            <th class="text-center">Hành động</th>
                        </tr>
                        </thead>

                        <tbody>
                        @forelse($admins as $a)
                            <tr>

                                <td class="text-muted">#{{ $loop->iteration + ($admins->currentPage() - 1) * $admins->perPage() }}</td>

                                <td class="fw-semibold">{{ $a->name }}</td>

                                <td>{{ $a->email }}</td>

                                <td>{{ $a->phone_number }}</td>

                                <td>
                                    @if($a->role == 'admin')
                                        <span class="badge bg-danger">
                                        <i class="bi bi-shield-lock"></i> Quản trị
                                    </span>
                                    @else
                                        <span class="badge bg-success">
                                        <i class="bi bi-person-badge"></i> Thủ thư
                                    </span>
                                    @endif
                                </td>

                                <td class="text-center">

                                    <a href="{{ route('admins.edit',$a->id) }}"
                                       class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <form action="{{ route('admins.destroy',$a->id) }}"
                                          method="POST"
                                          class="d-inline">
                                        @csrf
                                        @method('DELETE')

                                        <button class="btn btn-sm btn-danger"
                                                onclick="return confirm('Bạn có chắc muốn xoá?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>

                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">
                                    Không có dữ liệu
                                </td>
                            </tr>
                        @endforelse
                        </tbody>

                    </table>
                </div>

            </div>
        </div>

    </div>

@endsection

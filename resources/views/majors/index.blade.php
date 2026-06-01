@extends('layouts.master')

@section('content')

    <div class="container mt-4">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="fw-bold">🎓 Danh sách chuyên ngành</h2>

            <a href="{{ route('majors.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Thêm chuyên ngành
            </a>
        </div>

        <!-- Thông báo -->
        @if(session('success'))
            <div class="alert alert-success shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <!-- Tìm kiếm -->
        <div class="card mb-3 shadow-sm">
            <div class="card-body">
                <form method="GET" action="{{ route('majors.index') }}">
                    <div class="row g-2">

                        <div class="col-md-10 position-relative">

                            <input type="text"
                                   name="keyword"
                                   value="{{ request('keyword') }}"
                                   placeholder="🔍 Tìm chuyên ngành"
                                   class="form-control pe-5">

                            @if(request('keyword'))
                                <a href="{{ route('majors.index') }}"
                                   class="search-clear">
                                    &times;
                                </a>
                            @endif

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

        <!-- Card -->
        <div class="card shadow-sm">
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-hover align-middle">

                        <thead class="table-dark text-center">
                        <tr>
                            <th>#</th>
                            <th>Tên</th>
                            <th>Mô tả</th>
                            <th>Hành động</th>
                        </tr>
                        </thead>

                        <tbody class="text-center">
                        @forelse($majors as $major)
                            <tr>
                                <td class="text-center text-muted">
                                    #{{ $loop->iteration }}
                                </td>

                                <td class="fw-semibold">
                                    {{ $major->name }}
                                </td>

                                <td>
                                    {{ \Illuminate\Support\Str::limit($major->description, 80) }}
                                </td>

                                <td class="text-center">
                                    <a href="{{ route('majors.edit',$major->id) }}"
                                       class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <form action="{{ route('majors.destroy',$major->id) }}"
                                          method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-sm btn-danger"
                                                onclick="return confirm('Bạn có chắc muốn xoá?')">
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

        <div class="d-flex justify-content-center mt-3">
            {{ $majors->links() }}
        </div>

    </div>

@endsection

@extends('layouts.master')

@section('content')

    <div class="container mt-4">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="fw-bold">📚 Thể loại sách</h2>

            <a href="{{ route('categories.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Thêm thể loại
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
                <form method="GET" action="{{ route('categories.index') }}">
                    <div class="row g-2">

                        <div class="col-md-10 position-relative">

                            <input type="text"
                                   name="keyword"
                                   value="{{ request('keyword') }}"
                                   placeholder="🔍 Tìm thể loại"
                                   class="form-control pe-5">

                            @if(request('keyword'))
                                <a href="{{ route('categories.index') }}"
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
                            <th style="width:5%">#</th>
                            <th style="width:30%">Tên thể loại</th>
                            <th style="width:45%">Mô tả</th>
                            <th style="width:20%">Hành động</th>
                        </tr>
                        </thead>

                        <tbody>
                        @forelse($categories as $category)
                            <tr>

                                <td class="text-center text-muted">
                                    #{{ $loop->iteration }}
                                </td>

                                <td class="text-center fw-semibold">
                                    {{ $category->name }}
                                </td>

                                <td>
                                    {{ \Illuminate\Support\Str::limit($category->description, 60) }}
                                </td>

                                <td class="text-center">
                                    <a href="{{ route('categories.edit',$category->id) }}"
                                       class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <form action="{{ route('categories.destroy',$category->id) }}"
                                          method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Xoá?')">
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
            {{ $categories->links() }}
        </div>

    </div>

@endsection

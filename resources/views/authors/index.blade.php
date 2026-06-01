@extends('layouts.master')

@section('content')

    <div class="container mt-4">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="fw-bold">✍️ Danh sách tác giả</h2>

            <a href="{{ route('authors.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Thêm tác giả
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
                <form method="GET" action="{{ route('authors.index') }}">
                    <div class="row g-2">

                        <div class="col-md-10 position-relative">

                            <input type="text"
                                   name="keyword"
                                   value="{{ request('keyword') }}"
                                   placeholder="🔍 Tìm tác giả"
                                   class="form-control pe-5">

                            @if(request('keyword'))
                                <a href="{{ route('authors.index') }}"
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

        <div class="card shadow-sm">
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-hover align-middle">

                        <thead class="table-dark">
                        <tr>
                            <th style="width:5%" class="text-center">#</th>
                            <th style="width:35%">Tên</th>
                            <th style="width:30%">Mô tả</th>
                            <th style="width:15%" class="text-center">Hành động</th>
                        </tr>
                        </thead>

                        <tbody>
                        @forelse($authors as $author)
                            <tr>
                                <td class="text-muted text-center" >#{{ $loop->iteration }}</td>

                                <td class="fw-semibold">{{ $author->name }}</td>

                                <td>{{ $author->description }}</td>

                                <td class="text-center">
                                    <a href="{{ route('authors.edit',$author->id) }}"
                                       class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <form action="{{ route('authors.destroy',$author->id) }}"
                                          method="POST" class="d-inline">
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

        <div class="d-flex justify-content-center mt-3">
            {{ $authors->links() }}
        </div>

    </div>

@endsection

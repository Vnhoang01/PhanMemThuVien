@extends('layouts.master')

@section('content')

    <div class="container mt-4">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="fw-bold">📚 Danh sách sách</h2>

            <a href="{{ route('books.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Thêm sách
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-hover align-middle">

                        <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Tên</th>
                            <th>Tác giả</th>
                            <th>Thể loại</th>
                            <th>NXB</th>
                            <th>Năm</th>
                            <th>Tổng sách</th>
                            <th>Còn sách</th>
                            <th>Trạng thái</th>
                            <th class="text-center">Hành động</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($books as $book)
                            <tr>
                                <td class="text-muted">#{{ $loop->iteration }}</td>

                                <td class="fw-semibold">{{ $book->name }}</td>

                                <td>{{ $book->author?->name}}</td>

                                <td>
                                    <span class="badge bg-info text-dark px-2 py-1">
                                        {{ $book->category?->name}}
                                    </span>
                                </td>

                                <td>{{ $book->publisher?->name}}</td>

                                <td>{{ $book->year_of_publication }}</td>

                                <td>{{ $book->total_quantity }}</td>

                                <td>
                                <span class="badge bg-success">
                                    {{ $book->available_quantity }}
                                </span>
                                </td>

                                <td>
                                    @if($book->status == 'Còn sách')
                                        <span class="badge bg-success">Còn sách</span>
                                    @else
                                        <span class="badge bg-danger">Hết sách</span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    <a href="{{ route('books.edit',$book->id) }}"
                                       class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <form action="{{ route('books.destroy',$book->id) }}"
                                          method="POST"
                                          class="d-inline">
                                        @csrf
                                        @method('DELETE')

                                        <button class="btn btn-sm btn-danger"
                                                onclick="return confirm('Xoá sách?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>

                    </table>
                </div>

            </div>
        </div>

    </div>

@endsection

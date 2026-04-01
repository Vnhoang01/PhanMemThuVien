@extends('layouts.master')

@section('content')

    <div class="container mt-4">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="fw-bold">📚 Danh sách sách</h2>

            <a href="{{ route('books.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Thêm sách
            </a>
        </div>

        <!-- Thông báo -->
        @if(session('success'))
            <div class="alert alert-success shadow-sm">
                {{ session('success') }}
            </div>
        @endif

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
                            <th>Năm xuất bản</th>
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
                                    <button class="btn btn-sm btn-info"
                                            data-bs-toggle="modal"
                                            data-bs-target="#bookDetailModal{{ $book->id }}">
                                        <i class="bi bi-eye"></i>
                                    </button>

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

    <!-- Modal Chi tiết sách -->
    @foreach($books as $book)
        <div class="modal fade" id="bookDetailModal{{ $book->id }}" tabindex="-1">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">

                    <!-- HEADER -->
                    <div class="modal-header bg-dark text-white">
                        <h5 class="modal-title">
                            📚 {{ $book->name }}
                        </h5>
                        <button type="button" class="btn-close btn-close-white"
                                data-bs-dismiss="modal"></button>
                    </div>

                    <!-- BODY -->
                    <div class="modal-body">

                        <!-- Thông tin sách -->
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <strong>Tác giả:</strong><br>
                                {{ $book->author?->name }}
                            </div>

                            <div class="col-md-4">
                                <strong>Thể loại:</strong><br>
                                <span class="badge bg-info">
                            {{ $book->category?->name }}
                        </span>
                            </div>

                            <div class="col-md-4">
                                <strong>Nhà xuất bản:</strong><br>
                                {{ $book->publisher?->name }}
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-3">
                                <strong>Năm xuất bản:</strong><br>
                                {{ $book->year_of_publication }}
                            </div>

                            <div class="col-md-3">
                                <strong>Tổng sách:</strong><br>
                                {{ $book->total_quantity }}
                            </div>

                            <div class="col-md-3">
                                <strong>Còn sách:</strong><br>
                                <span class="badge bg-success">
                            {{ $book->available_quantity }}
                        </span>
                            </div>

                            <div class="col-md-3">
                                <strong>Trạng thái:</strong><br>
                                @if($book->status == 'Còn sách')
                                    <span class="badge bg-success">Còn sách</span>
                                @else
                                    <span class="badge bg-danger">Hết sách</span>
                                @endif
                            </div>
                        </div>

                        <!-- Mô tả -->
                        <div class="mb-3">
                            <strong>Mô tả:</strong>
                            <p class="text-muted">
                                {{ $book->description ?? 'Không có mô tả' }}
                            </p>
                        </div>

                        <hr>

                        <!-- Danh sách book_detail -->
                        <h5>📖 Danh sách bản sao (Book Detail)</h5>

                        <table class="table table-bordered">
                            <thead class="table-secondary">
                            <tr>
                                <th>#</th>
                                <th>Barcode</th>
                                <th>Tên</th>
                                <th>Trạng thái</th>
                            </tr>
                            </thead>

                            <tbody>
                            @forelse($book->bookDetails as $detail)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $detail->barcode }}</td>
                                    <td>{{ $detail->name }}</td>

                                    <td>
                                        @if($detail->status == 'available')
                                            <span class="badge bg-success">🟢 Có sẵn</span>
                                        @elseif($detail->status == 'borrowed')
                                            <span class="badge bg-warning text-dark">🟡 Đang mượn</span>
                                        @else
                                            <span class="badge bg-danger">🔴 Mất/Hỏng</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">
                                        Không có bản sao
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>

                    </div>

                    <!-- FOOTER -->
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">
                            Đóng
                        </button>
                    </div>

                </div>
            </div>
        </div>
    @endforeach

@endsection

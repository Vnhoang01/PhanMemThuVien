@extends('layouts.layout_student')

@section('title', 'Phiếu mượn sách')

@section('content')

    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">

            @if(session('success'))

                <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm mb-4"
                     role="alert">

                    ✅ {{ session('success') }}

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="alert">
                    </button>

                </div>

            @endif

            @if(session('error'))

                <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm mb-4"
                     role="alert">

                    ❌ {{ session('error') }}

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="alert">
                    </button>

                </div>

            @endif

            <h2 class="fw-bold">
                📚 Phiếu mượn sách
            </h2>

        </div>

        @if(count($slip) > 0)

            <div class="card shadow-sm border-0 rounded-4">

                <div class="card-body p-4">

                    <form action="{{ route('student.slip.submit') }}"
                          method="POST">

                        @csrf

                        {{-- THÔNG TIN PHIẾU --}}
                        <div class="row mb-4">

                            <div class="col-md-6">

                                <label class="fw-bold mb-2">
                                    📅 Ngày mượn
                                </label>

                                <input type="date"
                                       class="form-control"
                                       value="{{ now()->format('Y-m-d') }}"
                                       readonly>

                            </div>

                            <div class="col-md-6">

                                <label class="fw-bold mb-2">
                                    📆 Ngày trả dự kiến
                                </label>

                                <input type="date"
                                       name="return_date"
                                       class="form-control"
                                       value="{{ now()->addDays(14)->format('Y-m-d') }}"
                                       min="{{ now()->addDay()->format('Y-m-d') }}"
                                       required>

                            </div>

                        </div>

                        {{-- DANH SÁCH SÁCH --}}
                        <div class="table-responsive">

                            <table class="table align-middle table-hover">

                                <thead class="table-dark">

                                <tr>
                                    <th width="60">#</th>
                                    <th>Mã sách</th>
                                    <th>ISBN</th>
                                    <th>Tên sách</th>
                                    <th>Tác giả</th>
                                    <th>Thể loại</th>
                                    <th width="100">Xóa</th>
                                </tr>

                                </thead>

                                <tbody>

                                @foreach($slip as $book)

                                    <tr>

                                        <td>
                                            {{ $loop->iteration }}
                                        </td>

                                        <td>

                                            <span class="badge bg-primary">
                                                {{ $book['barcode'] }}
                                            </span>

                                        </td>

                                        <td>
                                            {{ $book['isbn'] }}
                                        </td>

                                        <td>

                                            <div class="fw-bold">
                                                {{ $book['name'] }}
                                            </div>

                                        </td>

                                        <td>
                                            {{ $book['author'] }}
                                        </td>

                                        <td>
                                            {{ $book['category'] }}
                                        </td>

                                        <td>

                                            <a href="{{ route('student.slip.remove', $book['id']) }}"
                                               class="btn btn-sm btn-danger rounded-3"
                                               onclick="return confirm('Xóa sách khỏi phiếu mượn?')">

                                                🗑

                                            </a>

                                        </td>

                                    </tr>

                                    <input type="hidden"
                                           name="book_ids[]"
                                           value="{{ $book['id'] }}">

                                @endforeach

                                </tbody>

                            </table>

                        </div>

                        {{-- ACTION --}}
                        <div class="mt-4 d-flex gap-2">

                            <button type="submit"
                                    class="btn btn-success btn-lg rounded-3">

                                📚 Đăng kí phiếu mượn

                            </button>

                            <a href="{{ route('student.slip.clear') }}"
                               class="btn btn-danger btn-lg rounded-3"
                               onclick="return confirm('Xóa toàn bộ phiếu mượn?')">

                                🗑 Xóa phiếu mượn

                            </a>

                        </div>

                    </form>

                </div>

            </div>

        @else

            <div class="card shadow-sm border-0 rounded-4">

                <div class="card-body text-center py-5">

                    <h4 class="text-muted">
                        Chưa có sách trong phiếu mượn
                    </h4>

                    <a href="{{ route('student.borrow') }}"
                       class="btn btn-primary mt-3 rounded-3">

                        ➕ Chọn sách

                    </a>

                </div>

            </div>

        @endif

    </div>

@endsection

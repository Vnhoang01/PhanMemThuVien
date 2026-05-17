@extends('layouts.master')

@section('content')

    <div class="container mt-4">

        <h2 class="mb-4 fw-bold">
            📊 Tổng quan hệ thống
        </h2>

        <div class="row g-4">

            <!-- Tổng sách -->
            <div class="col-md-3">
                <div class="card dashboard-card bg-primary text-white shadow">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase">
                                Tổng số sách
                            </h6>

                            <h2 class="fw-bold">
                                {{ $totalBooks }}
                            </h2>
                        </div>

                        <i class="bi bi-book fs-1 opacity-75"></i>

                    </div>
                </div>
            </div>

            <!-- Sinh viên -->
            <div class="col-md-3">
                <div class="card dashboard-card bg-success text-white shadow">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase">
                                Sinh viên
                            </h6>

                            <h2 class="fw-bold">
                                {{ $totalStudents }}
                            </h2>
                        </div>

                        <i class="bi bi-people fs-1 opacity-75"></i>

                    </div>
                </div>
            </div>

            <!-- Sách còn -->
            <div class="col-md-3">
                <div class="card dashboard-card bg-warning text-dark shadow">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase">
                                Sách còn
                            </h6>

                            <h2 class="fw-bold">
                                {{ $availableBooks }}
                            </h2>
                        </div>

                        <i class="bi bi-check-circle fs-1 opacity-75"></i>

                    </div>
                </div>
            </div>

            <!-- Đang mượn -->
            <div class="col-md-3">
                <div class="card dashboard-card bg-danger text-white shadow">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase">
                                Đang mượn
                            </h6>

                            <h2 class="fw-bold">
                                {{ $totalBorrowed }}
                            </h2>
                        </div>

                        <i class="bi bi-journal-arrow-down fs-1 opacity-75"></i>

                    </div>
                </div>
            </div>
        </div>

        {{-- THỐNG KÊ --}}
        <div class="row mt-5">
            {{-- TOP SÁCH --}}
            <div class="col-md-6">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        📚 Sách được mượn nhiều nhất
                    </div>

                    <div class="card-body p-0">
                        <table class="table table-hover table-bordered mb-0">
                            <thead class="table-light">
                            <tr>
                                <th width="10%">#</th>
                                <th>Tên sách</th>
                                <th width="25%">Lượt mượn</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($topBooks as $index => $book)
                                <tr>
                                    <td>
                                        {{ $index + 1 }}
                                    </td>

                                    <td>
                                        {{ $book->name }}
                                    </td>

                                    <td>
                                        <span class="badge bg-success px-3 py-2">
                                            {{ $book->total_borrow }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3"
                                        class="text-center text-muted py-3">
                                        Chưa có dữ liệu
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- TOP LỖI --}}
            <div class="col-md-6">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-danger text-white">
                        ⚠️ Lỗi xuất hiện nhiều nhất
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-hover table-bordered mb-0">
                            <thead class="table-light">
                            <tr>
                                <th width="10%">#</th>
                                <th>Tên lỗi</th>
                                <th width="25%">Số lần</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($topErrors as $index => $error)
                                <tr>
                                    <td>
                                        {{ $index + 1 }}
                                    </td>
                                    <td>
                                        {{ $error->name }}
                                    </td>
                                    <td>
                                        <span class="badge bg-danger px-3 py-2">
                                            {{ $error->total_error }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3"
                                        class="text-center text-muted py-3">
                                        Chưa có dữ liệu
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.master')

@section('content')

    <div class="container mt-4">

        <h2 class="mb-4 fw-bold">
            📊 Tổng quan hệ thống
        </h2>

        <div class="card shadow-sm mb-4">

            <div class="card-body">

                <form method="GET">

                    <div class="row g-3 align-items-end">

                        <div class="col-md-3">

                            <label class="form-label">
                                Chọn tháng
                            </label>

                            <input type="month"
                                   name="month"
                                   class="form-control"
                                   value="{{ $selectedMonth }}">

                        </div>

                        <div class="col-md-2">

                            <button class="btn btn-primary w-100">
                                Lọc
                            </button>

                        </div>

                    </div>

                </form>

            </div>

        </div>

        {{-- THỐNG KÊ TỔNG QUAN --}}
        <div class="row g-4">

            {{-- Tổng sách --}}
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

            {{-- Sách còn --}}
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

            {{-- Đang duyệt --}}
            <div class="col-md-3">

                <div class="card dashboard-card bg-info text-white shadow">

                    <div class="card-body d-flex justify-content-between align-items-center">

                        <div>

                            <h6 class="text-uppercase">
                                Đang duyệt
                            </h6>

                            <h2 class="fw-bold">
                                {{ $totalPending }}
                            </h2>

                        </div>

                        <i class="bi bi-hourglass-split fs-1 opacity-75"></i>

                    </div>

                </div>

            </div>

            {{-- Đang mượn --}}
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

        {{-- TOP THỐNG KÊ --}}
        <div class="row mt-5">

            {{-- TOP SÁCH --}}
            <div class="col-md-6">

                <div class="card shadow-sm border-0">

                    <div class="card-header bg-primary text-white">
                        📚 Sách được mượn nhiều nhất
                        (Tháng {{ \Carbon\Carbon::createFromFormat('Y-m', $selectedMonth)->format('m/Y') }})
                    </div>

                    <div class="card-body p-0">

                        <table class="table table-hover table-bordered mb-0">

                            <thead class="table-light">

                            <tr>

                                <th width="10%">
                                    #
                                </th>

                                <th>
                                    Tên sách
                                </th>

                                <th width="25%">
                                    Lượt mượn
                                </th>

                            </tr>

                            </thead>

                            <tbody>

                            @forelse($topBooks as $index => $book)

                                <tr>

                                    <td class="align-middle">
                                        {{ $index + 1 }}
                                    </td>

                                    <td class="align-middle">
                                        <div class="d-flex align-items-center">

                                            @if($book->image)
                                                <img src="{{ asset('storage/' . $book->image) }}"
                                                     class="book-thumb me-3"
                                                     alt="{{ $book->name }}">
                                            @endif

                                            <div>
                                                <button
                                                    class="btn btn-link p-0 text-decoration-none fw-semibold text-start view-book"
                                                    data-id="{{ $book->id }}">
                                                    {{ $book->name }}
                                                </button>
                                            </div>

                                        </div>
                                    </td>

                                    <td class="align-middle">
                                        <span class="badge bg-success fs-6 px-3 py-2">
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
                        (Tháng {{ \Carbon\Carbon::createFromFormat('Y-m', $selectedMonth)->format('m/Y') }})
                    </div>

                    <div class="card-body p-0">

                        <table class="table table-hover table-bordered mb-0">

                            <thead class="table-light">

                            <tr>

                                <th width="10%">
                                    #
                                </th>

                                <th>
                                    Tên lỗi
                                </th>

                                <th width="25%">
                                    Số lần
                                </th>

                            </tr>

                            </thead>

                            <tbody>

                            @forelse($topErrors as $index => $error)

                                <tr>

                                    <td>
                                        {{ $index + 1 }}
                                    </td>

                                    <td>

                                        <button
                                            class="btn btn-link text-decoration-none fw-semibold p-0 view-error"
                                            data-id="{{ $error->id }}">

                                            {{ $error->name }}

                                        </button>

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

            <div class="card shadow-sm border-0 mt-4">

                <div class="card-header bg-success text-white">
                    🏆 Sinh viên mượn nhiều nhất
                    (Tháng {{ \Carbon\Carbon::createFromFormat('Y-m', $selectedMonth)->format('m/Y') }})
                </div>

                <div class="card-body p-0">

                    <table class="table table-hover table-bordered mb-0">

                        <thead class="table-light">

                        <tr>
                            <th width="10%">#</th>
                            <th>Mã SV</th>
                            <th>Họ tên</th>
                            <th width="20%">Lượt mượn</th>
                        </tr>

                        </thead>

                        <tbody>

                        @forelse($topStudents as $index => $student)

                            <tr>

                                <td>{{ $index + 1 }}</td>

                                <td>{{ $student->student_code }}</td>

                                <td>{{ $student->name }}</td>

                                <td>
                        <span class="badge bg-success">
                            {{ $student->total_borrow }}
                        </span>
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="4" class="text-center">
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

    {{-- MODAL --}}
    <div class="modal fade"
         id="bookModal"
         tabindex="-1">

        <div class="modal-dialog">

            <div class="modal-content">

                <div class="modal-header bg-primary text-white">

                    <h5 class="modal-title">
                        📚 Thống kê sách
                    </h5>

                    <button type="button"
                            class="btn-close btn-close-white"
                            data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <div id="bookContent">

                        <div class="text-center py-5">

                            <div class="spinner-border text-primary"></div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="modal fade"
         id="errorModal"
         tabindex="-1">

        <div class="modal-dialog modal-lg">

            <div class="modal-content">

                <div class="modal-header bg-danger text-white">

                    <h5 class="modal-title">
                        ⚠️ Danh sách sách bị lỗi
                    </h5>

                    <button type="button"
                            class="btn-close btn-close-white"
                            data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <div id="errorContent">

                        <div class="text-center py-5">
                            <div class="spinner-border text-danger"></div>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <script>

        document.addEventListener('DOMContentLoaded', function () {

            const modal = new bootstrap.Modal(
                document.getElementById('bookModal')
            );

            const content = document.getElementById('bookContent');

            document.querySelectorAll('.view-book')
                .forEach(button => {

                    button.addEventListener('click', function () {

                        let id = this.dataset.id;

                        content.innerHTML = `
<div class="text-center py-5">
    <div class="spinner-border text-primary"></div>
</div>
                                            `;

                        modal.show();

                        fetch(`/dashboard/book/${id}`)

                            .then(res => {

                                if (!res.ok) {
                                    throw new Error('Lỗi tải dữ liệu');
                                }

                                return res.json();

                            })

                            .then(data => {

                                let book = data.book;
                                let stats = data.statistics;

                                content.innerHTML = `
    <table class="table table-bordered table-hover align-middle">

        <thead class="table-dark text-center">
            <tr>
                <th>Tên sách</th>
                <th>Tổng</th>
                <th>Nguyên vẹn</th>
                <th>Đang mượn</th>
                <th>Hỏng</th>
                <th>Mất</th>
            </tr>
        </thead>

        <tbody>
            <tr class="text-center">

                <td>
                    <div class="d-flex align-items-center gap-3">

                        ${
                                    book.image
                                        ? `<img
                                        src="/storage/${book.image}"
                                        class="error-book-thumb"
                                        alt="${book.name}">
                                  `
                                        : ''
                                }

                        <span class="fw-semibold">
                            ${book.name}
                        </span>

                    </div>
                </td>

                <td>
                    <span class="badge bg-dark px-3 py-2">
                        ${stats.total}
                    </span>
                </td>

                <td>
                    <span class="badge bg-success px-3 py-2">
                        ${stats.available}
                    </span>
                </td>

                <td>
                    <span class="badge bg-primary px-3 py-2">
                        ${stats.borrowed}
                    </span>
                </td>

                <td>
                    <span class="badge bg-warning text-dark px-3 py-2">
                        ${stats.damaged}
                    </span>
                </td>

                <td>
                    <span class="badge bg-danger px-3 py-2">
                        ${stats.lost}
                    </span>
                </td>

            </tr>
        </tbody>

    </table>
`;

                            })

                            .catch(error => {

                                content.innerHTML = `

            <div class="alert alert-danger text-center">

                Không tải được dữ liệu

            </div>

        `;

                                console.log(error);

                            });

                    });

                });

            const errorModal = new bootstrap.Modal(
                document.getElementById('errorModal')
            );

            const errorContent = document.getElementById('errorContent');

            document.querySelectorAll('.view-error')
                .forEach(button => {

                    button.addEventListener('click', function () {

                        const id = this.dataset.id;

                        errorModal.show();

                        fetch(`/dashboard/error/${id}`)
                            .then(res => res.json())
                            .then(data => {

                                let html = `
                        <h5 class="mb-3">
                            Lỗi: ${data.error.name}
                        </h5>

                        <table class="table table-bordered table-hover align-middle">

                            <thead class="table-dark">
                                <tr>
                                    <th>Tên sách</th>
                                    <th width="25%">
                                        Số lần bị lỗi
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                    `;

                                data.books.forEach(book => {

                                    html += `
                            <tr>

                                <td>
                                    <div class="d-flex align-items-center gap-3">

                                        ${
                                        book.image
                                            ? `
                                                    <img
                                                        src="/storage/${book.image}"
                                                        class="error-book-thumb"
                                                        alt="${book.name}">
                                                `
                                            : ''
                                    }

                                        <span class="fw-semibold">
                                            ${book.name}
                                        </span>

                                    </div>
                                </td>

                                <td class="text-center">
                                    <span class="badge bg-danger">
                                        ${book.total}
                                    </span>
                                </td>

                            </tr>
                        `;
                                });

                                html += `
                            </tbody>

                        </table>
                    `;

                                errorContent.innerHTML = html;

                            })
                            .catch(error => {

                                errorContent.innerHTML = `
                        <div class="alert alert-danger text-center">
                            Không tải được dữ liệu
                        </div>
                    `;

                                console.error(error);

                            });

                    });

                });

        });

    </script>

@endsection

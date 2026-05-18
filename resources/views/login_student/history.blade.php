@extends('layouts.layout_student')

@section('title', 'Lịch sử mượn sách')

@section('content')

    <div class="container py-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">
                📚 Lịch sử phiếu mượn
            </h2>
        </div>


        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="card shadow-sm border-0">

            <div class="card-body p-0">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Ngày mượn</th>
                        <th>Hạn trả</th>
                        <th>Ngày trả</th>
                        <th>Người duyệt</th>
                        <th>Trạng thái</th>
                        <th>Tổng phạt</th>
                        <th>Chi tiết</th>
                    </tr>
                    </thead>

                    <tbody>

                    @forelse($loanSlips as $loan)

                        <tr>

                            <td>#{{ $loop->iteration }}</td>

                            <td>
                                {{ \Carbon\Carbon::parse($loan->start_date)->format('d/m/Y') }}
                            </td>

                            <td>
                                {{ $loan->due_date
                                    ? \Carbon\Carbon::parse($loan->due_date)->format('d/m/Y')
                                    : '---' }}
                            </td>

                            <td>
                                {{ $loan->return_date
                                    ? \Carbon\Carbon::parse($loan->return_date)->format('d/m/Y')
                                    : 'Chưa trả' }}
                            </td>

                            <td>
                                {{ $loan->admin?->name ?? 'Chưa duyệt' }}
                            </td>

                            <td>

                                @if($loan->status == 'pending')

                                    <span class="badge bg-info">
                                        ⏳ Đang duyệt
                                    </span>

                                @elseif($loan->status == 'borrowing')

                                    <span class="badge bg-warning text-dark">
                                        📕 Đang mượn
                                    </span>

                                @elseif($loan->status == 'returned')

                                    <span class="badge bg-success">
                                        ✅ Đã trả
                                    </span>

                                @elseif($loan->status == 'overdue')

                                    <span class="badge bg-danger">
                                        ⚠ Quá hạn
                                    </span>

                                @endif

                            </td>

                            <td class="text-danger fw-bold">
                                {{ number_format($loan->total_fine, 0) }} ₫
                            </td>

                            <td>

                                <button class="btn btn-sm btn-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#loanModal{{ $loan->id }}">

                                    <i class="bi bi-eye"></i> Xem

                                </button>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                Chưa có lịch sử mượn sách
                            </td>
                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

    {{-- MODAL --}}
    @foreach($loanSlips as $loan)

        <div class="modal fade"
             id="loanModal{{ $loan->id }}"
             tabindex="-1">

            <div class="modal-dialog modal-lg">

                <div class="modal-content">

                    <div class="modal-header bg-dark text-white">

                        <h5 class="modal-title">
                            📄 Chi tiết phiếu mượn #{{ $loan->id }}
                        </h5>

                        <button class="btn-close btn-close-white"
                                data-bs-dismiss="modal">
                        </button>

                    </div>

                    <div class="modal-body">

                        <div class="row mb-3">

                            <div class="col-md-6">
                                <strong>Ngày mượn:</strong><br>
                                {{ \Carbon\Carbon::parse($loan->start_date)->format('d/m/Y') }}
                            </div>

                            <div class="col-md-6">
                                <strong>Trạng thái:</strong><br>

                                @if($loan->status == 'pending')

                                    <span class="badge bg-info">
                                        ⏳ Đang duyệt
                                    </span>

                                @elseif($loan->status == 'borrowing')

                                    <span class="badge bg-warning text-dark">
                                        📕 Đang mượn
                                    </span>

                                @elseif($loan->status == 'returned')

                                    <span class="badge bg-success">
                                        ✅ Đã trả
                                    </span>

                                @endif

                            </div>

                        </div>

                        <hr>

                        <h5 class="mb-3">
                            📚 Danh sách sách
                        </h5>

                        <table class="table table-bordered">

                            <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Barcode</th>
                                <th>Tên sách</th>
                                <th>Tình trạng</th>
                                <th>Tiền phạt</th>
                            </tr>
                            </thead>

                            <tbody>

                            @foreach($loan->details as $detail)

                                @php
                                    $fine = $detail->errors->sum('pivot.fine_amount');
                                @endphp

                                <tr>

                                    <td>{{ $loop->iteration }}</td>

                                    <td>
                                        {{ $detail->bookDetail?->barcode }}
                                    </td>

                                    <td>
                                        {{ $detail->bookDetail?->book?->name }}
                                    </td>

                                    <td>

                                        @if($loan->status == 'pending')

                                            <span class="badge bg-info">
                                                ⏳ Chờ duyệt
                                            </span>

                                        @elseif($loan->status == 'borrowing')

                                            <span class="badge bg-warning text-dark">
                                                📕 Đang mượn
                                            </span>

                                        @elseif($loan->status == 'returned')

                                            @if($detail->errors->count() > 0)

                                                @foreach($detail->errors as $error)

                                                    <span class="text-danger fw-semibold">
                                                        {{ $error->name }}
                                                    </span><br>

                                                @endforeach

                                            @else

                                                <span class="text-success fw-semibold">
                                                    🟢 Nguyên vẹn
                                                </span>

                                            @endif

                                        @endif

                                    </td>

                                    <td class="text-danger">
                                        {{ number_format($fine, 0) }} ₫
                                    </td>

                                </tr>

                            @endforeach

                            </tbody>

                        </table>

                        <div class="text-end mt-3">

                            <h5 class="text-danger fw-bold">
                                Tổng tiền phạt:
                                {{ number_format($loan->total_fine, 0) }} ₫
                            </h5>

                        </div>

                    </div>

                    <div class="modal-footer">

                        <button class="btn btn-secondary"
                                data-bs-dismiss="modal">

                            Đóng

                        </button>

                    </div>

                </div>

            </div>

        </div>

    @endforeach

@endsection

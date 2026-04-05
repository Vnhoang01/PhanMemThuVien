@extends('layouts.master')

@section('content')

    <div class="container mt-4">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="fw-bold">📄 Danh sách phiếu mượn</h2>

            <a href="{{ route('loan_slips.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Thêm phiếu mượn
            </a>
        </div>

        {{-- Thông báo --}}
        @if(session('success'))
            <div class="alert alert-success shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body p-0">

                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Người duyệt</th>
                        <th>Sinh viên</th>
                        <th>Ngày mượn</th>
                        <th>Hạn trả</th>
                        <th>Ngày trả</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                    </thead>

                    <tbody>
                    @forelse($loanSlips as $loan)
                        <tr>
                            <td>#{{ $loop->iteration }}</td>

                            <td>{{ $loan->admin->name ?? '---' }}</td>

                            <td>{{ $loan->student->name ?? '---' }}</td>

                            <td>
                                {{ \Carbon\Carbon::parse($loan->start_date)->format('d/m/Y') }}
                            </td>

                            <td>
                                {{ $loan->due_date ? \Carbon\Carbon::parse($loan->due_date)->format('d/m/Y') : '---' }}
                            </td>

                            <td>
                                {{ $loan->return_date
                                    ? \Carbon\Carbon::parse($loan->return_date)->format('d/m/Y')
                                    : 'Chưa trả' }}
                            </td>

                            <td>
                                @switch($loan->status)
                                    @case('borrowed')
                                        <span class="badge bg-warning text-dark">Đang mượn</span>
                                        @break
                                    @case('returned')
                                        <span class="badge bg-success">Đã trả</span>
                                        @break
                                    @case('overdue')
                                        <span class="badge bg-danger">Quá hạn</span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary">{{ $loan->status }}</span>
                                @endswitch
                            </td>

                            <td>
                                {{-- Xem chi tiết --}}
                                <button class="btn btn-sm btn-info"
                                        data-bs-toggle="modal"
                                        data-bs-target="#loanDetailModal{{ $loan->id }}">
                                    <i class="bi bi-eye"></i>
                                </button>

                                {{-- Sửa --}}
                                <a href="{{ route('loan_slips.edit', $loan->id) }}"
                                   class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                {{-- Xóa --}}
                                <form action="{{ route('loan_slips.destroy', $loan->id) }}"
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
                            <td colspan="10" class="text-center text-muted">
                                Không có dữ liệu
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>

            </div>
        </div>

    </div>

    {{-- Modal --}}
    @foreach($loanSlips as $loan)
        <div class="modal fade" id="loanDetailModal{{ $loan->id }}" tabindex="-1">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">

                    <!-- HEADER -->
                    <div class="modal-header bg-dark text-white">
                        <h5 class="modal-title">
                            📄 Phiếu mượn #{{ $loan->id }}
                        </h5>
                        <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    <!-- BODY -->
                    <div class="modal-body">

                        <!-- THÔNG TIN -->
                        <div class="row mb-3">

                            <div class="col-md-4">
                                <strong>Sinh viên:</strong><br>
                                {{ $loan->student?->name }}
                            </div>

                            <div class="col-md-4">
                                <strong>Lớp - Ngành:</strong><br>
                                {{ $loan->student?->class?->name }} -
                                {{ $loan->student?->class?->major?->name }}
                            </div>

                            <div class="col-md-4">
                                <strong>Người duyệt:</strong><br>

                                <div class="fw-semibold">
                                    {{ $loan->admin?->name ?? '---' }}
                                </div>

                                <small class="text-muted">
                                    @if($loan->admin?->role == 'admin')
                                        <span class="badge bg-danger">
                                            <i class="bi bi-shield-lock"></i> Quản trị
                                        </span>
                                    @else
                                        <span class="badge bg-success">
                                            <i class="bi bi-person-badge"></i> Thủ thư
                                        </span>
                                    @endif
                                </small>
                            </div>

                        </div>

                        <div class="row mb-3">

                            <div class="col-md-3">
                                <strong>Ngày mượn:</strong><br>
                                {{ $loan->start_date }}
                            </div>

                            <div class="col-md-3">
                                <strong>Hạn trả:</strong><br>
                                {{ $loan->due_date }}
                            </div>

                            <div class="col-md-3">
                                <strong>Ngày trả:</strong><br>
                                {{ $loan->return_date ?? 'Chưa trả' }}
                            </div>

                            <div class="col-md-3">
                                <strong>Trạng thái:</strong><br>
                                @if($loan->status == 'borrowed')
                                    <span class="badge bg-warning text-dark">Đang mượn</span>
                                @elseif($loan->status == 'returned')
                                    <span class="badge bg-success">Đã trả</span>
                                @else
                                    <span class="badge bg-danger">Quá hạn</span>
                                @endif
                            </div>

                        </div>

                        <hr>

                        <!-- DANH SÁCH -->
                        <h5>📚 Danh sách sách mượn</h5>

                        <table class="table table-bordered table-hover">
                            <thead class="table-secondary">
                            <tr>
                                <th>#</th>
                                <th>Barcode</th>
                                <th>Tên sách</th>
                                <th>Trạng thái</th>
                                <th>Tiền phạt</th>
                            </tr>
                            </thead>

                            <tbody>
                            @forelse($loan->details as $detail)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>

                                    <td>
                                        <span class="badge bg-secondary">
                                            {{ $detail->bookDetail?->barcode }}
                                        </span>
                                    </td>

                                    <td>{{ $detail->bookDetail?->book?->name }}</td>

                                    <td>
                                        @if($detail->status == 'borrowed')
                                            <span class="badge bg-warning text-dark">Đang mượn</span>
                                        @elseif($detail->status == 'returned')
                                            <span class="badge bg-success">Đã trả</span>
                                        @else
                                            <span class="badge bg-danger">{{ $detail->status }}</span>
                                        @endif
                                    </td>

                                    <td class="text-danger">
                                        {{ number_format($detail->fine_amount ?? 0, 0) }} ₫
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

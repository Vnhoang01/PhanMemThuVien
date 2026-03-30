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
                        <th>Tổng sách</th>
                        <th>Tiền phạt</th>
                        <th>Trạng thái</th>
                        <th width="150">Hành động</th>
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
                                {{ \Carbon\Carbon::parse($loan->due_date)->format('d/m/Y') }}
                            </td>

                            <td>
                                {{ $loan->return_date
                                    ? \Carbon\Carbon::parse($loan->return_date)->format('d/m/Y')
                                    : 'Chưa trả' }}
                            </td>

                            <td>{{ $loan->total_quantity }}</td>

                            <td>{{ number_format($loan->total_fine, 0) }} ₫</td>

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
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">
                            📄 Chi tiết phiếu mượn #{{ $loan->id }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <p><strong>Sinh viên:</strong> {{ $loan->student->name ?? '---' }}</p>

                        <p>
                            <strong>Lớp - Ngành:</strong>
                            {{ $loan->student->class->name ?? '---' }} -
                            {{ $loan->student->class->major->name ?? '---' }}
                        </p>

                        <p>
                            <strong>Người duyệt:</strong>
                            {{ $loan->admin->name ?? '---' }}

                            @if($loan->admin)
                                @if($loan->admin->role == 'admin')
                                    <span class="badge bg-danger">Quản trị</span>
                                @else
                                    <span class="badge bg-success">Thủ thư</span>
                                @endif
                            @endif
                        </p>

                        <p><strong>Ngày mượn:</strong> {{ $loan->start_date }}</p>
                        <p><strong>Hạn trả:</strong> {{ $loan->due_date }}</p>
                        <p><strong>Ngày trả:</strong> {{ $loan->return_date ?? 'Chưa trả' }}</p>

                        <p><strong>Tổng sách:</strong> {{ $loan->total_quantity }}</p>
                        <p><strong>Tổng tiền phạt:</strong> {{ number_format($loan->total_fine, 0) }} ₫</p>

                        <p><strong>Trạng thái:</strong>
                            @switch($loan->status)
                                @case('borrowed') <span class="badge bg-warning text-dark">Đang mượn</span> @break
                                @case('returned') <span class="badge bg-success">Đã trả</span> @break
                                @case('overdue') <span class="badge bg-danger">Quá hạn</span> @break
                                @default <span class="badge bg-secondary">{{ $loan->status }}</span>
                            @endswitch
                        </p>

                        <h5 class="mt-3">📚 Danh sách chi tiết:</h5>

                        <table class="table table-bordered table-hover mt-2">
                            <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Tên sách</th>
                                <th>Tiền phạt</th>
                                <th>Trạng thái</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($loan->details as $index => $detail)
                                <tr>
                                    <td>{{ $index + 1 }}</td>

                                    <td>{{ $detail->book->name ?? 'N/A' }}</td>

                                    <td>{{ number_format($detail->fine_amount, 0) }} ₫</td>

                                    <td>
                                        @if($detail->status == 'Còn nguyên')
                                            <span class="badge bg-success">Còn nguyên</span>
                                        @elseif($detail->status == 'Hư hỏng')
                                            <span class="badge bg-warning text-dark">Hư hỏng</span>
                                        @elseif($detail->status == 'Mất')
                                            <span class="badge bg-danger">Mất</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $detail->status }}</span>
                                        @endif
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

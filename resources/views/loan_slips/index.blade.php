@extends('layouts.master')

@section('content')

    <div class="container mt-4">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="fw-bold">📄 Danh sách phiếu mượn</h2>

            <a href="{{ route('loan_slips.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Thêm phiếu mượn
            </a>
        </div>

        {{-- Success --}}
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
                        <th>Sinh viên mượn</th>
                        <th>Người duyệt</th>
                        <th>Sách</th>
                        <th>Ngày mượn</th>
                        <th>Hạn trả</th>
                        <th>Trạng thái</th>
                        <th width="120">Hành động</th>
                    </tr>
                    </thead>

                    <tbody>
                    @forelse($loanSlips as $loan)
                        <tr>
                            <td class="text-muted">#{{ $loop->iteration }}</td>
                            <td>{{ $loan->student->name ?? '---' }}</td>
                            <td>{{ $loan->admin->name ?? '---' }}</td>

                            <td>
                                @foreach($loan->details as $detail)
                                    <span class="badge bg-info text-dark">{{ $detail->book->name ?? 'N/A' }}</span>
                                @endforeach
                            </td>

                            <td>{{ \Carbon\Carbon::parse($loan->start_date)->format('d/m/Y') }}</td>

                            <td>{{ \Carbon\Carbon::parse($loan->due_date)->format('d/m/Y') }}</td>

                            <td>
                                @switch($loan->status)
                                    @case('borrowed') <span class="badge bg-warning text-dark">Đang mượn</span> @break
                                    @case('returned') <span class="badge bg-success">Đã trả</span> @break
                                    @case('overdue') <span class="badge bg-danger">Quá hạn</span> @break
                                    @default <span class="badge bg-secondary">{{ $loan->status }}</span>
                                @endswitch
                            </td>

                            <td>
                                <!-- Nút xem chi tiết popup -->
                                <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal"
                                        data-bs-target="#loanDetailModal{{ $loan->id }}" title="Xem chi tiết">
                                    <i class="bi bi-eye"></i>
                                </button>

                                <a href="{{ route('loan_slips.edit', $loan->id) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                <form action="{{ route('loan_slips.destroy', $loan->id) }}" method="POST"
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
                            <td colspan="8" class="text-center text-muted">Không có dữ liệu</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>

                {{--Modal popup--}}
                @foreach($loanSlips as $loan)
                    <div class="modal fade" id="loanDetailModal{{ $loan->id }}" tabindex="-1"
                         aria-labelledby="loanDetailModalLabel{{ $loan->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="loanDetailModalLabel{{ $loan->id }}">
                                        📄 Chi tiết phiếu mượn #{{ $loan->id }}
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Đóng"></button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>Sinh viên mượn:</strong> {{ $loan->student->name ?? '---' }}</p>
                                    <p>
                                        <strong>Lớp - Chuyên ngành:</strong> {{ $loan->student->class->name ?? '---' }}-
                                        {{ $loan->student->class->major->name ?? '---' }}
                                    </p>
                                    <p>
                                        <strong>Người duyệt:</strong>
                                        {{ $loan->admin->name ?? '---' }}

                                        @if($loan->admin)
                                            @if($loan->admin->role == 'admin')
                                                <span class="badge bg-danger">
                                                    <i class="bi bi-shield-lock"></i> Quản trị
                                                </span>
                                            @else
                                                <span class="badge bg-success">
                                                    <i class="bi bi-person-badge"></i> Thủ thư
                                                 </span>
                                            @endif
                                        @endif
                                    </p>
                                    <p><strong>Ngày mượn:</strong> {{ $loan->start_date }}</p>
                                    <p><strong>Hạn trả:</strong> {{ $loan->due_date }}</p>
                                    <p><strong>Ngày trả:</strong> {{ $loan->return_date ?? 'Chưa trả' }}</p>
                                    <p><strong>Tổng số lượng:</strong> {{ $loan->total_quantity }}</p>
                                    <p><strong>Tiền phạt:</strong> {{ number_format($loan->total_fine, 2) }}₫</p>
                                    <p><strong>Trạng thái:</strong>
                                        @switch($loan->status)
                                            @case('borrowed') <span
                                                class="badge bg-warning text-dark">Đang mượn</span> @break
                                            @case('returned') <span class="badge bg-success">Đã trả</span> @break
                                            @case('overdue') <span class="badge bg-danger">Quá hạn</span> @break
                                            @default <span class="badge bg-secondary">{{ $loan->status }}</span>
                                        @endswitch
                                    </p>

                                    <h5 class="mt-3">📚 Danh sách sách mượn:</h5>
                                    <ul>
                                        @foreach($loan->details as $detail)
                                            <li>{{ $detail->book->name ?? 'N/A' }} (Số
                                                lượng: {{ $detail->quantity ?? 1 }})
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>

    </div>

@endsection

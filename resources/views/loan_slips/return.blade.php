@extends('layouts.master')

@section('content')

    <div class="container mt-4">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>📦 Trả sách</h3>

        </div>

        {{-- THÔNG TIN PHIẾU MƯỢN --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-primary text-white">
                <strong>Thông tin phiếu mượn</strong>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-6 mb-2">
                        <strong>Mã phiếu:</strong>
                        #{{ $loan->id }}
                    </div>

                    <div class="col-md-6 mb-2">
                        <strong>Sinh viên:</strong>
                        {{ $loan->student->name ?? '' }}
                    </div>

                    <div class="col-md-6 mb-2">
                        <strong>Mã sinh viên:</strong>
                        {{ $loan->student->student_code ?? '' }}
                    </div>

                    <div class="col-md-6 mb-2">
                        <strong>Ngày mượn:</strong>
                        {{ \Carbon\Carbon::parse($loan->borrow_date)->format('d/m/Y') }}
                    </div>

                    <div class="col-md-6 mb-2">
                        <strong>Hạn trả:</strong>
                        {{ \Carbon\Carbon::parse($loan->due_date)->format('d/m/Y') }}
                    </div>

                    <div class="col-md-6 mb-2">
                        <strong>Số sách:</strong>
                        {{ $loan->details->count() }}
                    </div>

                    <div class="col-md-6 mb-2">
                        <strong>Trạng thái:</strong>

                        @if($loan->status == 'borrowing')
                            <span class="badge bg-warning text-dark">
                                Đang mượn
                            </span>
                        @elseif($loan->status == 'returned')
                            <span class="badge bg-success">
                                Đã trả
                            </span>
                        @else
                            <span class="badge bg-secondary">
                                {{ $loan->status }}
                            </span>
                        @endif
                    </div>

                </div>

            </div>
        </div>

        {{-- FORM TRẢ SÁCH --}}
        <form method="POST" action="{{ route('loan_slips.return', $loan->id) }}">
            @csrf

            <div class="card shadow-sm">

                <div class="card-header">
                    <strong>Danh sách sách trả</strong>
                </div>

                <div class="card-body p-0">

                    <table class="table table-bordered mb-0">

                        <thead class="table-light">
                        <tr>
                            <th width="50%">Sách</th>
                            <th>Lỗi</th>
                        </tr>
                        </thead>

                        <tbody>

                        @foreach($loan->details as $detail)

                            <tr>

                                <td>
                                    <strong>
                                        {{ $detail->bookDetail->book->name }}
                                    </strong>

                                    <br>

                                    <small class="text-muted">
                                        {{ $detail->bookDetail->barcode }}
                                    </small>
                                </td>

                                <td>

                                    @forelse($errors as $error)

                                        <div class="form-check mb-1 error-item">

                                            <input class="form-check-input error-checkbox"
                                                   type="checkbox"
                                                   name="errors[{{ $detail->id }}][]"
                                                   value="{{ $error->id }}"
                                                   data-error-name="{{ strtolower($error->name) }}"
                                                   data-detail-id="{{ $detail->id }}"
                                                   id="error_{{ $detail->id }}_{{ $error->id }}">

                                            <label class="form-check-label"
                                                   for="error_{{ $detail->id }}_{{ $error->id }}">

                                                {{ $error->name }}

                                                <span class="text-danger">
                                            ({{ number_format($error->fine_amount) }}đ)
                                        </span>

                                            </label>

                                        </div>

                                    @empty

                                        <span class="text-muted">
                                    Không có lỗi
                                </span>

                                    @endforelse

                                </td>

                            </tr>

                        @endforeach

                        </tbody>

                    </table>

                </div>

            </div>

            <div class="mt-3 d-flex gap-2">

                <button class="btn btn-success">
                    ✅ Xác nhận trả sách
                </button>

                <a href="{{ route('loan_slips.index') }}"
                   class="btn btn-secondary">
                    ← Hủy trả sách
                </a>

            </div>

        </form>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const checkboxes = document.querySelectorAll('.error-checkbox');

            checkboxes.forEach(cb => {

                cb.addEventListener('change', function () {

                    const detailId = this.dataset.detailId;

                    const currentGroup = document.querySelectorAll(
                        '.error-checkbox[data-detail-id="' + detailId + '"]'
                    );

                    // checkbox mất sách
                    const lostCheckbox = Array.from(currentGroup).find(item =>
                        item.dataset.errorName.includes('mất')
                    );

                    // các checkbox khác
                    const otherCheckboxes = Array.from(currentGroup).filter(item =>
                        !item.dataset.errorName.includes('mất')
                    );

                    // Nếu tích MẤT SÁCH
                    if (this === lostCheckbox) {

                        if (this.checked) {

                            otherCheckboxes.forEach(item => {

                                item.checked = false;

                                item.closest('.error-item').style.display = 'none';

                            });

                        } else {

                            otherCheckboxes.forEach(item => {

                                item.closest('.error-item').style.display = 'block';

                            });

                        }

                    }

                    // Nếu tích lỗi khác
                    else {

                        const anyOtherChecked = otherCheckboxes.some(item => item.checked);

                        if (anyOtherChecked) {

                            lostCheckbox.checked = false;
                            lostCheckbox.closest('.error-item').style.display = 'none';

                        } else {

                            lostCheckbox.closest('.error-item').style.display = 'block';

                        }

                    }

                });

            });

        });
    </script>

@endsection

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
                            <th class="text-center">#</th>
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
                                    @if($book->available_quantity > 0)
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
                                <span class="total-quantity">{{ $book->total_quantity }}</span>
                            </div>

                            <div class="col-md-3">
                                <strong>Còn sách:</strong><br>
                                <span class="badge bg-success available-quantity">
                                    {{ $book->available_quantity }}
                                </span>
                            </div>

                            <div class="col-md-3">
                                <strong>Trạng thái:</strong><br>
                                <div class="book-status">
                                    @if($book->available_quantity > 0)
                                        <span class="badge bg-success">Còn sách</span>
                                    @else
                                        <span class="badge bg-danger">Hết sách</span>
                                    @endif
                                </div>
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

                        <form action="{{ route('book_details.store') }}" method="POST">
                            @csrf

                            <input type="hidden" name="book_id" value="{{ $book->id }}">

                            <button type="submit" class="btn btn-sm btn-primary mb-2">
                                + Thêm bản sao
                            </button>
                        </form>

                        <table class="table table-bordered">
                            <thead class="table-secondary">
                            <tr>
                                <th>#</th>
                                <th>Mã sách</th>
                                <th>Tên sách</th>
                                <th>Trạng thái</th>
                                <th>Hành động</th>
                            </tr>
                            </thead>

                            <tbody>
                            @forelse($book->bookDetails as $detail)
                                <tr>
                                    <td class="text-muted">#{{ $loop->iteration }}</td>
                                    <td>{{ $detail->barcode }}</td>
                                    <td>{{ $detail->name }}</td>

                                    <td>
                                        <select class="form-select form-select-sm update-status"
                                                data-id="{{ $detail->id }}">

                                            <option value="available" {{ $detail->status == 'available' ? 'selected' : '' }}>
                                                🟢 Còn nguyên
                                            </option>

                                            <option value="damaged" {{ $detail->status == 'damaged' ? 'selected' : '' }}>
                                                🔴 Hỏng
                                            </option>

                                            <option value="lost" {{ $detail->status == 'lost' ? 'selected' : '' }}>
                                                ⚫ Mất
                                            </option>

                                        </select>
                                    </td>

                                    <td>
                                        <button class="btn btn-sm btn-danger btn-delete-detail"
                                                data-id="{{ $detail->id }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
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

    <script>
        // Thêm bản ghi
        document.querySelectorAll('.update-status').forEach(select => {

            select.addEventListener('change', function () {

                let id = this.dataset.id;
                let status = this.value;
                let modal = this.closest('.modal');

                fetch(`{{ url('book-details/update-status') }}`, {
                    method: 'POST', //
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({id, status})
                })
                    .then(res => res.json())
                    .then(data => {

                        if (data.success) {

                            let qty = modal.querySelector('.available-quantity');
                            if (qty) qty.innerText = data.available_quantity;

                            let statusBox = modal.querySelector('.book-status');

                            if (statusBox) {
                                statusBox.innerHTML = data.available_quantity > 0
                                    ? '<span class="badge bg-success">Còn sách</span>'
                                    : '<span class="badge bg-danger">Hết sách</span>';
                            }
                        }
                    });
            });
        });
    </script>

    <script>
        // Xóa bản ghi
        document.querySelectorAll('.btn-delete-detail').forEach(btn => {

            btn.addEventListener('click', function () {

                if (!confirm('Xoá bản sao này?')) return;

                let id = this.dataset.id;
                let row = this.closest('tr');
                let modal = this.closest('.modal');

                fetch(`{{ url('book-details') }}/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                    .then(res => res.json())
                    .then(data => {

                        if (data.success) {

                            // xoá dòng
                            row.remove();

                            // ập nhật CÒN SÁCH
                            let qty = modal.querySelector('.available-quantity');
                            if (qty) qty.innerText = data.available_quantity;

                            // cập nhật TỔNG SÁCH
                            let total = modal.querySelector('.total-quantity');
                            if (total) total.innerText = data.total_quantity;

                            // cập nhật trạng thái
                            let statusBox = modal.querySelector('.book-status');

                            if (statusBox) {
                                statusBox.innerHTML = data.available_quantity > 0
                                    ? '<span class="badge bg-success">Còn sách</span>'
                                    : '<span class="badge bg-danger">Hết sách</span>';
                            }
                        }
                    });
            });
        });
    </script>

@endsection

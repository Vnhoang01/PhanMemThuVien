@extends('layouts.master')

@section('content')

    <div class="container mt-4">


        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="fw-bold">📚 Danh sách sách</h2>

            <a href="{{ route('books.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Thêm sách
            </a>
        </div>

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
                            <th>Mã sách</th>
                            <th>ISBN</th>
                            <th>Tên</th>
                            <th>Tác giả</th>
                            <th>Thể loại</th>
                            <th>NXB</th>
                            <th>Năm XB</th>
                            <th>Tổng</th>
                            <th>Còn</th>
                            <th>Trạng thái</th>
                            <th class="text-center">Hành động</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($books as $book)
                            <tr>
                                <td class="text-muted">#{{ $loop->iteration }}</td>

                                <td>
                                    <span class="badge bg-dark">{{ $book->book_code }}</span>
                                </td>

                                <td>
                                    <span class="badge bg-secondary">{{ $book->isbn }}</span>
                                </td>

                                <td class="fw-semibold">{{ $book->name }}</td>

                                <td>{{ $book->author?->name }}</td>

                                <td>
                            <span class="badge bg-info text-dark">
                                {{ $book->category?->name }}
                            </span>
                                </td>

                                <td>{{ $book->publisher?->name }}</td>

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

    <!-- MODAL -->

    @foreach($books as $book)

        <div class="modal fade" id="bookDetailModal{{ $book->id }}" tabindex="-1">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">

                    <div class="modal-header bg-dark text-white">
                        <h5 class="modal-title">
                            📚 {{ $book->name }}
                            <small class="text-muted">({{ $book->book_code }})</small>
                        </h5>

                        <button type="button" class="btn-close btn-close-white"
                                data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <div class="row mb-3">
                            <div class="col-md-3">
                                <strong>ISBN:</strong><br>
                                <span class="badge bg-secondary">{{ $book->isbn }}</span>
                            </div>

                            <div class="col-md-3">
                                <strong>Tác giả:</strong><br>
                                {{ $book->author?->name }}
                            </div>

                            <div class="col-md-3">
                                <strong>Thể loại:</strong><br>
                                <span class="badge bg-info">
                        {{ $book->category?->name }}
                    </span>
                            </div>

                            <div class="col-md-3">
                                <strong>NXB:</strong><br>
                                {{ $book->publisher?->name }}
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-3">
                                <strong>Năm XB:</strong><br>
                                {{ $book->year_of_publication }}
                            </div>

                            <div class="col-md-3">
                                <strong>Tổng:</strong><br>
                                <span class="total-quantity">{{ $book->total_quantity }}</span>
                            </div>

                            <div class="col-md-3">
                                <strong>Còn:</strong><br>
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

                        <div class="mb-3">
                            <strong>Mô tả:</strong>
                            <p class="text-muted">
                                {{ $book->description ?? 'Không có mô tả' }}
                            </p>
                        </div>

                        <hr>

                        <h5>📖 Danh sách bản sao</h5>

                        <form action="{{ route('book_details.store') }}"
                              method="POST"
                              class="d-flex gap-2 mb-3">

                            @csrf

                            <input type="hidden"
                                   name="book_id"
                                   value="{{ $book->id }}">

                            <input type="number"
                                   name="quantity"
                                   class="form-control form-control-sm"
                                   min="1"
                                   value="1"
                                   style="width:120px"
                                   required>

                            <button type="submit"
                                    class="btn btn-sm btn-primary">

                                <i class="bi bi-plus-circle"></i>
                                Thêm bản vật lí

                            </button>
                        </form>

                        <table class="table table-bordered">
                            <thead class="table-secondary">
                            <tr>
                                <th>#</th>
                                <th>Barcode</th>
                                <th>Trạng thái</th>
                                <th>Hành động</th>
                            </tr>
                            </thead>

                            <tbody>
                            @forelse($book->details as $detail)
                                <tr>
                                    <td>#{{ $loop->iteration }}</td>
                                    <td>{{ $detail->barcode }}</td>

                                    <td>
                                        <select class="form-select form-select-sm update-status"
                                                data-id="{{ $detail->id }}">

                                            <option value="available" {{ $detail->status == 'available' ? 'selected' : '' }}>🟢 Nguyên vẹn</option>
                                            <option value="damaged" {{ $detail->status == 'damaged' ? 'selected' : '' }}>🔴 Hỏng</option>
                                            <option value="lost" {{ $detail->status == 'lost' ? 'selected' : '' }}>⚫ Mất</option>
                                            <option value="borrowed" {{ $detail->status == 'borrowed' ? 'selected' : '' }}>🟡 Đang mượn</option>

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
        document.querySelectorAll('.update-status').forEach(select => {

            select.addEventListener('change', function () {

                let id = this.dataset.id;
                let status = this.value;
                let modal = this.closest('.modal');

                clearTimeout(this._timeout);

                this._timeout = setTimeout(() => {

                    fetch(`{{ url('book-details') }}/update-status`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ id, status })
                    })
                        .then(res => res.json())
                        .then(data => {

                            if (!data.success) return alert('Lỗi');

                            modal.querySelector('.available-quantity').innerText = data.available_quantity;
                            modal.querySelector('.total-quantity').innerText = data.total_quantity;

                            modal.querySelector('.book-status').innerHTML =
                                data.available_quantity > 0
                                    ? '<span class="badge bg-success">Còn sách</span>'
                                    : '<span class="badge bg-danger">Hết sách</span>';

                        });

                }, 300);
            });
        });
    </script>

@endsection

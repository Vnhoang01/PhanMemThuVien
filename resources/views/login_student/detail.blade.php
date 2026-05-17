@extends('layouts.layout_student')

@section('title', $book->name)

@section('content')

    <div class="container py-4">

        <div class="card border-0 shadow-lg rounded-4 overflow-hidden">

            <div class="row g-0">

{{--                <!-- IMAGE -->--}}
{{--                <div class="col-lg-4 bg-light p-4 text-center">--}}

{{--                    <img src="{{ $book->image--}}
{{--                        ? asset('storage/' . $book->image)--}}
{{--                        : 'https://via.placeholder.com/300x450' }}"--}}
{{--                         class="img-fluid rounded-4 shadow-sm"--}}
{{--                         style="max-height:450px; object-fit:cover;">--}}

{{--                </div>--}}

                <!-- INFO -->
                <div class="col-lg-8">

                    <div class="card-body p-4 p-lg-5">

                        <h2 class="fw-bold mb-4">
                            {{ $book->name }}
                        </h2>

                        <div class="book-info">

                            <div class="info-item">
                                <strong>Tác giả:</strong>
                                {{ $book->author?->name ?? 'Không có' }}
                            </div>

                            <div class="info-item">
                                <strong>Thể loại:</strong>
                                {{ $book->category->name ?? 'Không có' }}
                            </div>

                            <div class="info-item">
                                <strong>Nhà xuất bản:</strong>
                                {{ $book->publisher?->name ?? 'Không có' }}
                            </div>

                            <div class="info-item">
                                <strong>ISBN:</strong>
                                {{ $book->isbn ?? 'Không có' }}
                            </div>

                            <div class="info-item">
                                <strong>Năm xuất bản:</strong>
                                {{ $book->year_of_publication ?? 'Không có' }}
                            </div>

                            <div class="info-item">
                                <strong>Số lượng còn:</strong>

                                <span class="badge bg-success fs-6">
                                {{ $book->available_quantity }}
                            </span>
                            </div>

                        </div>

                        <hr class="my-4">

                        <h5 class="fw-bold mb-3">
                            Mô tả sách
                        </h5>

                        <p class="text-muted lh-lg">
                            {{ $book->description ?? 'Chưa có mô tả cho sách này.' }}
                        </p>

                        <div class="d-flex gap-3 mt-4">

                            <a href="{{ route('student.borrow') }}"
                               class="btn btn-light rounded-3 px-4">

                                ← Quay lại

                            </a>

                            <button type="button"
                                    class="btn btn-primary rounded-3 px-4"
                                    onclick="selectBook({{ $book->id }}, this)"
                                {{ $book->available_quantity < 1 ? 'disabled' : '' }}>

                                {{ $book->available_quantity < 1
                                    ? 'Không Có Sẵn'
                                    : 'Chọn Mượn' }}

                            </button>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <style>

        .book-info{
            display:grid;
            gap:16px;
        }

        .info-item{
            font-size:16px;
            color:#334155;
        }

        .info-item strong{
            color:#0f172a;
            margin-right:8px;
        }

    </style>

@endsection

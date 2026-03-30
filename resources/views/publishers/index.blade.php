@extends('layouts.master')

@section('content')

    <div class="container mt-4">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="fw-bold">🏢 Nhà xuất bản</h2>

            <a href="{{ route('publishers.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Thêm nhà xuất bản
            </a>
        </div>

        <!-- Thông báo -->
        @if(session('success'))
            <div class="alert alert-success shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <!-- Card -->
        <div class="card shadow-sm">
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-hover align-middle">

                        <thead class="table-dark text-center">
                        <tr>
                            <th style="width:5%">#</th>
                            <th style="width:25%">Tên</th>
                            <th style="width:40%">Địa chỉ</th>
                            <th style="width:15%">SĐT</th>
                            <th style="width:15%">Hành động</th>
                        </tr>
                        </thead>

                        <tbody>
                        @forelse($publishers as $publisher)
                            <tr>

                                <!-- STT -->
                                <td class="text-muted text-center">
                                    #{{ $loop->iteration }}
                                </td>

                                <!-- Name -->
                                <td class="fw-semibold text-center">
                                    {{ $publisher->name }}
                                </td>

                                <!-- Address -->
                                <td class="text-center">
                                    {{ \Illuminate\Support\Str::limit($publisher->address, 60) }}
                                </td>

                                <!-- Phone -->
                                <td class="text-center">
                                    {{ $publisher->phone_number }}
                                </td>

                                <!-- Action -->
                                <td class="text-center">

                                    <a href="{{ route('publishers.edit',$publisher->id) }}"
                                       class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <form action="{{ route('publishers.destroy',$publisher->id) }}"
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
                                <td colspan="5" class="text-center text-muted">
                                    Không có dữ liệu
                                </td>
                            </tr>
                        @endforelse
                        </tbody>

                    </table>
                </div>

            </div>
        </div>

    </div>

@endsection

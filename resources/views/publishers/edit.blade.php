@extends('layouts.master')

@section('content')

    <div class="container mt-4">

        <h2 class="fw-bold mb-4">✏️ Cập nhật nhà xuất bản</h2>

        <!-- Hiển thị lỗi -->
        @if ($errors->any())
            <div class="alert alert-danger shadow-sm">
                <ul class="mb-0">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body">

                <form action="{{ route('publishers.update',$publisher->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">

                        <!-- Name -->
                        <div class="col-md-6">
                            <label class="form-label">Tên nhà xuất bản</label>
                            <input type="text" name="name"
                                   value="{{ old('name', $publisher->name) }}"
                                   class="form-control"
                                   placeholder="Nhập tên NXB">
                        </div>

                        <!-- Phone -->
                        <div class="col-md-6">
                            <label class="form-label">Số điện thoại</label>
                            <input type="text" name="phone_number"
                                   value="{{ old('phone_number', $publisher->phone_number) }}"
                                   class="form-control"
                                   placeholder="Nhập số điện thoại">
                        </div>

                        <!-- Address -->
                        <div class="col-12">
                            <label class="form-label">Địa chỉ</label>
                            <input type="text" name="address"
                                   value="{{ old('address', $publisher->address) }}"
                                   class="form-control"
                                   placeholder="Nhập địa chỉ">
                        </div>

                    </div>

                    <!-- Buttons -->
                    <div class="mt-4 d-flex gap-2">
                        <button class="btn btn-success">
                            <i class="bi bi-save"></i> Cập nhật
                        </button>

                        <a href="{{ route('publishers.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Quay lại
                        </a>
                    </div>

                </form>

            </div>
        </div>

    </div>

@endsection

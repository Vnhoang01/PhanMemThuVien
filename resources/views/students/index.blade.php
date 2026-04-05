@extends('layouts.master')

@section('content')

    <div class="container mt-4">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="fw-bold">👨‍🎓 Danh sách sinh viên</h2>
            <a href="{{ route('students.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Thêm sinh viên
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
                            <th>Ngày sinh</th>
                            <th>Giới tính</th>
                            <th>Lớp</th>
                            <th>Email</th>
                            <th>Điện thoại</th>
                            <th>Địa chỉ</th>
                            <th>Trạng thái</th>
                            <th class="text-center">Hành động</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($students as $student)
                            <tr>
                                <td class="text-muted">#{{ $loop->iteration }}</td>

                                <td class="fw-semibold">{{ $student->name }}</td>

                                <td>{{ \Carbon\Carbon::parse($student->date_of_birth)->format('d/m/Y') }}</td>

                                <td>
                                    @if($student->gender == 'Male')
                                        <span class="badge bg-info">Nam</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Nữ</span>
                                    @endif
                                </td>

                                <td>
                                    {{ $student->class->name ?? '' }} -
                                    {{ $student->class->major->name ?? '' }}
                                </td>

                                <td>{{ $student->email }}</td>

                                <td>{{ $student->phone_number }}</td>

                                <td>{{ $student->address }}</td>

                                <td>
                                    @if($student->status == 'active')
                                        <span class="badge bg-success">Hoạt động</span>
                                    @else
                                        <span class="badge bg-secondary">Ngưng</span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    <a href="{{ route('students.edit',$student->id) }}"
                                       class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <form action="{{ route('students.destroy',$student->id) }}"
                                          method="POST"
                                          style="display:inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger"
                                                onclick="return confirm('Bạn có chắc muốn xoá?')">
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

@endsection

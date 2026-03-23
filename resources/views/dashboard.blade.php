@extends('layouts.master')

@section('content')

    <div class="container mt-4">

        <h2 class="mb-4 fw-bold">📊 Tổng quan hệ thống</h2>

        <div class="row g-4">

            <!-- Tổng sách -->
            <div class="col-md-3">
                <div class="card dashboard-card bg-primary text-white shadow">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase">Tổng số sách</h6>
                            <h2 class="fw-bold">{{ $totalBooks }}</h2>
                        </div>
                        <i class="bi bi-book fs-1 opacity-75"></i>
                    </div>
                </div>
            </div>

            <!-- Sinh viên -->
            <div class="col-md-3">
                <div class="card dashboard-card bg-success text-white shadow">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase">Sinh viên</h6>
                            <h2 class="fw-bold">{{ $totalStudents }}</h2>
                        </div>
                        <i class="bi bi-people fs-1 opacity-75"></i>
                    </div>
                </div>
            </div>

            <!-- Sách còn -->
            <div class="col-md-3">
                <div class="card dashboard-card bg-warning text-dark shadow">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase">Sách còn</h6>
                            <h2 class="fw-bold">{{ $availableBooks }}</h2>
                        </div>
                        <i class="bi bi-check-circle fs-1 opacity-75"></i>
                    </div>
                </div>
            </div>

            <!-- Đang mượn -->
            <div class="col-md-3">
                <div class="card dashboard-card bg-danger text-white shadow">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase">Đang mượn</h6>
                            <h2 class="fw-bold">{{ $totalBorrowed }}</h2>
                        </div>
                        <i class="bi bi-journal-arrow-down fs-1 opacity-75"></i>
                    </div>
                </div>
            </div>

        </div>

    </div>

@endsection

<!DOCTYPE html>
<html>
<head>
    <title>Phần mềm quản lý thư viện</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        body {
            margin: 0;
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            min-height: 100vh;
            background: #1e293b;
            color: white;
            padding: 20px;
        }

        .sidebar a {
            color: #cbd5e1;
            text-decoration: none;
            display: block;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 5px;
            transition: 0.3s;
        }

        .sidebar a:hover {
            background: #334155;
            color: #fff;
        }

        .sidebar a.active {
            background: #3b82f6;
            color: #fff;
            font-weight: bold;
        }

        /* Content */
        .content {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        /* Navbar */
        .navbar-custom {
            background: #ffffff;
            padding: 15px 20px;
            border-bottom: 1px solid #ddd;
        }

        .main-content {
            padding: 20px;
            flex: 1;
            background: #f1f5f9;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px;
            color: #cbd5e1;
            text-decoration: none;
            border-radius: 8px;
        }

        .sidebar-menu a:hover {
            background: #1e293b;
            color: #fff;
        }

        .submenu {
            list-style: none;
            padding-left: 20px;
        }

        .submenu a {
            font-size: 14px;
            padding: 8px;
            color: #94a3b8;
        }

        .submenu a:hover {
            color: #fff;
        }

        .dashboard-card {
            border-radius: 15px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .dashboard-card:hover {
            transform: translateY(-8px) scale(1.03);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .sidebar a.active {
            background: #3b82f6;
            color: #fff !important;
            font-weight: bold;
        }

        .submenu a.active {
            background: #2563eb;
            color: #fff !important;
        }

        option:disabled {
            color: #ccc;
        }
    </style>
</head>

<body>

@include('layouts.sidebar')

<div class="content">

    <!-- Navbar -->
    <div class="navbar-custom d-flex justify-content-between align-items-center">
        <h5 class="mb-0">📚 Hệ thống quản lý thư viện</h5>

        <!-- Có thể thêm user info -->
        <div class="d-flex align-items-center">
            <i class="bi bi-person-circle me-2"></i>

            @auth('admin')
                <span class="fw-bold">
                    Xin chào, {{ auth('admin')->user()->name }}
                </span>
            @endauth
        </div>
    </div>

    <!-- Nội dung -->
    <div class="main-content">
        @yield('content')
    </div>

</div>

</body>
</html>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Student System')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f5f6fa;
        }
        .sidebar {
            height: 100vh;
            background: #2f3640;
            color: #fff;
        }
        .sidebar a {
            color: #dcdde1;
            text-decoration: none;
            display: block;
            padding: 10px;
        }
        .sidebar a:hover {
            background: #353b48;
        }
        .header {
            background: #40739e;
            color: white;
            padding: 10px 20px;
        }
        .content {
            padding: 20px;
        }
        .footer {
            background: #dcdde1;
            text-align: center;
            padding: 10px;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">

        <!-- Sidebar -->
        <div class="col-md-2 sidebar">
            <h4 class="p-3">thư viện</h4>
            <a href="#">Sách</a>
            <a href="#">thể loại</a>
            <a href="#">thông tin cá nhân</a>
        </div>

        <!-- Main -->
        <div class="col-md-10">

            <!-- Header -->
            <div class="header d-flex justify-content-between">
                <div>thư viện</div>
                <div>người dùng</div>
            </div>

            <!-- Content -->
            <div class="content">
                @yield('content')
            </div>

            <!-- Footer -->
            <div class="footer">
                © 2026 hệ thống quản lý thư viện
            </div>

        </div>
    </div>
</div>

</body>
</html>


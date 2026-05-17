<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sinh viên')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* =========================
        USER DROPDOWN
        ========================= */

        .user-dropdown{
            display:flex;
            align-items:center;
            gap:12px;

            background:rgba(255,255,255,0.12);
            border:none;

            color:white;

            padding:8px 14px;

            border-radius:14px;

            transition:0.3s ease;
        }

        .user-dropdown:hover{
            background:rgba(255,255,255,0.2);
            color:white;
        }

        .user-dropdown:focus{
            box-shadow:none;
            color:white;
        }

        .user-avatar{
            width:38px;
            height:38px;

            border-radius:50%;

            background:white;
            color:#2563eb;

            display:flex;
            align-items:center;
            justify-content:center;

            font-weight:700;
        }

        .dropdown-menu{
            border-radius:16px;
            padding:10px;
            min-width:240px;
        }

        .dropdown-item{
            padding:10px 14px;
            border-radius:10px;
            transition:0.2s ease;
        }

        .dropdown-item:hover{
            background:#f1f5f9;
        }

        .dropdown-header{
            padding:10px 14px;
        }

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        body{
            font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;
            background:#f1f5f9;
            color:#1e293b;
        }

        /* =========================
            SIDEBAR
        ========================= */

        .student-sidebar{
            position:fixed;
            top:0;
            left:0;

            width:260px;
            height:100vh;

            background:linear-gradient(180deg,#0f172a,#1e293b);

            padding:30px 20px;
            overflow-y:auto;

            z-index:1000;
        }

        .student-sidebar h5{
            color:white;
            font-size:32px;
            font-weight:700;
            margin-bottom:40px;
            text-align:center;
        }

        .student-sidebar a{
            display:flex;
            align-items:center;

            color:#cbd5e1;
            text-decoration:none;

            padding:14px 18px;
            border-radius:14px;

            margin-bottom:12px;

            transition:0.3s ease;

            font-size:17px;
            font-weight:600;
        }

        .student-sidebar a:hover{
            background:rgba(255,255,255,0.08);
            color:white;
            transform:translateX(5px);
        }

        /* =========================
            MAIN CONTENT
        ========================= */

        .main-wrapper{
            margin-left:260px;
            min-height:100vh;
        }

        /* =========================
            HEADER
        ========================= */

        .student-header{
            background:linear-gradient(90deg,#2563eb,#3b82f6);
            color:white;

            padding:20px 35px;

            display:flex;
            justify-content:space-between;
            align-items:center;

            box-shadow:0 4px 12px rgba(0,0,0,0.08);
        }

        .student-header h4{
            margin:0;
            font-size:24px;
            font-weight:700;
        }

        /* =========================
            MAIN
        ========================= */

        .student-main{
            padding:30px;
        }

        .student-container{
            background:white;
            border-radius:24px;
            padding:30px;

            box-shadow:0 10px 30px rgba(0,0,0,0.05);
        }

        /* =========================
            BOOK LIST
        ========================= */

        .book-list{
            display:grid;
            grid-template-columns:repeat(auto-fit,minmax(320px,1fr));
            gap:24px;
        }

        .book-item{
            background:white;

            border-radius:20px;

            padding:25px;

            border:1px solid #e2e8f0;

            transition:0.3s ease;

            box-shadow:0 4px 12px rgba(0,0,0,0.04);
        }

        .book-item:hover{
            transform:translateY(-6px);

            box-shadow:0 14px 30px rgba(0,0,0,0.08);
        }

        .book-item h3{
            font-size:24px;
            margin-bottom:18px;
            color:#0f172a;
            font-weight:700;
        }

        .book-item p{
            margin-bottom:10px;
            color:#475569;
            font-size:16px;
        }

        /* =========================
            BUTTONS
        ========================= */

        .borrow-btn{
            width:100%;

            border:none;

            padding:14px;

            border-radius:14px;

            background:linear-gradient(90deg,#16a34a,#22c55e);

            color:white;

            font-size:16px;
            font-weight:700;

            transition:0.3s ease;

            margin-top:18px;
        }

        .borrow-btn:hover{
            transform:scale(1.02);
            opacity:0.95;
        }

        .borrow-btn:disabled{
            background:#94a3b8;
            cursor:not-allowed;
        }

        .submit-btn{
            border:none;

            padding:15px 30px;

            border-radius:14px;

            background:linear-gradient(90deg,#2563eb,#3b82f6);

            color:white;

            font-size:17px;
            font-weight:700;

            transition:0.3s ease;
        }

        .submit-btn:hover{
            transform:translateY(-2px);

            box-shadow:0 10px 20px rgba(37,99,235,0.25);
        }

        /* =========================
            FORM
        ========================= */

        .form-group{
            margin-bottom:24px;
        }

        label{
            display:block;

            margin-bottom:8px;

            font-weight:700;

            color:#334155;
        }

        input[type="text"],
        input[type="date"],
        select{
            width:100%;

            padding:14px 16px;

            border-radius:14px;

            border:1px solid #cbd5e1;

            outline:none;

            transition:0.2s ease;

            background:white;
        }

        input:focus,
        select:focus{
            border-color:#3b82f6;

            box-shadow:0 0 0 4px rgba(59,130,246,0.15);
        }

        /* =========================
            SELECTED
        ========================= */

        .selected{
            border:2px solid #22c55e;
            background:#f0fdf4;
        }

        /* =========================
            FOOTER
        ========================= */

        .student-footer{
            margin-top:30px;

            background:white;

            padding:20px;

            text-align:center;

            color:#64748b;

            border-top:1px solid #e2e8f0;
        }

        /* =========================
            MOBILE
        ========================= */

        @media(max-width:991px){

            .student-sidebar{
                position:relative;
                width:100%;
                height:auto;
            }

            .main-wrapper{
                margin-left:0;
            }

            .student-header{
                padding:18px 20px;
            }

            .student-main{
                padding:20px;
            }
        }

        @media(max-width:576px){

            .student-header h4{
                font-size:20px;
            }

            .student-container{
                padding:20px;
            }

            .book-item{
                padding:20px;
            }
        }

    </style>

</head>

<body>

<!-- SIDEBAR -->
<div class="student-sidebar">

    <h5>📚 Nerd</h5>

    <a href="{{ route('student.borrow') }}">
        Trang chủ
    </a>

    <form id="student-logout-form"
          action="{{ route('student.logout') }}"
          method="POST"
          class="d-none">

        @csrf

    </form>

</div>

<!-- MAIN -->
<div class="main-wrapper">

    <!-- HEADER -->
    <header class="student-header">

        <h4>
            @yield('title', 'Sinh viên')
        </h4>

        <!-- USER DROPDOWN -->
        <div class="dropdown">

            <button
                class="btn user-dropdown dropdown-toggle"
                type="button"
                data-bs-toggle="dropdown"
                aria-expanded="false">

                <div class="user-avatar">
                    {{ strtoupper(substr(auth('student')->user()->name ?? 'S',0,1)) }}
                </div>

                <span>
                {{ auth('student')->user()->name ?? 'Sinh viên' }}
            </span>

            </button>

            <ul class="dropdown-menu dropdown-menu-end shadow border-0">

                <li class="dropdown-header">

                    <div class="fw-bold">
                        {{ auth('student')->user()->name ?? 'Sinh viên' }}
                    </div>

                    <small class="text-muted">
                        {{ auth('student')->user()->email ?? '' }}
                    </small>

                </li>

                <li>
                    <hr class="dropdown-divider">
                </li>

                <li>

                    <a class="dropdown-item"
                       href="{{ route('login_student.profile') }}">

                        👤 Thông tin cá nhân

                    </a>

                </li>

                <li>

                    <a class="dropdown-item text-danger"
                       href="#"
                       onclick="event.preventDefault();
                   document.getElementById('student-logout-form').submit();">

                        🚪 Đăng xuất

                    </a>

                </li>

            </ul>

        </div>

    </header>

    <!-- CONTENT -->
    <main class="student-main">

        <div class="student-container">

            @yield('content')

        </div>

    </main>

    <!-- FOOTER -->
    <footer class="student-footer">

        © {{ date('Y') }} Thư viện nerd

    </footer>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

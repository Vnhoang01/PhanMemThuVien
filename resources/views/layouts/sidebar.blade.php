<div class="sidebar d-flex flex-column">

    <!-- Header -->
    <div class="sidebar-header text-center mb-3">
        <a href="{{ route('admins.index') }}" class="text-white text-decoration-none fw-bold">
            🛠 Quản trị viên
        </a>
    </div>

    <ul class="sidebar-menu flex-grow-1 list-unstyled">

        <!-- Dashboard -->
        <li>
            <a href="{{ route('dashboard') }}"
               class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i>
                <span>Bảng điều khiển</span>
            </a>
        </li>

        <!-- Thư viện -->
        <li>
            <a data-bs-toggle="collapse" href="#libraryMenu"
               class="{{ request()->is('books*','authors*','categories*','publishers*') ? '' : 'collapsed' }}">
                <i class="bi bi-book"></i>
                <span>Thư viện</span>
                <i class="bi bi-chevron-down ms-auto"></i>
            </a>

            <ul class="collapse submenu
                {{ request()->is('books*','authors*','categories*','publishers*') ? 'show' : '' }}"
                id="libraryMenu">

                <li>
                    <a href="{{ route('books.index') }}"
                       class="{{ request()->routeIs('books.*') ? 'active' : '' }}">
                        Sách
                    </a>
                </li>

                <li>
                    <a href="{{ route('authors.index') }}"
                       class="{{ request()->routeIs('authors.*') ? 'active' : '' }}">
                        Tác giả
                    </a>
                </li>

                <li>
                    <a href="{{ route('categories.index') }}"
                       class="{{ request()->routeIs('categories.*') ? 'active' : '' }}">
                        Thể loại
                    </a>
                </li>

                <li>
                    <a href="{{ route('publishers.index') }}"
                       class="{{ request()->routeIs('publishers.*') ? 'active' : '' }}">
                        NXB
                    </a>
                </li>
            </ul>
        </li>

        <!-- Sinh viên -->
        <li>
            <a data-bs-toggle="collapse" href="#studentMenu"
               class="{{ request()->is('students*','classes*','majors*') ? '' : 'collapsed' }}">
                <i class="bi bi-people"></i>
                <span>Sinh viên</span>
                <i class="bi bi-chevron-down ms-auto"></i>
            </a>

            <ul class="collapse submenu
                {{ request()->is('students*','classes*','majors*') ? 'show' : '' }}"
                id="studentMenu">

                <li>
                    <a href="{{ route('students.index') }}"
                       class="{{ request()->routeIs('students.*') ? 'active' : '' }}">
                        Sinh viên
                    </a>
                </li>

                <li>
                    <a href="{{ route('classes.index') }}"
                       class="{{ request()->routeIs('classes.*') ? 'active' : '' }}">
                        Lớp
                    </a>
                </li>

                <li>
                    <a href="{{ route('majors.index') }}"
                       class="{{ request()->routeIs('majors.*') ? 'active' : '' }}">
                        Ngành
                    </a>
                </li>
            </ul>
        </li>

        <!-- Mượn trả -->
        <li>
            <a data-bs-toggle="collapse" href="#borrowMenu"
               class="{{ request()->is('loan_slips*') ? '' : 'collapsed' }}">
                <i class="bi bi-receipt"></i>
                <span>Mượn trả</span>
                <i class="bi bi-chevron-down ms-auto"></i>
            </a>

            <ul class="collapse submenu
                {{ request()->is('loan_slips*') ? 'show' : '' }}"
                id="borrowMenu">

                <li>
                    <a href="{{ route('loan_slips.index') }}"
                       class="{{ request()->routeIs('loan_slips.*') ? 'active' : '' }}">
                        Phiếu mượn
                    </a>
                </li>
            </ul>
        </li>

    </ul>

    <!-- Logout -->
    <div class="sidebar-footer mt-auto">
        <form action="{{ route('admin.logout') }}" method="POST">
            @csrf
            <button class="btn btn-danger w-100">
                <i class="bi bi-box-arrow-right"></i> Đăng xuất
            </button>
        </form>
    </div>

</div>

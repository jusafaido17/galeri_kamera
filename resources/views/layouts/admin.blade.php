<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin - Galeri Kamera')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-red: #DC2626;
            --dark-gray: #1F2937;
            --medium-gray: #4B5563;
            --light-gray: #F3F4F6;
            --white: #FFFFFF;
            --black: #111827;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light-gray);
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 260px;
            background: linear-gradient(180deg, var(--black) 0%, var(--dark-gray) 100%);
            padding: 1.5rem 0;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            overflow-y: auto;
            z-index: 1000;
        }

        .sidebar-brand {
            padding: 0 1.5rem;
            margin-bottom: 2rem;
        }

        .sidebar-brand h4 {
            color: var(--white);
            font-weight: 700;
            font-size: 1.5rem;
        }

        .sidebar-brand span {
            color: var(--primary-red);
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
        }

        .sidebar-menu li {
            margin-bottom: 0.5rem;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 0.9rem 1.5rem;
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            transition: all 0.3s;
            font-weight: 500;
        }

        .sidebar-menu a i {
            margin-right: 0.8rem;
            width: 20px;
            font-size: 1.1rem;
        }

        .sidebar-menu a:hover {
            background: rgba(220, 38, 38, 0.1);
            color: var(--white);
            border-left: 4px solid var(--primary-red);
        }

        .sidebar-menu a.active {
            background: rgba(220, 38, 38, 0.15);
            color: var(--white);
            border-left: 4px solid var(--primary-red);
        }

        /* Main Content Area */
        .main-wrapper {
            margin-left: 260px;
            min-height: 100vh;
        }

        /* Top Navbar */
        .top-navbar {
            background: var(--white);
            padding: 1rem 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .top-navbar h5 {
            margin: 0;
            color: var(--dark-gray);
            font-weight: 600;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-menu .btn-back {
            background-color: var(--medium-gray);
            color: var(--white);
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.3s;
        }

        .user-menu .btn-back:hover {
            background-color: var(--dark-gray);
        }

        .user-menu .dropdown-toggle {
            background: none;
            border: none;
            color: var(--dark-gray);
            font-weight: 500;
            cursor: pointer;
        }

        /* Content */
        .content-area {
            padding: 2rem;
        }

        /* Cards */
        .card-custom {
            border: none;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 1.5rem;
        }

        .card-custom .card-header {
            background: linear-gradient(135deg, var(--dark-gray) 0%, var(--medium-gray) 100%);
            color: var(--white);
            border-radius: 15px 15px 0 0;
            padding: 1rem 1.5rem;
            font-weight: 600;
        }

        .card-custom .card-body {
            padding: 1.5rem;
        }

        /* Stats Cards */
        .stat-card {
            border-radius: 15px;
            padding: 1.5rem;
            color: var(--white);
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            transform: translate(30%, -30%);
        }

        .stat-card .icon {
            font-size: 2.5rem;
            opacity: 0.3;
            position: absolute;
            bottom: 10px;
            right: 15px;
        }

        .stat-card h3 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .stat-card p {
            margin: 0;
            opacity: 0.9;
        }

        .stat-red { background: linear-gradient(135deg, #DC2626 0%, #B91C1C 100%); }
        .stat-blue { background: linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%); }
        .stat-green { background: linear-gradient(135deg, #059669 0%, #047857 100%); }
        .stat-purple { background: linear-gradient(135deg, #7C3AED 0%, #6D28D9 100%); }

        /* Table */
        .table-custom {
            background: var(--white);
            border-radius: 10px;
            overflow: hidden;
        }

        .table-custom thead {
            background: var(--dark-gray);
            color: var(--white);
        }

        .table-custom tbody tr:hover {
            background-color: var(--light-gray);
        }

        /* Buttons */
        .btn-primary-custom {
            background: linear-gradient(135deg, var(--primary-red) 0%, #B91C1C 100%);
            border: none;
            color: var(--white);
            padding: 0.6rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(220, 38, 38, 0.3);
        }

        /* Badge */
        .badge-custom {
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-weight: 500;
            font-size: 0.85rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .main-wrapper {
                margin-left: 0;
            }
        }
    </style>

    @yield('styles')
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <h4><i class="fas fa-camera"></i> Galeri<span>Kamera</span></h4>
            <small style="color: rgba(255,255,255,0.5);">Admin Panel</small>
        </div>
        <ul class="sidebar-menu">
            <li>
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    <i class="fas fa-list"></i> Kategori
                </a>
            </li>
            <li>
                <a href="{{ route('admin.products.index') }}" class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                    <i class="fas fa-box"></i> Produk
                </a>
            </li>
            <li>
                <a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                    <i class="fas fa-shopping-cart"></i> Pesanan
                </a>
            </li>
            <li style="margin-top: 2rem; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 1rem;">
                <a href="{{ route('home') }}">
                    <i class="fas fa-globe"></i> Lihat Website
                </a>
            </li>
            <li>
                <a href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </li>
        </ul>
    </aside>

    <!-- Main Content -->
    <div class="main-wrapper">
        <!-- Top Navbar -->
        <nav class="top-navbar">
            <h5>@yield('page-title', 'Dashboard')</h5>
            <div class="user-menu">
                <a href="{{ route('home') }}" class="btn-back">
                    <i class="fas fa-home"></i> Ke Website
                </a>
                <div class="dropdown">
                    <button class="dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle"></i> {{ Auth::user()->name }}
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form-top').submit();">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                            <form id="logout-form-top" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Content Area -->
        <div class="content-area">
            <!-- Alert Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @yield('scripts')
</body>
</html>

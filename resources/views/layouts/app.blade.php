<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Galeri Kamera - Rental Kamera & Alat Fotografi')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            /* Warna Tema */
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
            color: var(--dark-gray);
        }

        /* Navbar */
        .navbar-custom {
            background: linear-gradient(135deg, var(--black) 0%, var(--dark-gray) 100%);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 1rem 0;
        }

        .navbar-custom .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--white) !important;
        }

        .navbar-custom .navbar-brand span {
            color: var(--primary-red);
        }

        .navbar-custom .nav-link {
            color: var(--white) !important;
            font-weight: 500;
            margin: 0 0.5rem;
            transition: all 0.3s;
        }

        .navbar-custom .nav-link:hover {
            color: var(--primary-red) !important;
        }

        .navbar-custom .btn-login {
            background-color: transparent;
            border: 2px solid var(--primary-red);
            color: var(--primary-red) !important;
            padding: 0.5rem 1.5rem;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .navbar-custom .btn-login:hover {
            background-color: var(--primary-red);
            color: var(--white) !important;
        }

        .navbar-custom .btn-cart {
            position: relative;
            color: var(--white) !important;
            font-size: 1.3rem;
        }

        .navbar-custom .cart-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background-color: var(--primary-red);
            color: var(--white);
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
        }

        /* Main Content */
        .main-content {
            min-height: calc(100vh - 200px);
            padding: 2rem 0;
        }

        /* Footer */
        .footer-custom {
            background: linear-gradient(135deg, var(--black) 0%, var(--dark-gray) 100%);
            color: var(--white);
            padding: 3rem 0 1rem;
            margin-top: 4rem;
        }

        .footer-custom h5 {
            color: var(--primary-red);
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .footer-custom p, .footer-custom a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            font-size: 0.9rem;
        }

        .footer-custom a:hover {
            color: var(--primary-red);
        }

        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,0.1);
            padding-top: 1rem;
            margin-top: 2rem;
            text-align: center;
            color: rgba(255,255,255,0.6);
        }

        /* Buttons */
        .btn-primary-custom {
            background: linear-gradient(135deg, var(--primary-red) 0%, #B91C1C 100%);
            border: none;
            color: var(--white);
            padding: 0.75rem 2rem;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(220, 38, 38, 0.4);
        }

        /* Alert */
        .alert-custom {
            border-radius: 10px;
            border: none;
            padding: 1rem 1.5rem;
        }

        /* Cards */
        .card-custom {
            border: none;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: all 0.3s;
        }

        .card-custom:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
    </style>

    @yield('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-camera"></i> Galeri<span>Kamera</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('products.index') }}">Produk</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn-cart" href="{{ route('cart.index') }}">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="cart-badge" id="cart-count">{{ $cartCount ?? 0 }}</span>
                        </a>
                    </li>
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Register</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn-login" href="{{ route('login') }}">Login</a>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
    @php
        $levelIcons = [
            'bronze' => 'fas fa-medal',
            'silver' => 'fas fa-trophy',
            'platinum' => 'fas fa-crown'
        ];
        $levelColors = [
            'bronze' => '#CD7F32',
            'silver' => '#C0C0C0',
            'platinum' => '#E5E4E2'
        ];
    @endphp
    <i class="{{ $levelIcons[Auth::user()->member_level] }}" style="color: {{ $levelColors[Auth::user()->member_level] }};"></i>
    {{ Auth::user()->name }}
</a>
                            <ul class="dropdown-menu">
    @if(Auth::user()->isAdmin())
        <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">
            <i class="fas fa-tachometer-alt"></i> Dashboard Admin
        </a></li>
        <li><hr class="dropdown-divider"></li>
    @else
        <li><a class="dropdown-item" href="{{ route('member.dashboard') }}">
            <i class="fas fa-crown"></i> Member Dashboard
        </a></li>
        <li><hr class="dropdown-divider"></li>
    @endif
    <li><a class="dropdown-item" href="{{ route('orders.index') }}">
        <i class="fas fa-box"></i> Pesanan Saya
    </a></li>
    <li><hr class="dropdown-divider"></li>
    <li>
        <a class="dropdown-item" href="{{ route('logout') }}"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </li>
</ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <!-- Alert Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-custom alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-custom alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('info'))
                <div class="alert alert-info alert-custom alert-dismissible fade show" role="alert">
                    <i class="fas fa-info-circle"></i> {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer-custom">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <h5><i class="fas fa-camera"></i> GaleriKamera</h5>
                    <p>Platform terpercaya untuk sewa kamera dan alat fotografi berkualitas dengan harga terjangkau.</p>
                </div>
                <div class="col-md-4 mb-3">
                    <h5>Link Cepat</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('home') }}">Home</a></li>
                        <li><a href="{{ route('products.index') }}">Produk</a></li>
                        <li><a href="{{ route('cart.index') }}">Keranjang</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-3">
                    <h5>Kontak</h5>
                    <p><i class="fas fa-envelope"></i> info@galerikamera.com</p>
                    <p><i class="fas fa-phone"></i> +62 812-3456-7890</p>
                    <p><i class="fas fa-map-marker-alt"></i> Blitar, Jawa Timur</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2026 Galeri Kamera. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @yield('scripts')
</body>
</html>

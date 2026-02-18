@extends('layouts.app')

@section('title', 'Galeri Kamera - Rental Kamera & Alat Fotografi Profesional')

@section('styles')
<style>
    /* Hero Section */
    .hero-section {
        background: linear-gradient(135deg, var(--black) 0%, var(--dark-gray) 100%);
        color: var(--white);
        padding: 5rem 0;
        margin-top: -2rem;
        margin-bottom: 3rem;
        border-radius: 0 0 30px 30px;
    }

    .hero-section h1 {
        font-size: 3rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }

    .hero-section h1 span {
        color: var(--primary-red);
    }

    .hero-section p {
        font-size: 1.2rem;
        opacity: 0.9;
        margin-bottom: 2rem;
    }

    /* Category Cards */
    .category-card {
        background: var(--white);
        border-radius: 15px;
        padding: 2rem;
        text-align: center;
        transition: all 0.3s;
        cursor: pointer;
        border: 2px solid transparent;
        height: 100%;
    }

    .category-card:hover {
        border-color: var(--primary-red);
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(220, 38, 38, 0.2);
    }

    .category-card i {
        font-size: 3rem;
        color: var(--primary-red);
        margin-bottom: 1rem;
    }

    .category-card h5 {
        color: var(--dark-gray);
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .category-card .product-count {
        color: var(--medium-gray);
        font-size: 0.9rem;
    }

    /* Product Cards */
    .product-card {
        background: var(--white);
        border-radius: 15px;
        overflow: hidden;
        transition: all 0.3s;
        border: none;
        height: 100%;
    }

    .product-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }

    .product-image {
        width: 100%;
        height: 250px;
        object-fit: cover;
        background: var(--light-gray);
    }

    .product-card .card-body {
        padding: 1.5rem;
    }

    .product-category {
        display: inline-block;
        background: var(--light-gray);
        color: var(--medium-gray);
        padding: 0.3rem 0.8rem;
        border-radius: 20px;
        font-size: 0.8rem;
        margin-bottom: 0.5rem;
    }

    .product-name {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--dark-gray);
        margin-bottom: 0.5rem;
    }

    .product-price {
        color: var(--primary-red);
        font-size: 1.3rem;
        font-weight: 700;
    }

    .product-price small {
        font-size: 0.8rem;
        color: var(--medium-gray);
        font-weight: 400;
    }

    .stock-badge {
        display: inline-block;
        padding: 0.3rem 0.8rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
    }

    .stock-available {
        background: #D1FAE5;
        color: #065F46;
    }

    .stock-low {
        background: #FEF3C7;
        color: #92400E;
    }

    /* Section Title */
    .section-title {
        text-align: center;
        margin-bottom: 3rem;
    }

    .section-title h2 {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--dark-gray);
        margin-bottom: 0.5rem;
    }

    .section-title p {
        color: var(--medium-gray);
        font-size: 1.1rem;
    }
</style>
@endsection

@section('content')
<!-- Hero Section -->
<div class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <h1>Galeri <span>Kamera</span> & Alat Fotografi Profesional</h1>
                <p>Platform terpercaya untuk menyewa kamera DSLR, Mirrorless, Lensa, dan peralatan fotografi berkualitas tinggi dengan harga terjangkau.</p>
                <a href="{{ route('products.index') }}" class="btn btn-primary-custom btn-lg">
                    <i class="fas fa-search"></i> Jelajahi Produk
                </a>
            </div>
            <div class="col-lg-5 text-center">
                <i class="fas fa-camera" style="font-size: 15rem; opacity: 0.1;"></i>
            </div>
        </div>
    </div>
</div>

<!-- Categories Section -->
<section class="mb-5">
    <div class="section-title">
        <h2>Kategori <span style="color: var(--primary-red);">Produk</span></h2>
        <p>Pilih kategori sesuai kebutuhan fotografi Anda</p>
    </div>

    <div class="row g-4">
        @foreach($categories->take(6) as $category)
        <div class="col-md-4 col-lg-2">
            <a href="{{ route('products.category', $category->slug) }}" style="text-decoration: none;">
                <div class="category-card">
                    <i class="fas fa-camera"></i>
                    <h5>{{ $category->name }}</h5>
                    <p class="product-count">{{ $category->products_count }} Produk</p>
                </div>
            </a>
        </div>
        @endforeach
    </div>
</section>

<!-- Products Section -->
<section class="mb-5">
    <div class="section-title">
        <h2>Produk <span style="color: var(--primary-red);">Terbaru</span></h2>
        <p>Peralatan fotografi terkini dengan kualitas terbaik</p>
    </div>

    <div class="row g-4">
        @forelse($products as $product)
        <div class="col-md-6 col-lg-3">
            <div class="card product-card">
                @if($product->image)
                    <img src="{{ asset('uploads/products/' . $product->image) }}" class="product-image" alt="{{ $product->name }}">
                @else
                    <div class="product-image d-flex align-items-center justify-content-center">
                        <i class="fas fa-camera" style="font-size: 4rem; color: var(--medium-gray);"></i>
                    </div>
                @endif

                <div class="card-body">
                    <span class="product-category">{{ $product->category->name }}</span>
                    <h5 class="product-name">{{ Str::limit($product->name, 40) }}</h5>

                    <div class="mb-2">
                        @if($product->stock > 2)
                            <span class="stock-badge stock-available">
                                <i class="fas fa-check-circle"></i> Tersedia ({{ $product->stock }})
                            </span>
                        @elseif($product->stock > 0)
                            <span class="stock-badge stock-low">
                                <i class="fas fa-exclamation-circle"></i> Stok Terbatas ({{ $product->stock }})
                            </span>
                        @else
                            <span class="stock-badge" style="background: #FEE2E2; color: #991B1B;">
                                <i class="fas fa-times-circle"></i> Habis
                            </span>
                        @endif
                    </div>

                    <p class="product-price">
                        Rp {{ number_format($product->price_6_hours, 0, ',', '.') }}
                        <small>/6 jam</small>
                    </p>

                    <a href="{{ route('products.show', $product->slug) }}" class="btn btn-primary-custom w-100">
                        <i class="fas fa-info-circle"></i> Lihat Detail
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center">
            <p class="text-muted">Belum ada produk</p>
        </div>
        @endforelse
    </div>

    <div class="text-center mt-4">
        <a href="{{ route('products.index') }}" class="btn btn-outline-dark btn-lg">
            Lihat Semua Produk <i class="fas fa-arrow-right"></i>
        </a>
    </div>
</section>

<!-- Features Section -->
<section class="mb-5">
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card card-custom text-center p-4">
                <i class="fas fa-shield-alt" style="font-size: 3rem; color: var(--primary-red); margin-bottom: 1rem;"></i>
                <h5>Produk Berkualitas</h5>
                <p class="text-muted">Semua peralatan terawat dengan baik dan siap pakai</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-custom text-center p-4">
                <i class="fas fa-clock" style="font-size: 3rem; color: var(--primary-red); margin-bottom: 1rem;"></i>
                <h5>Fleksibel</h5>
                <p class="text-muted">Pilihan durasi sewa mulai dari 6 jam hingga 1.5 hari</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-custom text-center p-4">
                <i class="fas fa-headset" style="font-size: 3rem; color: var(--primary-red); margin-bottom: 1rem;"></i>
                <h5>Support 24/7</h5>
                <p class="text-muted">Tim kami siap membantu Anda kapan saja</p>
            </div>
        </div>
    </div>
</section>
@endsection

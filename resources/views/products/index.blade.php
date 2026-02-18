@extends('layouts.app')

@section('title', 'Katalog Produk - Sewa Kamera')

@section('styles')
<style>
    .filter-section {
        background: var(--white);
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    .filter-section h5 {
        color: var(--dark-gray);
        font-weight: 600;
        margin-bottom: 1rem;
    }

    .category-list {
        list-style: none;
        padding: 0;
    }

    .category-list li {
        margin-bottom: 0.5rem;
    }

    .category-list a {
        color: var(--medium-gray);
        text-decoration: none;
        padding: 0.5rem;
        display: block;
        border-radius: 8px;
        transition: all 0.3s;
    }

    .category-list a:hover,
    .category-list a.active {
        background: var(--light-gray);
        color: var(--primary-red);
        font-weight: 600;
    }

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
        min-height: 50px;
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

    .stock-out {
        background: #FEE2E2;
        color: #991B1B;
    }
</style>
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2 class="mb-3">Katalog <span style="color: var(--primary-red);">Produk</span></h2>
        <p class="text-muted">Temukan kamera dan peralatan fotografi yang Anda butuhkan</p>
    </div>
</div>

<div class="row">
    <!-- Sidebar Filter -->
    <div class="col-lg-3">
        <div class="filter-section">
            <h5><i class="fas fa-filter"></i> Filter Kategori</h5>
            <ul class="category-list">
                <li>
                    <a href="{{ route('products.index') }}" class="{{ !request('category') ? 'active' : '' }}">
                        <i class="fas fa-th"></i> Semua Kategori
                    </a>
                </li>
                @foreach($categories as $cat)
                <li>
                    <a href="{{ route('products.index', ['category' => $cat->slug]) }}"
                       class="{{ request('category') == $cat->slug ? 'active' : '' }}">
                        <i class="fas fa-camera"></i> {{ $cat->name }} ({{ $cat->products_count }})
                    </a>
                </li>
                @endforeach
            </ul>
        </div>

        <div class="filter-section">
            <h5><i class="fas fa-search"></i> Pencarian</h5>
            <form action="{{ route('products.index') }}" method="GET">
                <input type="hidden" name="category" value="{{ request('category') }}">
                <div class="input-group">
                    <input type="text" name="search" class="form-control"
                           placeholder="Cari produk..." value="{{ request('search') }}">
                    <button class="btn btn-primary-custom" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Product List -->
    <div class="col-lg-9">
        <!-- Sort & Result Info -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <span class="text-muted">Menampilkan {{ $products->count() }} dari {{ $products->total() }} produk</span>
            </div>
            <div>
                <form action="{{ route('products.index') }}" method="GET" id="sortForm">
                    <input type="hidden" name="category" value="{{ request('category') }}">
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <select name="sort" class="form-select" onchange="document.getElementById('sortForm').submit()">
                        <option value="">Urutkan</option>
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nama A-Z</option>
                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Harga Terendah</option>
                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Harga Tertinggi</option>
                    </select>
                </form>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="row g-4">
            @forelse($products as $product)
            <div class="col-md-6 col-lg-4">
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
                        <h5 class="product-name">{{ Str::limit($product->name, 50) }}</h5>

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
                                <span class="stock-badge stock-out">
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
            <div class="col-12 text-center py-5">
                <i class="fas fa-box-open" style="font-size: 5rem; color: var(--medium-gray); opacity: 0.3;"></i>
                <h4 class="mt-3 text-muted">Produk tidak ditemukan</h4>
                <p class="text-muted">Coba kata kunci lain atau lihat kategori lainnya</p>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection

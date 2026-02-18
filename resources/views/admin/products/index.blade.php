@extends('layouts.admin')

@section('title', 'Kelola Produk - Admin')
@section('page-title', 'Kelola Produk')

@section('styles')
<style>
    .product-image-thumb {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
        background: var(--light-gray);
    }

    .filter-box {
        background: var(--white);
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
</style>
@endsection

@section('content')
<!-- Filter Section -->
<div class="filter-box">
    <form action="{{ route('admin.products.index') }}" method="GET" class="row g-3">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control" placeholder="Cari produk..." value="{{ request('search') }}">
        </div>
        <div class="col-md-3">
            <select name="category" class="form-select">
                <option value="">Semua Kategori</option>
                @foreach(\App\Models\Category::all() as $cat)
                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <select name="status" class="form-select">
                <option value="">Semua Status</option>
                <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Tersedia</option>
                <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Tidak Tersedia</option>
            </select>
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-primary me-2">
                <i class="fas fa-search"></i> Filter
            </button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                <i class="fas fa-redo"></i> Reset
            </a>
        </div>
    </form>
</div>

<!-- Product List -->
<div class="card-custom">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-box"></i> Daftar Produk</h5>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary-custom">
            <i class="fas fa-plus"></i> Tambah Produk
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-custom">
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th width="10%">Gambar</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th>Harga (6 Jam)</th>
                        <th>Stok</th>
                        <th>Status</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $index => $product)
                    <tr>
                        <td>{{ $products->firstItem() + $index }}</td>
                        <td>
                            @if($product->image)
                                <img src="{{ asset('uploads/products/' . $product->image) }}" class="product-image-thumb" alt="{{ $product->name }}">
                            @else
                                <div class="product-image-thumb d-flex align-items-center justify-content-center">
                                    <i class="fas fa-camera text-muted"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <strong>{{ $product->name }}</strong>
                            <br><small class="text-muted">{{ $product->slug }}</small>
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $product->category->name }}</span>
                        </td>
                        <td>
                            <strong class="text-danger">Rp {{ number_format($product->price_6_hours, 0, ',', '.') }}</strong>
                        </td>
                        <td>
                            @if($product->stock > 5)
                                <span class="badge bg-success">{{ $product->stock }} unit</span>
                            @elseif($product->stock > 0)
                                <span class="badge bg-warning text-dark">{{ $product->stock }} unit</span>
                            @else
                                <span class="badge bg-danger">Habis</span>
                            @endif
                        </td>
                        <td>
                            @if($product->is_available)
                                <span class="badge bg-success">Tersedia</span>
                            @else
                                <span class="badge bg-secondary">Tidak Tersedia</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.products.show', $product->id) }}" class="btn btn-sm btn-info" title="Lihat">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus produk ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            <i class="fas fa-box-open" style="font-size: 3rem; opacity: 0.3;"></i>
                            <p class="mb-0 mt-2">Belum ada produk</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-3">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection

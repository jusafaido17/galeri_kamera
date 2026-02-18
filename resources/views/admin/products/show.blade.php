@extends('layouts.admin')

@section('title', 'Detail Produk - Admin')
@section('page-title', 'Detail Produk')

@section('styles')
<style>
    .product-detail-image {
        width: 100%;
        max-width: 400px;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }

    .info-table {
        width: 100%;
    }

    .info-table tr {
        border-bottom: 1px solid var(--light-gray);
    }

    .info-table td {
        padding: 1rem;
    }

    .info-table td:first-child {
        font-weight: 600;
        color: var(--medium-gray);
        width: 30%;
    }

    .price-box {
        background: var(--light-gray);
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 1rem;
    }

    .price-item {
        display: flex;
        justify-content: space-between;
        padding: 0.5rem 0;
    }

    .price-item .label {
        color: var(--medium-gray);
    }

    .price-item .value {
        font-weight: 700;
        color: var(--primary-red);
    }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-4">
        <div class="card-custom">
            <div class="card-body text-center">
                @if($product->image)
                    <img src="{{ asset('uploads/products/' . $product->image) }}" class="product-detail-image" alt="{{ $product->name }}">
                @else
                    <div class="product-detail-image d-flex align-items-center justify-content-center" style="background: var(--light-gray); height: 400px;">
                        <i class="fas fa-camera" style="font-size: 5rem; color: var(--medium-gray);"></i>
                    </div>
                @endif
            </div>
        </div>

        <div class="card-custom mt-3">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-dollar-sign"></i> Harga Sewa</h5>
            </div>
            <div class="card-body">
                <div class="price-box">
                    <div class="price-item">
                        <span class="label">6 Jam</span>
                        <span class="value">Rp {{ number_format($product->price_6_hours, 0, ',', '.') }}</span>
                    </div>
                    <div class="price-item">
                        <span class="label">12 Jam</span>
                        <span class="value">Rp {{ number_format($product->price_12_hours, 0, ',', '.') }}</span>
                    </div>
                    <div class="price-item">
                        <span class="label">24 Jam</span>
                        <span class="value">Rp {{ number_format($product->price_24_hours, 0, ',', '.') }}</span>
                    </div>
                    <div class="price-item">
                        <span class="label">1.5 Hari</span>
                        <span class="value">Rp {{ number_format($product->price_1_5_days, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card-custom">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informasi Produk</h5>
            </div>
            <div class="card-body">
                <table class="info-table">
                    <tr>
                        <td>Nama Produk</td>
                        <td><strong>{{ $product->name }}</strong></td>
                    </tr>
                    <tr>
                        <td>Slug</td>
                        <td><code>{{ $product->slug }}</code></td>
                    </tr>
                    <tr>
                        <td>Kategori</td>
                        <td><span class="badge bg-info">{{ $product->category->name }}</span></td>
                    </tr>
                    <tr>
                        <td>Deskripsi</td>
                        <td style="white-space: pre-line;">{{ $product->description }}</td>
                    </tr>
                    <tr>
                        <td>Stok</td>
                        <td>
                            @if($product->stock > 5)
                                <span class="badge bg-success">{{ $product->stock }} unit</span>
                            @elseif($product->stock > 0)
                                <span class="badge bg-warning text-dark">{{ $product->stock }} unit</span>
                            @else
                                <span class="badge bg-danger">Habis</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Status</td>
                        <td>
                            @if($product->is_available)
                                <span class="badge bg-success">Tersedia</span>
                            @else
                                <span class="badge bg-secondary">Tidak Tersedia</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Dibuat</td>
                        <td>{{ $product->created_at->format('d M Y, H:i') }}</td>
                    </tr>
                    <tr>
                        <td>Terakhir Update</td>
                        <td>{{ $product->updated_at->format('d M Y, H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        @if($product->specifications)
        <div class="card-custom mt-3">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-list"></i> Spesifikasi</h5>
            </div>
            <div class="card-body">
                <table class="info-table">
                    @foreach($product->specifications as $key => $value)
                    <tr>
                        <td>{{ $key }}</td>
                        <td>{{ $value }}</td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
        @endif

        <div class="card-custom mt-3">
            <div class="card-body">
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Edit Produk
                    </a>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus produk ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

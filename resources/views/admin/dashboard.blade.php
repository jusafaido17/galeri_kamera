@extends('layouts.admin')

@section('title', 'Dashboard Admin - Galeri Kamera')
@section('page-title', 'Dashboard')

@section('styles')
<style>
    .stat-card {
        border-radius: 15px;
        padding: 1.5rem;
        color: var(--white);
        position: relative;
        overflow: hidden;
        margin-bottom: 1.5rem;
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
    .stat-orange { background: linear-gradient(135deg, #EA580C 0%, #C2410C 100%); }
    .stat-yellow { background: linear-gradient(135deg, #EAB308 0%, #CA8A04 100%); }

    .recent-orders-table {
        background: var(--white);
        border-radius: 10px;
        overflow: hidden;
    }

    .recent-orders-table thead {
        background: var(--dark-gray);
        color: var(--white);
    }

    .recent-orders-table tbody tr:hover {
        background-color: var(--light-gray);
    }

    .low-stock-item {
        display: flex;
        align-items: center;
        padding: 1rem;
        border-bottom: 1px solid var(--light-gray);
        transition: all 0.3s;
    }

    .low-stock-item:hover {
        background: var(--light-gray);
    }

    .low-stock-item:last-child {
        border-bottom: none;
    }

    .low-stock-image {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
        background: var(--light-gray);
        margin-right: 1rem;
    }

    .low-stock-details {
        flex: 1;
    }

    .low-stock-name {
        font-weight: 600;
        color: var(--dark-gray);
        margin-bottom: 0.3rem;
    }

    .low-stock-stock {
        color: var(--primary-red);
        font-weight: 700;
    }
</style>
@endsection

@section('content')
<!-- Stats Cards -->
<div class="row">
    <div class="col-md-6 col-lg-3">
        <div class="stat-card stat-blue">
            <h3>{{ $totalProducts }}</h3>
            <p>Total Produk</p>
            <i class="fas fa-box icon"></i>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="stat-card stat-green">
            <h3>{{ $totalCategories }}</h3>
            <p>Total Kategori</p>
            <i class="fas fa-list icon"></i>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="stat-card stat-purple">
            <h3>{{ $totalOrders }}</h3>
            <p>Total Pesanan</p>
            <i class="fas fa-shopping-cart icon"></i>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="stat-card stat-orange">
            <h3>{{ $totalUsers }}</h3>
            <p>Total User</p>
            <i class="fas fa-users icon"></i>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 col-lg-3">
        <div class="stat-card stat-yellow">
            <h3>{{ $pendingOrders }}</h3>
            <p>Pesanan Pending</p>
            <i class="fas fa-clock icon"></i>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="stat-card stat-red">
            <h3>{{ $pendingPayments }}</h3>
            <p>Pembayaran Perlu Konfirmasi</p>
            <i class="fas fa-credit-card icon"></i>
        </div>
    </div>
    <div class="col-md-6 col-lg-6">
        <div class="stat-card stat-green">
            <h3>Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}</h3>
            <p>Revenue Bulan Ini</p>
            <i class="fas fa-chart-line icon"></i>
        </div>
    </div>
</div>

<!-- Recent Orders & Low Stock -->
<div class="row">
    <!-- Recent Orders -->
    <div class="col-lg-8">
        <div class="card-custom">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-shopping-cart"></i> Pesanan Terbaru</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table recent-orders-table mb-0">
                        <thead>
                            <tr>
                                <th>Order Number</th>
                                <th>Customer</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                            <tr>
                                <td><strong>{{ $order->order_number }}</strong></td>
                                <td>{{ $order->user->name }}</td>
                                <td class="text-danger"><strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong></td>
                                <td>
                                    @switch($order->status)
                                        @case('pending')
                                            <span class="badge bg-warning">Pending</span>
                                            @break
                                        @case('confirmed')
                                            <span class="badge bg-info">Confirmed</span>
                                            @break
                                        @case('processing')
                                            <span class="badge bg-primary">Processing</span>
                                            @break
                                        @case('completed')
                                            <span class="badge bg-success">Completed</span>
                                            @break
                                        @case('cancelled')
                                            <span class="badge bg-danger">Cancelled</span>
                                            @break
                                    @endswitch
                                </td>
                                <td>{{ $order->created_at->format('d M Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    Belum ada pesanan
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Low Stock Products -->
    <div class="col-lg-4">
        <div class="card-custom">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-exclamation-triangle"></i> Stok Menipis</h5>
            </div>
            <div class="card-body p-0">
                @forelse($lowStockProducts as $product)
                <div class="low-stock-item">
                    <div>
                        @if($product->image)
                            <img src="{{ asset('uploads/products/' . $product->image) }}" class="low-stock-image" alt="{{ $product->name }}">
                        @else
                            <div class="low-stock-image d-flex align-items-center justify-content-center">
                                <i class="fas fa-camera" style="font-size: 1.5rem; color: var(--medium-gray);"></i>
                            </div>
                        @endif
                    </div>
                    <div class="low-stock-details">
                        <div class="low-stock-name">{{ Str::limit($product->name, 30) }}</div>
                        <small class="text-muted">{{ $product->category->name }}</small>
                    </div>
                    <div class="low-stock-stock">
                        {{ $product->stock }} unit
                    </div>
                </div>
                @empty
                <div class="p-4 text-center text-muted">
                    <i class="fas fa-check-circle" style="font-size: 2rem; opacity: 0.3;"></i>
                    <p class="mb-0 mt-2">Semua stok aman</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

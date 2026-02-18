@extends('layouts.admin')

@section('title', 'Kelola Pesanan - Admin')
@section('page-title', 'Kelola Pesanan')

@section('styles')
<style>
    .filter-box {
        background: var(--white);
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }

    .order-number {
        font-weight: 700;
        color: var(--dark-gray);
    }

    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.85rem;
    }

    .payment-badge {
        padding: 0.4rem 0.8rem;
        border-radius: 15px;
        font-weight: 500;
        font-size: 0.8rem;
    }
</style>
@endsection

@section('content')
<!-- Filter Section -->
<div class="filter-box">
    <form action="{{ route('admin.orders.index') }}" method="GET" class="row g-3">
        <div class="col-md-3">
            <input type="text" name="search" class="form-control" placeholder="Cari order number / nama..." value="{{ request('search') }}">
        </div>
        <div class="col-md-2">
            <select name="status" class="form-select">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </div>
        <div class="col-md-2">
            <select name="payment_status" class="form-select">
                <option value="">Semua Pembayaran</option>
                <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Belum Bayar</option>
                <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Sudah Bayar</option>
                <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>Gagal</option>
            </select>
        </div>
        <div class="col-md-2">
            <select name="payment_type" class="form-select">
                <option value="">Semua Tipe</option>
                <option value="full" {{ request('payment_type') == 'full' ? 'selected' : '' }}>Bayar Lunas</option>
                <option value="dp" {{ request('payment_type') == 'dp' ? 'selected' : '' }}>DP 30%</option>
            </select>
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-primary me-2">
                <i class="fas fa-search"></i> Filter
            </button>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                <i class="fas fa-redo"></i> Reset
            </a>
        </div>
    </form>
</div>

<!-- Orders List -->
<div class="card-custom">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-shopping-cart"></i> Daftar Pesanan</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-custom">
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th>Order Number</th>
                        <th>Customer</th>
                        <th>Items</th>
                        <th>Total</th>
                        <th>Tipe Bayar</th>
                        <th>Status Order</th>
                        <th>Status Payment</th>
                        <th>Tanggal</th>
                        <th width="10%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $index => $order)
                    <tr>
                        <td>{{ $orders->firstItem() + $index }}</td>
                        <td>
                            <span class="order-number">{{ $order->order_number }}</span>
                        </td>
                        <td>
                            <strong>{{ $order->user->name }}</strong>
                            <br><small class="text-muted">{{ $order->user->email }}</small>
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $order->orderItems->count() }} Item</span>
                        </td>
                        <td>
                            <strong class="text-danger">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong>
                            @if($order->payment->payment_type == 'dp')
                                <br><small class="text-muted">
                                    Dibayar: Rp {{ number_format($order->payment->amount, 0, ',', '.') }}
                                </small>
                            @endif
                        </td>
                        <td>
                            @if($order->payment->payment_type == 'dp')
                                <span class="payment-badge" style="background: #FEF3C7; color: #92400E;">
                                    <i class="fas fa-percentage"></i> DP 30%
                                </span>
                            @else
                                <span class="payment-badge" style="background: #D1FAE5; color: #065F46;">
                                    <i class="fas fa-check"></i> Lunas
                                </span>
                            @endif
                        </td>
                        <td>
                            @switch($order->status)
                                @case('pending')
                                    <span class="status-badge" style="background: #FEF3C7; color: #92400E;">
                                        <i class="fas fa-clock"></i> Pending
                                    </span>
                                    @break
                                @case('confirmed')
                                    <span class="status-badge" style="background: #DBEAFE; color: #1E40AF;">
                                        <i class="fas fa-check-circle"></i> Confirmed
                                    </span>
                                    @break
                                @case('processing')
                                    <span class="status-badge" style="background: #E0E7FF; color: #4338CA;">
                                        <i class="fas fa-cog"></i> Processing
                                    </span>
                                    @break
                                @case('completed')
                                    <span class="status-badge" style="background: #D1FAE5; color: #065F46;">
                                        <i class="fas fa-check-double"></i> Completed
                                    </span>
                                    @break
                                @case('cancelled')
                                    <span class="status-badge" style="background: #FEE2E2; color: #991B1B;">
                                        <i class="fas fa-times-circle"></i> Cancelled
                                    </span>
                                    @break
                            @endswitch
                        </td>
                        <td>
                            @switch($order->payment->status)
                                @case('pending')
                                    <span class="payment-badge" style="background: #FEE2E2; color: #991B1B;">
                                        <i class="fas fa-clock"></i> Belum Bayar
                                    </span>
                                    @if($order->payment->proof_image)
                                        <br><small class="text-warning">
                                            <i class="fas fa-exclamation-triangle"></i> Perlu Konfirmasi
                                        </small>
                                    @endif
                                    @break
                                @case('paid')
                                    <span class="payment-badge" style="background: #D1FAE5; color: #065F46;">
                                        <i class="fas fa-check-circle"></i> Sudah Bayar
                                    </span>
                                    @break
                                @case('failed')
                                    <span class="payment-badge" style="background: #FEE2E2; color: #991B1B;">
                                        <i class="fas fa-times-circle"></i> Gagal
                                    </span>
                                    @break
                            @endswitch
                        </td>
                        <td>
                            {{ $order->created_at->format('d M Y') }}
                            <br><small class="text-muted">{{ $order->created_at->format('H:i') }}</small>
                        </td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-primary" title="Lihat Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center text-muted py-4">
                            <i class="fas fa-box-open" style="font-size: 3rem; opacity: 0.3;"></i>
                            <p class="mb-0 mt-2">Belum ada pesanan</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-3">
            {{ $orders->links() }}
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mt-4">
    <div class="col-md-3">
        <div class="card-custom" style="background: linear-gradient(135deg, #FEF3C7 0%, #FDE68A 100%); padding: 1.5rem;">
            <h3 class="mb-1" style="color: #92400E;">{{ \App\Models\Order::where('status', 'pending')->count() }}</h3>
            <p class="mb-0" style="color: #92400E;">Pending Orders</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card-custom" style="background: linear-gradient(135deg, #DBEAFE 0%, #BFDBFE 100%); padding: 1.5rem;">
            <h3 class="mb-1" style="color: #1E40AF;">{{ \App\Models\Order::where('status', 'confirmed')->count() }}</h3>
            <p class="mb-0" style="color: #1E40AF;">Confirmed Orders</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card-custom" style="background: linear-gradient(135deg, #E0E7FF 0%, #C7D2FE 100%); padding: 1.5rem;">
            <h3 class="mb-1" style="color: #4338CA;">{{ \App\Models\Order::where('status', 'processing')->count() }}</h3>
            <p class="mb-0" style="color: #4338CA;">Processing Orders</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card-custom" style="background: linear-gradient(135deg, #FEE2E2 0%, #FECACA 100%); padding: 1.5rem;">
            <h3 class="mb-1" style="color: #991B1B;">
                {{ \App\Models\Payment::where('status', 'pending')->whereNotNull('proof_image')->count() }}
            </h3>
            <p class="mb-0" style="color: #991B1B;">Perlu Konfirmasi</p>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Pesanan Saya - Sewa Kamera')

@section('styles')
<style>
    .order-card {
        background: var(--white);
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        margin-bottom: 1.5rem;
        transition: all 0.3s;
    }

    .order-card:hover {
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }

    .order-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-bottom: 1rem;
        border-bottom: 2px solid var(--light-gray);
        margin-bottom: 1rem;
    }

    .order-number {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--dark-gray);
    }

    .order-status {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .status-pending {
        background: #FEF3C7;
        color: #92400E;
    }

    .status-confirmed {
        background: #DBEAFE;
        color: #1E40AF;
    }

    .status-processing {
        background: #E0E7FF;
        color: #4338CA;
    }

    .status-completed {
        background: #D1FAE5;
        color: #065F46;
    }

    .status-cancelled {
        background: #FEE2E2;
        color: #991B1B;
    }

    .order-items {
        margin: 1rem 0;
    }

    .order-item-row {
        display: flex;
        align-items: center;
        padding: 0.8rem 0;
        border-bottom: 1px solid var(--light-gray);
    }

    .order-item-row:last-child {
        border-bottom: none;
    }

    .order-item-image {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
        background: var(--light-gray);
        margin-right: 1rem;
    }

    .order-item-name {
        flex: 1;
        font-weight: 600;
        color: var(--dark-gray);
    }

    .order-item-qty {
        color: var(--medium-gray);
        margin-right: 1rem;
    }

    .order-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 1rem;
        margin-top: 1rem;
        border-top: 2px solid var(--light-gray);
    }

    .order-total {
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--primary-red);
    }

    .empty-orders {
        text-align: center;
        padding: 4rem 2rem;
        background: var(--white);
        border-radius: 15px;
    }

    .empty-orders i {
        font-size: 6rem;
        color: var(--medium-gray);
        opacity: 0.3;
        margin-bottom: 1.5rem;
    }
</style>
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2 class="mb-3"><i class="fas fa-box"></i> Pesanan <span style="color: var(--primary-red);">Saya</span></h2>
        <p class="text-muted">Riwayat dan status pesanan Anda</p>
    </div>
</div>

@if($orders->isEmpty())
    <div class="empty-orders">
        <i class="fas fa-box-open"></i>
        <h4>Belum Ada Pesanan</h4>
        <p class="text-muted mb-4">Anda belum pernah melakukan pesanan</p>
        <a href="{{ route('products.index') }}" class="btn btn-primary-custom btn-lg">
            <i class="fas fa-camera"></i> Mulai Belanja
        </a>
    </div>
@else
    @foreach($orders as $order)
    <div class="order-card">
        <div class="order-header">
            <div>
                <div class="order-number">
                    <i class="fas fa-receipt"></i> {{ $order->order_number }}
                </div>
                <small class="text-muted">
                    <i class="fas fa-calendar"></i> {{ $order->created_at->format('d M Y, H:i') }}
                </small>
            </div>
            <div>
                <span class="order-status status-{{ $order->status }}">
                    @switch($order->status)
                        @case('pending')
                            <i class="fas fa-clock"></i> Menunggu Pembayaran
                            @break
                        @case('confirmed')
                            <i class="fas fa-check-circle"></i> Dikonfirmasi
                            @break
                        @case('processing')
                            <i class="fas fa-cog"></i> Diproses
                            @break
                        @case('completed')
                            <i class="fas fa-check-double"></i> Selesai
                            @break
                        @case('cancelled')
                            <i class="fas fa-times-circle"></i> Dibatalkan
                            @break
                    @endswitch
               </span>

                @if($order->payment->payment_type == 'dp')
                    <span class="badge bg-warning text-dark ms-2">
                        <i class="fas fa-percentage"></i> DP 30%
                    </span>
                @endif
            </div>
        </div>

        <div class="order-items">
            @foreach($order->orderItems->take(3) as $item)
            <div class="order-item-row">
                <div>
                    @if($item->product->image)
                        <img src="{{ asset('uploads/products/' . $item->product->image) }}" class="order-item-image" alt="{{ $item->product->name }}">
                    @else
                        <div class="order-item-image d-flex align-items-center justify-content-center">
                            <i class="fas fa-camera" style="font-size: 1.5rem; color: var(--medium-gray);"></i>
                        </div>
                    @endif
                </div>
                <div class="order-item-name">{{ $item->product->name }}</div>
                <div class="order-item-qty">{{ $item->quantity }}x</div>
                <div class="order-item-price" style="color: var(--primary-red); font-weight: 600;">
                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                </div>
            </div>
            @endforeach

            @if($order->orderItems->count() > 3)
                <p class="text-muted small mb-0 mt-2">
                    <i class="fas fa-plus"></i> {{ $order->orderItems->count() - 3 }} produk lainnya
                </p>
            @endif
        </div>

        <div class="order-footer">
            <div>
                <strong>Total:</strong>
                <span class="order-total">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
            </div>
            <div>
                <a href="{{ route('orders.show', $order->order_number) }}" class="btn btn-primary-custom">
                    <i class="fas fa-eye"></i> Lihat Detail
                </a>
            </div>
        </div>
    </div>
    @endforeach

    <!-- Pagination -->
    <div class="mt-4">
        {{ $orders->links() }}
    </div>
@endif
@endsection

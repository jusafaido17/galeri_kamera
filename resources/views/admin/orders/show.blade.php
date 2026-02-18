@extends('layouts.admin')

@section('title', 'Detail Pesanan - Admin')
@section('page-title', 'Detail Pesanan #' . $order->order_number)

@section('styles')
<style>
    .order-detail-card {
        background: var(--white);
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        margin-bottom: 1.5rem;
    }

    .info-row {
        display: flex;
        padding: 0.8rem 0;
        border-bottom: 1px solid var(--light-gray);
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-label {
        width: 200px;
        font-weight: 600;
        color: var(--medium-gray);
    }

    .info-value {
        flex: 1;
        color: var(--dark-gray);
    }

    .order-item-row {
        display: flex;
        align-items: center;
        padding: 1rem 0;
        border-bottom: 1px solid var(--light-gray);
    }

    .order-item-row:last-child {
        border-bottom: none;
    }

    .order-item-image {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 10px;
        background: var(--light-gray);
        margin-right: 1rem;
    }

    .proof-image {
        max-width: 100%;
        max-height: 400px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .action-buttons {
        display: flex;
        gap: 1rem;
        margin-top: 1rem;
    }

    .timeline {
        position: relative;
        padding-left: 2rem;
    }

    .timeline-item {
        position: relative;
        padding-bottom: 1.5rem;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: -1.5rem;
        top: 0;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: var(--primary-red);
    }

    .timeline-item::after {
        content: '';
        position: absolute;
        left: -1.45rem;
        top: 12px;
        width: 2px;
        height: calc(100% - 12px);
        background: var(--light-gray);
    }

    .timeline-item:last-child::after {
        display: none;
    }
</style>
@endsection

@section('content')
<div class="row">
    <!-- Left Column -->
    <div class="col-lg-8">
        <!-- Order Info -->
        <div class="order-detail-card">
            <h5 style="border-bottom: 2px solid var(--light-gray); padding-bottom: 1rem; margin-bottom: 1.5rem;">
                <i class="fas fa-info-circle"></i> Informasi Pesanan
            </h5>

            <div class="info-row">
                <div class="info-label">Order Number</div>
                <div class="info-value">
                    <strong style="font-size: 1.1rem;">{{ $order->order_number }}</strong>
                </div>
            </div>

            <div class="info-row">
                <div class="info-label">Customer</div>
                <div class="info-value">
                    <strong>{{ $order->user->name }}</strong>
                    <br><small class="text-muted">{{ $order->user->email }}</small>
                </div>
            </div>

            <div class="info-row">
                <div class="info-label">Tanggal Order</div>
                <div class="info-value">{{ $order->created_at->format('d M Y, H:i') }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Status Order</div>
                <div class="info-value">
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
                </div>
            </div>

            @if($order->notes)
            <div class="info-row">
                <div class="info-label">Catatan Customer</div>
                <div class="info-value">{{ $order->notes }}</div>
            </div>
            @endif
        </div>

        <!-- Order Items -->
        <div class="order-detail-card">
            <h5 style="border-bottom: 2px solid var(--light-gray); padding-bottom: 1rem; margin-bottom: 1.5rem;">
                <i class="fas fa-box"></i> Produk yang Disewa
            </h5>

            @foreach($order->orderItems as $item)
            <div class="order-item-row">
                <div>
                    @if($item->product->image)
                        <img src="{{ asset('uploads/products/' . $item->product->image) }}" class="order-item-image" alt="{{ $item->product->name }}">
                    @else
                        <div class="order-item-image d-flex align-items-center justify-content-center">
                            <i class="fas fa-camera" style="font-size: 2rem; color: var(--medium-gray);"></i>
                        </div>
                    @endif
                </div>
                <div style="flex: 1;">
                    <h6 style="margin-bottom: 0.5rem;">{{ $item->product->name }}</h6>
                    <p class="text-muted mb-1" style="font-size: 0.9rem;">
                        <i class="fas fa-clock"></i>
                        @switch($item->duration)
                            @case('6_hours') 6 Jam @break
                            @case('12_hours') 12 Jam @break
                            @case('24_hours') 24 Jam @break
                            @case('1_5_days') 1.5 Hari @break
                        @endswitch
                        | Qty: {{ $item->quantity }}
                    </p>
                    <p class="text-muted mb-0" style="font-size: 0.85rem;">
                        <i class="fas fa-calendar"></i>
                        {{ \Carbon\Carbon::parse($item->rental_start)->format('d M Y H:i') }} -
                        {{ \Carbon\Carbon::parse($item->rental_end)->format('d M Y H:i') }}
                    </p>
                </div>
                <div style="text-align: right;">
                    <div style="color: var(--primary-red); font-weight: 700; font-size: 1.1rem;">
                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                    </div>
                    <div class="text-muted" style="font-size: 0.85rem;">
                        @ Rp {{ number_format($item->price, 0, ',', '.') }}
                    </div>
                </div>
            </div>
            @endforeach

            <div style="padding-top: 1rem; margin-top: 1rem; border-top: 2px solid var(--dark-gray);">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <strong style="font-size: 1.3rem;">Total Pesanan</strong>
                    <span style="color: var(--primary-red); font-weight: 700; font-size: 1.5rem;">
                        Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Update Status Form -->
        <div class="order-detail-card">
            <h5 style="border-bottom: 2px solid var(--light-gray); padding-bottom: 1rem; margin-bottom: 1.5rem;">
                <i class="fas fa-edit"></i> Update Status Order
            </h5>

            <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-8">
                        <select name="status" class="form-select form-select-lg" required>
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary-custom w-100 btn-lg">
                            <i class="fas fa-save"></i> Update Status
                        </button>
                    </div>
                </div>
            </form>

            <div class="alert alert-info mt-3 mb-0">
                <small>
                    <strong>Keterangan Status:</strong>
                    <ul class="mb-0 mt-2">
                        <li><strong>Pending:</strong> Menunggu pembayaran</li>
                        <li><strong>Confirmed:</strong> Pembayaran sudah dikonfirmasi</li>
                        <li><strong>Processing:</strong> Barang sedang disiapkan</li>
                        <li><strong>Completed:</strong> Pesanan selesai</li>
                        <li><strong>Cancelled:</strong> Pesanan dibatalkan</li>
                    </ul>
                </small>
            </div>
        </div>
    </div>

    <!-- Right Column -->
    <div class="col-lg-4">
        <!-- Payment Info -->
        <div class="order-detail-card">
            <h5 style="border-bottom: 2px solid var(--light-gray); padding-bottom: 1rem; margin-bottom: 1.5rem;">
                <i class="fas fa-credit-card"></i> Informasi Pembayaran
            </h5>

            <div class="info-row">
                <div class="info-label">Metode</div>
                <div class="info-value text-capitalize">
                    @switch($order->payment->payment_method)
                        @case('transfer') <i class="fas fa-university"></i> Transfer Bank @break
                        @case('e-wallet') <i class="fas fa-wallet"></i> E-Wallet @break
                        @case('manual') <i class="fas fa-hand-holding-usd"></i> Manual @break
                    @endswitch
                </div>
            </div>

            <div class="info-row">
                <div class="info-label">Tipe Pembayaran</div>
                <div class="info-value">
                    @if($order->payment->payment_type == 'dp')
                        <span class="badge bg-warning text-dark">
                            <i class="fas fa-percentage"></i> DP 30%
                        </span>
                    @else
                        <span class="badge bg-success">
                            <i class="fas fa-check-circle"></i> Bayar Lunas
                        </span>
                    @endif
                </div>
            </div>

            @if($order->payment->payment_type == 'dp')
            <div class="info-row">
                <div class="info-label">Jumlah DP (30%)</div>
                <div class="info-value">
                    <strong style="color: var(--primary-red);">Rp {{ number_format($order->payment->dp_amount, 0, ',', '.') }}</strong>
                </div>
            </div>
            <div class="info-row">
                <div class="info-label">Sisa Pembayaran</div>
                <div class="info-value">
                    <strong style="color: var(--dark-gray);">Rp {{ number_format($order->payment->remaining_amount, 0, ',', '.') }}</strong>
                    <br><small class="text-muted">Dibayar saat pengambilan</small>
                </div>
            </div>
            @endif

            <div class="info-row">
                <div class="info-label">Yang Harus Dibayar</div>
                <div class="info-value">
                    <strong style="color: var(--primary-red); font-size: 1.2rem;">
                        Rp {{ number_format($order->payment->amount, 0, ',', '.') }}
                    </strong>
                </div>
            </div>

            <div class="info-row">
                <div class="info-label">Status Pembayaran</div>
                <div class="info-value">
                    @switch($order->payment->status)
                        @case('pending')
                            <span class="badge bg-warning">Belum Bayar</span>
                            @break
                        @case('paid')
                            <span class="badge bg-success">Sudah Bayar</span>
                            @break
                        @case('failed')
                            <span class="badge bg-danger">Gagal</span>
                            @break
                    @endswitch
                </div>
            </div>

            @if($order->payment->paid_at)
            <div class="info-row">
                <div class="info-label">Tanggal Bayar</div>
                <div class="info-value">{{ $order->payment->paid_at->format('d M Y, H:i') }}</div>
            </div>
            @endif
        </div>

        <!-- Payment Proof -->
        @if($order->payment->proof_image)
        <div class="order-detail-card">
            <h5 style="border-bottom: 2px solid var(--light-gray); padding-bottom: 1rem; margin-bottom: 1.5rem;">
                <i class="fas fa-image"></i> Bukti Pembayaran
            </h5>

            <div class="text-center mb-3">
                <a href="{{ asset('uploads/payments/' . $order->payment->proof_image) }}" target="_blank">
                    <img src="{{ asset('uploads/payments/' . $order->payment->proof_image) }}" class="proof-image" alt="Bukti Pembayaran">
                </a>
            </div>

            @if($order->payment->status == 'pending')
            <form action="{{ route('admin.orders.confirm-payment', $order->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-success w-100 btn-lg" onclick="return confirm('Konfirmasi pembayaran ini?')">
                    <i class="fas fa-check-circle"></i> Konfirmasi Pembayaran
                </button>
            </form>

            <div class="alert alert-warning mt-3 mb-0">
                <small>
                    <i class="fas fa-exclamation-triangle"></i>
                    Pastikan bukti pembayaran valid sebelum konfirmasi
                </small>
            </div>
            @else
            <div class="alert alert-success mb-0">
                <i class="fas fa-check-circle"></i> Pembayaran sudah dikonfirmasi
            </div>
            @endif
        </div>
        @else
        <div class="order-detail-card">
            <div class="alert alert-info mb-0">
                <i class="fas fa-info-circle"></i>
                Customer belum upload bukti pembayaran
            </div>
        </div>
        @endif

        <!-- Action Buttons -->
        <!-- Action Buttons -->
<!-- Action Buttons -->
<div class="order-detail-card">
    <div class="d-flex flex-column gap-2">
        <!-- Tombol Lihat Struk -->
        <a href="{{ route('orders.receipt', $order->order_number) }}"
           target="_blank"
           class="btn btn-primary w-100">
            <i class="fas fa-file-alt"></i> Lihat Struk
        </a>

        <!-- Tombol Cetak Struk -->
        <a href="{{ route('orders.receipt', $order->order_number) }}"
           target="_blank"
           id="btnCetakStruk"
           class="btn btn-success w-100">
            <i class="fas fa-print"></i> Cetak Struk
        </a>

        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary w-100">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar
        </a>
    </div>
</div>
    </div>
</div>
@push('scripts')
<script>
    document.getElementById('btnCetakStruk').addEventListener('click', function(e) {
        e.preventDefault();
        const url = this.href;
        const win = window.open(url, '_blank');
        win.onload = function() {
            win.print();
        };
    });
</script>
@endpush
@endsection

@extends('layouts.app')

@section('title', 'Detail Pesanan #' . $order->order_number)

@section('styles')
<style>
    .order-detail-card {
        background: var(--white);
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        margin-bottom: 1.5rem;
    }

    .order-detail-card h5 {
        font-weight: 700;
        color: var(--dark-gray);
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid var(--light-gray);
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

    .order-status-badge {
        padding: 0.7rem 1.5rem;
        border-radius: 25px;
        font-weight: 600;
        font-size: 1.1rem;
        display: inline-block;
    }

    .payment-status-badge {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.9rem;
        display: inline-block;
    }

    .payment-info-box {
        background: var(--light-gray);
        border-radius: 10px;
        padding: 1.5rem;
        margin: 1rem 0;
    }

    .bank-info {
        background: var(--white);
        border: 2px dashed var(--primary-red);
        border-radius: 10px;
        padding: 1.5rem;
        margin: 1rem 0;
    }

    .bank-info h6 {
        color: var(--primary-red);
        font-weight: 700;
        margin-bottom: 1rem;
    }

    .proof-image {
        max-width: 300px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    /* Style untuk tombol cetak struk */
    .receipt-buttons {
        display: flex;
        gap: 10px;
        margin-top: 15px;
    }

    .btn-receipt {
        flex: 1;
        padding: 12px 20px;
        border-radius: 8px;
        font-weight: 600;
        text-align: center;
        text-decoration: none;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-receipt-view {
        background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%);
        color: white;
        border: none;
    }

    .btn-receipt-view:hover {
        background: linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }

    .btn-receipt-print {
        background: linear-gradient(135deg, #10B981 0%, #059669 100%);
        color: white;
        border: none;
    }

    .btn-receipt-print:hover {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .payment-confirmed-card {
        background: linear-gradient(135deg, #D1FAE5 0%, #A7F3D0 100%);
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 1.5rem;
        border: 2px solid #10B981;
    }

    .payment-confirmed-icon {
        font-size: 4rem;
        color: #10B981;
        margin-bottom: 1rem;
    }

    .payment-confirmed-card h4 {
        color: #065F46;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .payment-confirmed-card p {
        color: #047857;
        margin-bottom: 0;
    }
</style>
@endsection

@section('content')
<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('orders.index') }}">Pesanan Saya</a></li>
        <li class="breadcrumb-item active">{{ $order->order_number }}</li>
    </ol>
</nav>

<div class="row mb-4">
    <div class="col-12">
        <h2 class="mb-3">Detail Pesanan</h2>
        <p class="text-muted">{{ $order->order_number }}</p>
    </div>
</div>

<div class="row">
    <!-- Left Column -->
    <div class="col-lg-8">
        <!-- Order Status -->
        <div class="order-detail-card">
            <h5><i class="fas fa-info-circle"></i> Status Pesanan</h5>
            <div class="text-center py-3">
                <span class="order-status-badge status-{{ $order->status }}">
                    @switch($order->status)
                        @case('pending')
                            <i class="fas fa-clock"></i> Menunggu Pembayaran
                            @break
                        @case('confirmed')
                            <i class="fas fa-check-circle"></i> Pesanan Dikonfirmasi
                            @break
                        @case('processing')
                            <i class="fas fa-cog"></i> Sedang Diproses
                            @break
                        @case('completed')
                            <i class="fas fa-check-double"></i> Pesanan Selesai
                            @break
                        @case('cancelled')
                            <i class="fas fa-times-circle"></i> Pesanan Dibatalkan
                            @break
                    @endswitch
                </span>
            </div>
        </div>

        <!-- Status Pembayaran & Tombol Cetak Struk -->
        @if($order->payment->status == 'paid')
        <!-- Pembayaran Sudah Dikonfirmasi - Tampilkan Tombol Cetak Struk -->
        <div class="payment-confirmed-card">
            <div class="text-center">
                <i class="fas fa-check-circle payment-confirmed-icon"></i>
                <h4>✅ Pembayaran Telah Dikonfirmasi</h4>
                <p>Pesanan Anda telah dibayar dan dikonfirmasi oleh admin. Terima kasih!</p>
                
                <!-- Tombol Cetak Struk -->
                <div class="receipt-buttons">
                    <a href="{{ route('orders.receipt', $order->order_number) }}" 
                       class="btn-receipt btn-receipt-view" 
                       target="_blank">
                        <i class="fas fa-receipt"></i> Lihat Struk
                    </a>
                    
                    <a href="{{ route('orders.receipt', $order->order_number) }}?print=1" 
                       class="btn-receipt btn-receipt-print" 
                       target="_blank">
                        <i class="fas fa-print"></i> Cetak Struk
                    </a>
                </div>

                @if($order->payment->proof_image)
                <div class="mt-4">
                    <small class="text-muted">Bukti Pembayaran:</small>
                    <br>
                    <img src="{{ asset('uploads/payments/' . $order->payment->proof_image) }}"
                         alt="Bukti Pembayaran"
                         style="max-width: 100%; max-height: 300px; border-radius: 10px; margin-top: 0.5rem; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                </div>
                @endif
            </div>
        </div>
        @elseif($order->payment->proof_image && $order->payment->status == 'pending')
        <!-- Bukti Sudah Diupload, Menunggu Konfirmasi -->
        <div class="card-custom mt-4">
            <div class="card-header" style="background: linear-gradient(135deg, #FEF3C7 0%, #FDE68A 100%);">
                <h5 class="mb-0" style="color: #92400E;">
                    <i class="fas fa-clock"></i> Menunggu Konfirmasi Admin
                </h5>
            </div>
            <div class="card-body">
                <div class="alert alert-warning">
                    <i class="fas fa-info-circle"></i>
                    <strong>Bukti pembayaran Anda sedang diverifikasi oleh admin.</strong>
                    <br>
                    Silakan tunggu konfirmasi. Anda akan dapat mencetak struk setelah pembayaran dikonfirmasi.
                </div>
                <div class="text-center">
                    <small class="text-muted">Bukti Pembayaran yang Diupload:</small>
                    <br>
                    <img src="{{ asset('uploads/payments/' . $order->payment->proof_image) }}"
                         alt="Bukti Pembayaran"
                         style="max-width: 100%; max-height: 400px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-top: 0.5rem;">
                </div>
            </div>
        </div>
        @else
        <!-- Upload Bukti Pembayaran -->
        <div class="card-custom mt-4">
            <div class="card-header" style="background: linear-gradient(135deg, #FEF3C7 0%, #FDE68A 100%);">
                <h5 class="mb-0" style="color: #92400E;">
                    <i class="fas fa-upload"></i> Upload Bukti Pembayaran
                </h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <strong><i class="fas fa-info-circle"></i> Informasi Rekening:</strong>
                    <br>
                    <strong>Bank:</strong> BCA
                    <br>
                    <strong>No. Rekening:</strong> 1234567890
                    <br>
                    <strong>Atas Nama:</strong> Galeri Kamera
                    <br><br>
                    <strong>Jumlah yang harus dibayar:</strong>
                    <span style="color: var(--primary-red); font-size: 1.3rem; font-weight: 700;">
                        Rp {{ number_format($order->payment->amount, 0, ',', '.') }}
                    </span>
                </div>

                <!-- Form Upload -->
                <form action="{{ route('orders.upload-payment', $order->order_number) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label"><strong>Pilih File Bukti Transfer</strong></label>
                        <input type="file" name="proof_image" class="form-control" accept="image/*" required>
                        <small class="text-muted">Format: JPG, PNG, JPEG (Max: 2MB)</small>
                    </div>
                    <button type="submit" class="btn btn-primary-custom btn-lg w-100">
                        <i class="fas fa-upload"></i> Upload Bukti Pembayaran
                    </button>
                </form>
            </div>
        </div>
        @endif

        <!-- Order Items -->
        <div class="order-detail-card">
            <h5><i class="fas fa-box"></i> Produk yang Disewa</h5>
            @foreach($order->orderItems as $item)
            <div class="order-item-row" style="display: flex; align-items: center; padding: 1rem 0; border-bottom: 1px solid var(--light-gray);">
                <div>
                    @if($item->product->image)
                        <img src="{{ asset('uploads/products/' . $item->product->image) }}" style="width: 80px; height: 80px; object-fit: cover; border-radius: 10px; margin-right: 1rem;" alt="{{ $item->product->name }}">
                    @else
                        <div style="width: 80px; height: 80px; background: var(--light-gray); border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-right: 1rem;">
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
                    <strong style="font-size: 1.3rem;">Total Pembayaran</strong>
                    <span style="color: var(--primary-red); font-weight: 700; font-size: 1.5rem;">
                        Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Order Details -->
        <div class="order-detail-card">
            <h5><i class="fas fa-receipt"></i> Detail Pesanan</h5>
            <div class="info-row">
                <div class="info-label">Nomor Pesanan</div>
                <div class="info-value">{{ $order->order_number }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Tanggal Pesanan</div>
                <div class="info-value">{{ $order->created_at->format('d M Y, H:i') }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Status Pesanan</div>
                <div class="info-value">
                    <span class="order-status-badge status-{{ $order->status }}" style="padding: 0.3rem 0.8rem; font-size: 0.85rem;">
                        @switch($order->status)
                            @case('pending') Menunggu Pembayaran @break
                            @case('confirmed') Dikonfirmasi @break
                            @case('processing') Diproses @break
                            @case('completed') Selesai @break
                            @case('cancelled') Dibatalkan @break
                        @endswitch
                    </span>
                </div>
            </div>
            @if($order->notes)
            <div class="info-row">
                <div class="info-label">Catatan</div>
                <div class="info-value">{{ $order->notes }}</div>
            </div>
            @endif
        </div>
    </div>

    <!-- Right Column -->
    <div class="col-lg-4">
        <!-- Payment Info -->
        <div class="order-detail-card">
            <h5><i class="fas fa-credit-card"></i> Informasi Pembayaran</h5>
            
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
                    <br><small class="text-muted">Dibayar saat pengambilan barang</small>
                </div>
            </div>
            @endif

            <div class="info-row">
                <div class="info-label">
                    @if($order->payment->payment_type == 'dp')
                        Bayar Sekarang (DP)
                    @else
                        Total Pembayaran
                    @endif
                </div>
                <div class="info-value">
                    <strong style="color: var(--primary-red); font-size: 1.2rem;">
                        Rp {{ number_format($order->payment->amount, 0, ',', '.') }}
                    </strong>
                </div>
            </div>

            <div class="info-row">
                <div class="info-label">Status Pembayaran</div>
                <div class="info-value">
                    @if($order->payment->status == 'pending')
                        <span class="badge bg-warning">Belum Bayar</span>
                    @elseif($order->payment->status == 'paid')
                        <span class="badge bg-success">Sudah Bayar</span>
                    @else
                        <span class="badge bg-danger">Gagal</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Checkout - Sewa Kamera')

@section('styles')
<style>
    .checkout-card {
        background: var(--white);
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        margin-bottom: 1.5rem;
    }

    .checkout-card h5 {
        font-weight: 700;
        color: var(--dark-gray);
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid var(--light-gray);
    }

    .order-item {
        display: flex;
        padding: 1rem 0;
        border-bottom: 1px solid var(--light-gray);
    }

    .order-item:last-child {
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

    .order-item-details {
        flex: 1;
    }

    .order-item-name {
        font-weight: 600;
        color: var(--dark-gray);
        margin-bottom: 0.3rem;
    }

    .order-item-info {
        font-size: 0.85rem;
        color: var(--medium-gray);
        margin-bottom: 0.2rem;
    }

    .order-item-price {
        color: var(--primary-red);
        font-weight: 700;
    }

    .payment-method {
        border: 2px solid var(--light-gray);
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 1rem;
        cursor: pointer;
        transition: all 0.3s;
    }

    .payment-method:hover {
        border-color: var(--primary-red);
        background: rgba(220, 38, 38, 0.05);
    }

    .payment-method input[type="radio"] {
        margin-right: 1rem;
    }

    .payment-method.selected {
        border-color: var(--primary-red);
        background: rgba(220, 38, 38, 0.1);
    }

    .summary-box {
        background: var(--light-gray);
        border-radius: 10px;
        padding: 1.5rem;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        padding: 0.5rem 0;
    }

    .summary-row.total {
        border-top: 2px solid var(--dark-gray);
        margin-top: 1rem;
        padding-top: 1rem;
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--primary-red);
    }
</style>
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2 class="mb-3"><i class="fas fa-credit-card"></i> Checkout</h2>
        <p class="text-muted">Lengkapi pesanan Anda dan lakukan pembayaran</p>
    </div>
</div>

<form action="{{ route('checkout.process') }}" method="POST">
    @csrf
    <div class="row">
        <!-- Order Details -->
        <div class="col-lg-8">
            <!-- User Info -->
            <div class="checkout-card">
                <h5><i class="fas fa-user"></i> Informasi Pemesan</h5>
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Nama:</strong> {{ Auth::user()->name }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="checkout-card">
                <h5><i class="fas fa-box"></i> Detail Pesanan</h5>
                @foreach($cartItems as $item)
                <div class="order-item">
                    <div>
                        @if($item->product->image)
                            <img src="{{ asset('uploads/products/' . $item->product->image) }}" class="order-item-image" alt="{{ $item->product->name }}">
                        @else
                            <div class="order-item-image d-flex align-items-center justify-content-center">
                                <i class="fas fa-camera" style="font-size: 2rem; color: var(--medium-gray);"></i>
                            </div>
                        @endif
                    </div>
                    <div class="order-item-details">
                        <div class="order-item-name">{{ $item->product->name }}</div>
                        <div class="order-item-info">
                            <i class="fas fa-clock"></i>
                            @switch($item->duration)
                                @case('6_hours') 6 Jam @break
                                @case('12_hours') 12 Jam @break
                                @case('24_hours') 24 Jam @break
                                @case('1_5_days') 1.5 Hari @break
                            @endswitch
                            | Qty: {{ $item->quantity }}
                        </div>
                        <div class="order-item-info">
                            <i class="fas fa-calendar"></i>
                            {{ \Carbon\Carbon::parse($item->rental_start)->format('d M Y H:i') }} -
                            {{ \Carbon\Carbon::parse($item->rental_end)->format('d M Y H:i') }}
                        </div>
                    </div>
                    <div class="order-item-price">
                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Payment Method -->
            <div class="checkout-card">
                <h5><i class="fas fa-credit-card"></i> Metode Pembayaran</h5>

                <label class="payment-method">
                    <input type="radio" name="payment_method" value="transfer" required>
                    <div>
                        <strong><i class="fas fa-university"></i> Transfer Bank</strong>
                        <p class="mb-0 text-muted small">Transfer ke rekening bank kami</p>
                    </div>
                </label>

                <label class="payment-method">
                    <input type="radio" name="payment_method" value="e-wallet" required>
                    <div>
                        <strong><i class="fas fa-wallet"></i> E-Wallet</strong>
                        <p class="mb-0 text-muted small">GoPay, OVO, DANA, ShopeePay</p>
                    </div>
                </label>

                <label class="payment-method">
                    <input type="radio" name="payment_method" value="manual" required>
                    <div>
                        <strong><i class="fas fa-hand-holding-usd"></i> Bayar Manual</strong>
                        <p class="mb-0 text-muted small">Bayar di tempat saat pengambilan</p>
                    </div>
                </label>
            </div>

            <!-- Payment Type (DP or Full) -->
            <div class="checkout-card">
                <h5><i class="fas fa-money-bill-wave"></i> Tipe Pembayaran</h5>

                <label class="payment-method">
                    <input type="radio" name="payment_type" value="full" required checked>
                    <div>
                        <strong><i class="fas fa-check-circle"></i> Bayar Lunas</strong>
                        <p class="mb-0 text-muted small">Bayar penuh saat ini</p>
                        <p class="mb-0 text-success"><strong id="fullPaymentAmount">Rp {{ number_format($total, 0, ',', '.') }}</strong></p>
                    </div>
                </label>

                <label class="payment-method">
                    <input type="radio" name="payment_type" value="dp" required>
                    <div>
                        <strong><i class="fas fa-percentage"></i> DP 30%</strong>
                        <p class="mb-0 text-muted small">Bayar DP 30% dulu, sisanya saat pengambilan</p>
                        <p class="mb-0 text-warning">
                            <strong>DP: <span id="dpAmount">Rp {{ number_format($total * 0.3, 0, ',', '.') }}</span></strong>
                        </p>
                        <p class="mb-0 text-muted small">
                            Sisa: <span id="remainingAmount">Rp {{ number_format($total * 0.7, 0, ',', '.') }}</span> (bayar saat ambil barang)
                        </p>
                    </div>
                </label>
            </div>

            <!-- Notes -->
            <div class="checkout-card">
                <h5><i class="fas fa-comment"></i> Catatan (Opsional)</h5>
                <textarea name="notes" class="form-control" rows="4" placeholder="Tambahkan catatan untuk pesanan Anda..."></textarea>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="col-lg-4">
            <div class="checkout-card" style="position: sticky; top: 100px;">
                <h5><i class="fas fa-receipt"></i> Ringkasan Pembayaran</h5>

               <div class="summary-box">
                    <div class="summary-row">
                        <span>Subtotal ({{ $cartItems->count() }} item)</span>
                        <span id="subtotalDisplay">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                    <div class="summary-row">
                        <span>Tipe Pembayaran</span>
                        <span id="paymentTypeDisplay">Bayar Lunas</span>
                    </div>
                    <div class="summary-row total">
                        <span>Yang Harus Dibayar</span>
                        <span id="totalDisplay">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                    <div id="remainingInfo" style="display: none; margin-top: 1rem; padding-top: 1rem; border-top: 1px solid rgba(0,0,0,0.1);">
                        <small class="text-muted">
                            <i class="fas fa-info-circle"></i>
                            Sisa pembayaran <strong id="remainingDisplay">Rp 0</strong> dibayar saat pengambilan barang
                        </small>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary-custom w-100 btn-lg mt-3">
                    <i class="fas fa-check-circle"></i> Konfirmasi Pesanan
                </button>

                <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary w-100 mt-2">
                    <i class="fas fa-arrow-left"></i> Kembali ke Keranjang
                </a>

                <div class="alert alert-info mt-3 mb-0">
                    <small>
                        <i class="fas fa-info-circle"></i>
                        Setelah konfirmasi, Anda akan diarahkan ke halaman pembayaran
                    </small>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('scripts')
<script>
// Auto select payment method on click
document.querySelectorAll('.payment-method').forEach(element => {
    element.addEventListener('click', function() {
        // Remove selected from all in same group
        const radio = this.querySelector('input[type="radio"]');
        const groupName = radio.name;

        document.querySelectorAll(`input[name="${groupName}"]`).forEach(r => {
            r.closest('.payment-method').classList.remove('selected');
        });

        this.classList.add('selected');
        radio.checked = true;

        // Update summary if payment_type changed
        if (groupName === 'payment_type') {
            updatePaymentSummary();
        }
    });
});

function updatePaymentSummary() {
    const total = {{ $total }};
    const paymentType = document.querySelector('input[name="payment_type"]:checked').value;

    if (paymentType === 'dp') {
        const dp = total * 0.3;
        const remaining = total * 0.7;

        document.getElementById('paymentTypeDisplay').textContent = 'DP 30%';
        document.getElementById('totalDisplay').textContent = 'Rp ' + formatNumber(dp);
        document.getElementById('remainingDisplay').textContent = 'Rp ' + formatNumber(remaining);
        document.getElementById('remainingInfo').style.display = 'block';
    } else {
        document.getElementById('paymentTypeDisplay').textContent = 'Bayar Lunas';
        document.getElementById('totalDisplay').textContent = 'Rp ' + formatNumber(total);
        document.getElementById('remainingInfo').style.display = 'none';
    }
}

function formatNumber(num) {
    return Math.round(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    updatePaymentSummary();
});
</script>
@endsection

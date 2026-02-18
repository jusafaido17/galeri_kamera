@extends('layouts.app')

@section('title', 'Keranjang Belanja - Sewa Kamera')

@section('styles')
<style>
    .cart-card {
        background: var(--white);
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        margin-bottom: 1.5rem;
    }

    .cart-item {
        display: flex;
        align-items: center;
        padding: 1.5rem;
        border-bottom: 1px solid var(--light-gray);
    }

    .cart-item:last-child {
        border-bottom: none;
    }

    .cart-item-image {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 10px;
        background: var(--light-gray);
        margin-right: 1.5rem;
    }

    .cart-item-details {
        flex: 1;
    }

    .cart-item-name {
        font-size: 1.2rem;
        font-weight: 600;
        color: var(--dark-gray);
        margin-bottom: 0.5rem;
    }

    .cart-item-info {
        color: var(--medium-gray);
        font-size: 0.9rem;
        margin-bottom: 0.3rem;
    }

    .cart-item-price {
        color: var(--primary-red);
        font-size: 1.3rem;
        font-weight: 700;
        margin-top: 0.5rem;
    }

    .cart-item-actions {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        align-items: flex-end;
    }

    .quantity-control {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .quantity-control button {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        border: 2px solid var(--primary-red);
        background: var(--white);
        color: var(--primary-red);
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s;
    }

    .quantity-control button:hover {
        background: var(--primary-red);
        color: var(--white);
    }

    .quantity-control input {
        width: 60px;
        text-align: center;
        border: 2px solid var(--light-gray);
        border-radius: 8px;
        padding: 0.5rem;
        font-weight: 600;
    }

    .cart-summary {
        background: var(--light-gray);
        border-radius: 15px;
        padding: 2rem;
        position: sticky;
        top: 100px;
    }

    .cart-summary h5 {
        font-weight: 700;
        color: var(--dark-gray);
        margin-bottom: 1.5rem;
    }

    .summary-item {
        display: flex;
        justify-content: space-between;
        padding: 0.8rem 0;
        border-bottom: 1px solid rgba(0,0,0,0.1);
    }

    .summary-item:last-child {
        border-bottom: none;
        padding-top: 1rem;
        margin-top: 1rem;
        border-top: 2px solid var(--dark-gray);
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--primary-red);
    }

    .empty-cart {
        text-align: center;
        padding: 4rem 2rem;
    }

    .empty-cart i {
        font-size: 6rem;
        color: var(--medium-gray);
        opacity: 0.3;
        margin-bottom: 1.5rem;
    }

    /* Member Code Styles */
    .member-code-card {
        background: linear-gradient(135deg, #EDE9FE 0%, #DDD6FE 100%);
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    .member-code-card h5 {
        color: #5B21B6;
        font-weight: 700;
        margin-bottom: 1rem;
    }

    .member-info-box {
        background: white;
        border-radius: 10px;
        padding: 1.5rem;
        margin-top: 1rem;
    }

    .member-info-box .member-header {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid var(--light-gray);
    }

    .member-info-box .member-header i {
        font-size: 2.5rem;
        margin-right: 1rem;
    }

    @media (max-width: 768px) {
        .cart-item {
            flex-direction: column;
            text-align: center;
        }
        .cart-item-image {
            margin-right: 0;
            margin-bottom: 1rem;
        }
        .cart-item-actions {
            align-items: center;
        }
    }
</style>
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2 class="mb-3"><i class="fas fa-shopping-cart"></i> Keranjang <span style="color: var(--primary-red);">Belanja</span></h2>
        <p class="text-muted">Kelola pesanan Anda sebelum checkout</p>
    </div>
</div>

@if($cartItems->isEmpty())
    <div class="cart-card">
        <div class="empty-cart">
            <i class="fas fa-shopping-cart"></i>
            <h4>Keranjang Anda Kosong</h4>
            <p class="text-muted mb-4">Belum ada produk di keranjang. Mulai belanja sekarang!</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary-custom btn-lg">
                <i class="fas fa-camera"></i> Lihat Produk
            </a>
        </div>
    </div>
@else
<div class="row">
    <!-- Cart Items -->
    <div class="col-lg-8">
        <div class="cart-card">
            @foreach($cartItems as $item)
            <div class="cart-item">
                <div>
                    @if($item->product->image)
                        <img src="{{ asset('uploads/products/' . $item->product->image) }}" class="cart-item-image" alt="{{ $item->product->name }}">
                    @else
                        <div class="cart-item-image d-flex align-items-center justify-content-center">
                            <i class="fas fa-camera" style="font-size: 3rem; color: var(--medium-gray);"></i>
                        </div>
                    @endif
                </div>

                <div class="cart-item-details">
                    <h5 class="cart-item-name">{{ $item->product->name }}</h5>
                    <p class="cart-item-info">
                        <i class="fas fa-tag"></i> {{ $item->product->category->name }}
                    </p>
                    <p class="cart-item-info">
                        <i class="fas fa-clock"></i>
                        @switch($item->duration)
                            @case('6_hours') 6 Jam @break
                            @case('12_hours') 12 Jam @break
                            @case('24_hours') 24 Jam @break
                            @case('1_5_days') 1.5 Hari @break
                        @endswitch
                    </p>
                    <p class="cart-item-info">
                        <i class="fas fa-calendar"></i> {{ \Carbon\Carbon::parse($item->rental_date)->format('d M Y') }}
                        <i class="fas fa-arrow-right mx-2"></i>
                        {{ \Carbon\Carbon::parse($item->rental_end)->format('d M Y H:i') }}
                    </p>
                    <p class="cart-item-price">
                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                    </p>
                </div>

                <div class="cart-item-actions">
                    <!-- Quantity Control -->
                    <div class="quantity-control">
                        <button type="button" onclick="updateQuantity({{ $item->id }}, {{ $item->quantity - 1 }})">-</button>
                        <input type="number" value="{{ $item->quantity }}" readonly>
                        <button type="button" onclick="updateQuantity({{ $item->id }}, {{ $item->quantity + 1 }})">+</button>
                    </div>

                    <!-- Remove Button -->
                    <form action="{{ route('cart.remove', $item->id) }}" method="POST" onsubmit="return confirm('Hapus item ini dari keranjang?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Member Code Verification (BARU) 🆕 -->
        <div class="member-code-card">
            <h5><i class="fas fa-id-card"></i> Punya Kartu Member?</h5>
            <p class="text-muted mb-3" style="font-size: 0.9rem;">
                Masukkan kode member Anda untuk mendapatkan benefit eksklusif!
            </p>

            <div class="input-group mb-3">
                <input type="text"
                       class="form-control form-control-lg"
                       id="memberCodeInput"
                       placeholder="Contoh: MBR-BRO-0001"
                       style="text-transform: uppercase;">
                <button class="btn btn-primary" type="button" id="verifyMemberBtn">
                    <i class="fas fa-check-circle"></i> Verifikasi
                </button>
            </div>

            <!-- Member Info (Hidden by default) -->
            <div id="memberInfo" style="display: none;">
                <div class="member-info-box" id="memberInfoBox">
                    <div class="member-header">
                        <i id="memberIcon"></i>
                        <div>
                            <h6 class="mb-0" id="memberName" style="font-weight: 700;"></h6>
                            <small id="memberEmail" class="text-muted"></small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <p class="mb-2"><strong>Level:</strong> <span id="memberLevelName"></span></p>
                            <p class="mb-2"><strong>Total Transaksi:</strong> <span id="memberOrders"></span>x</p>
                        </div>
                        <div class="col-6">
                            <p class="mb-2"><strong>Diskon:</strong> <span id="memberDiscount" style="color: var(--primary-red); font-weight: 700;"></span></p>
                        </div>
                    </div>
                    <hr>
                    <p class="mb-2"><strong>Benefits:</strong></p>
                    <ul id="memberBenefits" style="font-size: 0.9rem;"></ul>
                </div>
            </div>

            <!-- Error Message -->
            <div id="memberError" class="alert alert-danger" style="display: none; margin-top: 1rem;">
                <i class="fas fa-exclamation-circle"></i> <span id="errorMessage"></span>
            </div>
        </div>
    </div>

    <!-- Cart Summary -->
    <div class="col-lg-4">
        <div class="cart-summary">
            <h5><i class="fas fa-receipt"></i> Ringkasan Pesanan</h5>

            <div class="summary-item">
                <span>Total Item</span>
                <span>{{ $cartItems->count() }} Produk</span>
            </div>

            <div class="summary-item">
                <span>Subtotal</span>
                <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
            </div>

            <div class="summary-item">
                <span>Total</span>
                <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
            </div>

            <a href="{{ route('checkout.index') }}" class="btn btn-primary-custom w-100 btn-lg mt-3">
                <i class="fas fa-credit-card"></i> Lanjut ke Checkout
            </a>

            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary w-100 mt-2">
                <i class="fas fa-arrow-left"></i> Lanjut Belanja
            </a>
        </div>
    </div>
</div>
@endif
@endsection

@section('scripts')
<script>
// Update Quantity
function updateQuantity(cartId, newQuantity) {
    if (newQuantity < 1) {
        alert('Jumlah minimal adalah 1');
        return;
    }

    fetch(`/cart/update/${cartId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ quantity: newQuantity })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan. Silakan coba lagi.');
    });
}

// Verify Member Code (BARU) 🆕
document.getElementById('verifyMemberBtn').addEventListener('click', function() {
    const memberCode = document.getElementById('memberCodeInput').value.trim().toUpperCase();
    const btn = this;

    if (!memberCode) {
        showError('Masukkan kode member terlebih dahulu!');
        return;
    }

    // Loading state
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Verifikasi...';

    // Hide previous results
    document.getElementById('memberInfo').style.display = 'none';
    document.getElementById('memberError').style.display = 'none';

    // AJAX Request
    fetch('/cart/verify-member', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            member_code: memberCode
        })
    })
    .then(response => response.json())
    .then(data => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-check-circle"></i> Verifikasi';

        if (data.success) {
            showMemberInfo(data.data);
        } else {
            showError(data.message);
        }
    })
    .catch(error => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-check-circle"></i> Verifikasi';
        showError('Terjadi kesalahan. Silakan coba lagi.');
        console.error('Error:', error);
    });
});

function showMemberInfo(member) {
    const memberInfo = document.getElementById('memberInfo');
    const memberInfoBox = document.getElementById('memberInfoBox');

    // Set colors based on level
    let bgColor, borderColor;
    if (member.level === 'bronze') {
        bgColor = '#FEF3C7';
        borderColor = '#CD7F32';
    } else if (member.level === 'silver') {
        bgColor = '#E5E7EB';
        borderColor = '#C0C0C0';
    } else {
        bgColor = '#EDE9FE';
        borderColor = '#E5E4E2';
    }

    memberInfoBox.style.backgroundColor = bgColor;
    memberInfoBox.style.borderLeft = `4px solid ${borderColor}`;

    // Fill data
    document.getElementById('memberIcon').className = member.level_icon;
    document.getElementById('memberIcon').style.color = member.level_color;
    document.getElementById('memberName').textContent = member.name;
    document.getElementById('memberEmail').textContent = member.email;
    document.getElementById('memberLevelName').textContent = 'Member ' + member.level_name;
    document.getElementById('memberOrders').textContent = member.total_orders;
    document.getElementById('memberDiscount').textContent = member.discount_percentage + '%';

    // Benefits list
    const benefitsList = document.getElementById('memberBenefits');
    benefitsList.innerHTML = '';
    member.benefits.forEach(benefit => {
        const li = document.createElement('li');
        li.textContent = benefit;
        benefitsList.appendChild(li);
    });

    // Show info
    memberInfo.style.display = 'block';

    // Success alert
    alert('✅ Kode member berhasil diverifikasi!\n\nLevel: ' + member.level_name + '\nDiskon: ' + member.discount_percentage + '%');
}

function showError(message) {
    document.getElementById('errorMessage').textContent = message;
    document.getElementById('memberError').style.display = 'block';
}

// Allow Enter key to submit
document.getElementById('memberCodeInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        document.getElementById('verifyMemberBtn').click();
    }
});
</script>
@endsection

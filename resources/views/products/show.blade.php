@extends('layouts.app')

@section('title', $product->name . ' - Sewa Kamera')

@section('styles')
<style>
    .product-detail-card {
        background: var(--white);
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    .product-main-image {
        width: 100%;
        height: 500px;
        object-fit: cover;
        border-radius: 15px;
        background: var(--light-gray);
    }

    .product-detail-title {
        font-size: 2rem;
        font-weight: 700;
        color: var(--dark-gray);
        margin-bottom: 1rem;
    }

    .product-category-badge {
        background: var(--light-gray);
        color: var(--medium-gray);
        padding: 0.5rem 1rem;
        border-radius: 25px;
        display: inline-block;
        margin-bottom: 1rem;
    }

    .price-section {
        background: var(--light-gray);
        border-radius: 15px;
        padding: 1.5rem;
        margin: 1.5rem 0;
    }

    .price-item {
        display: flex;
        justify-content: space-between;
        padding: 0.8rem 0;
        border-bottom: 1px solid rgba(0,0,0,0.1);
    }

    .price-item:last-child {
        border-bottom: none;
    }

    .price-item .duration {
        color: var(--medium-gray);
        font-weight: 500;
    }

    .price-item .price {
        color: var(--primary-red);
        font-weight: 700;
        font-size: 1.2rem;
    }

    .specifications-table {
        width: 100%;
        margin: 1.5rem 0;
    }

    .specifications-table tr {
        border-bottom: 1px solid var(--light-gray);
    }

    .specifications-table td {
        padding: 1rem;
    }

    .specifications-table td:first-child {
        font-weight: 600;
        color: var(--dark-gray);
        width: 40%;
    }

    .specifications-table td:last-child {
        color: var(--medium-gray);
    }

    .rental-form {
        background: var(--white);
        border: 2px solid var(--light-gray);
        border-radius: 15px;
        padding: 2rem;
        position: sticky;
        top: 100px;
    }

    .rental-form h5 {
        color: var(--dark-gray);
        font-weight: 700;
        margin-bottom: 1.5rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        font-weight: 600;
        color: var(--dark-gray);
        margin-bottom: 0.5rem;
    }

    /* Kalender Ketersediaan */
    .availability-calendar {
        background: var(--white);
        border: 1px solid var(--light-gray);
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 1rem;
    }

    .calendar-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid var(--light-gray);
    }

    .calendar-header h6 {
        margin: 0;
        font-weight: 700;
        color: var(--dark-gray);
    }

    .calendar-nav {
        display: flex;
        gap: 0.5rem;
    }

    .calendar-nav button {
        background: var(--light-gray);
        border: none;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        cursor: pointer;
        transition: all 0.3s;
    }

    .calendar-nav button:hover {
        background: var(--primary-red);
        color: white;
    }

    .calendar-days {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 5px;
        margin-bottom: 0.5rem;
    }

    .calendar-day-name {
        text-align: center;
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--medium-gray);
        padding: 0.5rem 0;
    }

    .calendar-dates {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 5px;
    }

    .calendar-date {
        aspect-ratio: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.3s;
        border: 2px solid transparent;
        position: relative;
    }

    .calendar-date:hover:not(.disabled):not(.booked) {
        transform: scale(1.1);
    }

    .calendar-date.disabled {
        color: #ccc;
        cursor: not-allowed;
        background: #f9f9f9;
    }

    .calendar-date.available {
        background: #D1FAE5;
        color: #065F46;
        border-color: #059669;
    }

    .calendar-date.available:hover {
        background: #A7F3D0;
    }

    .calendar-date.limited {
        background: #FEF3C7;
        color: #92400E;
        border-color: #F59E0B;
    }

    .calendar-date.limited:hover {
        background: #FDE68A;
    }

    .calendar-date.booked {
        background: #FEE2E2;
        color: #991B1B;
        border-color: #DC2626;
        cursor: not-allowed;
    }

    .calendar-date.selected {
        border-color: var(--primary-red);
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.2);
    }

    .calendar-date .date-number {
        font-weight: 700;
        font-size: 1rem;
    }

    .calendar-date .stock-info {
        font-size: 0.65rem;
        margin-top: 2px;
    }

    .legend-item {
        display: inline-flex;
        align-items: center;
        margin-right: 1rem;
        font-size: 0.85rem;
    }

    .legend-item .dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        margin-right: 5px;
        display: inline-block;
    }

    .legend-item .dot.available {
        background: #10B981;
    }

    .legend-item .dot.limited {
        background: #F59E0B;
    }

    .legend-item .dot.booked {
        background: #DC2626;
    }

    .related-products {
        margin-top: 4rem;
    }

    .related-products h4 {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--dark-gray);
        margin-bottom: 2rem;
    }

    .product-image {
        width: 100%;
        object-fit: cover;
        background: var(--light-gray);
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
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }

    .product-name {
        font-weight: 600;
        color: var(--dark-gray);
        margin-bottom: 0.5rem;
    }

    .product-price {
        color: var(--primary-red);
        font-weight: 700;
    }

    .product-price small {
        font-size: 0.8rem;
        color: var(--medium-gray);
        font-weight: 400;
    }
</style>
@endsection

@section('content')
<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Produk</a></li>
        <li class="breadcrumb-item"><a href="{{ route('products.category', $product->category->slug) }}">{{ $product->category->name }}</a></li>
        <li class="breadcrumb-item active">{{ $product->name }}</li>
    </ol>
</nav>

<div class="row">
    <!-- Product Images & Info -->
    <div class="col-lg-7">
        <div class="product-detail-card">
            @if($product->image)
                <img src="{{ asset('uploads/products/' . $product->image) }}" class="product-main-image" alt="{{ $product->name }}">
            @else
                <div class="product-main-image d-flex align-items-center justify-content-center">
                    <i class="fas fa-camera" style="font-size: 8rem; color: var(--medium-gray); opacity: 0.3;"></i>
                </div>
            @endif
        </div>

        <div class="product-detail-card mt-4">
            <span class="product-category-badge">
                <i class="fas fa-tag"></i> {{ $product->category->name }}
            </span>
            <h1 class="product-detail-title">{{ $product->name }}</h1>

            <div class="mb-3">
                @if($product->stock > 2)
                    <span class="badge bg-success">
                        <i class="fas fa-check-circle"></i> Tersedia ({{ $product->stock }} unit)
                    </span>
                @elseif($product->stock > 0)
                    <span class="badge bg-warning text-dark">
                        <i class="fas fa-exclamation-circle"></i> Stok Terbatas ({{ $product->stock }} unit)
                    </span>
                @else
                    <span class="badge bg-danger">
                        <i class="fas fa-times-circle"></i> Stok Habis
                    </span>
                @endif
            </div>

            <h5 class="mt-4 mb-3">Deskripsi Produk</h5>
            <p style="white-space: pre-line; color: var(--medium-gray); line-height: 1.8;">{{ $product->description }}</p>

            <h5 class="mt-4 mb-3">Harga Sewa</h5>
            <div class="price-section">
                <div class="price-item">
                    <span class="duration"><i class="fas fa-clock"></i> 6 Jam</span>
                    <span class="price">Rp {{ number_format($product->price_6_hours, 0, ',', '.') }}</span>
                </div>
                <div class="price-item">
                    <span class="duration"><i class="fas fa-clock"></i> 12 Jam</span>
                    <span class="price">Rp {{ number_format($product->price_12_hours, 0, ',', '.') }}</span>
                </div>
                <div class="price-item">
                    <span class="duration"><i class="fas fa-clock"></i> 24 Jam</span>
                    <span class="price">Rp {{ number_format($product->price_24_hours, 0, ',', '.') }}</span>
                </div>
                <div class="price-item">
                    <span class="duration"><i class="fas fa-clock"></i> 1.5 Hari (36 Jam)</span>
                    <span class="price">Rp {{ number_format($product->price_1_5_days, 0, ',', '.') }}</span>
                </div>
            </div>

            @if($product->specifications)
                <h5 class="mt-4 mb-3">Spesifikasi</h5>
                <table class="specifications-table">
                    @foreach($product->specifications as $key => $value)
                    <tr>
                        <td>{{ $key }}</td>
                        <td>{{ $value }}</td>
                    </tr>
                    @endforeach
                </table>
            @endif
        </div>
    </div>

    <!-- Rental Form -->
    <div class="col-lg-5">
        <div class="rental-form">
            <h5><i class="fas fa-shopping-cart"></i> Pesan Sekarang</h5>

            <form action="{{ route('cart.add') }}" method="POST" id="rentalForm">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">

                <!-- Kalender Ketersediaan -->
                <div class="form-group">
                    <label class="form-label"><i class="fas fa-calendar-alt"></i> Kalender Ketersediaan</label>
                    <div id="availability-calendar" class="availability-calendar"></div>
                    <small class="text-muted">
                        <span class="legend-item"><span class="dot available"></span> Tersedia</span>
                        <span class="legend-item"><span class="dot limited"></span> Terbatas</span>
                        <span class="legend-item"><span class="dot booked"></span> Penuh</span>
                    </small>
                </div>

                <div class="form-group">
                    <label class="form-label">Durasi Sewa <span class="text-danger">*</span></label>
                    <select name="duration" class="form-select" required id="durationSelect">
                        <option value="">Pilih Durasi</option>
                        <option value="6_hours" data-price="{{ $product->price_6_hours }}">6 Jam - Rp {{ number_format($product->price_6_hours, 0, ',', '.') }}</option>
                        <option value="12_hours" data-price="{{ $product->price_12_hours }}">12 Jam - Rp {{ number_format($product->price_12_hours, 0, ',', '.') }}</option>
                        <option value="24_hours" data-price="{{ $product->price_24_hours }}">24 Jam - Rp {{ number_format($product->price_24_hours, 0, ',', '.') }}</option>
                        <option value="1_5_days" data-price="{{ $product->price_1_5_days }}">1.5 Hari - Rp {{ number_format($product->price_1_5_days, 0, ',', '.') }}</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Tanggal Mulai Sewa <span class="text-danger">*</span></label>
                    <input type="date" name="rental_date" class="form-control" required min="{{ date('Y-m-d') }}" id="rentalDate">
                </div>

                <div class="form-group">
                    <label class="form-label">Jam Mulai <span class="text-danger">*</span></label>
                    <input type="time" name="rental_time" class="form-control" required id="rentalTime">
                </div>

                <div class="form-group">
                    <label class="form-label">Jumlah <span class="text-danger">*</span></label>
                    <input type="number" name="quantity" class="form-control" value="1" min="1" max="{{ $product->stock }}" required id="quantityInput">
                    <small class="text-muted">Maksimal: <span id="maxStock">{{ $product->stock }}</span> unit</small>
                </div>

                <!-- Availability Status -->
                <div id="availabilityStatus" class="alert" style="display: none;"></div>

                @if($product->stock > 0)
                    <button type="submit" class="btn btn-primary-custom w-100 btn-lg" id="addToCartBtn">
                        <i class="fas fa-cart-plus"></i> Tambah ke Keranjang
                    </button>
                @else
                    <button type="button" class="btn btn-secondary w-100 btn-lg" disabled>
                        <i class="fas fa-times-circle"></i> Stok Habis
                    </button>
                @endif
            </form>
        </div>
    </div>
</div>

<!-- Related Products -->
@if($relatedProducts->count() > 0)
<div class="related-products">
    <h4><span style="color: var(--primary-red);">Produk</span> Terkait</h4>
    <div class="row g-4">
        @foreach($relatedProducts as $related)
        <div class="col-md-6 col-lg-3">
            <div class="card product-card">
                @if($related->image)
                    <img src="{{ asset('uploads/products/' . $related->image) }}" class="product-image" alt="{{ $related->name }}" style="height: 200px;">
                @else
                    <div class="product-image d-flex align-items-center justify-content-center" style="height: 200px;">
                        <i class="fas fa-camera" style="font-size: 3rem; color: var(--medium-gray);"></i>
                    </div>
                @endif

                <div class="card-body">
                    <h5 class="product-name" style="font-size: 1rem; min-height: 40px;">{{ Str::limit($related->name, 35) }}</h5>
                    <p class="product-price" style="font-size: 1.1rem;">
                        Rp {{ number_format($related->price_6_hours, 0, ',', '.') }}
                        <small>/6 jam</small>
                    </p>
                    <a href="{{ route('products.show', $related->slug) }}" class="btn btn-primary-custom w-100">
                        <i class="fas fa-info-circle"></i> Lihat
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif
@endsection

@section('scripts')
<script>
// Data bookings dari backend
const bookingsData = @json($bookings);
const productStock = {{ $product->stock }};
const productId = {{ $product->id }};

// Current month untuk kalender
let currentMonth = new Date();

// Render kalender
function renderCalendar() {
    const year = currentMonth.getFullYear();
    const month = currentMonth.getMonth();

    const firstDay = new Date(year, month, 1);
    const lastDay = new Date(year, month + 1, 0);

    const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

    let html = `
        <div class="calendar-header">
            <h6>${monthNames[month]} ${year}</h6>
            <div class="calendar-nav">
                <button type="button" onclick="previousMonth()"><i class="fas fa-chevron-left"></i></button>
                <button type="button" onclick="nextMonth()"><i class="fas fa-chevron-right"></i></button>
            </div>
        </div>
        <div class="calendar-days">
            <div class="calendar-day-name">Min</div>
            <div class="calendar-day-name">Sen</div>
            <div class="calendar-day-name">Sel</div>
            <div class="calendar-day-name">Rab</div>
            <div class="calendar-day-name">Kam</div>
            <div class="calendar-day-name">Jum</div>
            <div class="calendar-day-name">Sab</div>
        </div>
        <div class="calendar-dates">
    `;

    const startDay = firstDay.getDay();
    for (let i = 0; i < startDay; i++) {
        html += '<div class="calendar-date disabled"></div>';
    }

    const today = new Date();
    today.setHours(0, 0, 0, 0);

    for (let day = 1; day <= lastDay.getDate(); day++) {
        const date = new Date(year, month, day);
        // PERBAIKAN: Format tanggal dengan leading zero
        const dateKey = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        const isPast = date < today;

        let className = 'calendar-date';
        let stockInfo = '';
        let available = productStock;

        if (isPast) {
            className += ' disabled';
        } else {
            if (bookingsData[dateKey]) {
                const booked = bookingsData[dateKey].booked_quantity;
                available = productStock - booked;

                if (available <= 0) {
                    className += ' booked';
                    stockInfo = 'Penuh';
                } else if (available <= 2) {
                    className += ' limited';
                    stockInfo = `${available} unit`;
                } else {
                    className += ' available';
                    stockInfo = `${available} unit`;
                }
            } else {
                className += ' available';
                stockInfo = `${productStock} unit`;
            }
        }

        html += `
            <div class="${className}" data-date="${dateKey}" data-available="${available}" onclick="selectDate('${dateKey}', ${available})">
                <span class="date-number">${day}</span>
                ${stockInfo ? `<span class="stock-info">${stockInfo}</span>` : ''}
            </div>
        `;
    }

    html += '</div>';

    document.getElementById('availability-calendar').innerHTML = html;
}

// PERBAIKAN: Select date dari kalender
function selectDate(dateKey, available) {
    document.querySelectorAll('.calendar-date').forEach(el => {
        el.classList.remove('selected');
    });

    const dateEl = document.querySelector(`[data-date="${dateKey}"]`);
    if (dateEl && !dateEl.classList.contains('disabled') && !dateEl.classList.contains('booked')) {
        dateEl.classList.add('selected');

        // Auto fill form - dateKey sudah format YYYY-MM-DD yang benar
        document.getElementById('rentalDate').value = dateKey;
        document.getElementById('maxStock').textContent = available;
        document.getElementById('quantityInput').max = available;

        checkAvailability();
    }
}

function previousMonth() {
    currentMonth.setMonth(currentMonth.getMonth() - 1);
    renderCalendar();
}

function nextMonth() {
    currentMonth.setMonth(currentMonth.getMonth() + 1);
    renderCalendar();
}

function checkAvailability() {
    const date = document.getElementById('rentalDate').value;
    const quantity = document.getElementById('quantityInput').value;

    if (!date || !quantity) return;

    fetch('/products/check-availability', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            product_id: productId,
            date: date,
            quantity: parseInt(quantity)
        })
    })
    .then(response => response.json())
    .then(data => {
        const statusDiv = document.getElementById('availabilityStatus');
        const addBtn = document.getElementById('addToCartBtn');

        statusDiv.style.display = 'block';

        if (data.available) {
            statusDiv.className = 'alert alert-success';
            statusDiv.innerHTML = `<i class="fas fa-check-circle"></i> ${data.message}`;
            if (addBtn) addBtn.disabled = false;
        } else {
            statusDiv.className = 'alert alert-warning';
            statusDiv.innerHTML = `<i class="fas fa-exclamation-triangle"></i> ${data.message}`;
            if (addBtn) addBtn.disabled = true;
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

document.addEventListener('DOMContentLoaded', function() {
    renderCalendar();

    const rentalDate = document.getElementById('rentalDate');
    const quantityInput = document.getElementById('quantityInput');

    if (rentalDate) rentalDate.addEventListener('change', checkAvailability);
    if (quantityInput) quantityInput.addEventListener('input', checkAvailability);
});
</script>
@endsection

@extends('layouts.app')

@section('title', 'Member Dashboard - Galeri Kamera')

@section('styles')
<style>
    .member-card {
        background: linear-gradient(135deg, {{ $memberInfo['bg_color'] }} 0%, {{ $memberInfo['bg_color'] }}dd 100%);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        position: relative;
        overflow: hidden;
    }

    .member-card::before {
        content: '';
        position: absolute;
        top: -50px;
        right: -50px;
        width: 200px;
        height: 200px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }

    .member-badge {
        display: inline-flex;
        align-items: center;
        gap: 1rem;
        background: white;
        padding: 1rem 2rem;
        border-radius: 50px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        margin-bottom: 1.5rem;
    }

    .member-badge i {
        font-size: 2rem;
        color: {{ $memberInfo['color'] }};
    }

    .member-badge .level-name {
        font-size: 1.5rem;
        font-weight: 700;
        color: {{ $memberInfo['text_color'] }};
    }

    .member-code-badge {
        background: rgba(255,255,255,0.9);
        padding: 0.8rem 1.5rem;
        border-radius: 30px;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        margin-top: 1rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        cursor: pointer;
        transition: all 0.3s;
    }

    .member-code-badge:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .member-code-badge i {
        font-size: 1.2rem;
        color: {{ $memberInfo['color'] }};
    }

    .member-code-text {
        font-family: 'Courier New', monospace;
        font-size: 1.1rem;
        font-weight: 700;
        color: {{ $memberInfo['text_color'] }};
        letter-spacing: 1px;
    }

    .progress-container {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        margin-top: 1.5rem;
    }

    .progress {
        height: 30px;
        border-radius: 15px;
        background: #E5E7EB;
    }

    .progress-bar {
        background: linear-gradient(90deg, {{ $memberInfo['color'] }} 0%, {{ $memberInfo['color'] }}cc 100%);
        border-radius: 15px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .stats-card {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        text-align: center;
        height: 100%;
    }

    .stats-card h3 {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--primary-red);
        margin-bottom: 0.5rem;
    }

    .stats-card p {
        color: var(--medium-gray);
        margin: 0;
    }

    .benefits-card {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    .benefit-item {
        display: flex;
        align-items: center;
        padding: 1rem 0;
        border-bottom: 1px solid var(--light-gray);
    }

    .benefit-item:last-child {
        border-bottom: none;
    }

    .benefit-item i {
        color: var(--primary-red);
        margin-right: 1rem;
        font-size: 1.2rem;
    }

    .next-level-card {
        background: linear-gradient(135deg, #1F2937 0%, #111827 100%);
        color: white;
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 5px 20px rgba(0,0,0,0.2);
    }

    .copy-tooltip {
        position: absolute;
        background: rgba(0,0,0,0.8);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 5px;
        font-size: 0.85rem;
        display: none;
        z-index: 1000;
    }
</style>
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2 class="mb-3">👋 Halo, <span style="color: var(--primary-red);">{{ Auth::user()->name }}</span></h2>
        <p class="text-muted">Selamat datang di Member Dashboard Anda</p>
    </div>
</div>

<!-- Member Level Card -->
<div class="member-card">
    <div class="member-badge">
        <i class="{{ $memberInfo['icon'] }}"></i>
        <div>
            <div class="level-name">Member {{ $memberInfo['name'] }}</div>
            <small style="color: {{ $memberInfo['text_color'] }};">
                {{ Auth::user()->total_completed_orders }} transaksi selesai
            </small>
        </div>
    </div>

    <!-- Member Code Badge (BARU) 🆕 -->
    <div class="member-code-badge" onclick="copyMemberCode()" title="Klik untuk copy">
        <i class="fas fa-id-card"></i>
        <span class="member-code-text" id="memberCodeText">{{ Auth::user()->member_code }}</span>
        <i class="fas fa-copy" style="font-size: 0.9rem; opacity: 0.7;"></i>
    </div>
    <div class="copy-tooltip" id="copyTooltip">Copied!</div>

    <p style="color: {{ $memberInfo['text_color'] }}; font-size: 1.1rem; margin-bottom: 0; margin-top: 1rem;">
        <i class="fas fa-star"></i> Terima kasih atas kepercayaan Anda kepada GaleriKamera!
    </p>

    @if($progress['next_level'])
    <div class="progress-container">
        <div class="d-flex justify-content-between mb-2">
            <span><strong>Progress ke {{ $progress['next_level'] }}</strong></span>
            <span><strong>{{ $progress['current'] }} / {{ $progress['target'] }}</strong></span>
        </div>
        <div class="progress">
            <div class="progress-bar" role="progressbar" style="width: {{ $progress['percentage'] }}%">
                {{ number_format($progress['percentage'], 0) }}%
            </div>
        </div>
        <p class="text-muted mt-2 mb-0">
            <i class="fas fa-info-circle"></i>
            Selesaikan <strong>{{ $progress['remaining'] }} transaksi lagi</strong> untuk naik ke level {{ $progress['next_level'] }}!
        </p>
    </div>
    @else
    <div class="progress-container">
        <div class="text-center">
            <i class="fas fa-trophy" style="font-size: 3rem; color: {{ $memberInfo['color'] }}; margin-bottom: 1rem;"></i>
            <h5 style="color: {{ $memberInfo['text_color'] }};">🎉 Anda sudah di level tertinggi!</h5>
            <p class="text-muted">Terima kasih telah menjadi pelanggan setia kami</p>
        </div>
    </div>
    @endif
</div>

<!-- Statistics -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stats-card">
            <h3>{{ Auth::user()->total_completed_orders }}</h3>
            <p>Transaksi Selesai</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card">
            <h3>{{ Auth::user()->orders()->where('status', 'pending')->count() }}</h3>
            <p>Pesanan Pending</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card">
            <h3>{{ Auth::user()->orders()->where('status', 'processing')->count() }}</h3>
            <p>Sedang Diproses</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card">
            <h3>Rp {{ number_format(Auth::user()->orders()->where('status', 'completed')->sum('total_amount'), 0, ',', '.') }}</h3>
            <p>Total Pengeluaran</p>
        </div>
    </div>
</div>

<div class="row">
    <!-- Benefits -->
    <div class="col-lg-8">
        <div class="benefits-card">
            <h5 class="mb-4">
                <i class="fas fa-gift"></i> Benefit Member {{ $memberInfo['name'] }}
            </h5>
            @foreach($memberInfo['benefits'] as $benefit)
            <div class="benefit-item">
                <i class="fas fa-check-circle"></i>
                <span>{{ $benefit }}</span>
            </div>
            @endforeach
        </div>

        <div class="card-custom mt-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-history"></i> Riwayat Transaksi Terbaru</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Order Number</th>
                                <th>Tanggal</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(Auth::user()->orders()->latest()->take(5)->get() as $order)
                            <tr>
                                <td><strong>{{ $order->order_number }}</strong></td>
                                <td>{{ $order->created_at->format('d M Y') }}</td>
                                <td class="text-danger"><strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong></td>
                                <td>
                                    @switch($order->status)
                                        @case('pending') <span class="badge bg-warning">Pending</span> @break
                                        @case('confirmed') <span class="badge bg-info">Confirmed</span> @break
                                        @case('processing') <span class="badge bg-primary">Processing</span> @break
                                        @case('completed') <span class="badge bg-success">Completed</span> @break
                                        @case('cancelled') <span class="badge bg-danger">Cancelled</span> @break
                                    @endswitch
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">Belum ada transaksi</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <a href="{{ route('orders.index') }}" class="btn btn-primary-custom w-100 mt-3">
                    <i class="fas fa-eye"></i> Lihat Semua Pesanan
                </a>
            </div>
        </div>
    </div>

    <!-- Next Level Info -->
    <div class="col-lg-4">
        @if($progress['next_level'])
        <div class="next-level-card">
            <h5 style="color: var(--primary-red); margin-bottom: 1.5rem;">
                <i class="fas fa-arrow-up"></i> Naik ke {{ $progress['next_level'] }}?
            </h5>
            <p style="opacity: 0.9;">Benefit tambahan yang akan Anda dapatkan:</p>
            <ul style="opacity: 0.8;">
                @if($progress['next_level'] == 'Silver')
                    <li>Diskon 5% setiap transaksi</li>
                    <li>Prioritas booking</li>
                    <li>Customer support prioritas</li>
                @else
                    <li>Diskon 10% setiap transaksi</li>
                    <li>Early access produk baru</li>
                    <li>Free upgrade (jika tersedia)</li>
                    <li>VIP support 24/7</li>
                @endif
            </ul>
            <p class="mt-3 mb-0" style="font-size: 0.9rem; opacity: 0.7;">
                <i class="fas fa-lightbulb"></i>
                Lakukan {{ $progress['remaining'] }} transaksi lagi untuk naik level!
            </p>
        </div>
        @endif

        <div class="card-custom mt-3">
            <div class="card-body text-center">
                <i class="fas fa-camera" style="font-size: 3rem; color: var(--primary-red); margin-bottom: 1rem;"></i>
                <h5>Mulai Sewa Sekarang</h5>
                <p class="text-muted">Lihat katalog produk terbaru kami</p>
                <a href="{{ route('products.index') }}" class="btn btn-primary-custom w-100">
                    <i class="fas fa-shopping-cart"></i> Browse Produk
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Copy Member Code to Clipboard
function copyMemberCode() {
    const codeText = document.getElementById('memberCodeText').textContent;
    const tooltip = document.getElementById('copyTooltip');

    // Copy to clipboard
    navigator.clipboard.writeText(codeText).then(function() {
        // Show tooltip
        const badge = document.querySelector('.member-code-badge');
        const rect = badge.getBoundingClientRect();

        tooltip.style.display = 'block';
        tooltip.style.position = 'fixed';
        tooltip.style.top = (rect.bottom + 10) + 'px';
        tooltip.style.left = (rect.left + (rect.width / 2) - 40) + 'px';

        // Hide tooltip after 2 seconds
        setTimeout(function() {
            tooltip.style.display = 'none';
        }, 2000);
    }).catch(function(err) {
        alert('Gagal menyalin kode: ' + err);
    });
}
</script>
@endsection

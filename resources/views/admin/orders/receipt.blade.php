<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pesanan - {{ $order->order_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Courier New', monospace;
            background-color: #f5f5f5;
            padding: 20px;
        }

        .receipt-container {
            max-width: 400px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .receipt-header {
            text-align: center;
            border-bottom: 2px dashed #333;
            padding-bottom: 15px;
            margin-bottom: 15px;
        }

        .receipt-header h1 {
            font-size: 20px;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .receipt-header p {
            font-size: 12px;
            line-height: 1.5;
        }

        .receipt-info {
            margin-bottom: 15px;
            font-size: 12px;
        }

        .receipt-info table {
            width: 100%;
        }

        .receipt-info td {
            padding: 3px 0;
        }

        .receipt-info td:first-child {
            width: 120px;
        }

        .items-header {
            border-top: 2px dashed #333;
            border-bottom: 2px dashed #333;
            padding: 10px 0;
            margin: 15px 0;
            font-weight: bold;
            font-size: 12px;
        }

        .item-row {
            margin-bottom: 10px;
            font-size: 12px;
            padding-bottom: 10px;
            border-bottom: 1px dotted #ccc;
        }

        .item-name {
            font-weight: bold;
            margin-bottom: 3px;
        }

        .item-details {
            color: #666;
            font-size: 11px;
            margin-left: 10px;
        }

        .item-price {
            display: flex;
            justify-content: space-between;
            margin-top: 3px;
        }

        .receipt-totals {
            border-top: 2px dashed #333;
            padding-top: 10px;
            margin-top: 15px;
            font-size: 13px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
        }

        .total-row.grand-total {
            font-size: 16px;
            font-weight: bold;
            border-top: 2px solid #333;
            padding-top: 10px;
            margin-top: 5px;
        }

        .receipt-footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 2px dashed #333;
            font-size: 11px;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: bold;
            margin-top: 10px;
        }

        .status-paid {
            background-color: #4CAF50;
            color: white;
        }

        .payment-info {
            background-color: #f9f9f9;
            padding: 10px;
            margin: 15px 0;
            border-radius: 5px;
            font-size: 12px;
        }

        .payment-info div {
            margin-bottom: 5px;
        }

        /* Print styles */
        @media print {
            body {
                background-color: white;
                padding: 0;
            }

            .receipt-container {
                box-shadow: none;
                max-width: 100%;
            }

            .no-print {
                display: none !important;
            }
        }

        /* Button styles */
        .button-container {
            text-align: center;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 2px dashed #333;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 0 5px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            transition: all 0.3s;
        }

        .btn-print {
            background-color: #4CAF50;
            color: white;
        }

        .btn-print:hover {
            background-color: #45a049;
        }

        .btn-back {
            background-color: #666;
            color: white;
        }

        .btn-back:hover {
            background-color: #555;
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <!-- Header -->
        <div class="receipt-header">
            <h1>GALERI KAMERA</h1>
            <p>
                Jl. Kalibrantas No.71 Kota Blitar <br>
                Telp: 081-553-781-711<br>
                Email: info@kamerakamera.com
            </p>
        </div>

        <!-- Order Info -->
        <div class="receipt-info">
            <table>
                <tr>
                    <td>No. Pesanan</td>
                    <td>: {{ $order->order_number }}</td>
                </tr>
                <tr>
                    <td>Tanggal</td>
                    <td>: {{ $order->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                <tr>
                    <td>Nama Pelanggan</td>
                    <td>: {{ $order->user->name }}</td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td>: {{ $order->user->email }}</td>
                </tr>
            </table>
        </div>

        <!-- Items Header -->
        <div class="items-header">
            DETAIL PESANAN
        </div>

        <!-- Order Items -->
        @foreach($order->orderItems as $item)
        <div class="item-row">
            <div class="item-name">{{ $item->product->name }}</div>
            <div class="item-details">
                <div>Jumlah: {{ $item->quantity }} unit</div>
                <div>Durasi: {{ $item->duration }} hari</div>
                <div>Tanggal Sewa: {{ \Carbon\Carbon::parse($item->rental_date)->format('d/m/Y') }}</div>
                <div>Periode: {{ \Carbon\Carbon::parse($item->rental_start)->format('d/m/Y H:i') }} - {{ \Carbon\Carbon::parse($item->rental_end)->format('d/m/Y H:i') }}</div>
            </div>
            <div class="item-price">
                <span>Rp {{ number_format($item->price, 0, ',', '.') }} x {{ $item->quantity }} x {{ $item->duration }} hari</span>
                <strong>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</strong>
            </div>
        </div>
        @endforeach

        <!-- Totals -->
        <div class="receipt-totals">
            <div class="total-row">
                <span>Subtotal</span>
                <span>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
            </div>

            @if($order->payment->payment_type == 'dp')
            <div class="total-row">
                <span>DP (30%)</span>
                <span>Rp {{ number_format($order->payment->dp_amount, 0, ',', '.') }}</span>
            </div>
            <div class="total-row">
                <span>Sisa Pembayaran</span>
                <span>Rp {{ number_format($order->payment->remaining_amount, 0, ',', '.') }}</span>
            </div>
            @endif

            <div class="total-row grand-total">
                <span>TOTAL DIBAYAR</span>
                <span>Rp {{ number_format($order->payment->amount, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Payment Info -->
        <div class="payment-info">
            <div><strong>Metode Pembayaran:</strong>
                @if($order->payment->payment_method == 'transfer')
                    Transfer Bank
                @elseif($order->payment->payment_method == 'e-wallet')
                    E-Wallet
                @else
                    Manual
                @endif
            </div>
            <div><strong>Tipe Pembayaran:</strong>
                @if($order->payment->payment_type == 'dp')
                    Down Payment (DP)
                @else
                    Lunas
                @endif
            </div>
            <div><strong>Status:</strong>
                <span class="status-badge status-paid">LUNAS</span>
            </div>
        </div>

        @if($order->notes)
        <div class="payment-info">
            <div><strong>Catatan:</strong></div>
            <div>{{ $order->notes }}</div>
        </div>
        @endif

        <!-- Footer -->
        <div class="receipt-footer">
            <p>Terima kasih atas kepercayaan Anda!</p>
            <p>Barang yang sudah disewa tidak dapat dikembalikan</p>
            <p style="margin-top: 10px;">*** Struk ini adalah bukti pembayaran yang sah ***</p>
        </div>

        <!-- Buttons (hidden when printing) -->
        <div class="button-container no-print">
            <button onclick="window.print()" class="btn btn-print">
                🖨️ Cetak Struk
            </button>
            <a href="{{ route('orders.show', $order->order_number) }}" class="btn btn-back">
                ← Kembali
            </a>
        </div>
    </div>

    <script>
        // Auto focus untuk print dialog jika parameter print=1 ada di URL
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('print') === '1') {
            window.onload = function() {
                window.print();
            };
        }
    </script>
</body>
</html>

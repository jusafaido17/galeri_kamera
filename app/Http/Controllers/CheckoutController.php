<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CheckoutController extends Controller
{
    // Halaman checkout
   public function index()
{
    // Cek apakah user sudah login
    if (!Auth::check()) {
        return redirect()->route('login')
            ->with('info', 'Silakan login atau daftar terlebih dahulu untuk melanjutkan checkout');
    }

    $cartItems = $this->getCartItems();

    if ($cartItems->isEmpty()) {
        return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong');
    }

    $total = $cartItems->sum('subtotal');

    return view('checkout.index', compact('cartItems', 'total'));
}

   // Proses checkout
public function process(Request $request)
{
    $request->validate([
        'payment_method' => 'required|in:transfer,e-wallet,manual',
        'payment_type' => 'required|in:full,dp',
        'notes' => 'nullable|string|max:500',
    ]);

    $cartItems = $this->getCartItems();

    if ($cartItems->isEmpty()) {
        return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong');
    }

    $totalAmount = $cartItems->sum('subtotal');

    // Hitung DP jika payment_type = dp
    $dpAmount = null;
    $remainingAmount = null;
    $paymentAmount = $totalAmount;

    if ($request->payment_type == 'dp') {
        $dpAmount = $totalAmount * 0.3; // 30% dari total
        $remainingAmount = $totalAmount - $dpAmount;
        $paymentAmount = $dpAmount; // Yang dibayar sekarang = DP
    }

    DB::beginTransaction();
    try {
        // Buat order
        $order = Order::create([
            'order_number' => Order::generateOrderNumber(),
            'user_id' => Auth::id(),
            'total_amount' => $totalAmount,
            'status' => 'pending',
            'notes' => $request->notes,
        ]);

        // Buat order items
        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'duration' => $item->duration,
                'rental_date' => $item->rental_date,
                'rental_start' => $item->rental_start,
                'rental_end' => $item->rental_end,
                'price' => $item->price,
                'subtotal' => $item->subtotal,
            ]);
        }

        // Buat payment record
        Payment::create([
            'order_id' => $order->id,
            'payment_method' => $request->payment_method,
            'payment_type' => $request->payment_type,
            'dp_amount' => $dpAmount,
            'remaining_amount' => $remainingAmount,
            'amount' => $paymentAmount, // Jumlah yang harus dibayar sekarang
            'status' => 'pending',
        ]);

        // Hapus cart
        Cart::where('user_id', Auth::id())->delete();

        DB::commit();

        return redirect()->route('orders.show', $order->order_number)
            ->with('success', 'Pesanan berhasil dibuat! Silakan lakukan pembayaran.');

    } catch (\Exception $e) {
        DB::rollback();
        return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}
    // Halaman riwayat pesanan user
    public function orders()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with(['orderItems.product', 'payment'])
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    // Detail pesanan
    public function show($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->with(['orderItems.product', 'payment'])
            ->firstOrFail();

        return view('orders.show', compact('order'));
    }

    // Upload bukti pembayaran
    public function uploadPayment(Request $request, $orderNumber)
    {
        $request->validate([
            'proof_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($order->payment->status == 'paid') {
            return redirect()->back()->with('error', 'Pembayaran sudah dikonfirmasi');
        }

        // Upload bukti pembayaran
        if ($request->hasFile('proof_image')) {
            $file = $request->file('proof_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/payments'), $filename);

            $order->payment->update([
                'proof_image' => $filename,
            ]);
        }

        return redirect()->back()->with('success', 'Bukti pembayaran berhasil diupload. Menunggu konfirmasi admin.');
    }

    // Cetak struk pesanan
public function printReceipt($orderNumber)
{
    $user = Auth::user();

    $query = Order::where('order_number', $orderNumber)
        ->with(['orderItems.product', 'payment', 'user']);

    // Jika bukan admin, hanya bisa lihat milik sendiri
    if ($user->role !== 'admin') {
        $query->where('user_id', $user->id);
    }

    $order = $query->firstOrFail();

    // Cek apakah pembayaran sudah dikonfirmasi
    if ($order->payment->status != 'paid') {
        // Admin redirect ke halaman admin, user redirect ke halaman user
        if ($user->role === 'admin') {
            return redirect()->route('admin.orders.show', $order->id)
                ->with('error', 'Struk hanya bisa dicetak setelah pembayaran dikonfirmasi');
        }
        return redirect()->route('orders.show', $orderNumber)
            ->with('error', 'Struk hanya bisa dicetak setelah pembayaran dikonfirmasi oleh admin');
    }

    return view('orders.receipt', compact('order'));
}
    // Download struk sebagai PDF
    public function downloadReceiptPDF($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->with(['orderItems.product', 'payment', 'user'])
            ->firstOrFail();

        // Cek apakah pembayaran sudah dikonfirmasi
        if ($order->payment->status != 'paid') {
            return redirect()->route('orders.show', $orderNumber)
                ->with('error', 'Struk hanya bisa dicetak setelah pembayaran dikonfirmasi oleh admin');
        }

        // Jika ingin menggunakan PDF library seperti DomPDF
        // Uncomment code di bawah dan install: composer require barryvdh/laravel-dompdf

        // $pdf = \PDF::loadView('orders.receipt-pdf', compact('order'));
        // return $pdf->download('struk-' . $order->order_number . '.pdf');

        // Untuk sementara redirect ke halaman print
        return redirect()->route('orders.receipt', $orderNumber);
    }

    // Helper: Ambil cart items
    private function getCartItems()
    {
        return Cart::where('user_id', Auth::id())->with('product')->get();
    }
}

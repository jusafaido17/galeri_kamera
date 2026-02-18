<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // Tampilkan daftar pesanan
    public function index(Request $request)
    {
        $query = Order::with(['user', 'payment', 'orderItems']);

        // Filter berdasarkan status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan payment status
        if ($request->has('payment_status') && $request->payment_status != '') {
            $query->whereHas('payment', function($q) use ($request) {
                $q->where('status', $request->payment_status);
            });
        }

        // Search berdasarkan order number atau nama user
        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('order_number', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', function($q2) use ($request) {
                      $q2->where('name', 'like', '%' . $request->search . '%');
                  });
            });
        }

        $orders = $query->latest()->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

    // Detail pesanan
    public function show(Order $order)
    {
        $order->load(['user', 'payment', 'orderItems.product']);
        return view('admin.orders.show', compact('order'));
    }

   // Update status pesanan
public function updateStatus(Request $request, Order $order)
{
    $request->validate([
        'status' => 'required|in:pending,confirmed,processing,completed,cancelled',
    ]);

    $oldStatus = $order->status;
    $newStatus = $request->status;

    $order->update([
        'status' => $newStatus
    ]);

    // Jika status berubah menjadi 'completed', update member level user
    if ($newStatus == 'completed' && $oldStatus != 'completed') {
        $order->user->updateMemberLevel();
    }

    return redirect()->back()
        ->with('success', 'Status pesanan berhasil diupdate');
}

    // Konfirmasi pembayaran
    public function confirmPayment(Request $request, Order $order)
    {
        $payment = $order->payment;

        if (!$payment) {
            return redirect()->back()
                ->with('error', 'Data pembayaran tidak ditemukan');
        }

        if ($payment->status == 'paid') {
            return redirect()->back()
                ->with('error', 'Pembayaran sudah dikonfirmasi sebelumnya');
        }

        $payment->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        // Update status order menjadi confirmed
        if ($order->status == 'pending') {
            $order->update(['status' => 'confirmed']);
        }

        return redirect()->back()
            ->with('success', 'Pembayaran berhasil dikonfirmasi');
    }
}

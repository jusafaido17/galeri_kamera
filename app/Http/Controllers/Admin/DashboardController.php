<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik umum
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalOrders = Order::count();
        $totalUsers = User::where('role', 'user')->count();

        // Pesanan pending (perlu diproses)
        $pendingOrders = Order::where('status', 'pending')->count();

        // Pembayaran yang perlu dikonfirmasi
        $pendingPayments = Order::whereHas('payment', function($q) {
            $q->where('status', 'pending')
              ->whereNotNull('proof_image');
        })->count();

        // Revenue bulan ini
        $monthlyRevenue = Order::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->whereIn('status', ['confirmed', 'processing', 'completed'])
            ->sum('total_amount');

        // Pesanan terbaru (5 terakhir)
        $recentOrders = Order::with(['user', 'payment'])
            ->latest()
            ->take(5)
            ->get();

        // Produk stok menipis (stok <= 2)
        $lowStockProducts = Product::where('stock', '<=', 2)
            ->where('is_available', true)
            ->get();

        // Chart: Orders per bulan (6 bulan terakhir)
        $ordersChart = Order::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as total')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalCategories',
            'totalOrders',
            'totalUsers',
            'pendingOrders',
            'pendingPayments',
            'monthlyRevenue',
            'recentOrders',
            'lowStockProducts',
            'ordersChart'
        ));
    }
}

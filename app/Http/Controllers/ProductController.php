<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Menampilkan semua produk dengan filter
    public function index(Request $request)
    {
        $query = Product::with('category')->where('is_available', true);

        // Filter berdasarkan kategori
        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }

        // Filter berdasarkan search
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Sort
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'name':
                    $query->orderBy('name', 'asc');
                    break;
                case 'price_low':
                    $query->orderBy('price_6_hours', 'asc');
                    break;
                case 'price_high':
                    $query->orderBy('price_6_hours', 'desc');
                    break;
                default:
                    $query->latest();
            }
        } else {
            $query->latest();
        }

        $products = $query->paginate(12);
        $categories = Category::withCount('products')->get();

        return view('products.index', compact('products', 'categories'));
    }

    // Menampilkan detail produk (USER) dengan kalender
    public function show($slug)
    {
        $product = Product::with('category')->where('slug', $slug)->firstOrFail();

        // Ambil produk terkait (kategori sama, exclude produk ini)
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_available', true)
            ->take(4)
            ->get();

        // Ambil data booking untuk kalender (30 hari ke depan)
        $bookings = $this->getProductBookings($product->id, 30);

        return view('products.show', compact('product', 'relatedProducts', 'bookings'));
    }

    // Menampilkan produk berdasarkan kategori
    public function category($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        $products = Product::with('category')
            ->where('category_id', $category->id)
            ->where('is_available', true)
            ->latest()
            ->paginate(12);

        $categories = Category::withCount('products')->get();

        return view('products.index', compact('products', 'categories', 'category'));
    }

    /**
     * Get product bookings for calendar
     */
    private function getProductBookings($productId, $days = 30)
    {
        $startDate = now()->startOfDay();
        $endDate = now()->addDays($days)->endOfDay();

        // Ambil semua order items untuk produk ini dalam range waktu
        $orderItems = \App\Models\OrderItem::where('product_id', $productId)
            ->whereHas('order', function($query) {
                // Hanya order yang confirmed, processing, atau completed
                $query->whereIn('status', ['confirmed', 'processing', 'completed']);
            })
            ->where(function($query) use ($startDate, $endDate) {
                $query->whereBetween('rental_start', [$startDate, $endDate])
                      ->orWhereBetween('rental_end', [$startDate, $endDate])
                      ->orWhere(function($q) use ($startDate, $endDate) {
                          $q->where('rental_start', '<=', $startDate)
                            ->where('rental_end', '>=', $endDate);
                      });
            })
            ->with('order')
            ->get();

        // Format untuk kalender
        $bookings = [];
        foreach ($orderItems as $item) {
            $start = \Carbon\Carbon::parse($item->rental_start);
            $end = \Carbon\Carbon::parse($item->rental_end);

            // Loop setiap hari dari rental_start sampai rental_end
            $current = $start->copy()->startOfDay();
            $endDay = $end->copy()->startOfDay();

            while ($current->lte($endDay)) {
                $dateKey = $current->format('Y-m-d');

                if (!isset($bookings[$dateKey])) {
                    $bookings[$dateKey] = [
                        'date' => $dateKey,
                        'booked_quantity' => 0,
                        'orders' => []
                    ];
                }

                $bookings[$dateKey]['booked_quantity'] += $item->quantity;
                $bookings[$dateKey]['orders'][] = [
                    'order_number' => $item->order->order_number,
                    'quantity' => $item->quantity,
                    'start' => $item->rental_start,
                    'end' => $item->rental_end,
                ];

                $current->addDay();
            }
        }

        return $bookings;
    }

    /**
     * Check availability for specific date (AJAX)
     */
    public function checkAvailability(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'date' => 'required|date',
                'quantity' => 'required|integer|min:1',
            ]);

            $product = Product::findOrFail($request->product_id);
            $date = \Carbon\Carbon::parse($request->date)->startOfDay();

            // Cek booking di tanggal tersebut
            $bookedQuantity = \App\Models\OrderItem::where('product_id', $product->id)
                ->whereHas('order', function($query) {
                    $query->whereIn('status', ['confirmed', 'processing', 'completed']);
                })
                ->where(function($query) use ($date) {
                    $dateEnd = $date->copy()->endOfDay();
                    $query->where(function($q) use ($date, $dateEnd) {
                        $q->where('rental_start', '<=', $dateEnd)
                          ->where('rental_end', '>=', $date);
                    });
                })
                ->sum('quantity');

            $availableQuantity = $product->stock - $bookedQuantity;
            $isAvailable = $availableQuantity >= $request->quantity;

            return response()->json([
                'available' => $isAvailable,
                'available_quantity' => max(0, $availableQuantity),
                'booked_quantity' => $bookedQuantity,
                'total_stock' => $product->stock,
                'message' => $isAvailable
                    ? "Tersedia {$availableQuantity} unit untuk tanggal ini"
                    : "Hanya tersedia {$availableQuantity} unit (Anda minta {$request->quantity} unit)"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'available' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}

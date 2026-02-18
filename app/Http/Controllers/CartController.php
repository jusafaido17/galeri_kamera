<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class CartController extends Controller
{
    // Tampilkan keranjang
    public function index()
    {
        $cartItems = $this->getCartItems();
        $total = $cartItems->sum('subtotal');

        return view('cart.index', compact('cartItems', 'total'));
    }

    // Tambah produk ke keranjang
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'duration' => 'required|in:6_hours,12_hours,24_hours,1_5_days',
            'rental_date' => 'required|date|after_or_equal:today',
            'rental_time' => 'required',
        ]);

        $product = Product::findOrFail($request->product_id);

        // Cek stok
        if ($product->stock < $request->quantity) {
            return redirect()->back()->with('error', 'Stok tidak mencukupi');
        }

        // Hitung waktu sewa
        $rentalStart = Carbon::parse($request->rental_date . ' ' . $request->rental_time);
        $rentalEnd = $this->calculateRentalEnd($rentalStart, $request->duration);

        // Cek ketersediaan di tanggal tersebut
        $isAvailable = $this->checkAvailabilityForDate(
            $product->id,
            $rentalStart,
            $rentalEnd,
            $request->quantity
        );

        if (!$isAvailable) {
            return redirect()->back()->with('error', 'Produk tidak tersedia pada tanggal dan waktu tersebut');
        }

        // Ambil harga berdasarkan durasi
        $price = $product->getPriceByDuration($request->duration);

        // Simpan ke cart
        Cart::create([
            'user_id' => Auth::id(),
            'session_id' => Auth::check() ? null : Session::getId(),
            'product_id' => $product->id,
            'quantity' => $request->quantity,
            'duration' => $request->duration,
            'rental_date' => $request->rental_date,
            'rental_start' => $rentalStart,
            'rental_end' => $rentalEnd,
            'price' => $price,
        ]);

        return redirect()->route('cart.index')->with('success', 'Produk berhasil ditambahkan ke keranjang');
    }

    // Update quantity di keranjang
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = Cart::findOrFail($id);
        $product = $cartItem->product;

        // Cek stok
        if ($product->stock < $request->quantity) {
            return response()->json(['success' => false, 'message' => 'Stok tidak mencukupi']);
        }

        // Cek ketersediaan
        $isAvailable = $this->checkAvailabilityForDate(
            $product->id,
            $cartItem->rental_start,
            $cartItem->rental_end,
            $request->quantity,
            $cartItem->id
        );

        if (!$isAvailable) {
            return response()->json(['success' => false, 'message' => 'Produk tidak tersedia untuk quantity tersebut']);
        }

        $cartItem->update(['quantity' => $request->quantity]);

        $cartItems = $this->getCartItems();
        $total = $cartItems->sum('subtotal');

        return response()->json([
            'success' => true,
            'subtotal' => number_format($cartItem->subtotal, 0, ',', '.'),
            'total' => number_format($total, 0, ',', '.')
        ]);
    }

    // Hapus item dari keranjang
    public function remove($id)
    {
        $cartItem = Cart::findOrFail($id);
        $cartItem->delete();

        return redirect()->back()->with('success', 'Item berhasil dihapus dari keranjang');
    }

    // Cek ketersediaan produk (AJAX)
    public function checkAvailability(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rental_date' => 'required|date',
            'rental_time' => 'required',
            'duration' => 'required|in:6_hours,12_hours,24_hours,1_5_days',
            'quantity' => 'required|integer|min:1',
        ]);

        $rentalStart = Carbon::parse($request->rental_date . ' ' . $request->rental_time);
        $rentalEnd = $this->calculateRentalEnd($rentalStart, $request->duration);

        $isAvailable = $this->checkAvailabilityForDate(
            $request->product_id,
            $rentalStart,
            $rentalEnd,
            $request->quantity
        );

        return response()->json([
            'available' => $isAvailable,
            'message' => $isAvailable ? 'Produk tersedia' : 'Produk tidak tersedia pada tanggal tersebut'
        ]);
    }

    // ========================================
    // VERIFY MEMBER CODE (NEW FEATURE) 🆕
    // ========================================

    /**
     * Verify Member Code
     */
    public function verifyMemberCode(Request $request)
    {
        $request->validate([
            'member_code' => 'required|string'
        ]);

        // Cari user berdasarkan member code
        $user = User::where('member_code', $request->member_code)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Kode member tidak valid!'
            ], 404);
        }

        // Get member level info
        $memberInfo = $user->getMemberLevelInfo();

        return response()->json([
            'success' => true,
            'message' => 'Kode member berhasil diverifikasi!',
            'data' => [
                'member_code' => $user->member_code,
                'name' => $user->name,
                'email' => $user->email,
                'level' => $user->member_level,
                'level_name' => $memberInfo['name'],
                'level_icon' => $memberInfo['icon'],
                'level_color' => $memberInfo['color'],
                'total_orders' => $user->total_completed_orders,
                'benefits' => $memberInfo['benefits'],
                'discount_percentage' => $user->getDiscountPercentage(),
            ]
        ]);
    }

    // ========================================
    // HELPER FUNCTIONS (PRIVATE)
    // ========================================

    /**
     * Ambil cart items
     */
    private function getCartItems()
    {
        if (Auth::check()) {
            return Cart::where('user_id', Auth::id())->with('product')->get();
        } else {
            return Cart::where('session_id', Session::getId())->with('product')->get();
        }
    }

    /**
     * Hitung waktu selesai sewa
     */
    private function calculateRentalEnd($start, $duration)
    {
        return match($duration) {
            '6_hours' => $start->copy()->addHours(6),
            '12_hours' => $start->copy()->addHours(12),
            '24_hours' => $start->copy()->addHours(24),
            '1_5_days' => $start->copy()->addHours(36),
            default => $start
        };
    }

    /**
     * Cek ketersediaan produk di tanggal tertentu
     */
    private function checkAvailabilityForDate($productId, $rentalStart, $rentalEnd, $quantity, $excludeCartId = null)
    {
        $product = Product::findOrFail($productId);

        // Hitung berapa produk yang sudah di-booking di cart
        $bookedInCart = Cart::where('product_id', $productId)
            ->when($excludeCartId, function($q) use ($excludeCartId) {
                $q->where('id', '!=', $excludeCartId);
            })
            ->where(function($q) use ($rentalStart, $rentalEnd) {
                $q->whereBetween('rental_start', [$rentalStart, $rentalEnd])
                  ->orWhereBetween('rental_end', [$rentalStart, $rentalEnd])
                  ->orWhere(function($q2) use ($rentalStart, $rentalEnd) {
                      $q2->where('rental_start', '<=', $rentalStart)
                         ->where('rental_end', '>=', $rentalEnd);
                  });
            })
            ->sum('quantity');

        // Hitung berapa produk yang sudah di-booking di orders
        $bookedInOrders = OrderItem::whereHas('order', function($q) {
                $q->whereIn('status', ['pending', 'confirmed', 'processing']);
            })
            ->where('product_id', $productId)
            ->where(function($q) use ($rentalStart, $rentalEnd) {
                $q->whereBetween('rental_start', [$rentalStart, $rentalEnd])
                  ->orWhereBetween('rental_end', [$rentalStart, $rentalEnd])
                  ->orWhere(function($q2) use ($rentalStart, $rentalEnd) {
                      $q2->where('rental_start', '<=', $rentalStart)
                         ->where('rental_end', '>=', $rentalEnd);
                  });
            })
            ->sum('quantity');

        $totalBooked = $bookedInCart + $bookedInOrders;
        $availableStock = $product->stock - $totalBooked;

        return $availableStock >= $quantity;
    }
}

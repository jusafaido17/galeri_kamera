<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController;

// Route untuk halaman depan (GUEST BISA AKSES)
Route::get('/', [HomeController::class, 'index'])->name('home');

// Route untuk produk (GUEST BISA AKSES)
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/category/{slug}', [ProductController::class, 'category'])->name('products.category');
Route::post('/products/check-availability', [ProductController::class, 'checkAvailability'])->name('products.check-availability');

// Route untuk keranjang (GUEST BISA AKSES - belum perlu login)
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/check-availability', [CartController::class, 'checkAvailability'])->name('cart.check-availability');
Route::post('/cart/verify-member', [CartController::class, 'verifyMemberCode'])->name('cart.verify-member'); // 🆕 VERIFY MEMBER CODE

// Route untuk checkout (WAJIB LOGIN)
Route::middleware(['auth'])->group(function () {
    // Member Dashboard
    Route::get('/member/dashboard', [MemberController::class, 'dashboard'])->name('member.dashboard');

    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');

    // Orders
    Route::get('/orders', [CheckoutController::class, 'orders'])->name('orders.index');
    Route::get('/orders/{orderNumber}', [CheckoutController::class, 'show'])->name('orders.show');
    Route::post('/orders/{orderNumber}/upload-payment', [CheckoutController::class, 'uploadPayment'])->name('orders.upload-payment');
});

// Route untuk Admin (WAJIB login sebagai admin)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Kelola Kategori
    Route::resource('categories', CategoryController::class);

    // Kelola Produk
    Route::resource('products', AdminProductController::class);

    // Kelola Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/update-status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::post('/orders/{order}/confirm-payment', [OrderController::class, 'confirmPayment'])->name('orders.confirm-payment');
});

// Route untuk checkout (WAJIB LOGIN)
Route::middleware(['auth'])->group(function () {
    Route::get('/member/dashboard', [MemberController::class, 'dashboard'])->name('member.dashboard');
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/orders', [CheckoutController::class, 'orders'])->name('orders.index');
    Route::get('/orders/{orderNumber}', [CheckoutController::class, 'show'])->name('orders.show');
    Route::post('/orders/{orderNumber}/upload-payment', [CheckoutController::class, 'uploadPayment'])->name('orders.upload-payment');

    // ✅ Struk - bisa diakses admin & user
    Route::get('/orders/{orderNumber}/receipt', [CheckoutController::class, 'printReceipt'])->name('orders.receipt');
});
Auth::routes();


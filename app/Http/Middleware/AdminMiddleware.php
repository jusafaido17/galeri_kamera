<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        /**
         * 2. Cek apakah user adalah admin.
         * Menggunakan pengecekan kolom 'role' secara langsung lebih aman 
         * daripada memanggil fungsi isAdmin() jika belum didefinisikan di Model.
         */
        if (Auth::user()->role !== 'admin') { 
            return redirect()->route('home')->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        /**
         * PENTING: Jangan tambahkan redirect->secure() di sini.
         * Redirect ke HTTPS sudah ditangani oleh TrustProxies dan AppServiceProvider.
         * Menambahkannya di sini akan menyebabkan ERR_TOO_MANY_REDIRECTS.
         */

        return $next($request);
    }
}
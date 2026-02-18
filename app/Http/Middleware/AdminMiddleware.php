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

        /** * 2. Cek apakah user adalah admin.
         * Gunakan pengecekan kolom 'role' secara langsung jika fungsi isAdmin() belum ada.
         */
        if (Auth::user()->role !== 'admin') { 
            return redirect()->route('home')->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        /**
         * 3. Keamanan Tambahan: Memastikan request sensitif di admin 
         * tetap menggunakan HTTPS di lingkungan produksi.
         */
        if (app()->environment('production') && !$request->secure()) {
            return redirect()->secure($request->getRequestUri());
        }

        return $next($request);
    }
}
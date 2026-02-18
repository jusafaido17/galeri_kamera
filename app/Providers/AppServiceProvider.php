<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL; // 👈 TAMBAHKAN INI

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // 👇 FORCE HTTPS DI PRODUCTION
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        // Share cart count to all views
        view()->composer('*', function ($view) {
            $cartCount = 0;

            if (Auth::check()) {
                $cartCount = \App\Models\Cart::where('user_id', Auth::id())->count();
            } else {
                $cartCount = \App\Models\Cart::where('session_id', Session::getId())->count();
            }

            $view->with('cartCount', $cartCount);
        });
    }
}

<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
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

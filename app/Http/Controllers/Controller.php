<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Get cart count for current user/session
     *
     * @return int
     */
    protected function getCartCount()
    {
        if (Auth::check()) {
            return \App\Models\Cart::where('user_id', Auth::id())->count();
        } else {
            return \App\Models\Cart::where('session_id', Session::getId())->count();
        }
    }
}

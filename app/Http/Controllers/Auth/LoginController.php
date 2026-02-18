<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        // Sinkronisasi cart dari session guest ke user yang login
        $sessionId = Session::getId();
        \App\Models\Cart::where('session_id', $sessionId)
            ->whereNull('user_id')
            ->update(['user_id' => $user->id, 'session_id' => null]);

        // Jika user adalah admin, redirect ke dashboard admin
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard')
                ->with('success', 'Selamat datang, ' . $user->name);
        }

        // Jika user biasa, redirect ke home
        return redirect()->route('home')
            ->with('success', 'Login berhasil! Selamat datang, ' . $user->name);
    }
}

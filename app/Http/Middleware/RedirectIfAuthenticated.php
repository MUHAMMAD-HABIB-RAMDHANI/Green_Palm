<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::user();

                // 1. Cek Admin
                if ($user->email === 'admin@gmail.com' && $user->username === 'admin') {
                    return redirect()->route('admin.beranda');
                }

                // 2. Cek Toke
                if ($user->email === 'tokesawit@gmail.com') {
                    return redirect()->route('toke.beranda');
                }

                // 3. User Biasa (Default)
                // Pastikan ini mengarah ke 'dashboard.beranda' bukan '/dashboard'
                return redirect()->route('dashboard.beranda');
            }
        }

        return $next($request);
    }
}
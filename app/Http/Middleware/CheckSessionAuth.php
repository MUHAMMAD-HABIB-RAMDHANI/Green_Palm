<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckSessionAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // ✅ Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();
        $currentRoute = $request->route()->getName();

        // ✅ Identifikasi role user
        $isAdmin = ($user->email === 'admin@gmail.com' && $user->username === 'admin');
        $isToke = ($user->email === 'tokesawit@gmail.com');

        // ============================================================
        // ✅ JIKA USER ADALAH ADMIN
        // ============================================================
        if ($isAdmin) {
            // Admin mencoba akses halaman user biasa atau toke → TOLAK
            if (str_starts_with($currentRoute, 'dashboard.') || 
                str_starts_with($currentRoute, 'kebun.') || 
                str_starts_with($currentRoute, 'panen.') || 
                str_starts_with($currentRoute, 'catatan.') ||
                str_starts_with($currentRoute, 'toke.')) {
                return redirect()->route('admin.beranda')
                    ->with('error', 'Silakan gunakan dashboard admin.');
            }
            // Admin akses route admin → IZINKAN
            return $next($request);
        }

        // ============================================================
        // ✅ JIKA USER ADALAH TOKE SAWIT
        // ============================================================
        elseif ($isToke) {
            // Toke mencoba akses halaman admin atau user biasa → TOLAK
            if (str_starts_with($currentRoute, 'admin.') ||
                str_starts_with($currentRoute, 'dashboard.') ||
                str_starts_with($currentRoute, 'kebun.') ||
                str_starts_with($currentRoute, 'panen.') ||
                str_starts_with($currentRoute, 'catatan.')) {
                return redirect()->route('toke.beranda')
                    ->with('error', 'Akses ditolak! Silakan gunakan dashboard Toke.');
            }
            // Toke akses route toke → IZINKAN
            return $next($request);
        }

        // ============================================================
        // ✅ JIKA USER ADALAH USER BIASA
        // ============================================================
        else {
            // User biasa mencoba akses halaman admin atau toke → TOLAK
            if (str_starts_with($currentRoute, 'admin.') ||
                str_starts_with($currentRoute, 'toke.')) {
                return redirect()->route('dashboard.beranda')
                    ->with('error', 'Akses ditolak!');
            }
            // User biasa akses route user → IZINKAN
            return $next($request);
        }
    }
}
<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule; // ✅ TAMBAHKAN INI

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // 1. Konfigurasi Alias Middleware (Tetap seperti sebelumnya)
        $middleware->alias([
            'auth.session' => \App\Http\Middleware\CheckSessionAuth::class,
            'auth' => \App\Http\Middleware\Authenticate::class,
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'premium' => \App\Http\Middleware\PremiumMiddleware::class,
        ]);

        // 2. WAJIB: Matikan CSRF untuk Callback Midtrans
        // Ini agar Midtrans bisa mengirim status "success" ke website Anda
        $middleware->validateCsrfTokens(except: [
            'payment/callback', 
        ]);
    })
    ->withSchedule(function (Schedule $schedule) {
        // ✅ TAMBAHKAN INI: Jadwalkan pengecekan maintenance setiap hari jam 06:00
        $schedule->command('maintenance:check-schedule')
                 ->dailyAt('09:00')
                 ->timezone('Asia/Jakarta');
        
        // ✅ ALTERNATIF: Jalankan setiap jam (lebih responsif)
        // $schedule->command('maintenance:check-schedule')->hourly();
        
        // ✅ ALTERNATIF 2: Jalankan setiap 30 menit (paling responsif)
        // $schedule->command('maintenance:check-schedule')->everyThirtyMinutes();
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
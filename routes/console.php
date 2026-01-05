<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule; // 1. Wajib import Facade Schedule

// Command bawaan Laravel (biarkan saja)
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// ==========================================
// JADWAL NOTIFIKASI PERAWATAN SAWIT
// ==========================================

// Menjalankan command 'jadwal:check' (dari file CheckJadwalPerawatan.php)
// setiap hari pada pukul 08:00 pagi.
Schedule::command('jadwal:check')->dailyAt('08:00');
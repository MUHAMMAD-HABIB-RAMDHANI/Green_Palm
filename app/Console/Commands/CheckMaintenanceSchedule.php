<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pemupukan;
use App\Models\Penunasan;
use App\Models\Penyemprotan;
use App\Models\Sanitasi;
use App\Models\Kastrasi;
use App\Models\Notification;
use App\Models\User;
use App\Models\DataKebun;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CheckMaintenanceSchedule extends Command
{
    protected $signature = 'maintenance:check-schedule';
    protected $description = 'Cek jadwal perawatan per kebun dan kirim notifikasi reminder ke user';

    public function handle()
    {
        $this->info('Memulai pengecekan jadwal perawatan...');
        $this->info('Tanggal hari ini: ' . Carbon::now()->format('Y-m-d'));

        $this->checkPemupukan();
        $this->checkPenunasan();
        $this->checkPenyemprotan();
        $this->checkSanitasi();
        $this->checkKastrasi();

        $this->info('Selesai mengecek jadwal perawatan.');
        return 0;
    }

    private function checkPemupukan()
    {
        $users = User::whereHas('pemupukan')->get();
        $this->info("🌱 Cek Pemupukan - Total user: " . $users->count());
        
        foreach ($users as $user) {
            $kebuns = DataKebun::where('user_id', $user->id)->get();

            foreach ($kebuns as $kebun) {
                $lastPemupukan = Pemupukan::where('user_id', $user->id)
                    ->where('kebun_id', $kebun->id)
                    ->orderBy('tanggal_pemupukan', 'desc')
                    ->first();

                if (!$lastPemupukan) continue;

                $tanggalTerakhir = Carbon::parse($lastPemupukan->tanggal_pemupukan)->startOfDay();
                $hariIni = Carbon::now()->startOfDay();
                
                // ✅ FIX: Hitung selisih dari tanggal terakhir ke hari ini (hasil positif jika sudah lewat)
                $selisihHari = $tanggalTerakhir->diffInDays($hariIni, false);
                
                // Debug log
                $this->info("  Kebun: {$kebun->nama_kebun}");
                $this->info("  Pemupukan terakhir: " . $tanggalTerakhir->format('Y-m-d'));
                $this->info("  Selisih hari: {$selisihHari}");
                
                $link = url('/catatan/input/pemupukan') . '?kebun_id=' . $kebun->id . '&last_id=' . $lastPemupukan->id . '&autofill=true';
                $namaKebun = $kebun->nama_kebun;

                // ✅ Data JSON untuk mobile (kebun_id + jenis_pupuk dari history)
                $notifData = [
                    'kebun_id' => $kebun->id,
                    'jenis_pupuk' => $lastPemupukan->jenis_pupuk,
                    'tanggal_terakhir' => $lastPemupukan->tanggal_pemupukan
                ];

                if ($selisihHari == 30) {
                    $this->info("  → Tepat 30 hari, kirim notifikasi");
                    $this->createNotificationWithCheck(
                        $user->id, 
                        "Waktunya Pemupukan! ({$namaKebun}) 🌱",
                        "Sudah 1 bulan sejak pemupukan terakhir di {$namaKebun}. Saatnya melakukan pemupukan lagi!",
                        '🌱', 
                        $link,
                        $notifData
                    );
                }

                if ($selisihHari > 30) {
                    $hariTerlambat = $selisihHari - 30;
                    $this->info("  → Terlambat {$hariTerlambat} hari, kirim notifikasi");
                    $this->createNotificationWithCheck(
                        $user->id, 
                        "Pemupukan Terlambat! ({$namaKebun}) ⚠️",
                        "Anda telat {$hariTerlambat} hari melakukan pemupukan di {$namaKebun}. Segera lakukan!",
                        '⚠️', 
                        $link,
                        $notifData
                    );
                }
            }
        }
    }

    private function checkPenunasan()
    {
        $users = User::whereHas('penunasan')->get();
        $this->info("🌿 Cek Penunasan - Total user: " . $users->count());
        
        foreach ($users as $user) {
            $kebuns = DataKebun::where('user_id', $user->id)->get();

            foreach ($kebuns as $kebun) {
                $lastPenunasan = Penunasan::where('user_id', $user->id)
                    ->where('kebun_id', $kebun->id)
                    ->orderBy('tanggal_penunasan', 'desc')
                    ->first();

                if (!$lastPenunasan) continue;

                $tanggalTerakhir = Carbon::parse($lastPenunasan->tanggal_penunasan)->startOfDay();
                $hariIni = Carbon::now()->startOfDay();
                $selisihHari = $tanggalTerakhir->diffInDays($hariIni, false);

                $this->info("  Kebun: {$kebun->nama_kebun}");
                $this->info("  Penunasan terakhir: " . $tanggalTerakhir->format('Y-m-d'));
                $this->info("  Selisih hari: {$selisihHari}");

                $link = url('/catatan/input/penunasan') . '?kebun_id=' . $kebun->id . '&last_id=' . $lastPenunasan->id . '&autofill=true';
                $namaKebun = $kebun->nama_kebun;

                // ✅ Data JSON untuk mobile
                $notifData = [
                    'kebun_id' => $kebun->id,
                    'tanggal_terakhir' => $lastPenunasan->tanggal_penunasan
                ];

                if ($selisihHari == 60) {
                    $this->createNotificationWithCheck(
                        $user->id, 
                        "Waktunya Penunasan! ({$namaKebun}) 🌿",
                        "Sudah 2 bulan sejak penunasan terakhir di {$namaKebun}. Saatnya melakukan penunasan hari ini!",
                        '🌿', 
                        $link,
                        $notifData
                    );
                }

                if ($selisihHari > 60) {
                    $hariTerlambat = $selisihHari - 60;
                    $this->createNotificationWithCheck(
                        $user->id, 
                        "Penunasan Terlambat! ({$namaKebun}) ⚠️",
                        "Anda terlambat {$hariTerlambat} hari melakukan penunasan di {$namaKebun}.",
                        '⚠️', 
                        $link,
                        $notifData
                    );
                }
            }
        }
    }

    private function checkPenyemprotan()
    {
        $users = User::whereHas('penyemprotan')->get();
        $this->info("💧 Cek Penyemprotan - Total user: " . $users->count());
        
        foreach ($users as $user) {
            $kebuns = DataKebun::where('user_id', $user->id)->get();

            foreach ($kebuns as $kebun) {
                $lastPenyemprotan = Penyemprotan::where('user_id', $user->id)
                    ->where('kebun_id', $kebun->id)
                    ->orderBy('tanggal_penyemprotan', 'desc')
                    ->first();

                if (!$lastPenyemprotan) continue;

                $tanggalTerakhir = Carbon::parse($lastPenyemprotan->tanggal_penyemprotan)->startOfDay();
                $hariIni = Carbon::now()->startOfDay();
                $selisihHari = $tanggalTerakhir->diffInDays($hariIni, false);

                $link = url('/catatan/input/penyemprotan') . '?kebun_id=' . $kebun->id . '&last_id=' . $lastPenyemprotan->id . '&autofill=true';
                $namaKebun = $kebun->nama_kebun;

                if ($selisihHari == 90) {
                    $this->createNotificationWithCheck(
                        $user->id, 
                        "Waktunya Penyemprotan! ({$namaKebun}) 💧",
                        "Sudah 3 bulan sejak penyemprotan terakhir di {$namaKebun}.",
                        '💧', $link
                    );
                }

                if ($selisihHari > 90) {
                    $hariTerlambat = $selisihHari - 90;
                    $this->createNotificationWithCheck(
                        $user->id, 
                        "Penyemprotan Terlambat! ({$namaKebun}) ⚠️",
                        "Anda terlambat {$hariTerlambat} hari melakukan penyemprotan di {$namaKebun}.",
                        '⚠️', $link
                    );
                }
            }
        }
    }

    private function checkSanitasi()
    {
        $users = User::whereHas('sanitasi')->get();
        $this->info("🧹 Cek Sanitasi - Total user: " . $users->count());
        
        foreach ($users as $user) {
            $kebuns = DataKebun::where('user_id', $user->id)->get();

            foreach ($kebuns as $kebun) {
                $lastSanitasi = Sanitasi::where('user_id', $user->id)
                    ->where('kebun_id', $kebun->id)
                    ->orderBy('tanggal_sanitasi', 'desc')
                    ->first();

                if (!$lastSanitasi) continue;

                $tanggalTerakhir = Carbon::parse($lastSanitasi->tanggal_sanitasi)->startOfDay();
                $hariIni = Carbon::now()->startOfDay();
                $selisihHari = $tanggalTerakhir->diffInDays($hariIni, false);

                $link = url('/catatan/input/sanitasi') . '?kebun_id=' . $kebun->id . '&last_id=' . $lastSanitasi->id . '&autofill=true';
                $namaKebun = $kebun->nama_kebun;

                if ($selisihHari == 60) {
                    $this->createNotificationWithCheck(
                        $user->id, 
                        "Waktunya Sanitasi! ({$namaKebun}) 🧹",
                        "Sudah 2 bulan sejak sanitasi terakhir di {$namaKebun}.",
                        '🧹', $link
                    );
                }

                if ($selisihHari > 60) {
                    $hariTerlambat = $selisihHari - 60;
                    $this->createNotificationWithCheck(
                        $user->id, 
                        "Sanitasi Terlambat! ({$namaKebun}) ⚠️",
                        "Anda terlambat {$hariTerlambat} hari melakukan sanitasi di {$namaKebun}.",
                        '⚠️', $link
                    );
                }
            }
        }
    }

    private function checkKastrasi()
    {
        $users = User::whereHas('kastrasi')->get();
        $this->info("✂️ Cek Kastrasi - Total user: " . $users->count());
        
        foreach ($users as $user) {
            $kebuns = DataKebun::where('user_id', $user->id)->get();

            foreach ($kebuns as $kebun) {
                $lastKastrasi = Kastrasi::where('user_id', $user->id)
                    ->where('kebun_id', $kebun->id)
                    ->orderBy('tanggal_kastrasi', 'desc')
                    ->first();

                if (!$lastKastrasi) continue;

                $tanggalTerakhir = Carbon::parse($lastKastrasi->tanggal_kastrasi)->startOfDay();
                $hariIni = Carbon::now()->startOfDay();
                $selisihHari = $tanggalTerakhir->diffInDays($hariIni, false);

                $link = url('/catatan/input/kastrasi') . '?kebun_id=' . $kebun->id . '&last_id=' . $lastKastrasi->id . '&autofill=true';
                $namaKebun = $kebun->nama_kebun;

                if ($selisihHari == 90) {
                    $this->createNotificationWithCheck(
                        $user->id, 
                        "Waktunya Kastrasi! ({$namaKebun}) ✂️",
                        "Sudah 3 bulan sejak kastrasi terakhir di {$namaKebun}.",
                        '✂️', $link
                    );
                }

                if ($selisihHari > 90) {
                    $hariTerlambat = $selisihHari - 90;
                    $this->createNotificationWithCheck(
                        $user->id, 
                        "Kastrasi Terlambat! ({$namaKebun}) ⚠️",
                        "Anda terlambat {$hariTerlambat} hari melakukan kastrasi di {$namaKebun}.",
                        '⚠️', $link
                    );
                }
            }
        }
    }

    private function createNotificationWithCheck($userId, $title, $message, $icon, $link, $data = null)
    {
        $today = Carbon::now()->startOfDay();

        $exists = Notification::where('user_id', $userId)
            ->where('title', $title)
            ->whereDate('created_at', $today)
            ->exists();

        if (!$exists) {
            $this->createNotification($userId, $title, $message, $icon, $link, $data);
            $this->info("  ✅ Notifikasi dibuat: {$title}");
        } else {
            $this->info("  ⏭️ Notifikasi sudah ada: {$title}");
        }
    }

    private function createNotification($userId, $title, $message, $icon, $link, $data = null)
    {
        try {
            Notification::create([
                'user_id' => $userId,
                'type'    => 'reminder',
                'title'   => $title,
                'message' => $message,
                'icon'    => $icon,
                'link'    => $link,
                'data'    => $data, // ✅ Simpan data sebagai JSON
                'read_at' => null,
            ]);
            Log::info("Notifikasi dibuat untuk user ID {$userId}: {$title}");
        } catch (\Exception $e) {
            Log::error("Gagal membuat notifikasi: " . $e->getMessage());
            $this->error("  ❌ Error: " . $e->getMessage());
        }
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Str;

use App\Models\EducationVideo;
use App\Models\PalmPrice;
use App\Models\Hama;
use App\Models\Penyakit;
use App\Models\DataKebun;
use App\Models\Panen;
use App\Models\Pemupukan;
use App\Models\Penunasan;
use App\Models\Penyemprotan;
use App\Models\Sanitasi;
use App\Models\Kastrasi;
use App\Models\Ram;
use App\Models\KabarSawit;

class DashboardController extends Controller
{
    /**
     * ✅ DIPERBAIKI: Hanya ambil user & tanggal (HANYA PAKAI AUTH)
     */
    private function getCommonData()
    {
        if (!Auth::check()) {
            return null;
        }
        
        $user = Auth::user();
        $tanggal = Carbon::now()->translatedFormat('l, d F Y');
        
        return compact('user', 'tanggal');
    }

    /**
     * Menampilkan halaman Beranda
     */
    public function showBeranda(Request $request)
    {
        $commonData = $this->getCommonData();
        if (!$commonData) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $kondisi_cuaca = 'Cerah Berawan';
        $suhu = '30';
        $apiKey = env('OPENWEATHER_API_KEY');
        
        if (!empty($apiKey)) {
            try {
                $weatherData = Cache::remember('weather_dumai', 1800, function () use ($apiKey) {
                    $response = Http::get('https://api.openweathermap.org/data/2.5/weather', [
                        'q' => 'Dumai',
                        'appid' => $apiKey,
                        'units' => 'metric',
                        'lang' => 'id'
                    ]);
                    return $response->successful() ? $response->json() : null;
                });

                if ($weatherData) {
                    $kondisi_cuaca = $weatherData['weather'][0]['description'] ?? 'Tidak tersedia';
                    $suhu = isset($weatherData['main']['temp']) ? round($weatherData['main']['temp']) : '-';
                }
            } catch (\Exception $e) {
                Log::error('Error API Cuaca: ' . $e->getMessage());
            }
        }

        // ✅ Ambil kebun pertama untuk ditampilkan di beranda
        $daftarKebun = DataKebun::where('user_id', Auth::id())->get();
        $kebun = $daftarKebun->first();

        // ✅ TAMBAHAN: Ambil data keuangan untuk grafik
        $keuanganData = $this->getKeuanganData();

        $data = array_merge($commonData, compact('kondisi_cuaca', 'suhu', 'kebun', 'daftarKebun', 'keuanganData'));

        if ($request->ajax() || $request->wantsJson()) {
            return view('dashboard.beranda-content', $data);
        }

        return view('dashboard.beranda', $data);
    }

    public function showProfil(Request $request)
    {
        $commonData = $this->getCommonData();
        if (!$commonData) return redirect()->route('login');

        if ($request->ajax() || $request->wantsJson()) {
            return view('dashboard.profil-content', $commonData);
        }
        return view('dashboard.profil', $commonData);
    }

    public function showNotifikasi(Request $request)
    {
        $commonData = $this->getCommonData();
        if (!$commonData) return redirect()->route('login');

        // ✅ PERBAIKAN: Tandai notifikasi unread yang relevan untuk user ini sebagai read
        // Termasuk notifikasi personal DAN broadcast
        \App\Models\Notification::forUser(Auth::id())
            ->whereNotNull('user_id')
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        // ✅ PERBAIKAN: Ambil notifikasi yang relevan untuk user ini
        // - Notifikasi personal (user_id = Auth::id())
        // - Notifikasi broadcast dari admin (user_id = NULL)
        $notifications = \App\Models\Notification::forUser(Auth::id())
            ->latest()
            ->paginate(15);
        
        // Karena sudah di-update di atas, maka unreadCount sekarang adalah 0
        $unreadCount = 0; 

        $data = array_merge($commonData, compact('notifications', 'unreadCount'));

        if ($request->ajax() || $request->wantsJson()) {
            return view('dashboard.notifikasi-content', $data);
        }
        return view('dashboard.notifikasi', $data);
    }

    /**
     * Tandai notifikasi sebagai sudah dibaca
     */
    public function markNotificationAsRead($id)
    {
        // ✅ PERBAIKAN: Pastikan notifikasi adalah milik user atau broadcast
        $notification = \App\Models\Notification::forUser(Auth::id())
            ->findOrFail($id);
        $notification->markAsRead();

        return back()->with('success', 'Notifikasi telah ditandai sebagai dibaca');
    }

    /**
     * Tandai semua notifikasi sebagai sudah dibaca
     */
    public function markAllNotificationsAsRead()
    {
        // ✅ PERBAIKAN: Tandai notifikasi yang relevan untuk user ini
        \App\Models\Notification::forUser(Auth::id())
            ->whereNotNull('user_id')
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return back()->with('success', 'Semua notifikasi telah ditandai sebagai dibaca');
    }

    /**
     * Hapus notifikasi
     */
    public function deleteNotification($id)
    {
        // ✅ PERBAIKAN: User hanya bisa hapus notifikasi personal miliknya
        // Notifikasi broadcast tidak bisa dihapus oleh user biasa
        $notification = \App\Models\Notification::where('user_id', Auth::id())
            ->findOrFail($id);
        $notification->delete();

        return back()->with('success', 'Notifikasi berhasil dihapus');
    }

    public function edukasi(Request $request)
    {
        $commonData = $this->getCommonData();
        if (!$commonData) return redirect()->route('login');

        // Ambil semua video edukasi yang sudah ditambahkan admin
        // Urutkan dari yang terbaru
        $videos = EducationVideo::orderBy('created_at', 'desc')->get();
        
        $data = array_merge($commonData, compact('videos'));

        return view('dashboard.edukasi', $data);
    }

    public function hargaSawit(Request $request)
    {
        $commonData = $this->getCommonData();
        if (!$commonData) return redirect()->route('login');

        // ✅ UBAH DISINI: Ambil data dari model Ram, urutkan dari yang paling baru diupdate
        $prices = Ram::orderBy('updated_at', 'desc')->get();
        
        $data = array_merge($commonData, compact('prices'));

        return view('dashboard.harga-sawit', $data);
    }

    public function detailRam($id)
    {
        $commonData = $this->getCommonData();
        if (!$commonData) return redirect()->route('login');

        // Ambil data RAM berdasarkan ID, jika tidak ada tampilkan 404
        $ram = \App\Models\Ram::with('user')->findOrFail($id);

        $data = array_merge($commonData, compact('ram'));

        return view('dashboard.detail-ram', $data);
    }

    public function kabarSawit(Request $request)
    {
        $commonData = $this->getCommonData();
        if (!$commonData) return redirect()->route('login');

        $search = trim($request->input('q'));
        $category = $request->input('category');

        $query = \App\Models\KabarSawit::query();

        if ($search) {
            $query->where('title', 'like', '%' . $search . '%');
        }

        if ($category && $category !== 'Semua') {
            $query->where('category', $category);
        }

        $popularNews = \App\Models\KabarSawit::where('is_popular', true)
                                ->latest()
                                ->take(5)
                                ->get();

        $recentNews = $query->latest('published_at')->paginate(10);
        $recentNews->appends(['q' => $search, 'category' => $category]);

        $data = array_merge($commonData, compact('popularNews', 'recentNews', 'search', 'category'));

        return view('dashboard.kabar-sawit', $data);
    }

    public function penyakitSawit(Request $request)
    {
        $commonData = $this->getCommonData();
        if (!$commonData) return redirect()->route('login');
        return view('dashboard.penyakit', $commonData);
    }

    public function daftarHama(Request $request)
    {
        $commonData = $this->getCommonData();
        if (!$commonData) return redirect()->route('login');

        $hamas = Hama::orderBy('name', 'asc')->get();
        $data = array_merge($commonData, compact('hamas'));

        return view('dashboard.daftar-hama', $data);
    }

    public function detailHama($id)
    {
        $commonData = $this->getCommonData();
        if (!$commonData) return redirect()->route('login');

        // Ambil data hama berdasarkan ID, jika tidak ada tampilkan 404
        $hama = \App\Models\Hama::findOrFail($id);

        // Gabungkan data user (commonData) dengan data hama
        $data = array_merge($commonData, compact('hama'));

        return view('dashboard.detail-hama', $data);
    }

    public function daftarPenyakit(Request $request)
    {
        $commonData = $this->getCommonData();
        if (!$commonData) return redirect()->route('login');

        $penyakits = Penyakit::orderBy('name', 'asc')->get();
        $data = array_merge($commonData, compact('penyakits'));

        return view('dashboard.daftar-penyakit', $data);
    }

    public function detailPenyakit($id)
    {
        $commonData = $this->getCommonData();
        if (!$commonData) return redirect()->route('login');

        // Ambil data penyakit berdasarkan ID, jika tidak ada tampilkan 404
        $penyakit = \App\Models\Penyakit::findOrFail($id);

        // Gabungkan data user dengan data penyakit
        $data = array_merge($commonData, compact('penyakit'));

        return view('dashboard.detail-penyakit', $data);
    }

    public function diagnosaSawit()
    {
        $commonData = $this->getCommonData();
        if (!$commonData) return redirect()->route('login');
        return view('dashboard.diagnosa', $commonData);
    }

    // ========================================================
    // ✅ FUNGSI BARU UNTUK GRAFIK KEUANGAN
    // ========================================================

    /**
     * Menampilkan halaman Laporan Keuangan lengkap
     */
    public function laporan()
    {
        $commonData = $this->getCommonData();
        if (!$commonData) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();
        $userId = Auth::id();

        // Ambil semua kebun milik user
        $kebuns = DataKebun::where('user_id', $userId)->get();

        // Data keuangan detail
        $laporanData = $this->getLaporanDetail();

        $data = array_merge($commonData, compact('kebuns', 'laporanData'));

        return view('dashboard.laporan', $data);
    }

    /**
     * Mengambil data keuangan per bulan untuk grafik
     */
    private function getKeuanganData()
    {
        $userId = Auth::id();
        $tahunIni = Carbon::now()->year;
        
        // Ambil data per bulan untuk tahun ini
        $bulanData = [];
        
        for ($bulan = 1; $bulan <= 12; $bulan++) {
            $startDate = Carbon::create($tahunIni, $bulan, 1)->startOfMonth();
            $endDate = Carbon::create($tahunIni, $bulan, 1)->endOfMonth();
            
            // ✅ FIX: Hitung total PENDAPATAN dari kolom 'pendapatan'
            $pendapatan = Panen::where('user_id', $userId)
                ->whereBetween('tanggal_panen', [$startDate, $endDate])
                ->sum('pendapatan'); 
            
            // ✅ FIX: Pengeluaran Panen (Upah)
            $upahPanen = Panen::where('user_id', $userId)
                ->whereBetween('tanggal_panen', [$startDate, $endDate])
                ->sum('total_upah_panen');

            // Hitung total pengeluaran dari semua aktivitas perawatan
            $pengeluaranPemupukan = Pemupukan::where('user_id', $userId)
                ->whereBetween('tanggal_pemupukan', [$startDate, $endDate])
                ->sum('total_upah');
                
            $pengeluaranPenunasan = Penunasan::where('user_id', $userId)
                ->whereBetween('tanggal_penunasan', [$startDate, $endDate])
                ->sum('total_upah');
                
            $pengeluaranPenyemprotan = Penyemprotan::where('user_id', $userId)
                ->whereBetween('tanggal_penyemprotan', [$startDate, $endDate])
                ->sum('total_upah');
                
            $pengeluaranSanitasi = Sanitasi::where('user_id', $userId)
                ->whereBetween('tanggal_sanitasi', [$startDate, $endDate])
                ->sum('total_upah');
                
            $pengeluaranKastrasi = Kastrasi::where('user_id', $userId)
                ->whereBetween('tanggal_kastrasi', [$startDate, $endDate])
                ->sum('total_upah');

            // Tambahkan biaya pembelian pupuk
            $biayaPupuk = Pemupukan::where('user_id', $userId)
                ->whereBetween('tanggal_pemupukan', [$startDate, $endDate])
                ->sum('biaya_pembelian');

            // Tambahkan biaya lainnya dari setiap aktivitas
            $biayaLainPenunasan = Penunasan::where('user_id', $userId)
                ->whereBetween('tanggal_penunasan', [$startDate, $endDate])
                ->sum('biaya_lain');

            $biayaLainPenyemprotan = Penyemprotan::where('user_id', $userId)
                ->whereBetween('tanggal_penyemprotan', [$startDate, $endDate])
                ->sum('biaya_lain');

            $biayaLainSanitasi = Sanitasi::where('user_id', $userId)
                ->whereBetween('tanggal_sanitasi', [$startDate, $endDate])
                ->sum('biaya_lain');

            $biayaLainKastrasi = Kastrasi::where('user_id', $userId)
                ->whereBetween('tanggal_kastrasi', [$startDate, $endDate])
                ->sum('biaya_lain');
            
            // ✅ FIX: Total Pengeluaran = Upah Panen + Upah Perawatan + Biaya Material
            $totalPengeluaran = $upahPanen + 
                                $pengeluaranPemupukan + $pengeluaranPenunasan + 
                                $pengeluaranPenyemprotan + $pengeluaranSanitasi + 
                                $pengeluaranKastrasi + $biayaPupuk + 
                                $biayaLainPenunasan + $biayaLainPenyemprotan + 
                                $biayaLainSanitasi + $biayaLainKastrasi;
            
            $bulanData[] = [
                'bulan' => Carbon::create($tahunIni, $bulan, 1)->format('M'),
                'pendapatan' => $pendapatan,
                'pengeluaran' => $totalPengeluaran,
            ];
        }

        return $bulanData;
    }

    /**
     * Mengambil detail laporan keuangan lengkap
     */
    private function getLaporanDetail()
    {
        $userId = Auth::id();
        $tahunIni = Carbon::now()->year;
        $startDate = Carbon::create($tahunIni, 1, 1);
        $endDate = Carbon::now();

        // ✅ FIX: Total Pendapatan diambil dari kolom 'pendapatan'
        $totalPendapatan = Panen::where('user_id', $userId)
            ->whereBetween('tanggal_panen', [$startDate, $endDate])
            ->sum('pendapatan');

        $totalBeratTBS = Panen::where('user_id', $userId)
            ->whereBetween('tanggal_panen', [$startDate, $endDate])
            ->sum('berat_total_tbs');

        $rataHargaTBS = $totalBeratTBS > 0 ? $totalPendapatan / $totalBeratTBS : 0;

        // ✅ FIX: Upah Panen (ini adalah Pengeluaran, bukan pendapatan)
        $upahPanen = Panen::where('user_id', $userId)
            ->whereBetween('tanggal_panen', [$startDate, $endDate])
            ->sum('total_upah_panen');

        // Upah Langsir (jika ada tabel terpisah, sesuaikan)
        $upahLangsir = 0;

        // Upah Perawatan (Hanya Upah Tenaga Kerja)
        $upahPerawatan = Pemupukan::where('user_id', $userId)
            ->whereBetween('tanggal_pemupukan', [$startDate, $endDate])
            ->sum('total_upah') +
            Penunasan::where('user_id', $userId)
            ->whereBetween('tanggal_penunasan', [$startDate, $endDate])
            ->sum('total_upah') +
            Penyemprotan::where('user_id', $userId)
            ->whereBetween('tanggal_penyemprotan', [$startDate, $endDate])
            ->sum('total_upah') +
            Sanitasi::where('user_id', $userId)
            ->whereBetween('tanggal_sanitasi', [$startDate, $endDate])
            ->sum('total_upah') +
            Kastrasi::where('user_id', $userId)
            ->whereBetween('tanggal_kastrasi', [$startDate, $endDate])
            ->sum('total_upah');

        // Biaya Pembelian Pupuk
        $biayaPembelianPupuk = Pemupukan::where('user_id', $userId)
            ->whereBetween('tanggal_pemupukan', [$startDate, $endDate])
            ->sum('biaya_pembelian');

        // Biaya Lainnya (Material/Alat selain pupuk)
        $biayaLainnya = Penunasan::where('user_id', $userId)
            ->whereBetween('tanggal_penunasan', [$startDate, $endDate])
            ->sum('biaya_lain') +
            Penyemprotan::where('user_id', $userId)
            ->whereBetween('tanggal_penyemprotan', [$startDate, $endDate])
            ->sum('biaya_lain') +
            Sanitasi::where('user_id', $userId)
            ->whereBetween('tanggal_sanitasi', [$startDate, $endDate])
            ->sum('biaya_lain') +
            Kastrasi::where('user_id', $userId)
            ->whereBetween('tanggal_kastrasi', [$startDate, $endDate])
            ->sum('biaya_lain');

        // ✅ FIX: Total Pengeluaran = Upah Panen + Upah Perawatan + Biaya Material
        $totalPengeluaran = $upahPanen + $upahLangsir + $upahPerawatan + 
                           $biayaPembelianPupuk + $biayaLainnya;

        $pendapatanBersih = $totalPendapatan - $totalPengeluaran;

        // Data grafik
        $grafikData = $this->getKeuanganData();

        return [
            'periode' => 'Jan ' . $tahunIni . ' - Des ' . $tahunIni,
            'pendapatan_bersih' => $pendapatanBersih,
            'total_pendapatan' => $totalPendapatan,
            'total_berat_tbs' => $totalBeratTBS,
            'rata_harga_tbs' => $rataHargaTBS,
            'total_pengeluaran' => $totalPengeluaran,
            'upah_panen' => $upahPanen,
            'upah_langsir' => $upahLangsir,
            'upah_perawatan' => $upahPerawatan,
            'biaya_pupuk' => $biayaPembelianPupuk,
            'biaya_lainnya' => $biayaLainnya,
            'grafik_data' => $grafikData,
        ];
    }
}
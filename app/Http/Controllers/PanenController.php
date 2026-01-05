<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use App\Models\Panen;
use App\Models\DataKebun;

class PanenController extends Controller
{
    /**
     * Menampilkan halaman index dengan fitur FILTER
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $userId = Auth::id();

        // 1. Ambil semua kebun milik user untuk Dropdown Filter
        $kebuns = DataKebun::where('user_id', $userId)->get();

        // 2. Tentukan Filter yang dipilih User (Default: Tahun Ini & Semua Kebun)
        $selectedYear = $request->input('tahun', date('Y'));
        $selectedKebun = $request->input('kebun_id');

        // 3. Query Data Panen (Base Query)
        $query = Panen::where('user_id', $userId)
            ->with('kebun')
            ->orderBy('tanggal_panen', 'desc');

        // -> Terapkan Filter Kebun (jika dipilih)
        if ($selectedKebun) {
            $query->where('kebun_id', $selectedKebun);
        }

        // -> Terapkan Filter Tahun
        if ($selectedYear) {
            $query->whereYear('tanggal_panen', $selectedYear);
        }

        // Eksekusi Query
        $panens = $query->get();

        // 4. Generate Daftar Tahun untuk Dropdown 
        // (Mengambil tahun dari data terlama s/d tahun sekarang)
        $oldestPanen = Panen::where('user_id', $userId)->orderBy('tanggal_panen', 'asc')->first();
        $startYear = $oldestPanen ? date('Y', strtotime($oldestPanen->tanggal_panen)) : date('Y');
        $currentYear = date('Y');
        
        // Buat array range tahun (Misal: 2025, 2024, 2023) descending
        $years = range($currentYear, $startYear); 

        return view('panen.index', compact(
            'user', 
            'kebuns', 
            'panens', 
            'years', 
            'selectedYear', 
            'selectedKebun'
        ));
    }

    /**
     * Menampilkan form create panen
     */
    public function create()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $userId = Auth::id();

        // Ambil daftar kebun
        $kebunList = DataKebun::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        // Redirect jika belum punya kebun
        if ($kebunList->isEmpty()) {
            return redirect()->route('kebun.create')
                ->with('error', 'Anda belum memiliki kebun. Silakan buat kebun terlebih dahulu.');
        }

        return view('panen.catat-panen', compact('user', 'kebunList'));
    }

    /**
     * Menyimpan data panen beserta kalkulasi pendapatan
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // 1. Validasi Input
        $validated = $request->validate([
            'kebun_id' => [
                'required',
                'integer',
                Rule::exists('data_kebun', 'id')->where('user_id', Auth::id())
            ],
            'tanggal_panen' => 'required|date',
            'berat_total_tbs' => 'required|numeric|min:0',
            'harga_tbs' => 'required|numeric|min:0', 
            'jumlah_tbs' => 'nullable|integer|min:0',
            'berat_brondolan' => 'nullable|numeric|min:0',
            'tanggal_panen_berikutnya' => 'nullable|date|after:tanggal_panen',
            'upah_panen' => 'nullable|array',
            'upah_panen.*.jenis' => 'nullable|string|max:255',
            'upah_panen.*.jumlah' => 'nullable|numeric|min:0',
        ], [
            'kebun_id.required' => 'Silakan pilih kebun terlebih dahulu.',
            'berat_total_tbs.required' => 'Berat total TBS wajib diisi.',
            'harga_tbs.required' => 'Harga TBS wajib diisi untuk menghitung pendapatan.',
            'tanggal_panen_berikutnya.after' => 'Tanggal panen berikutnya harus setelah tanggal panen.',
        ]);

        try {
            // 2. Hitung Pendapatan Kotor
            // Rumus: Berat (kg) x Harga (Rp/kg)
            $pendapatanKotor = $validated['berat_total_tbs'] * $validated['harga_tbs'];

            // 3. Hitung Total Upah Panen (Pengeluaran)
            $totalUpah = 0;
            $biayaLainnya = [];

            if ($request->has('upah_panen') && is_array($request->upah_panen)) {
                foreach ($request->upah_panen as $upah) {
                    if (!empty($upah['jenis']) && !empty($upah['jumlah'])) {
                        $totalUpah += $upah['jumlah'];
                        $biayaLainnya[] = [
                            'jenis' => $upah['jenis'],
                            'jumlah' => $upah['jumlah']
                        ];
                    }
                }
            }

            // 4. Simpan ke Database
            Panen::create([
                'user_id' => Auth::id(),
                'kebun_id' => $validated['kebun_id'],
                'tanggal_panen' => $validated['tanggal_panen'],
                'berat_total_tbs' => $validated['berat_total_tbs'],
                'jumlah_tbs' => $validated['jumlah_tbs'],
                'berat_brondolan' => $validated['berat_brondolan'],
                'tanggal_panen_berikutnya' => $validated['tanggal_panen_berikutnya'],
                
                // Simpan hasil kalkulasi pendapatan
                'pendapatan' => $pendapatanKotor, 
                
                'total_upah_panen' => $totalUpah,
                'biaya_lainnya' => !empty($biayaLainnya) ? $biayaLainnya : null,
            ]);

            return redirect()->route('panen.index')
                ->with('success', 'Data Panen berhasil disimpan! Pendapatan: Rp ' . number_format($pendapatanKotor, 0, ',', '.'));

        } catch (\Exception $e) {
            Log::error('Error saat menyimpan panen: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan detail panen
     */
    public function show($id)
    {
        // 1. Ambil data user yang sedang login
        $user = Auth::user(); 

        $panen = Panen::with('kebun')->findOrFail($id);

        // Security check: Pastikan user hanya melihat datanya sendiri
        if ($panen->user_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        // 2. Tambahkan 'user' ke dalam compact agar dikirim ke View
        return view('panen.detail', compact('panen', 'user'));
    }

    /**
     * Menghapus data panen
     */
    public function destroy($id)
    {
        // 1. Cari data panen
        $panen = Panen::findOrFail($id);

        // 2. Security Check: Pastikan yang menghapus adalah pemilik data
        if ($panen->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // 3. Hapus data
        try {
            $panen->delete();
            return redirect()->route('panen.index')
                ->with('success', 'Data panen berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
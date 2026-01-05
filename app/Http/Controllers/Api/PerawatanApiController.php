<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use App\Models\Pemupukan;
use App\Models\Penunasan;
use App\Models\Penyemprotan;
use App\Models\Sanitasi;
use App\Models\Kastrasi;
use App\Models\DataKebun;

class PerawatanApiController extends Controller
{
    /**
     * Get all perawatan records (semua jenis)
     * GET /api/perawatan
     */
    public function index(Request $request)
    {
        $userId = Auth::id();
        $kebunId = $request->input('kebun_id');
        
        // Ambil semua data perawatan
        $pemupukan = Pemupukan::where('user_id', $userId)
            ->when($kebunId, fn($q) => $q->where('kebun_id', $kebunId))
            ->with('kebun')
            ->get()
            ->map(fn($item) => array_merge($item->toArray(), ['jenis' => 'pemupukan']));
            
        $penunasan = Penunasan::where('user_id', $userId)
            ->when($kebunId, fn($q) => $q->where('kebun_id', $kebunId))
            ->with('kebun')
            ->get()
            ->map(fn($item) => array_merge($item->toArray(), ['jenis' => 'penunasan']));
            
        $penyemprotan = Penyemprotan::where('user_id', $userId)
            ->when($kebunId, fn($q) => $q->where('kebun_id', $kebunId))
            ->with('kebun')
            ->get()
            ->map(fn($item) => array_merge($item->toArray(), ['jenis' => 'penyemprotan']));
            
        $sanitasi = Sanitasi::where('user_id', $userId)
            ->when($kebunId, fn($q) => $q->where('kebun_id', $kebunId))
            ->with('kebun')
            ->get()
            ->map(fn($item) => array_merge($item->toArray(), ['jenis' => 'sanitasi']));
            
        $kastrasi = Kastrasi::where('user_id', $userId)
            ->when($kebunId, fn($q) => $q->where('kebun_id', $kebunId))
            ->with('kebun')
            ->get()
            ->map(fn($item) => array_merge($item->toArray(), ['jenis' => 'kastrasi']));
        
        // Gabungkan semua dan urutkan berdasarkan tanggal terbaru
        $allPerawatan = collect()
            ->merge($pemupukan)
            ->merge($penunasan)
            ->merge($penyemprotan)
            ->merge($sanitasi)
            ->merge($kastrasi)
            ->sortByDesc(function ($item) {
                return $item['tanggal_pemupukan'] 
                    ?? $item['tanggal_penunasan'] 
                    ?? $item['tanggal_penyemprotan'] 
                    ?? $item['tanggal_sanitasi'] 
                    ?? $item['tanggal_kastrasi'];
            })
            ->values();

        return response()->json([
            'status' => 'success',
            'message' => 'Data perawatan berhasil diambil',
            'data' => $allPerawatan
        ]);
    }

    /**
     * Store Pemupukan
     * POST /api/perawatan/pemupukan
     */
    public function storePemupukan(Request $request)
    {
        // 1. Validasi Input
        $validated = $request->validate([
            'kebun_id' => ['required', 'integer', Rule::exists('data_kebun', 'id')->where('user_id', Auth::id())],
            'tanggal_pemupukan' => 'required|date',
            'jenis_pupuk' => 'required|string|max:255',
            'total_pupuk' => 'required|numeric|min:0',
            'pupuk_per_pokok' => 'nullable|numeric|min:0',
            
            // Biaya Beli sekarang wajib (sesuai logika web/android baru)
            'biaya_pembelian' => 'required|numeric|min:0', 
            
            // Validasi Array Rincian Upah
            'rincian_upah' => 'nullable|array',
            'rincian_upah.*.jenis' => 'required_with:rincian_upah|string',
            'rincian_upah.*.jumlah' => 'required_with:rincian_upah|numeric|min:0',
        ]);

        try {
            // 2. Logika Hitung Total Upah dari Rincian (Sama seperti Web)
            $inputRincian = $request->input('rincian_upah', []);
            $totalUpahHitung = 0;
            $dataRincianSimpan = [];

            if (!empty($inputRincian) && is_array($inputRincian)) {
                foreach ($inputRincian as $item) {
                    $jumlah = isset($item['jumlah']) ? (float)$item['jumlah'] : 0;
                    
                    if ($jumlah > 0) {
                        $totalUpahHitung += $jumlah;
                        $dataRincianSimpan[] = [
                            'jenis'  => $item['jenis'] ?? 'Biaya Operasional',
                            'jumlah' => $jumlah
                        ];
                    }
                }
            }

            // Jika Android mengirim 'total_upah' sebagai fallback, kita bisa menggunakannya 
            // HANYA JIKA tidak ada rincian. Tapi prioritas adalah hasil hitung rincian.
            if ($totalUpahHitung == 0 && $request->has('total_upah')) {
                $totalUpahHitung = $request->input('total_upah');
            }

            // 3. Simpan ke Database
            $pemupukan = Pemupukan::create([
                'user_id' => Auth::id(),
                'kebun_id' => $validated['kebun_id'],
                'tanggal_pemupukan' => $validated['tanggal_pemupukan'],
                'jenis_pupuk' => $validated['jenis_pupuk'],
                'total_pupuk' => $validated['total_pupuk'],
                'pupuk_per_pokok' => $request->input('pupuk_per_pokok', 0),
                'biaya_pembelian' => $validated['biaya_pembelian'],
                
                // Simpan total hasil hitungan server
                'total_upah' => $totalUpahHitung,
                
                // Simpan Array Rincian (Model akan otomatis cast ke JSON)
                'rincian_upah' => count($dataRincianSimpan) > 0 ? $dataRincianSimpan : null,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Data pemupukan berhasil disimpan',
                'data' => $pemupukan
            ], 201);

        } catch (\Exception $e) {
            Log::error('Error API pemupukan: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyimpan data pemupukan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get Last Pemupukan untuk kebun tertentu
     * GET /api/perawatan/pemupukan/last/{kebunId}
     */
    public function getLastPemupukan($kebunId)
    {
        try {
            $userId = Auth::id();

            // Validasi bahwa kebun adalah milik user
            $kebun = DataKebun::where('id', $kebunId)
                ->where('user_id', $userId)
                ->first();

            if (!$kebun) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Kebun tidak ditemukan atau bukan milik Anda'
                ], 404);
            }

            // Ambil data pemupukan terakhir untuk kebun ini
            $lastPemupukan = Pemupukan::where('user_id', $userId)
                ->where('kebun_id', $kebunId)
                ->orderBy('tanggal_pemupukan', 'desc')
                ->orderBy('created_at', 'desc')
                ->first();

            if (!$lastPemupukan) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Belum ada data pemupukan untuk kebun ini',
                    'data' => null
                ], 200);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Data pemupukan terakhir berhasil diambil',
                'data' => $lastPemupukan
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error get last pemupukan: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data pemupukan'
            ], 500);
        }
    }

    /**
     * Store Penunasan
     * POST /api/perawatan/penunasan
     */
    public function storePenunasan(Request $request)
    {
        $validated = $request->validate([
            'kebun_id' => ['required', 'integer', Rule::exists('data_kebun', 'id')->where('user_id', Auth::id())],
            'tanggal_penunasan' => 'required|date',
            'jumlah_pokok_ditunas' => 'required|integer|min:1',
            
            // Biaya Lain sekarang wajib (isi 0 jika tidak ada)
            'biaya_lain' => 'required|numeric|min:0',
            
            // Validasi Array Rincian
            'rincian_upah' => 'nullable|array',
            'rincian_upah.*.jenis' => 'required_with:rincian_upah|string',
            'rincian_upah.*.jumlah' => 'required_with:rincian_upah|numeric|min:0',
        ]);

        try {
            // 1. Logika Hitung Total Upah dari Rincian
            $inputRincian = $request->input('rincian_upah', []);
            $totalUpahHitung = 0;
            $dataRincianSimpan = [];

            if (!empty($inputRincian) && is_array($inputRincian)) {
                foreach ($inputRincian as $item) {
                    $jumlah = isset($item['jumlah']) ? (float)$item['jumlah'] : 0;
                    
                    if ($jumlah > 0) {
                        $totalUpahHitung += $jumlah;
                        $dataRincianSimpan[] = [
                            'jenis'  => $item['jenis'] ?? 'Upah Penunasan',
                            'jumlah' => $jumlah
                        ];
                    }
                }
            }

            // Fallback jika Android mengirim total manual (untuk backward compatibility)
            if ($totalUpahHitung == 0 && $request->has('total_upah')) {
                $totalUpahHitung = $request->input('total_upah');
            }

            // Tentukan status ada_biaya_lain
            $biayaLain = $validated['biaya_lain'];
            $adaBiayaLain = $biayaLain > 0 ? 'Ya' : 'Tidak';

            // 2. Simpan Data
            $penunasan = Penunasan::create([
                'user_id' => Auth::id(),
                'kebun_id' => $validated['kebun_id'],
                'tanggal_penunasan' => $validated['tanggal_penunasan'],
                'jumlah_pokok_ditunas' => $validated['jumlah_pokok_ditunas'],
                'total_upah' => $totalUpahHitung, // Hasil hitung server
                'ada_biaya_lain' => $adaBiayaLain,
                'biaya_lain' => $biayaLain,
                'rincian_upah' => count($dataRincianSimpan) > 0 ? $dataRincianSimpan : null,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Data penunasan berhasil disimpan',
                'data' => $penunasan
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error penunasan: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyimpan data penunasan'
            ], 500);
        }
    }

    /**
     * Store Penyemprotan
     * POST /api/perawatan/penyemprotan
     */
    public function storePenyemprotan(Request $request)
    {
        $validated = $request->validate([
            'kebun_id' => ['required', 'integer', Rule::exists('data_kebun', 'id')->where('user_id', Auth::id())],
            'tanggal_penyemprotan' => 'required|date',
            'jenis_pestisida_racun' => 'nullable|string|max:255',
            'penggunaan_pestisida' => 'required|numeric|min:0',
            'luas_lahan_disemprot' => 'required|numeric|min:0',
            'total_upah' => 'required|numeric|min:0',
            'ada_biaya_lain' => 'required|in:Ya,Tidak',
            'biaya_lain' => 'nullable|numeric|min:0',
        ]);

        try {
            $penyemprotan = Penyemprotan::create([
                'user_id' => Auth::id(),
                'kebun_id' => $validated['kebun_id'],
                'tanggal_penyemprotan' => $validated['tanggal_penyemprotan'],
                'jenis_pestisida_racun' => $validated['jenis_pestisida_racun'],
                'penggunaan_pestisida' => $validated['penggunaan_pestisida'],
                'luas_lahan_disemprot' => $validated['luas_lahan_disemprot'],
                'total_upah' => $validated['total_upah'],
                'ada_biaya_lain' => $validated['ada_biaya_lain'],
                'biaya_lain' => $validated['biaya_lain'] ?? null,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Data penyemprotan berhasil disimpan',
                'data' => $penyemprotan
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error penyemprotan: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyimpan data penyemprotan'
            ], 500);
        }
    }

    /**
     * Store Sanitasi
     * POST /api/perawatan/sanitasi
     */
    public function storeSanitasi(Request $request)
    {
        $validated = $request->validate([
            'kebun_id' => ['required', 'integer', Rule::exists('data_kebun', 'id')->where('user_id', Auth::id())],
            'tanggal_sanitasi' => 'required|date',
            'jumlah_pokok_disanitasi' => 'required|integer|min:1',
            
            // Biaya Lain Wajib (isi 0 jika tidak ada)
            'biaya_lain' => 'required|numeric|min:0',
            
            // Validasi Array Rincian
            'rincian_upah' => 'nullable|array',
            'rincian_upah.*.jenis' => 'required_with:rincian_upah|string',
            'rincian_upah.*.jumlah' => 'required_with:rincian_upah|numeric|min:0',
        ]);

        try {
            // 1. Logika Hitung Total Upah dari Rincian
            $inputRincian = $request->input('rincian_upah', []);
            $totalUpahHitung = 0;
            $dataRincianSimpan = [];

            if (!empty($inputRincian) && is_array($inputRincian)) {
                foreach ($inputRincian as $item) {
                    $jumlah = isset($item['jumlah']) ? (float)$item['jumlah'] : 0;
                    
                    if ($jumlah > 0) {
                        $totalUpahHitung += $jumlah;
                        $dataRincianSimpan[] = [
                            'jenis'  => $item['jenis'] ?? 'Upah Sanitasi',
                            'jumlah' => $jumlah
                        ];
                    }
                }
            }

            // Fallback total upah
            if ($totalUpahHitung == 0 && $request->has('total_upah')) {
                $totalUpahHitung = $request->input('total_upah');
            }

            // Status Biaya Lain
            $biayaLain = $validated['biaya_lain'];
            $adaBiayaLain = $biayaLain > 0 ? 'Ya' : 'Tidak';

            // 2. Simpan Data
            $sanitasi = Sanitasi::create([
                'user_id' => Auth::id(),
                'kebun_id' => $validated['kebun_id'],
                'tanggal_sanitasi' => $validated['tanggal_sanitasi'],
                'jumlah_pokok_disanitasi' => $validated['jumlah_pokok_disanitasi'],
                'total_upah' => $totalUpahHitung, // Hasil hitung server
                'ada_biaya_lain' => $adaBiayaLain,
                'biaya_lain' => $biayaLain,
                'rincian_upah' => count($dataRincianSimpan) > 0 ? $dataRincianSimpan : null,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Data sanitasi berhasil disimpan',
                'data' => $sanitasi
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error sanitasi: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyimpan data sanitasi'
            ], 500);
        }
    }

    /**
     * Store Kastrasi
     * POST /api/perawatan/kastrasi
     */
    public function storeKastrasi(Request $request)
    {
        $validated = $request->validate([
            'kebun_id' => ['required', 'integer', Rule::exists('data_kebun', 'id')->where('user_id', Auth::id())],
            'tanggal_kastrasi' => 'required|date',
            'jumlah_pokok_dikastrasi' => 'required|integer|min:1',
            
            // Biaya Lain Wajib (isi 0 jika tidak ada)
            'biaya_lain' => 'required|numeric|min:0',
            
            // Validasi Array Rincian
            'rincian_upah' => 'nullable|array',
            'rincian_upah.*.jenis' => 'required_with:rincian_upah|string',
            'rincian_upah.*.jumlah' => 'required_with:rincian_upah|numeric|min:0',
        ]);

        try {
            // 1. Logika Hitung Total Upah dari Rincian
            $inputRincian = $request->input('rincian_upah', []);
            $totalUpahHitung = 0;
            $dataRincianSimpan = [];

            if (!empty($inputRincian) && is_array($inputRincian)) {
                foreach ($inputRincian as $item) {
                    $jumlah = isset($item['jumlah']) ? (float)$item['jumlah'] : 0;
                    
                    if ($jumlah > 0) {
                        $totalUpahHitung += $jumlah;
                        $dataRincianSimpan[] = [
                            'jenis'  => $item['jenis'] ?? 'Upah Kastrasi',
                            'jumlah' => $jumlah
                        ];
                    }
                }
            }

            // Fallback total upah
            if ($totalUpahHitung == 0 && $request->has('total_upah')) {
                $totalUpahHitung = $request->input('total_upah');
            }

            // Status Biaya Lain
            $biayaLain = $validated['biaya_lain'];
            $adaBiayaLain = $biayaLain > 0 ? 'Ya' : 'Tidak';

            // 2. Simpan Data
            $kastrasi = Kastrasi::create([
                'user_id' => Auth::id(),
                'kebun_id' => $validated['kebun_id'],
                'tanggal_kastrasi' => $validated['tanggal_kastrasi'],
                'jumlah_pokok_dikastrasi' => $validated['jumlah_pokok_dikastrasi'],
                'total_upah' => $totalUpahHitung, // Hasil hitung server
                'ada_biaya_lain' => $adaBiayaLain,
                'biaya_lain' => $biayaLain,
                'rincian_upah' => count($dataRincianSimpan) > 0 ? $dataRincianSimpan : null,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Data kastrasi berhasil disimpan',
                'data' => $kastrasi
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error kastrasi: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyimpan data kastrasi'
            ], 500);
        }
    }
}
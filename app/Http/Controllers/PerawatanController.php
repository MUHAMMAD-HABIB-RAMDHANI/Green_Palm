<?php

namespace App\Http\Controllers;

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

class PerawatanController extends Controller
{
    // FUNGSI UNTUK UNIT TEST: Logika Rekomendasi Pemupukan
    public function rekomendasiPupuk($usiaBulan, $jenisTanah)
    {
        // 1. Validasi Input Usia (TC-05)
        if ($usiaBulan < 0) {
            return "Error: Usia tidak valid";
        }

        // 2. Normalisasi input string agar tidak sensitif huruf besar/kecil
        $tanahLower = strtolower(trim($jenisTanah));

        // 3. Validasi Jenis Tanah (TC-06)
        if ($tanahLower !== 'mineral' && $tanahLower !== 'gambut') {
            return "Error: Tanah tidak terdaftar";
        }

        // 4. Logika Fase Tumbuh (0-12 bulan)
        if ($usiaBulan <= 12) {
            return "Fokus Urea & ZA";
        }

        // 5. Logika Fase TBM (13-36 bulan)
        if ($usiaBulan > 12 && $usiaBulan <= 36) {
            return "NPK Rutin";
        }

        // 6. Logika Fase TM (> 36 bulan) dibedakan berdasarkan tanah
        if ($usiaBulan > 36) {
            if ($tanahLower === 'gambut') {
                return "NPK + Ekstra Cu & Zn"; // TC-04
            } else {
                return "NPK + KCl"; // TC-03
            }
        }
    }
    
    public function menu()
    {
        $user = Auth::user();
        $hasKebun = DataKebun::where('user_id', Auth::id())->exists();
        
        return view('perawatan.menu', compact('user', 'hasKebun'));
    }

    public function create($jenis, Request $request)
    {
        $validActivities = ['pemupukan', 'penunasan', 'penyemprotan', 'sanitasi', 'kastrasi'];
        
        if (!in_array(strtolower($jenis), $validActivities)) {
            abort(404, 'Jenis kegiatan tidak ditemukan.'); 
        }

        $user = Auth::user();
        $jenis_kegiatan = strtolower($jenis);
        
        $kebunList = DataKebun::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        if ($kebunList->isEmpty()) {
            return redirect()->route('kebun.create')
                ->with('error', 'Anda belum memiliki kebun. Silakan buat kebun terlebih dahulu.');
        }

        $lastData = null;
        
        // Logika Autofill / Last Data (Prioritas: Autofill -> Last ID)
        if ($request->has('autofill') && $request->get('autofill') == 'true') {
            switch ($jenis_kegiatan) {
                case 'pemupukan': $lastData = Pemupukan::where('user_id', Auth::id())->latest('tanggal_pemupukan')->first(); break;
                case 'penunasan': $lastData = Penunasan::where('user_id', Auth::id())->latest('tanggal_penunasan')->first(); break;
                case 'penyemprotan': $lastData = Penyemprotan::where('user_id', Auth::id())->latest('tanggal_penyemprotan')->first(); break;
                case 'sanitasi': $lastData = Sanitasi::where('user_id', Auth::id())->latest('tanggal_sanitasi')->first(); break;
                case 'kastrasi': $lastData = Kastrasi::where('user_id', Auth::id())->latest('tanggal_kastrasi')->first(); break;
            }
        } elseif ($request->has('last_id')) {
            $lastId = $request->get('last_id');
            switch ($jenis_kegiatan) {
                case 'pemupukan': $lastData = Pemupukan::where('id', $lastId)->where('user_id', Auth::id())->first(); break;
                case 'penunasan': $lastData = Penunasan::where('id', $lastId)->where('user_id', Auth::id())->first(); break;
                case 'penyemprotan': $lastData = Penyemprotan::where('id', $lastId)->where('user_id', Auth::id())->first(); break;
                case 'sanitasi': $lastData = Sanitasi::where('id', $lastId)->where('user_id', Auth::id())->first(); break;
                case 'kastrasi': $lastData = Kastrasi::where('id', $lastId)->where('user_id', Auth::id())->first(); break;
            }
        }

        return view('perawatan.' . $jenis_kegiatan, compact('user', 'jenis_kegiatan', 'kebunList', 'lastData'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_kegiatan' => 'required|string',
        ]);

        switch (strtolower($request->jenis_kegiatan)) {
            case 'pemupukan': return $this->storePemupukan($request);
            case 'penunasan': return $this->storePenunasan($request);
            case 'penyemprotan': return $this->storePenyemprotan($request);
            case 'sanitasi': return $this->storeSanitasi($request);
            case 'kastrasi': return $this->storeKastrasi($request);
            default: return redirect()->back()->with('error', 'Jenis kegiatan tidak dikenali.');
        }
    }

    // =========================================================================
    // [MODIFIKASI KHUSUS] Store Pemupukan dengan Rincian Upah Dinamis (Web)
    // =========================================================================
    private function storePemupukan(Request $request)
    {
        $validated = $request->validate([
            'kebun_id' => ['required', 'integer', Rule::exists('data_kebun', 'id')->where('user_id', Auth::id())],
            'tanggal_pemupukan' => 'required|date',
            'jenis_pupuk' => 'required|string|max:255',
            'total_pupuk' => 'required|numeric|min:0',
            'pupuk_per_pokok' => 'nullable|numeric|min:0',
            
            // [UBAH] Biaya Pembelian jadi REQUIRED (Wajib)
            'biaya_pembelian' => 'required|string', 
            
            // Rincian Upah Opsional (Nullable)
            'rincian_upah' => 'nullable|array',
            'rincian_upah.*.jenis' => 'nullable|string',
            'rincian_upah.*.jumlah' => 'nullable', 
        ]);

        try {
            // 1. Proses Rincian Upah (Jika User Mengisi)
            $inputRincian = $request->input('rincian_upah', []);
            $totalUpah = 0;
            $dataRincianSimpan = [];

            // Cek apakah ada inputan upah
            if (!empty($inputRincian) && is_array($inputRincian)) {
                foreach ($inputRincian as $item) {
                    $cleanJumlah = isset($item['jumlah']) ? (float)str_replace(['.', ','], '', $item['jumlah']) : 0;
                    
                    if ($cleanJumlah > 0) {
                        $totalUpah += $cleanJumlah;
                        $dataRincianSimpan[] = [
                            'jenis'  => $item['jenis'] ?? 'Biaya Operasional',
                            'jumlah' => $cleanJumlah
                        ];
                    }
                }
            }

            // 2. Bersihkan Format Rupiah pada Biaya Pembelian (Wajib Ada)
            $biayaPembelian = 0;
            if ($request->filled('biaya_pembelian')) {
                $cleanBiaya = str_replace(['.', ','], '', $request->biaya_pembelian);
                $biayaPembelian = (float) $cleanBiaya;
            }

            // 3. Simpan ke Database
            Pemupukan::create([
                'user_id' => Auth::id(),
                'kebun_id' => $validated['kebun_id'],
                'tanggal_pemupukan' => $validated['tanggal_pemupukan'],
                'jenis_pupuk' => $validated['jenis_pupuk'],
                'total_pupuk' => $validated['total_pupuk'],
                'pupuk_per_pokok' => $request->input('pupuk_per_pokok', 0),
                'biaya_pembelian' => $biayaPembelian,
                
                // Total Upah otomatis 0 jika tidak ada inputan rincian
                'total_upah' => $totalUpah,
                
                // Simpan Array Rincian (NULL jika kosong)
                'rincian_upah' => count($dataRincianSimpan) > 0 ? $dataRincianSimpan : null,
            ]);

            return redirect()->route('perawatan.riwayat')->with('success', 'Data Pemupukan berhasil disimpan!');
        } catch (\Exception $e) {
            Log::error('Error pemupukan: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    // =========================================================================
    // [UPDATE] Store Penunasan: Logika Dinamis (Sama seperti Pemupukan)
    // =========================================================================
    private function storePenunasan(Request $request)
    {
        $validated = $request->validate([
            'kebun_id' => ['required', 'integer', Rule::exists('data_kebun', 'id')->where('user_id', Auth::id())],
            'tanggal_penunasan' => 'required|date',
            'jumlah_pokok_ditunas' => 'required|integer|min:1',
            
            // Biaya Lain (Pengganti Biaya Beli di Pemupukan) - Wajib format rupiah string
            'biaya_lain' => 'required|string', 
            
            // Rincian Upah Opsional
            'rincian_upah' => 'nullable|array',
            'rincian_upah.*.jenis' => 'nullable|string',
            'rincian_upah.*.jumlah' => 'nullable', 
        ]);

        try {
            // 1. Proses Rincian Upah & Hitung Total
            $inputRincian = $request->input('rincian_upah', []);
            $totalUpah = 0;
            $dataRincianSimpan = [];

            if (!empty($inputRincian) && is_array($inputRincian)) {
                foreach ($inputRincian as $item) {
                    $cleanJumlah = isset($item['jumlah']) ? (float)str_replace(['.', ','], '', $item['jumlah']) : 0;
                    
                    if ($cleanJumlah > 0) {
                        $totalUpah += $cleanJumlah;
                        $dataRincianSimpan[] = [
                            'jenis'  => $item['jenis'] ?? 'Biaya Operasional',
                            'jumlah' => $cleanJumlah
                        ];
                    }
                }
            }

            // 2. Bersihkan Format Rupiah pada Biaya Lain
            $biayaLain = 0;
            if ($request->filled('biaya_lain')) {
                $cleanBiaya = str_replace(['.', ','], '', $request->biaya_lain);
                $biayaLain = (float) $cleanBiaya;
            }

            // Otomatis set enum ada_biaya_lain
            $adaBiayaLain = $biayaLain > 0 ? 'Ya' : 'Tidak';

            // 3. Simpan ke Database
            Penunasan::create([
                'user_id' => Auth::id(),
                'kebun_id' => $validated['kebun_id'],
                'tanggal_penunasan' => $validated['tanggal_penunasan'],
                'jumlah_pokok_ditunas' => $validated['jumlah_pokok_ditunas'],
                'ada_biaya_lain' => $adaBiayaLain,
                'biaya_lain' => $biayaLain,
                
                // Total Upah dari hasil hitungan
                'total_upah' => $totalUpah,
                
                // Simpan JSON
                'rincian_upah' => count($dataRincianSimpan) > 0 ? $dataRincianSimpan : null,
            ]);

            return redirect()->route('catatan.menu')->with('success', 'Data Penunasan berhasil disimpan!');
        } catch (\Exception $e) {
            Log::error('Error penunasan: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    private function storePenyemprotan(Request $request)
    {
        $validated = $request->validate([
            'kebun_id' => ['required', 'integer', Rule::exists('data_kebun', 'id')->where('user_id', Auth::id())],
            'tanggal_penyemprotan' => 'required|date',
            'jenis_pestisida_racun' => 'nullable|string|max:255',
            'penggunaan_pestisida' => 'required|numeric|min:0',
            'luas_lahan_disemprot' => 'required|numeric|min:0',
            
            // Biaya Lain Wajib (String Rupiah)
            'biaya_lain' => 'required|string',
            
            // Rincian Upah Opsional
            'rincian_upah' => 'nullable|array',
            'rincian_upah.*.jenis' => 'nullable|string',
            'rincian_upah.*.jumlah' => 'nullable',
        ]);

        try {
            // 1. Hitung Total Upah dari Rincian
            $inputRincian = $request->input('rincian_upah', []);
            $totalUpah = 0;
            $dataRincianSimpan = [];

            if (!empty($inputRincian) && is_array($inputRincian)) {
                foreach ($inputRincian as $item) {
                    $cleanJumlah = isset($item['jumlah']) ? (float)str_replace(['.', ','], '', $item['jumlah']) : 0;
                    
                    if ($cleanJumlah > 0) {
                        $totalUpah += $cleanJumlah;
                        $dataRincianSimpan[] = [
                            'jenis'  => $item['jenis'] ?? 'Upah Semprot',
                            'jumlah' => $cleanJumlah
                        ];
                    }
                }
            }

            // 2. Bersihkan Format Biaya Lain
            $biayaLain = 0;
            if ($request->filled('biaya_lain')) {
                $cleanBiaya = str_replace(['.', ','], '', $request->biaya_lain);
                $biayaLain = (float) $cleanBiaya;
            }

            $adaBiayaLain = $biayaLain > 0 ? 'Ya' : 'Tidak';

            // 3. Simpan Database
            Penyemprotan::create([
                'user_id' => Auth::id(),
                'kebun_id' => $validated['kebun_id'],
                'tanggal_penyemprotan' => $validated['tanggal_penyemprotan'],
                'jenis_pestisida_racun' => $validated['jenis_pestisida_racun'],
                'penggunaan_pestisida' => $validated['penggunaan_pestisida'],
                'luas_lahan_disemprot' => $validated['luas_lahan_disemprot'],
                'ada_biaya_lain' => $adaBiayaLain,
                'biaya_lain' => $biayaLain,
                
                'total_upah' => $totalUpah,
                'rincian_upah' => count($dataRincianSimpan) > 0 ? $dataRincianSimpan : null,
            ]);

            return redirect()->route('catatan.menu')->with('success', 'Data Penyemprotan berhasil disimpan!');
        } catch (\Exception $e) {
            Log::error('Error penyemprotan: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data');
        }
    }

    private function storeSanitasi(Request $request)
    {
        $validated = $request->validate([
            'kebun_id' => ['required', 'integer', Rule::exists('data_kebun', 'id')->where('user_id', Auth::id())],
            'tanggal_sanitasi' => 'required|date',
            'jumlah_pokok_disanitasi' => 'required|integer|min:1',
            
            // Biaya Lain Wajib (String Rupiah)
            'biaya_lain' => 'required|string',
            
            // Rincian Upah Opsional
            'rincian_upah' => 'nullable|array',
            'rincian_upah.*.jenis' => 'nullable|string',
            'rincian_upah.*.jumlah' => 'nullable',
        ]);

        try {
            // 1. Hitung Total Upah dari Rincian
            $inputRincian = $request->input('rincian_upah', []);
            $totalUpah = 0;
            $dataRincianSimpan = [];

            if (!empty($inputRincian) && is_array($inputRincian)) {
                foreach ($inputRincian as $item) {
                    // Bersihkan format rupiah
                    $cleanJumlah = isset($item['jumlah']) ? (float)str_replace(['.', ','], '', $item['jumlah']) : 0;
                    
                    if ($cleanJumlah > 0) {
                        $totalUpah += $cleanJumlah;
                        $dataRincianSimpan[] = [
                            'jenis'  => $item['jenis'] ?? 'Upah Sanitasi',
                            'jumlah' => $cleanJumlah
                        ];
                    }
                }
            }

            // 2. Bersihkan Format Biaya Lain
            $biayaLain = 0;
            if ($request->filled('biaya_lain')) {
                $cleanBiaya = str_replace(['.', ','], '', $request->biaya_lain);
                $biayaLain = (float) $cleanBiaya;
            }

            // Otomatis tentukan status ada_biaya_lain
            $adaBiayaLain = $biayaLain > 0 ? 'Ya' : 'Tidak';

            // 3. Simpan ke Database
            Sanitasi::create([
                'user_id' => Auth::id(),
                'kebun_id' => $validated['kebun_id'],
                'tanggal_sanitasi' => $validated['tanggal_sanitasi'],
                'jumlah_pokok_disanitasi' => $validated['jumlah_pokok_disanitasi'],
                'ada_biaya_lain' => $adaBiayaLain,
                'biaya_lain' => $biayaLain,
                
                // Total hasil hitungan
                'total_upah' => $totalUpah,
                
                // Simpan detail rincian
                'rincian_upah' => count($dataRincianSimpan) > 0 ? $dataRincianSimpan : null,
            ]);

            return redirect()->route('catatan.menu')->with('success', 'Data Sanitasi berhasil disimpan!');
        } catch (\Exception $e) {
            Log::error('Error sanitasi: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data');
        }
    }

    private function storeKastrasi(Request $request)
    {
        $validated = $request->validate([
            'kebun_id' => ['required', 'integer', Rule::exists('data_kebun', 'id')->where('user_id', Auth::id())],
            'tanggal_kastrasi' => 'required|date',
            'jumlah_pokok_dikastrasi' => 'required|integer|min:1',
            
            // Biaya Lain Wajib (String Rupiah)
            'biaya_lain' => 'required|string',
            
            // Rincian Upah Opsional
            'rincian_upah' => 'nullable|array',
            'rincian_upah.*.jenis' => 'nullable|string',
            'rincian_upah.*.jumlah' => 'nullable',
        ]);

        try {
            // 1. Hitung Total Upah dari Rincian
            $inputRincian = $request->input('rincian_upah', []);
            $totalUpah = 0;
            $dataRincianSimpan = [];

            if (!empty($inputRincian) && is_array($inputRincian)) {
                foreach ($inputRincian as $item) {
                    // Bersihkan format rupiah
                    $cleanJumlah = isset($item['jumlah']) ? (float)str_replace(['.', ','], '', $item['jumlah']) : 0;
                    
                    if ($cleanJumlah > 0) {
                        $totalUpah += $cleanJumlah;
                        $dataRincianSimpan[] = [
                            'jenis'  => $item['jenis'] ?? 'Upah Kastrasi',
                            'jumlah' => $cleanJumlah
                        ];
                    }
                }
            }

            // 2. Bersihkan Format Biaya Lain
            $biayaLain = 0;
            if ($request->filled('biaya_lain')) {
                $cleanBiaya = str_replace(['.', ','], '', $request->biaya_lain);
                $biayaLain = (float) $cleanBiaya;
            }

            // Otomatis tentukan status ada_biaya_lain
            $adaBiayaLain = $biayaLain > 0 ? 'Ya' : 'Tidak';

            // 3. Simpan ke Database
            Kastrasi::create([
                'user_id' => Auth::id(),
                'kebun_id' => $validated['kebun_id'],
                'tanggal_kastrasi' => $validated['tanggal_kastrasi'],
                'jumlah_pokok_dikastrasi' => $validated['jumlah_pokok_dikastrasi'],
                'ada_biaya_lain' => $adaBiayaLain,
                'biaya_lain' => $biayaLain,
                
                // Total hasil hitungan
                'total_upah' => $totalUpah,
                
                // Simpan detail rincian
                'rincian_upah' => count($dataRincianSimpan) > 0 ? $dataRincianSimpan : null,
            ]);

            return redirect()->route('catatan.menu')->with('success', 'Data Kastrasi berhasil disimpan!');
        } catch (\Exception $e) {
            Log::error('Error kastrasi: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data');
        }
    }

    /**
     * Menghapus data perawatan berdasarkan jenis dan ID
     */
    public function destroy($jenis, $id)
    {
        $user = Auth::user();
        $model = null;

        switch (strtolower($jenis)) {
            case 'pemupukan': $model = Pemupukan::where('id', $id)->where('user_id', $user->id)->first(); break;
            case 'penunasan': $model = Penunasan::where('id', $id)->where('user_id', $user->id)->first(); break;
            case 'penyemprotan': $model = Penyemprotan::where('id', $id)->where('user_id', $user->id)->first(); break;
            case 'sanitasi': $model = Sanitasi::where('id', $id)->where('user_id', $user->id)->first(); break;
            case 'kastrasi': $model = Kastrasi::where('id', $id)->where('user_id', $user->id)->first(); break;
            default: abort(404);
        }

        if (!$model) {
            abort(404, 'Data tidak ditemukan atau akses ditolak.');
        }

        try {
            $model->delete();
            return redirect()->back()->with('success', 'Data kegiatan berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
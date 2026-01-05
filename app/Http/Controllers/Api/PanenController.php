<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use App\Models\Panen;
use App\Models\DataKebun;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PanenController extends Controller
{
    // ==========================================
    // 1. FUNGSI STORE (PENERIMA DATA DARI ANDROID)
    // ==========================================
    public function store(Request $request)
    {
        Log::info('Sync Panen Masuk:', $request->all());

        // 1. Validasi Input
        $validator = Validator::make($request->all(), [
            'kebun_id' => 'required|integer|exists:data_kebun,id',
            'tanggal_panen' => 'required|date',
            'berat_total_tbs' => 'required|numeric|min:0',
            'harga_tbs' => 'required|numeric|min:0', // ✅ Tetap terima dari Android (untuk hitung pendapatan)
            'jumlah_tbs' => 'nullable|integer|min:0',
            'berat_brondolan' => 'nullable|numeric|min:0',
            'tanggal_panen_berikutnya' => 'nullable|date',
            'upah_panen' => 'nullable|array',
            'upah_panen.*.jenis' => 'required_with:upah_panen|string',
            'upah_panen.*.jumlah' => 'required_with:upah_panen|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            $user_id = Auth::id();
            
            // ✅ Hitung Pendapatan (berat x harga per kg)
            $pendapatan = $request->berat_total_tbs * $request->harga_tbs;

            // ✅ Proses Array Upah dengan Benar
            $totalUpah = 0;
            $biayaLainnya = [];
            
            if ($request->has('upah_panen') && is_array($request->upah_panen)) {
                foreach ($request->upah_panen as $upah) {
                    if (!empty($upah['jenis']) && isset($upah['jumlah'])) {
                        $jumlahUpah = (double)$upah['jumlah'];
                        $totalUpah += $jumlahUpah;
                        
                        $biayaLainnya[] = [
                            'jenis' => $upah['jenis'],
                            'jumlah' => $jumlahUpah
                        ];
                    }
                }
            }

            Log::info('Processed Upah:', [
                'total_upah' => $totalUpah,
                'biaya_lainnya' => $biayaLainnya
            ]);

            // ✅ Simpan/Update Data (Mencegah Duplikasi)
            $panen = Panen::updateOrCreate(
                [
                    // Kunci Pencarian (Syarat Unik)
                    'user_id' => $user_id,
                    'kebun_id' => $request->kebun_id,
                    'tanggal_panen' => $request->tanggal_panen, 
                ],
                [
                    // Data yang akan disimpan/diupdate
                    'berat_total_tbs' => $request->berat_total_tbs,
                    'jumlah_tbs' => $request->jumlah_tbs ?? 0,
                    'berat_brondolan' => $request->berat_brondolan ?? 0,
                    'tanggal_panen_berikutnya' => $request->tanggal_panen_berikutnya,
                    'pendapatan' => $pendapatan,
                    'total_upah_panen' => $totalUpah,
                    'biaya_lainnya' => !empty($biayaLainnya) ? ($biayaLainnya) : null,
                ]
            );

            DB::commit();

            Log::info('Panen berhasil disimpan:', [
                'id' => $panen->id,
                'pendapatan' => $pendapatan,
                'total_upah' => $totalUpah
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil disimpan',
                'data' => [
                    'id' => $panen->id,
                    'kebun_id' => $panen->kebun_id,
                    'tanggal_panen' => $panen->tanggal_panen,
                    'pendapatan' => $panen->pendapatan,
                    'total_upah_panen' => $panen->total_upah_panen,
                ]
            ], 200);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('DB Error: ' . $e->getMessage());
            Log::error('Stack Trace: ' . $e->getTraceAsString());
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyimpan data: ' . $e->getMessage()
            ], 500);
        }
    }

    // ==========================================
    // 2. FUNGSI INDEX (LIST DATA)
    // ==========================================
    public function index(Request $request)
    {
        $userId = Auth::id();
        
        $panens = Panen::where('user_id', $userId)
            ->with('kebun')
            ->orderBy('tanggal_panen', 'desc')
            ->get();

        $data = $panens->map(function($p) {
            // ✅ Hitung estimasi harga per kg
            $estimasiHarga = $p->berat_total_tbs > 0 
                ? ($p->pendapatan / $p->berat_total_tbs) 
                : 0;

            return [
                'id' => $p->id,
                'kebun_id' => $p->kebun_id,
                'kebun_nama' => $p->kebun->nama_kebun ?? 'Kebun Dihapus',
                'kebun_lokasi' => $p->kebun->lokasi_kebun ?? '-',
                'tanggal_panen' => $p->tanggal_panen,
                'tanggal_panen_formatted' => \Carbon\Carbon::parse($p->tanggal_panen)->format('d M Y'),
                'berat_total_tbs' => $p->berat_total_tbs,
                'jumlah_tbs' => $p->jumlah_tbs ?? 0,
                'berat_brondolan' => $p->berat_brondolan ?? 0,
                'pendapatan' => $p->pendapatan,
                'total_upah_panen' => $p->total_upah_panen ?? 0, // ✅ Penting!
                'laba_bersih' => $p->pendapatan - ($p->total_upah_panen ?? 0),
                'estimasi_harga_per_kg' => round($estimasiHarga, 2),
                'tanggal_panen_berikutnya' => $p->tanggal_panen_berikutnya,
                'created_at' => $p->created_at,
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    // ==========================================
    // 3. FUNGSI SHOW (DETAIL)
    // ==========================================
    public function show($id)
    {
        try {
            $userId = Auth::id();
            
            $panen = Panen::with('kebun:id,nama_kebun,lokasi_kebun')
                ->where('id', $id)
                ->where('user_id', $userId)
                ->first();

            if (!$panen) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data panen tidak ditemukan'
                ], 404);
            }

            $labaBersih = $panen->pendapatan - ($panen->total_upah_panen ?? 0);
            $estimasiHarga = $panen->berat_total_tbs > 0 
                ? ($panen->pendapatan / $panen->berat_total_tbs) 
                : 0;
            
            // ✅ Parse biaya_lainnya dengan aman
            $biayaLainnya = [];
            if ($panen->biaya_lainnya) {
                if (is_string($panen->biaya_lainnya)) {
                    $decoded = json_decode($panen->biaya_lainnya, true);
                    if (is_array($decoded)) {
                        $biayaLainnya = $decoded;
                    }
                } elseif (is_array($panen->biaya_lainnya)) {
                    $biayaLainnya = $panen->biaya_lainnya;
                }
            }

            $data = [
                'id' => $panen->id,
                'kebun' => [
                    'id' => $panen->kebun->id,
                    'nama' => $panen->kebun->nama_kebun,
                    'lokasi' => $panen->kebun->lokasi_kebun ?? '-'
                ],
                'tanggal_panen' => $panen->tanggal_panen,
                'tanggal_panen_formatted' => \Carbon\Carbon::parse($panen->tanggal_panen)->format('d F Y'),
                'berat_total_tbs' => $panen->berat_total_tbs,
                'jumlah_tbs' => $panen->jumlah_tbs ?? 0,
                'berat_brondolan' => $panen->berat_brondolan ?? 0,
                'pendapatan' => $panen->pendapatan,
                'total_upah_panen' => $panen->total_upah_panen ?? 0,
                'laba_bersih' => $labaBersih,
                'estimasi_harga_per_kg' => round($estimasiHarga, 2),
                'tanggal_panen_berikutnya' => $panen->tanggal_panen_berikutnya,
                'tanggal_panen_berikutnya_formatted' => $panen->tanggal_panen_berikutnya 
                    ? \Carbon\Carbon::parse($panen->tanggal_panen_berikutnya)->format('d M Y') 
                    : null,
                'biaya_lainnya' => $biayaLainnya, // ✅ Detail upah
                'created_at' => $panen->created_at,
            ];

            return response()->json([
                'status' => 'success',
                'message' => 'Detail panen berhasil diambil',
                'data' => $data
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error saat mengambil detail panen: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil detail panen',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // ==========================================
    // 4. FUNGSI LAINNYA (Tidak berubah)
    // ==========================================
    public function getKebunList()
    {
        try {
            $userId = Auth::id();
            
            $kebuns = DataKebun::where('user_id', $userId)
                ->select('id', 'nama_kebun', 'lokasi_kebun')
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'status' => 'success',
                'message' => 'Daftar kebun berhasil diambil',
                'data' => $kebuns
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error saat mengambil daftar kebun: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil daftar kebun',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $user = Auth::user();
            $panen = Panen::where('id', $id)->where('user_id', $user->id)->first();

            if (!$panen) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data tidak ditemukan atau bukan milik Anda'
                ], 404);
            }

            $panen->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Data panen berhasil dihapus'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }
}
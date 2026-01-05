<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Panen;
use App\Models\Pemupukan;
use App\Models\Penunasan;
use App\Models\Penyemprotan;
use App\Models\Sanitasi;
use App\Models\Kastrasi;
use App\Models\DataKebun;

class KeuanganApiController extends Controller
{
    /**
     * Mendapatkan data grafik keuangan per bulan
     * GET /api/keuangan/grafik
     */
    public function getGrafikKeuangan(Request $request)
    {
        $userId = Auth::id();
        $tahun = $request->input('tahun', Carbon::now()->year);
        $kebunId = $request->input('kebun_id', null);
        
        $bulanData = [];
        
        for ($bulan = 1; $bulan <= 12; $bulan++) {
            $startDate = Carbon::create($tahun, $bulan, 1)->startOfMonth();
            $endDate = Carbon::create($tahun, $bulan, 1)->endOfMonth();
            
            // Query Pendapatan
            $queryPendapatan = Panen::where('user_id', $userId)
                ->whereBetween('tanggal_panen', [$startDate, $endDate]);
            
            if ($kebunId) {
                $queryPendapatan->where('kebun_id', $kebunId);
            }
            
            $pendapatan = $queryPendapatan->sum('pendapatan');
            
            // Query Pengeluaran
            $upahPanen = Panen::where('user_id', $userId)
                ->whereBetween('tanggal_panen', [$startDate, $endDate])
                ->when($kebunId, fn($q) => $q->where('kebun_id', $kebunId))
                ->sum('total_upah_panen');

            $pengeluaranPemupukan = Pemupukan::where('user_id', $userId)
                ->whereBetween('tanggal_pemupukan', [$startDate, $endDate])
                ->when($kebunId, fn($q) => $q->where('kebun_id', $kebunId))
                ->sum('total_upah');
                
            $pengeluaranPenunasan = Penunasan::where('user_id', $userId)
                ->whereBetween('tanggal_penunasan', [$startDate, $endDate])
                ->when($kebunId, fn($q) => $q->where('kebun_id', $kebunId))
                ->sum('total_upah');
                
            $pengeluaranPenyemprotan = Penyemprotan::where('user_id', $userId)
                ->whereBetween('tanggal_penyemprotan', [$startDate, $endDate])
                ->when($kebunId, fn($q) => $q->where('kebun_id', $kebunId))
                ->sum('total_upah');
                
            $pengeluaranSanitasi = Sanitasi::where('user_id', $userId)
                ->whereBetween('tanggal_sanitasi', [$startDate, $endDate])
                ->when($kebunId, fn($q) => $q->where('kebun_id', $kebunId))
                ->sum('total_upah');
                
            $pengeluaranKastrasi = Kastrasi::where('user_id', $userId)
                ->whereBetween('tanggal_kastrasi', [$startDate, $endDate])
                ->when($kebunId, fn($q) => $q->where('kebun_id', $kebunId))
                ->sum('total_upah');

            $biayaPupuk = Pemupukan::where('user_id', $userId)
                ->whereBetween('tanggal_pemupukan', [$startDate, $endDate])
                ->when($kebunId, fn($q) => $q->where('kebun_id', $kebunId))
                ->sum('biaya_pembelian');

            $biayaLainPenunasan = Penunasan::where('user_id', $userId)
                ->whereBetween('tanggal_penunasan', [$startDate, $endDate])
                ->when($kebunId, fn($q) => $q->where('kebun_id', $kebunId))
                ->sum('biaya_lain');

            $biayaLainPenyemprotan = Penyemprotan::where('user_id', $userId)
                ->whereBetween('tanggal_penyemprotan', [$startDate, $endDate])
                ->when($kebunId, fn($q) => $q->where('kebun_id', $kebunId))
                ->sum('biaya_lain');

            $biayaLainSanitasi = Sanitasi::where('user_id', $userId)
                ->whereBetween('tanggal_sanitasi', [$startDate, $endDate])
                ->when($kebunId, fn($q) => $q->where('kebun_id', $kebunId))
                ->sum('biaya_lain');

            $biayaLainKastrasi = Kastrasi::where('user_id', $userId)
                ->whereBetween('tanggal_kastrasi', [$startDate, $endDate])
                ->when($kebunId, fn($q) => $q->where('kebun_id', $kebunId))
                ->sum('biaya_lain');
            
            $totalPengeluaran = $upahPanen + 
                                $pengeluaranPemupukan + $pengeluaranPenunasan + 
                                $pengeluaranPenyemprotan + $pengeluaranSanitasi + 
                                $pengeluaranKastrasi + $biayaPupuk + 
                                $biayaLainPenunasan + $biayaLainPenyemprotan + 
                                $biayaLainSanitasi + $biayaLainKastrasi;
            
            $bulanData[] = [
                'bulan' => Carbon::create($tahun, $bulan, 1)->format('M'),
                'bulan_angka' => $bulan,
                'pendapatan' => (float) $pendapatan,
                'pengeluaran' => (float) $totalPengeluaran,
            ];
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data grafik keuangan berhasil diambil',
            'data' => [
                'tahun' => $tahun,
                'grafik' => $bulanData
            ]
        ]);
    }

    /**
     * Mendapatkan detail laporan keuangan lengkap
     * GET /api/keuangan/laporan
     */
    public function getLaporanDetail(Request $request)
    {
        $userId = Auth::id();
        $tahun = $request->input('tahun', Carbon::now()->year);
        $kebunId = $request->input('kebun_id', null);
        
        $startDate = Carbon::create($tahun, 1, 1);
        $endDate = Carbon::create($tahun, 12, 31)->endOfDay();

        // Query Base dengan filter kebun
        $queryPendapatan = Panen::where('user_id', $userId)
            ->whereBetween('tanggal_panen', [$startDate, $endDate]);
        
        if ($kebunId) {
            $queryPendapatan->where('kebun_id', $kebunId);
        }

        $totalPendapatan = $queryPendapatan->sum('pendapatan');
        $totalBeratTBS = $queryPendapatan->sum('berat_total_tbs');
        $rataHargaTBS = $totalBeratTBS > 0 ? $totalPendapatan / $totalBeratTBS : 0;

        // Upah Panen
        $upahPanen = Panen::where('user_id', $userId)
            ->whereBetween('tanggal_panen', [$startDate, $endDate])
            ->when($kebunId, fn($q) => $q->where('kebun_id', $kebunId))
            ->sum('total_upah_panen');

        // Upah Perawatan
        $upahPerawatan = Pemupukan::where('user_id', $userId)
            ->whereBetween('tanggal_pemupukan', [$startDate, $endDate])
            ->when($kebunId, fn($q) => $q->where('kebun_id', $kebunId))
            ->sum('total_upah') +
            Penunasan::where('user_id', $userId)
            ->whereBetween('tanggal_penunasan', [$startDate, $endDate])
            ->when($kebunId, fn($q) => $q->where('kebun_id', $kebunId))
            ->sum('total_upah') +
            Penyemprotan::where('user_id', $userId)
            ->whereBetween('tanggal_penyemprotan', [$startDate, $endDate])
            ->when($kebunId, fn($q) => $q->where('kebun_id', $kebunId))
            ->sum('total_upah') +
            Sanitasi::where('user_id', $userId)
            ->whereBetween('tanggal_sanitasi', [$startDate, $endDate])
            ->when($kebunId, fn($q) => $q->where('kebun_id', $kebunId))
            ->sum('total_upah') +
            Kastrasi::where('user_id', $userId)
            ->whereBetween('tanggal_kastrasi', [$startDate, $endDate])
            ->when($kebunId, fn($q) => $q->where('kebun_id', $kebunId))
            ->sum('total_upah');

        // Biaya Pembelian Pupuk
        $biayaPembelianPupuk = Pemupukan::where('user_id', $userId)
            ->whereBetween('tanggal_pemupukan', [$startDate, $endDate])
            ->when($kebunId, fn($q) => $q->where('kebun_id', $kebunId))
            ->sum('biaya_pembelian');

        // Biaya Lainnya
        $biayaLainnya = Penunasan::where('user_id', $userId)
            ->whereBetween('tanggal_penunasan', [$startDate, $endDate])
            ->when($kebunId, fn($q) => $q->where('kebun_id', $kebunId))
            ->sum('biaya_lain') +
            Penyemprotan::where('user_id', $userId)
            ->whereBetween('tanggal_penyemprotan', [$startDate, $endDate])
            ->when($kebunId, fn($q) => $q->where('kebun_id', $kebunId))
            ->sum('biaya_lain') +
            Sanitasi::where('user_id', $userId)
            ->whereBetween('tanggal_sanitasi', [$startDate, $endDate])
            ->when($kebunId, fn($q) => $q->where('kebun_id', $kebunId))
            ->sum('biaya_lain') +
            Kastrasi::where('user_id', $userId)
            ->whereBetween('tanggal_kastrasi', [$startDate, $endDate])
            ->when($kebunId, fn($q) => $q->where('kebun_id', $kebunId))
            ->sum('biaya_lain');

        $totalPengeluaran = $upahPanen + $upahPerawatan + 
                           $biayaPembelianPupuk + $biayaLainnya;

        $pendapatanBersih = $totalPendapatan - $totalPengeluaran;

        // Dapatkan info kebun jika ada filter
        $namaKebun = 'Semua Kebun';
        if ($kebunId) {
            $kebun = DataKebun::find($kebunId);
            $namaKebun = $kebun ? $kebun->nama_kebun : 'Kebun Tidak Ditemukan';
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Laporan keuangan berhasil diambil',
            'data' => [
                'periode' => 'Jan ' . $tahun . ' - Des ' . $tahun,
                'tahun' => $tahun,
                'kebun' => $namaKebun,
                'pendapatan_bersih' => (float) $pendapatanBersih,
                'total_pendapatan' => (float) $totalPendapatan,
                'total_berat_tbs' => (float) $totalBeratTBS,
                'rata_harga_tbs' => (float) $rataHargaTBS,
                'total_pengeluaran' => (float) $totalPengeluaran,
                'upah_panen' => (float) $upahPanen,
                'upah_perawatan' => (float) $upahPerawatan,
                'biaya_pupuk' => (float) $biayaPembelianPupuk,
                'biaya_lainnya' => (float) $biayaLainnya,
            ]
        ]);
    }

    /**
     * Mendapatkan ringkasan keuangan untuk dashboard
     * GET /api/keuangan/ringkasan
     */
    public function getRingkasan(Request $request)
    {
        $userId = Auth::id();
        $tahun = Carbon::now()->year;
        $bulanIni = Carbon::now()->month;
        
        $startBulanIni = Carbon::now()->startOfMonth();
        $endBulanIni = Carbon::now()->endOfMonth();

        // Pendapatan Bulan Ini
        $pendapatanBulanIni = Panen::where('user_id', $userId)
            ->whereBetween('tanggal_panen', [$startBulanIni, $endBulanIni])
            ->sum('pendapatan');

        // Pengeluaran Bulan Ini
        $upahPanen = Panen::where('user_id', $userId)
            ->whereBetween('tanggal_panen', [$startBulanIni, $endBulanIni])
            ->sum('total_upah_panen');

        $pengeluaranPerawatan = 
            Pemupukan::where('user_id', $userId)
                ->whereBetween('tanggal_pemupukan', [$startBulanIni, $endBulanIni])
                ->sum('total_upah') +
            Penunasan::where('user_id', $userId)
                ->whereBetween('tanggal_penunasan', [$startBulanIni, $endBulanIni])
                ->sum('total_upah') +
            Penyemprotan::where('user_id', $userId)
                ->whereBetween('tanggal_penyemprotan', [$startBulanIni, $endBulanIni])
                ->sum('total_upah') +
            Sanitasi::where('user_id', $userId)
                ->whereBetween('tanggal_sanitasi', [$startBulanIni, $endBulanIni])
                ->sum('total_upah') +
            Kastrasi::where('user_id', $userId)
                ->whereBetween('tanggal_kastrasi', [$startBulanIni, $endBulanIni])
                ->sum('total_upah');

        $biayaMaterial = 
            Pemupukan::where('user_id', $userId)
                ->whereBetween('tanggal_pemupukan', [$startBulanIni, $endBulanIni])
                ->sum('biaya_pembelian') +
            Penunasan::where('user_id', $userId)
                ->whereBetween('tanggal_penunasan', [$startBulanIni, $endBulanIni])
                ->sum('biaya_lain') +
            Penyemprotan::where('user_id', $userId)
                ->whereBetween('tanggal_penyemprotan', [$startBulanIni, $endBulanIni])
                ->sum('biaya_lain') +
            Sanitasi::where('user_id', $userId)
                ->whereBetween('tanggal_sanitasi', [$startBulanIni, $endBulanIni])
                ->sum('biaya_lain') +
            Kastrasi::where('user_id', $userId)
                ->whereBetween('tanggal_kastrasi', [$startBulanIni, $endBulanIni])
                ->sum('biaya_lain');

        $pengeluaranBulanIni = $upahPanen + $pengeluaranPerawatan + $biayaMaterial;

        return response()->json([
            'status' => 'success',
            'data' => [
                'bulan' => Carbon::now()->format('F Y'),
                'pendapatan_bulan_ini' => (float) $pendapatanBulanIni,
                'pengeluaran_bulan_ini' => (float) $pengeluaranBulanIni,
                'saldo_bulan_ini' => (float) ($pendapatanBulanIni - $pengeluaranBulanIni)
            ]
        ]);
    }
}
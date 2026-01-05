<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ram;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HargaSawitController extends Controller
{
    /**
     * Mengambil data RAM (Toke) untuk list di Android
     */
    public function index()
    {
        try {
            // Ambil data RAM urutkan dari yang terbaru diupdate
            $rams = Ram::orderBy('updated_at', 'desc')->get();

            $data = $rams->map(function ($item) {
                Carbon::setLocale('id');
                
                return [
                    'id' => $item->id,
                    'nama_ram' => $item->nama_ram,
                    'lokasi_ram' => $item->lokasi_ram,
                    'formatted_harga' => 'Rp ' . number_format($item->harga_beli_tbs, 0, ',', '.'),
                    'foto_url' => $item->foto_tampak_depan ? url('storage/' . $item->foto_tampak_depan) : null,
                    
                    // ✅ TAMBAHAN: Kirim Nomor WA ke Android
                    'nomor_wa' => $item->nomor_wa,

                    // Fasilitas (Boolean)
                    'layanan_jemput' => (bool)$item->layanan_jemput_buah,
                    'timbangan_digital' => (bool)$item->timbangan_digital,
                    
                    'updated_at_human' => $item->updated_at->diffForHumans(),
                ];
            });

            return response()->json([
                'status' => 'success',
                'message' => 'Data harga sawit (RAM) berhasil diambil',
                'data' => $data
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }
    
    /**
     * Mengambil detail satu RAM
     */
    public function show($id)
    {
        try {
            $ram = Ram::with('user')->findOrFail($id); 
            Carbon::setLocale('id');

            $data = [
                'id' => $ram->id,
                'nama_ram' => $ram->nama_ram,
                'lokasi_ram' => $ram->lokasi_ram,
                
                // ✅ TAMBAHAN: Kirim Nomor WA ke Android
                'nomor_wa' => $ram->nomor_wa,

                'formatted_harga' => 'Rp ' . number_format($ram->harga_beli_tbs, 0, ',', '.'),
                'foto_url' => $ram->foto_tampak_depan ? url('storage/' . $ram->foto_tampak_depan) : null,
                
                'layanan_jemput' => (bool)$ram->layanan_jemput_buah,
                'timbangan_digital' => (bool)$ram->timbangan_digital,
                'menerima_berondolan' => (bool)$ram->menerima_berondolan,
                'tidak_ada_pengembalian' => (bool)$ram->tidak_ada_pengembalian,
                
                'updated_at_human' => $ram->updated_at->diffForHumans(),
                'pemilik' => $ram->user->username ?? 'Toke Sawit',
                'latitude' => (double)$ram->latitude,
                'longitude' => (double)$ram->longitude
            ];

            return response()->json([
                'status' => 'success',
                'message' => 'Detail RAM berhasil diambil',
                'data' => $data
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error', 
                'message' => 'Data RAM tidak ditemukan'
            ], 404);
        }
    }
}
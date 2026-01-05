<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DataKebun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KebunController extends Controller
{
    /**
     * GET /api/kebun
     * Mengambil daftar kebun milik user yang login
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $kebuns = DataKebun::where('user_id', $user->id)->get();

        return response()->json([
            'status' => 'success',
            'data' => $kebuns
        ]);
    }

    /**
     * GET /api/kebun/{id}
     * Mengambil detail satu kebun
     */
    public function show(Request $request, $id)
    {
        $user = $request->user();
        
        $kebun = DataKebun::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$kebun) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kebun tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $kebun
        ]);
    }

    /**
     * POST /api/kebun
     * Membuat kebun baru
     */
    public function store(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'nama_kebun'         => 'required|string|max:255',
            'lokasi_kebun'       => 'required|string|max:255',
            'luas_lahan'         => 'required|numeric|min:0.01',
            'jumlah_hektar'      => 'required|integer|min:1',
            'tahun_tanam'        => 'required|integer|min:1900|max:' . date('Y'),
            'tahu_jenis_bibit'   => 'required|in:Ya,Tidak',
            'nama_jenis_bibit'   => 'required_if:tahu_jenis_bibit,Ya|nullable|string|max:255',
            'jenis_tanah'        => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $kebun = DataKebun::create([
                'user_id'           => $user->id,
                'nama_kebun'        => $request->nama_kebun,
                'lokasi_kebun'      => $request->lokasi_kebun,
                'luas_lahan'        => $request->luas_lahan,
                'jumlah_hektar'     => $request->jumlah_hektar,
                'tahun_tanam'       => $request->tahun_tanam,
                'tahu_jenis_bibit'  => $request->tahu_jenis_bibit === 'Ya' ? 1 : 0,
                'jenis_bibit_nama'  => $request->tahu_jenis_bibit === 'Ya' ? $request->nama_jenis_bibit : null,
                'jenis_tanah'       => $request->jenis_tanah,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Kebun berhasil ditambahkan',
                'data' => $kebun
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyimpan data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * PUT /api/kebun/{id}
     * Memperbarui data kebun
     */
    public function update(Request $request, $id)
    {
        $user = $request->user();

        $kebun = DataKebun::where('id', $id)->where('user_id', $user->id)->first();

        if (!$kebun) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kebun tidak ditemukan atau bukan milik Anda'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nama_kebun'         => 'required|string|max:255',
            'lokasi_kebun'       => 'required|string|max:255',
            'luas_lahan'         => 'required|numeric|min:0.01',
            'jumlah_hektar'      => 'required|integer|min:1',
            'tahun_tanam'        => 'required|integer|min:1900|max:' . date('Y'),
            'tahu_jenis_bibit'   => 'required|in:Ya,Tidak',
            'nama_jenis_bibit'   => 'required_if:tahu_jenis_bibit,Ya|nullable|string|max:255',
            'jenis_tanah'        => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $kebun->update([
                'nama_kebun'        => $request->nama_kebun,
                'lokasi_kebun'      => $request->lokasi_kebun,
                'luas_lahan'        => $request->luas_lahan,
                'jumlah_hektar'     => $request->jumlah_hektar,
                'tahun_tanam'       => $request->tahun_tanam,
                'tahu_jenis_bibit'  => $request->tahu_jenis_bibit === 'Ya' ? 1 : 0,
                'jenis_bibit_nama'  => $request->tahu_jenis_bibit === 'Ya' ? $request->nama_jenis_bibit : null,
                'jenis_tanah'       => $request->jenis_tanah,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Kebun berhasil diperbarui',
                'data' => $kebun
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memperbarui data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * DELETE /api/kebun/{id}
     * Menghapus data kebun
     */
    public function destroy(Request $request, $id)
    {
        $user = $request->user();

        $kebun = DataKebun::where('id', $id)->where('user_id', $user->id)->first();

        if (!$kebun) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kebun tidak ditemukan atau bukan milik Anda'
            ], 404);
        }

        try {
            $kebun->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Data kebun berhasil dihapus'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }
}
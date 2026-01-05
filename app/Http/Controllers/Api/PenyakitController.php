<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Penyakit;
use App\Models\Hama;
use Illuminate\Http\Request;

class PenyakitController extends Controller
{
    /**
     * Mengambil semua data penyakit
     */
    public function getPenyakit()
    {
        try {
            $penyakit = Penyakit::orderBy('name', 'asc')->get();

            $data = $penyakit->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'latin_name' => $item->latin_name,
                    'image_url' => $item->image_url, // Accessor dari model
                    'description' => $item->description,
                    'solution' => $item->solution,
                    'created_at' => $item->created_at->toISOString(),
                ];
            });

            return response()->json([
                'status' => 'success',
                'message' => 'Data penyakit berhasil diambil',
                'data' => $data
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data penyakit: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    /**
     * Mengambil detail penyakit berdasarkan ID
     */
    public function getPenyakitDetail($id)
    {
        try {
            $penyakit = Penyakit::findOrFail($id);

            $data = [
                'id' => $penyakit->id,
                'name' => $penyakit->name,
                'latin_name' => $penyakit->latin_name,
                'image_url' => $penyakit->image_url,
                'description' => $penyakit->description,
                'solution' => $penyakit->solution,
                'created_at' => $penyakit->created_at->toISOString(),
            ];

            return response()->json([
                'status' => 'success',
                'message' => 'Detail penyakit berhasil diambil',
                'data' => $data
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Penyakit tidak ditemukan',
                'data' => null
            ], 404);
        }
    }

    /**
     * Mengambil semua data hama
     */
    public function getHama()
    {
        try {
            $hama = Hama::orderBy('name', 'asc')->get();

            $data = $hama->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'latin_name' => $item->latin_name,
                    'image_url' => $item->image_url, // Accessor dari model
                    'description' => $item->description,
                    'solution' => $item->solution,
                    'created_at' => $item->created_at->toISOString(),
                ];
            });

            return response()->json([
                'status' => 'success',
                'message' => 'Data hama berhasil diambil',
                'data' => $data
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data hama: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    /**
     * Mengambil detail hama berdasarkan ID
     */
    public function getHamaDetail($id)
    {
        try {
            $hama = Hama::findOrFail($id);

            $data = [
                'id' => $hama->id,
                'name' => $hama->name,
                'latin_name' => $hama->latin_name,
                'image_url' => $hama->image_url,
                'description' => $hama->description,
                'solution' => $hama->solution,
                'created_at' => $hama->created_at->toISOString(),
            ];

            return response()->json([
                'status' => 'success',
                'message' => 'Detail hama berhasil diambil',
                'data' => $data
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Hama tidak ditemukan',
                'data' => null
            ], 404);
        }
    }
}
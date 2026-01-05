<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EducationVideo;
use Illuminate\Http\Request;

class VideoEdukasiController extends Controller
{
    /**
     * Mengambil semua video edukasi untuk ditampilkan di Android
     */
    public function index()
    {
        try {
            // Ambil semua video, urutkan dari terbaru
            $videos = EducationVideo::orderBy('created_at', 'desc')->get();

            // Transform data untuk menambahkan thumbnail
            $data = $videos->map(function ($video) {
                return [
                    'id' => $video->id,
                    'title' => $video->title,
                    'url' => $video->url,
                    'thumbnail' => $video->thumbnail, // Accessor dari model
                    'created_at' => $video->created_at->toISOString(),
                    'updated_at' => $video->updated_at->toISOString(),
                ];
            });

            return response()->json([
                'status' => 'success',
                'message' => 'Data video edukasi berhasil diambil',
                'data' => $data
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data video: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }
}
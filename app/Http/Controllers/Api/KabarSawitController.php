<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KabarSawit;

class KabarSawitController extends Controller
{
    public function index(Request $request)
    {
        try {
            // 1. Inisialisasi Query
            $query = KabarSawit::query();

            // 2. Filter Pencarian (Search)
            if ($request->has('q') && !empty($request->q)) {
                $search = $request->q;
                $query->where('title', 'like', '%' . $search . '%');
            }

            // 3. Filter Kategori
            if ($request->has('category') && !empty($request->category)) {
                $category = $request->category;
                if ($category !== 'Semua') {
                    $query->where('category', $category);
                }
            }

            // 4. Urutkan (Terbaru & Populer)
            // Jika tidak ada pencarian, tampilkan yang populer atau terbaru
            $query->orderBy('published_at', 'desc');

            // 5. Ambil Data
            $news = $query->get();

            // 6. Format Data (Penting: Tambahkan image_url)
            // Kita perlu memanggil accessor 'image_url' agar muncul di JSON
            $news->transform(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'url'   => $item->url,
                    'category' => $item->category,
                    'image_url' => $item->image_url, // Mengambil dari Accessor Model
                    'is_popular' => (bool) $item->is_popular,
                    'published_at' => $item->published_at,
                    'created_at' => $item->created_at,
                ];
            });

            // 7. Return JSON
            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil diambil',
                'data' => $news
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Server Error: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }
}
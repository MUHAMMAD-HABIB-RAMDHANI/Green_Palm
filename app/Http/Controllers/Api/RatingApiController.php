<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RatingApiController extends Controller
{
    /**
     * Get user's rating history
     */
    public function index()
    {
        try {
            $userId = Auth::id();
            
            $ratings = Rating::where('user_id', $userId)
                            ->latest()
                            ->get();

            return response()->json([
                'status' => 'success',
                'message' => 'Riwayat rating berhasil diambil',
                'data' => $ratings
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil riwayat rating: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get latest user rating
     */
    public function getLatest()
    {
        try {
            $userId = Auth::id();
            
            $latestRating = Rating::where('user_id', $userId)
                                 ->latest()
                                 ->first();

            return response()->json([
                'status' => 'success',
                'message' => $latestRating ? 'Rating terakhir ditemukan' : 'Belum ada rating',
                'data' => $latestRating
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil rating terakhir: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Submit new rating
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'rating' => 'required|integer|min:1|max:5',
                'comment' => 'nullable|string|max:500',
            ], [
                'rating.required' => 'Rating wajib diisi',
                'rating.integer' => 'Rating harus berupa angka',
                'rating.min' => 'Rating minimal 1 bintang',
                'rating.max' => 'Rating maksimal 5 bintang',
                'comment.max' => 'Komentar maksimal 500 karakter',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = Auth::user();

            $rating = Rating::create([
                'user_id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Terima kasih atas rating Anda! Feedback Anda sangat berarti bagi kami.',
                'data' => $rating
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyimpan rating: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check if user has submitted rating
     */
    public function checkStatus()
    {
        try {
            $userId = Auth::id();
            
            $hasRated = Rating::where('user_id', $userId)->exists();
            $ratingCount = Rating::where('user_id', $userId)->count();

            return response()->json([
                'status' => 'success',
                'data' => [
                    'has_rated' => $hasRated,
                    'rating_count' => $ratingCount
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memeriksa status rating: ' . $e->getMessage()
            ], 500);
        }
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RatingController extends Controller
{
    /**
     * Show rating form
     */
    public function create()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();
        
        // Cek apakah user sudah pernah memberikan rating
        $existingRating = Rating::where('user_id', $user->id)->latest()->first();

        return view('dashboard.rating', [
            'user' => $user,
            'existingRating' => $existingRating,
        ]);
    }

    /**
     * Store rating
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();

        // Validasi
        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ], [
            'rating.required' => 'Pilih rating terlebih dahulu',
            'rating.integer' => 'Rating harus berupa angka',
            'rating.min' => 'Rating minimal 1 bintang',
            'rating.max' => 'Rating maksimal 5 bintang',
            'comment.max' => 'Komentar maksimal 500 karakter',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Simpan rating baru
            Rating::create([
                'user_id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]);

            return redirect()
                ->route('dashboard.profil')
                ->with('success', '⭐ Terima kasih atas rating Anda! Feedback Anda sangat berarti bagi kami.');
                
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', '✗ Gagal menyimpan rating: ' . $e->getMessage());
        }
    }

    /**
     * Show user's rating history (optional feature)
     */
    public function history()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $ratings = Rating::where('user_id', $user->id)
                        ->latest()
                        ->paginate(10);

        return view('dashboard.rating-history', [
            'user' => $user,
            'ratings' => $ratings,
        ]);
    }
}
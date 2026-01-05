<?php

namespace App\Http\Controllers;

use App\Models\HelpRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
// 1. IMPORT INTERFACE MIDDLEWARE LARAVEL 11
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class HelpController extends Controller implements HasMiddleware // 2. IMPLEMENTS INTERFACE
{
    /**
     * GANTI CONSTRUCTOR DENGAN STATIC FUNCTION MIDDLEWARE
     * Ini adalah cara baru di Laravel 11+
     */
    public static function middleware(): array
    {
        return [
            new Middleware('auth'),
        ];
    }

    /**
     * Helper privat untuk mengambil data umum (User & Tanggal)
     */
    private function getCommonData()
    {
        $user = Auth::user();
        $tanggal = Carbon::now()->translatedFormat('l, d F Y');
        
        return compact('user', 'tanggal');
    }

    /**
     * Show help form (Halaman Utama Bantuan & Form)
     */
    public function create()
    {
        $commonData = $this->getCommonData();
        
        // Ambil riwayat bantuan user (5 terakhir)
        $helpHistory = HelpRequest::where('user_id', Auth::id())
                                  ->latest()
                                  ->take(5)
                                  ->get();

        return view('dashboard.bantuan', array_merge($commonData, [
            'helpHistory' => $helpHistory,
        ]));
    }

    /**
     * Store help request (Proses Simpan Pesan)
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // Validasi Input
        $validator = Validator::make($request->all(), [
            'message' => 'required|string|min:10|max:1000',
        ], [
            'message.required' => 'Pesan keluhan wajib diisi',
            'message.min' => 'Pesan minimal 10 karakter',
            'message.max' => 'Pesan maksimal 1000 karakter',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Simpan help request baru ke database
            HelpRequest::create([
                'user_id' => $user->id,
                'username' => $user->username,
                'message' => $request->message,
                'status' => 'pending',
            ]);

            return redirect()
                ->route('dashboard.help.create') 
                ->with('success', '✓ Pesan bantuan berhasil dikirim! Tim kami akan segera merespons.');
                
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', '✗ Gagal mengirim pesan: ' . $e->getMessage());
        }
    }

    /**
     * Show user's help history
     */
    public function history()
    {
        $commonData = $this->getCommonData();

        $helpRequests = HelpRequest::where('user_id', Auth::id())
                                   ->latest()
                                   ->paginate(10);

        return view('dashboard.bantuan-history', array_merge($commonData, [
            'helpRequests' => $helpRequests,
        ]));
    }

    /**
     * Show detail of specific help request
     */
    public function show($id)
    {
        $commonData = $this->getCommonData();

        // Security Check
        $helpRequest = HelpRequest::where('id', $id)
                                  ->where('user_id', Auth::id())
                                  ->firstOrFail();

        return view('dashboard.bantuan-detail', array_merge($commonData, [
            'helpRequest' => $helpRequest,
        ]));
    }
}
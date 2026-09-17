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
    // FUNGSI UNTUK UNIT TEST: Klasifikasi Otomatis Pesan Bantuan
    public function klasifikasiPesanBantuan($pesanBantuan)
    {
        // TC-05: Validasi jika pesan kosong
        if (trim($pesanBantuan) === "" || $pesanBantuan === null) {
            return "Error: Pesan kosong";
        }

        // Ubah ke huruf kecil semua agar pengecekan lebih mudah
        $pesanLower = strtolower($pesanBantuan);

        // TC-01: Cek Masalah Teknis (Prioritas Tinggi)
        if (str_contains($pesanLower, 'error') || str_contains($pesanLower, 'gagal') || str_contains($pesanLower, 'rusak')) {
            return "Tinggi: Teknis";
        }

        // TC-03: Cek Masalah Keuangan (Prioritas Sedang)
        if (str_contains($pesanLower, 'harga') || str_contains($pesanLower, 'bayar') || str_contains($pesanLower, 'uang')) {
            return "Sedang: Keuangan";
        }

        // TC-02: Cek Panduan Pengguna (Prioritas Rendah)
        if (str_contains($pesanLower, 'cara') || str_contains($pesanLower, 'bagaimana') || str_contains($pesanLower, 'panduan')) {
            return "Rendah: Panduan";
        }

        // TC-04: Jika tidak masuk kategori mana pun
        return "Normal: Umum";
    }
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
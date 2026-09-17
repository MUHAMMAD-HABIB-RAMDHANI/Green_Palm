<?php

namespace App\Http\Controllers;

use App\Models\EducationVideo;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // FUNGSI UNTUK UNIT TEST: Logika Status Verifikasi Akun Baru
    public function verifikasiPendaftaran($usia, $adaKtp, $adaBuktiLahan)
    {
        // TC-05: Validasi data usia (misal input minus atau nol)
        if ($usia <= 0) {
            return "Error: Data tidak valid";
        }

        // TC-02: Pengecekan umur (Prioritas pertama)
        if ($usia < 18) {
            return "Ditolak: Usia di bawah ketentuan";
        }

        // TC-03: Pengecekan KTP (Wajib)
        if ($adaKtp === false) {
            return "Ditolak: KTP wajib dilampirkan";
        }

        // TC-04: Pengecekan Bukti Lahan (Bisa ditangguhkan/pending)
        if ($adaKtp === true && $adaBuktiLahan === false) {
            return "Ditangguhkan: Menunggu bukti lahan";
        }

        // TC-01: Jika semua kondisi lolos
        return "Akun Disetujui";
    }
    
    public function storeVideo(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'url'   => 'required|url' // Validasi harus format URL valid
        ]);

        EducationVideo::create([
            'title' => $request->title,
            'url'   => $request->url
        ]);

        return redirect()->back()->with('success', 'Video berhasil ditambahkan!');
    }

    public function storePrice(Request $request)
    {
        $request->validate([
            'region' => 'required|string|max:255',
            'price'  => 'required|numeric',
            'date'   => 'required|date',
        ]);

        \App\Models\PalmPrice::create([
            'region' => $request->region,
            'price'  => $request->price,
            'updated_at_date' => $request->date,
        ]);

        return back()->with('success', 'Data harga sawit berhasil ditambahkan!');
    }
}

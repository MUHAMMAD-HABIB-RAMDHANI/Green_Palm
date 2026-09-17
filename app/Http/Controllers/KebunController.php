<?php

namespace App\Http\Controllers;

use App\Models\DataKebun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\Pemupukan;
use App\Models\Penunasan;
use App\Models\Penyemprotan;
use App\Models\Sanitasi;
use App\Models\Kastrasi;

class KebunController extends Controller
{
    public function estimasiPotensiPanen($luasHektar, $usiaTahun)
    {
        // Validasi Input
        if ($luasHektar <= 0 || $usiaTahun <= 0) {
            return "Error: Data tidak valid";
        }

        // Hitung berdasarkan usia pohon
        if ($usiaTahun < 3) {
            return "Potensi: 0 Ton (Belum Menghasilkan)";
        } 
        
        if ($usiaTahun >= 3 && $usiaTahun <= 8) {
            $potensi = $luasHektar * 1.5;
            return "Potensi: " . $potensi . " Ton";
        } 
        
        if ($usiaTahun >= 9 && $usiaTahun <= 15) {
            $potensi = $luasHektar * 2.5;
            return "Potensi: " . $potensi . " Ton";
        } 
        
        // Usia di atas 15 tahun
        $potensi = $luasHektar * 1.8;
        return "Potensi: " . $potensi . " Ton";
    }
    /**
     * 1. Tampilkan DAFTAR SEMUA KEBUN (Menu Utama)
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }
        
        $user = Auth::user();
        $userId = Auth::id();

        $kebuns = DataKebun::where('user_id', $userId)->get();

        return view('kebun.index', compact('kebuns', 'user'));
    }

    /**
     * 2. Tampilkan DETAIL SATU KEBUN (show.blade.php)
     */
    public function show($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $user = Auth::user();
        $userId = Auth::id();

        $kebun = DataKebun::find($id);

        if (!$kebun || $kebun->user_id != $userId) {
            return redirect()->route('kebun.daftar')->with('error', 'Kebun tidak ditemukan');
        }

        return view('kebun.show', compact('kebun', 'user'));
    }

    /**
     * 3. Tampilkan FORM EDIT (edit.blade.php)
     */
    public function edit($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $user = Auth::user();
        $userId = Auth::id();

        $kebun = DataKebun::find($id);
        
        if (!$kebun || $kebun->user_id != $userId) {
            return redirect()->route('kebun.daftar')->with('error', 'Kebun tidak ditemukan');
        }

        return view('kebun.edit', compact('kebun', 'user'));
    }

    /**
     * 4. PROSES UPDATE
     */
    public function update(Request $request, $id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $userId = Auth::id();
        $kebun = DataKebun::find($id);
        
        if (!$kebun || $kebun->user_id != $userId) {
            return redirect()->route('kebun.daftar')->with('error', 'Kebun tidak ditemukan');
        }

        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'luas_lahan' => 'required|numeric|min:0.01',
            'jumlah_hektar' => 'required|integer|min:1',
            'tahun_tanam' => 'required|integer|min:1900|max:' . date('Y'),
            'tahu_jenis_bibit' => 'required|in:Ya,Tidak',
            'nama_jenis_bibit' => 'required_if:tahu_jenis_bibit,Ya|nullable|string|max:255',
            'jenis_tanah' => 'nullable|string|in:Mineral Berpasir,Mineral Volkanik,Gambut',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $kebun->update([
            'nama_kebun' => $request->nama,
            'lokasi_kebun' => $request->lokasi,
            'luas_lahan' => $request->luas_lahan,
            'jumlah_hektar' => $request->jumlah_hektar,
            'tahun_tanam' => $request->tahun_tanam,
            'tahu_jenis_bibit' => $request->tahu_jenis_bibit === 'Ya' ? 1 : 0,
            'jenis_bibit_nama' => $request->tahu_jenis_bibit === 'Ya' ? $request->nama_jenis_bibit : null,
            'jenis_tanah' => $request->jenis_tanah,
        ]);

        return redirect()->route('kebun.show', $id)->with('success', 'Data kebun berhasil diperbarui');
    }

    /**
     * 5. Tampilkan FORM CREATE
     */
    public function create()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $user = Auth::user();
        
        return view('kebun.create', compact('user'));
    }

    /**
     * 6. PROSES STORE (Simpan Kebun Baru)
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $userId = Auth::id();

        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'luas_lahan' => 'required|numeric|min:0.01',
            'jumlah_hektar' => 'required|integer|min:1',
            'tahun_tanam' => 'required|integer|min:1900|max:' . date('Y'),
            'tahu_jenis_bibit' => 'required|in:Ya,Tidak',
            'nama_jenis_bibit' => 'required_if:tahu_jenis_bibit,Ya|nullable|string|max:255',
            'jenis_tanah' => 'nullable|string|in:Mineral Berpasir,Mineral Volkanik,Gambut',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DataKebun::create([
                'user_id' => $userId,
                'nama_kebun' => $request->nama,
                'lokasi_kebun' => $request->lokasi,
                'luas_lahan' => $request->luas_lahan,
                'jumlah_hektar' => $request->jumlah_hektar,
                'tahun_tanam' => $request->tahun_tanam,
                'tahu_jenis_bibit' => $request->tahu_jenis_bibit === 'Ya' ? 1 : 0,
                'jenis_bibit_nama' => $request->tahu_jenis_bibit === 'Ya' ? $request->nama_jenis_bibit : null,
                'jenis_tanah' => $request->jenis_tanah,
            ]);

            return redirect()->route('kebun.daftar')->with('success', 'Kebun berhasil ditambahkan');
            
        } catch (\Exception $e) {
            Log::error('Error saat menyimpan kebun: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan kebun: ' . $e->getMessage());
        }
    }

    /**
     * 7. RIWAYAT PER KEBUN
     */
    public function riwayatPerawatan($id)
    {
        // Pastikan user ada
        $user = Auth::user();
        if (!$user) return redirect()->route('login');

        $kebun = DataKebun::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();
        
        $pemupukan = Pemupukan::where('kebun_id', $id)->where('user_id', $user->id)->orderBy('tanggal_pemupukan', 'desc')->get();
        $penunasan = Penunasan::where('kebun_id', $id)->where('user_id', $user->id)->orderBy('tanggal_penunasan', 'desc')->get();
        $penyemprotan = Penyemprotan::where('kebun_id', $id)->where('user_id', $user->id)->orderBy('tanggal_penyemprotan', 'desc')->get();
        $sanitasi = Sanitasi::where('kebun_id', $id)->where('user_id', $user->id)->orderBy('tanggal_sanitasi', 'desc')->get();
        $kastrasi = Kastrasi::where('kebun_id', $id)->where('user_id', $user->id)->orderBy('tanggal_kastrasi', 'desc')->get();
        
        $allActivities = collect();
        
        foreach ($pemupukan as $item) $allActivities->push(['type' => 'pemupukan', 'date' => $item->tanggal_pemupukan, 'data' => $item]);
        foreach ($penunasan as $item) $allActivities->push(['type' => 'penunasan', 'date' => $item->tanggal_penunasan, 'data' => $item]);
        foreach ($penyemprotan as $item) $allActivities->push(['type' => 'penyemprotan', 'date' => $item->tanggal_penyemprotan, 'data' => $item]);
        foreach ($sanitasi as $item) $allActivities->push(['type' => 'sanitasi', 'date' => $item->tanggal_sanitasi, 'data' => $item]);
        foreach ($kastrasi as $item) $allActivities->push(['type' => 'kastrasi', 'date' => $item->tanggal_kastrasi, 'data' => $item]);
        
        $allActivities = $allActivities->sortByDesc('date');
        
        return view('kebun.riwayat-perawatan', compact(
            'user',
            'kebun',
            'pemupukan',
            'penunasan',
            'penyemprotan',
            'sanitasi',
            'kastrasi',
            'allActivities'
        ));
    }

    /**
     * 8. SEMUA RIWAYAT (GABUNGAN)
     */
    public function semuaRiwayat()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        $userId = $user->id;
        
        $kebun = DataKebun::where('user_id', $userId)->first();

        if (!$kebun) {
            return redirect()->route('kebun.create')->with('error', 'Buat kebun terlebih dahulu.');
        }

        $pemupukan = Pemupukan::where('user_id', $userId)->orderBy('tanggal_pemupukan', 'desc')->get();
        $penunasan = Penunasan::where('user_id', $userId)->orderBy('tanggal_penunasan', 'desc')->get();
        $penyemprotan = Penyemprotan::where('user_id', $userId)->orderBy('tanggal_penyemprotan', 'desc')->get();
        $sanitasi = Sanitasi::where('user_id', $userId)->orderBy('tanggal_sanitasi', 'desc')->get();
        $kastrasi = Kastrasi::where('user_id', $userId)->orderBy('tanggal_kastrasi', 'desc')->get();

        $allActivities = collect();
        foreach ($pemupukan as $item) $allActivities->push(['type' => 'pemupukan', 'date' => $item->tanggal_pemupukan, 'data' => $item]);
        foreach ($penunasan as $item) $allActivities->push(['type' => 'penunasan', 'date' => $item->tanggal_penunasan, 'data' => $item]);
        foreach ($penyemprotan as $item) $allActivities->push(['type' => 'penyemprotan', 'date' => $item->tanggal_penyemprotan, 'data' => $item]);
        foreach ($sanitasi as $item) $allActivities->push(['type' => 'sanitasi', 'date' => $item->tanggal_sanitasi, 'data' => $item]);
        foreach ($kastrasi as $item) $allActivities->push(['type' => 'kastrasi', 'date' => $item->tanggal_kastrasi, 'data' => $item]);

        $allActivities = $allActivities->sortByDesc('date');

        $kebun->nama_kebun = "Semua Kebun Anda";
        $kebun->lokasi_kebun = "Gabungan Data";

        return view('kebun.riwayat-perawatan', compact(
            'user',
            'kebun', 
            'pemupukan', 
            'penunasan', 
            'penyemprotan', 
            'sanitasi', 
            'kastrasi', 
            'allActivities'
        ));
    }

    /**
     * 9. HAPUS KEBUN
     */
    public function destroy($id)
    {
        $kebun = DataKebun::findOrFail($id);

        if ($kebun->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $kebun->delete();
            
            return redirect()->route('kebun.daftar')
                ->with('success', 'Data kebun berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
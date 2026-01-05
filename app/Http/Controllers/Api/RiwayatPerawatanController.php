<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

// Import Model
use App\Models\DataKebun;
use App\Models\Pemupukan;
use App\Models\Penunasan;
use App\Models\Penyemprotan;
use App\Models\Sanitasi;
use App\Models\Kastrasi;

class RiwayatPerawatanController extends Controller
{
    /**
     * Helper: Format data agar Flat (Datar) untuk Android
     */
    private function formatActivity($item, $type, $dateField)
    {
        $data = $item->toArray();
        $data['type'] = $type;
        $data['date'] = $item->$dateField;

        if (isset($item->kebun)) {
            $data['nama_kebun'] = $item->kebun->nama_kebun;
        } else {
            $data['nama_kebun'] = 'Kebun Tidak Ditemukan';
        }

        // Casting ke tipe data yang benar untuk Android
        $data['total_upah'] = (double)$item->total_upah;
        $data['biaya_lain'] = (double)($item->biaya_lain ?? 0);
        
        // [BARU] Pastikan rincian_upah dikirim (Laravel otomatis cast ke Array jika di model sudah di-cast)
        $data['rincian_upah'] = $item->rincian_upah; 

        // Khusus Pemupukan
        if ($type == 'pemupukan') {
            $data['biaya_pembelian'] = (double)($item->biaya_pembelian ?? 0);
            $data['total_pupuk'] = (double)$item->total_pupuk;
        } 
        // Khusus Penyemprotan
        else if ($type == 'penyemprotan') {
            $data['penggunaan_pestisida'] = (double)$item->penggunaan_pestisida;
            $data['luas_lahan_disemprot'] = (double)$item->luas_lahan_disemprot;
        }

        return $data;
    }

    /**
     * GET /api/catatan/riwayat-semua
     */
    public function semuaRiwayat(Request $request)
    {
        $user = $request->user();
        
        // [OPTIMASI] Gunakan with('kebun') untuk mencegah N+1 Query
        $pemupukan = Pemupukan::with('kebun')->where('user_id', $user->id)->orderBy('tanggal_pemupukan', 'desc')->get();
        $penunasan = Penunasan::with('kebun')->where('user_id', $user->id)->orderBy('tanggal_penunasan', 'desc')->get();
        $penyemprotan = Penyemprotan::with('kebun')->where('user_id', $user->id)->orderBy('tanggal_penyemprotan', 'desc')->get();
        $sanitasi = Sanitasi::with('kebun')->where('user_id', $user->id)->orderBy('tanggal_sanitasi', 'desc')->get();
        $kastrasi = Kastrasi::with('kebun')->where('user_id', $user->id)->orderBy('tanggal_kastrasi', 'desc')->get();

        $allActivities = collect();
        foreach ($pemupukan as $item) $allActivities->push($this->formatActivity($item, 'pemupukan', 'tanggal_pemupukan'));
        foreach ($penunasan as $item) $allActivities->push($this->formatActivity($item, 'penunasan', 'tanggal_penunasan'));
        foreach ($penyemprotan as $item) $allActivities->push($this->formatActivity($item, 'penyemprotan', 'tanggal_penyemprotan'));
        foreach ($sanitasi as $item) $allActivities->push($this->formatActivity($item, 'sanitasi', 'tanggal_sanitasi'));
        foreach ($kastrasi as $item) $allActivities->push($this->formatActivity($item, 'kastrasi', 'tanggal_kastrasi'));

        $sorted = $allActivities->sortByDesc('date')->values();

        return response()->json([
            'status' => 'success',
            'data' => $sorted
        ]);
    }

    /**
     * GET /api/catatan/{kebun_id}/riwayat
     */
    public function riwayatPerKebun(Request $request, $id)
    {
        $user = $request->user();

        // Validasi Kebun
        $kebun = DataKebun::where('id', $id)->where('user_id', $user->id)->first();
        if (!$kebun) {
            return response()->json(['status' => 'error', 'message' => 'Kebun tidak ditemukan'], 404);
        }

        // [OPTIMASI] Gunakan with('kebun')
        $pemupukan = Pemupukan::with('kebun')->where('kebun_id', $id)->orderBy('tanggal_pemupukan', 'desc')->get();
        $penunasan = Penunasan::with('kebun')->where('kebun_id', $id)->orderBy('tanggal_penunasan', 'desc')->get();
        $penyemprotan = Penyemprotan::with('kebun')->where('kebun_id', $id)->orderBy('tanggal_penyemprotan', 'desc')->get();
        $sanitasi = Sanitasi::with('kebun')->where('kebun_id', $id)->orderBy('tanggal_sanitasi', 'desc')->get();
        $kastrasi = Kastrasi::with('kebun')->where('kebun_id', $id)->orderBy('tanggal_kastrasi', 'desc')->get();

        $allActivities = collect();

        foreach ($pemupukan as $item) $allActivities->push($this->formatActivity($item, 'pemupukan', 'tanggal_pemupukan'));
        foreach ($penunasan as $item) $allActivities->push($this->formatActivity($item, 'penunasan', 'tanggal_penunasan'));
        foreach ($penyemprotan as $item) $allActivities->push($this->formatActivity($item, 'penyemprotan', 'tanggal_penyemprotan'));
        foreach ($sanitasi as $item) $allActivities->push($this->formatActivity($item, 'sanitasi', 'tanggal_sanitasi'));
        foreach ($kastrasi as $item) $allActivities->push($this->formatActivity($item, 'kastrasi', 'tanggal_kastrasi'));

        $sorted = $allActivities->sortByDesc('date')->values();

        return response()->json([
            'status' => 'success',
            'data' => $sorted
        ]);
    }

    /**
     * DELETE (Tidak berubah, sudah aman)
     */
    public function destroy(Request $request, $jenis, $id)
    {
        $user = $request->user();
        $model = null;

        switch (strtolower($jenis)) {
            case 'pemupukan': $model = Pemupukan::where('id', $id)->where('user_id', $user->id)->first(); break;
            case 'penunasan': $model = Penunasan::where('id', $id)->where('user_id', $user->id)->first(); break;
            case 'penyemprotan': $model = Penyemprotan::where('id', $id)->where('user_id', $user->id)->first(); break;
            case 'sanitasi': $model = Sanitasi::where('id', $id)->where('user_id', $user->id)->first(); break;
            case 'kastrasi': $model = Kastrasi::where('id', $id)->where('user_id', $user->id)->first(); break;
            default: return response()->json(['status' => 'error', 'message' => 'Jenis kegiatan tidak valid'], 400);
        }

        if (!$model) {
            return response()->json(['status' => 'error', 'message' => 'Data tidak ditemukan'], 404);
        }

        try {
            $model->delete();
            return response()->json(['status' => 'success', 'message' => 'Data kegiatan berhasil dihapus'], 200);
        } catch (\Exception $e) {
            Log::error("Error menghapus riwayat ($jenis): " . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Gagal menghapus data'], 500);
        }
    }
}
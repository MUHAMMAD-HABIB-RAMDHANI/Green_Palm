<?php

namespace App\Http\Controllers;

use App\Models\Ram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class TokeController extends Controller
{
    /**
     * Menampilkan Beranda Toke
     */
    public function beranda()
    {
        $user = Auth::user();
        
        // Ambil data RAM milik user yang sedang login
        $ram = Ram::where('user_id', $user->id)->first();

        // Data Tanggal & Waktu (Menggunakan Carbon)
        Carbon::setLocale('id');
        $tanggal = Carbon::now()->isoFormat('dddd, D MMMM Y');

        // Simulasi Data Cuaca
        $kondisi_cuaca = 'Cerah Berawan'; 
        $suhu = 32;

        return view('toke.beranda', [
            'user' => $user,
            'ram' => $ram,
            'tanggal' => $tanggal,
            'kondisi_cuaca' => $kondisi_cuaca,
            'suhu' => $suhu
        ]);
    }

    /**
     * Menampilkan Form Edit/Tambah RAM
     */
    public function editRam()
    {
        $user = Auth::user();
        
        // Cek apakah toke sudah punya RAM atau belum
        $ram = Ram::where('user_id', $user->id)->first();

        return view('toke.edit-ram', [
            'user' => $user,
            'ram' => $ram
        ]);
    }

    /**
     * Menyimpan atau Mengupdate Data RAM
     */
    public function storeRam(Request $request)
    {
        $user = Auth::user();

        // 1. Validasi Input
        $validator = Validator::make($request->all(), [
            'nama_ram' => 'required|string|max:255',
            'nomor_wa' => 'required|numeric|digits_between:10,15', // ✅ Validasi Nomor WA
            'lokasi_ram' => 'required|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'harga_beli_tbs' => 'required|numeric|min:0',
            'foto_tampak_depan' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Max 2MB
        ], [
            'nama_ram.required' => 'Nama RAM wajib diisi.',
            'nomor_wa.required' => 'Nomor WhatsApp wajib diisi.',
            'nomor_wa.numeric' => 'Nomor WhatsApp harus berupa angka.',
            'nomor_wa.digits_between' => 'Nomor WhatsApp harus antara 10 s/d 15 digit.',
            'lokasi_ram.required' => 'Lokasi RAM wajib diisi.',
            'harga_beli_tbs.required' => 'Harga beli TBS wajib diisi.',
            'foto_tampak_depan.image' => 'File harus berupa gambar.',
            'foto_tampak_depan.max' => 'Ukuran foto maksimal 2MB.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Cek apakah data RAM sudah ada
            $ram = Ram::where('user_id', $user->id)->first();
            
            // Persiapkan data untuk disimpan
            $data = [
                'nama_ram' => $request->nama_ram,
                'nomor_wa' => $request->nomor_wa, // ✅ Simpan Nomor WA
                'lokasi_ram' => $request->lokasi_ram,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'harga_beli_tbs' => $request->harga_beli_tbs,
                // Checkbox html mengirim value "on" jika dicentang, atau null jika tidak.
                'layanan_jemput_buah' => $request->has('layanan_jemput_buah'),
                'timbangan_digital' => $request->has('timbangan_digital'),
                'menerima_berondolan' => $request->has('menerima_berondolan'),
                'tidak_ada_pengembalian' => $request->has('tidak_ada_pengembalian'),
            ];

            // 2. Handle Upload Foto
            if ($request->hasFile('foto_tampak_depan')) {
                // Hapus foto lama jika ada dan sedang mode update
                if ($ram && $ram->foto_tampak_depan && Storage::disk('public')->exists($ram->foto_tampak_depan)) {
                    Storage::disk('public')->delete($ram->foto_tampak_depan);
                }

                // Simpan foto baru
                $file = $request->file('foto_tampak_depan');
                $filename = 'ram_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('foto_rams', $filename, 'public');
                
                $data['foto_tampak_depan'] = $path;
            }

            // 3. Simpan ke Database (Update jika ada, Create jika baru)
            Ram::updateOrCreate(
                ['user_id' => $user->id], // Kunci pencarian
                $data // Data yang disimpan/diupdate
            );

            return redirect()->route('toke.beranda')
                ->with('success', '✓ Data RAM berhasil disimpan!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', '✗ Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan Halaman Profil
     */
    public function profil()
    {
        $user = Auth::user();
        return view('toke.profile', [
            'user' => $user
        ]);
    }

    /**
     * Menampilkan Form Edit Profil
     */
    public function editProfile()
    {
        $user = Auth::user();
        return view('toke.edit-profil', [
            'user' => $user
        ]);
    }

    /**
     * Memproses Update Profil
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        // Validasi input
        $validator = Validator::make($request->all(), [
            'username'   => 'required|string|max:100|unique:users,username,' . $user->id,
            'phone'      => 'nullable|string|max:15',
            'gender'     => 'nullable|in:Laki-laki,Perempuan',
            'birth_date' => 'nullable|date|before:today',
        ], [
            'username.required' => 'Nama pengguna wajib diisi',
            'username.unique'   => 'Nama pengguna sudah digunakan orang lain',
            'birth_date.before' => 'Tanggal lahir tidak valid',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Update data user
            $user->update([
                'username'   => $request->username,
                'phone'      => $request->phone,
                'gender'     => $request->gender,
                'birth_date' => $request->birth_date,
            ]);

            return redirect()
                ->route('toke.profil')
                ->with('success', '✓ Profil berhasil diperbarui!');
                
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', '✗ Gagal memperbarui profil: ' . $e->getMessage());
        }
    }
}
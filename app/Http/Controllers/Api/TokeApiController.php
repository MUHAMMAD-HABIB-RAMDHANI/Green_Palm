<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ram;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class TokeApiController extends Controller
{
    /**
     * Get Dashboard Data untuk Toke
     */
    public function getDashboard(Request $request)
    {
        try {
            $user = $request->user();
            
            // Validasi apakah user adalah Toke
            if ($user->email !== 'tokesawit@gmail.com') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Akses ditolak. Hanya untuk akun Toke.'
                ], 403);
            }

            // Ambil data RAM milik toke
            $ram = Ram::where('user_id', $user->id)->first();

            // Data tanggal & waktu
            Carbon::setLocale('id');
            $tanggal = Carbon::now()->isoFormat('dddd, D MMMM Y');
            
            // Simulasi cuaca (nanti bisa diganti dengan API cuaca)
            $kondisi_cuaca = 'Cerah Berawan';
            $suhu = 32;

            return response()->json([
                'status' => 'success',
                'message' => 'Data dashboard berhasil diambil',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'username' => $user->username,
                        'email' => $user->email,
                        'phone' => $user->phone,
                        'profile_picture' => $user->profile_picture 
                            ? asset('storage/' . $user->profile_picture) 
                            : null
                    ],
                    'ram' => $ram ? [
                        'id' => $ram->id,
                        'nama_ram' => $ram->nama_ram,
                        'nomor_wa' => $ram->nomor_wa, // ✅ TAMBAHAN
                        'lokasi_ram' => $ram->lokasi_ram,
                        'latitude' => $ram->latitude,
                        'longitude' => $ram->longitude,
                        'harga_beli_tbs' => $ram->harga_beli_tbs,
                        'formatted_harga' => 'Rp ' . number_format($ram->harga_beli_tbs, 0, ',', '.'),
                        'layanan_jemput_buah' => (bool) $ram->layanan_jemput_buah,
                        'timbangan_digital' => (bool) $ram->timbangan_digital,
                        'menerima_berondolan' => (bool) $ram->menerima_berondolan,
                        'tidak_ada_pengembalian' => (bool) $ram->tidak_ada_pengembalian,
                        'foto_tampak_depan' => $ram->foto_tampak_depan 
                            ? asset('storage/' . $ram->foto_tampak_depan) 
                            : null
                    ] : null,
                    'info' => [
                        'tanggal' => $tanggal,
                        'kondisi_cuaca' => $kondisi_cuaca,
                        'suhu' => $suhu
                    ]
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data dashboard',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get Data RAM
     */
    public function getRam(Request $request)
    {
        try {
            $user = $request->user();
            
            if ($user->email !== 'tokesawit@gmail.com') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Akses ditolak'
                ], 403);
            }

            $ram = Ram::where('user_id', $user->id)->first();

            if (!$ram) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Belum ada data RAM',
                    'data' => null
                ], 200);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Data RAM berhasil diambil',
                'data' => [
                    'id' => $ram->id,
                    'nama_ram' => $ram->nama_ram,
                    'nomor_wa' => $ram->nomor_wa, // ✅ TAMBAHAN
                    'lokasi_ram' => $ram->lokasi_ram,
                    'latitude' => $ram->latitude,
                    'longitude' => $ram->longitude,
                    'harga_beli_tbs' => $ram->harga_beli_tbs,
                    'formatted_harga' => 'Rp ' . number_format($ram->harga_beli_tbs, 0, ',', '.'),
                    'layanan_jemput_buah' => (bool) $ram->layanan_jemput_buah,
                    'timbangan_digital' => (bool) $ram->timbangan_digital,
                    'menerima_berondolan' => (bool) $ram->menerima_berondolan,
                    'tidak_ada_pengembalian' => (bool) $ram->tidak_ada_pengembalian,
                    'foto_tampak_depan' => $ram->foto_tampak_depan 
                        ? asset('storage/' . $ram->foto_tampak_depan) 
                        : null,
                    'created_at' => $ram->created_at->toIso8601String(),
                    'updated_at' => $ram->updated_at->toIso8601String()
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data RAM',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store/Update Data RAM
     */
    public function storeRam(Request $request)
    {
        try {
            $user = $request->user();
            
            if ($user->email !== 'tokesawit@gmail.com') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Akses ditolak'
                ], 403);
            }

            // Validasi Input
            $validator = Validator::make($request->all(), [
                'nama_ram' => 'required|string|max:255',
                'nomor_wa' => 'required|numeric|digits_between:10,15', // ✅ TAMBAHAN: Validasi Nomor WA
                'lokasi_ram' => 'required|string|max:255',
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
                'harga_beli_tbs' => 'required|numeric|min:0',
                'layanan_jemput_buah' => 'nullable|boolean',
                'timbangan_digital' => 'nullable|boolean',
                'menerima_berondolan' => 'nullable|boolean',
                'tidak_ada_pengembalian' => 'nullable|boolean',
                'foto_tampak_depan' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
            ], [
                'nama_ram.required' => 'Nama RAM wajib diisi',
                'nomor_wa.required' => 'Nomor WhatsApp wajib diisi', // ✅ Custom Message
                'nomor_wa.numeric' => 'Nomor WhatsApp harus berupa angka',
                'nomor_wa.digits_between' => 'Nomor WhatsApp harus antara 10 s/d 15 digit',
                'lokasi_ram.required' => 'Lokasi RAM wajib diisi',
                'harga_beli_tbs.required' => 'Harga beli TBS wajib diisi',
                'foto_tampak_depan.image' => 'File harus berupa gambar',
                'foto_tampak_depan.max' => 'Ukuran foto maksimal 2MB'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Cek apakah sudah ada data RAM
            $ram = Ram::where('user_id', $user->id)->first();

            // Prepare data
            $data = [
                'nama_ram' => $request->nama_ram,
                'nomor_wa' => $request->nomor_wa, // ✅ TAMBAHAN: Simpan Nomor WA
                'lokasi_ram' => $request->lokasi_ram,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'harga_beli_tbs' => $request->harga_beli_tbs,
                'layanan_jemput_buah' => $request->boolean('layanan_jemput_buah', false),
                'timbangan_digital' => $request->boolean('timbangan_digital', false),
                'menerima_berondolan' => $request->boolean('menerima_berondolan', false),
                'tidak_ada_pengembalian' => $request->boolean('tidak_ada_pengembalian', false)
            ];

            // Handle Upload Foto
            if ($request->hasFile('foto_tampak_depan')) {
                // Hapus foto lama jika ada
                if ($ram && $ram->foto_tampak_depan && Storage::disk('public')->exists($ram->foto_tampak_depan)) {
                    Storage::disk('public')->delete($ram->foto_tampak_depan);
                }

                // Simpan foto baru
                $file = $request->file('foto_tampak_depan');
                $filename = 'ram_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('foto_rams', $filename, 'public');
                
                $data['foto_tampak_depan'] = $path;
            }

            // Simpan ke database
            $ram = Ram::updateOrCreate(
                ['user_id' => $user->id],
                $data
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Data RAM berhasil disimpan',
                'data' => [
                    'id' => $ram->id,
                    'nama_ram' => $ram->nama_ram,
                    'nomor_wa' => $ram->nomor_wa, // ✅ TAMBAHAN
                    'lokasi_ram' => $ram->lokasi_ram,
                    'latitude' => $ram->latitude,
                    'longitude' => $ram->longitude,
                    'harga_beli_tbs' => $ram->harga_beli_tbs,
                    'formatted_harga' => 'Rp ' . number_format($ram->harga_beli_tbs, 0, ',', '.'),
                    'layanan_jemput_buah' => (bool) $ram->layanan_jemput_buah,
                    'timbangan_digital' => (bool) $ram->timbangan_digital,
                    'menerima_berondolan' => (bool) $ram->menerima_berondolan,
                    'tidak_ada_pengembalian' => (bool) $ram->tidak_ada_pengembalian,
                    'foto_tampak_depan' => $ram->foto_tampak_depan 
                        ? asset('storage/' . $ram->foto_tampak_depan) 
                        : null
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyimpan data RAM',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update Profile Toke
     */
    public function updateProfile(Request $request)
    {
        try {
            $user = $request->user();
            
            if ($user->email !== 'tokesawit@gmail.com') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Akses ditolak'
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'username' => 'required|string|max:100|unique:users,username,' . $user->id,
                'phone' => 'nullable|string|max:15',
                'gender' => 'nullable|in:Laki-laki,Perempuan',
                'birth_date' => 'nullable|date|before:today'
            ], [
                'username.required' => 'Nama pengguna wajib diisi',
                'username.unique' => 'Nama pengguna sudah digunakan',
                'birth_date.before' => 'Tanggal lahir tidak valid'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user->update([
                'username' => $request->username,
                'phone' => $request->phone,
                'gender' => $request->gender,
                'birth_date' => $request->birth_date
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Profil berhasil diperbarui',
                'data' => [
                    'id' => $user->id,
                    'username' => $user->username,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'gender' => $user->gender,
                    'birth_date' => $user->birth_date,
                    'profile_picture' => $user->profile_picture 
                        ? asset('storage/' . $user->profile_picture) 
                        : null
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memperbarui profil',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update Profile Photo Toke
     */
    public function updateProfilePhoto(Request $request)
    {
        try {
            $user = $request->user();
            
            if ($user->email !== 'tokesawit@gmail.com') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Akses ditolak'
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'profile_picture' => 'required|image|mimes:jpeg,png,jpg|max:2048'
            ], [
                'profile_picture.required' => 'Foto profil wajib diisi',
                'profile_picture.image' => 'File harus berupa gambar',
                'profile_picture.max' => 'Ukuran foto maksimal 2MB'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Hapus foto lama jika ada
            if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
                Storage::disk('public')->delete($user->profile_picture);
            }

            // Upload foto baru
            $file = $request->file('profile_picture');
            $filename = 'profile_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('profile_pictures', $filename, 'public');

            $user->update(['profile_picture' => $path]);

            return response()->json([
                'status' => 'success',
                'message' => 'Foto profil berhasil diperbarui',
                'data' => [
                    'profile_picture' => asset('storage/' . $path)
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memperbarui foto profil',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete Profile Photo Toke
     */
    public function deleteProfilePhoto(Request $request)
    {
        try {
            $user = $request->user();
            
            if ($user->email !== 'tokesawit@gmail.com') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Akses ditolak'
                ], 403);
            }

            if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
                Storage::disk('public')->delete($user->profile_picture);
            }

            $user->update(['profile_picture' => null]);

            return response()->json([
                'status' => 'success',
                'message' => 'Foto profil berhasil dihapus'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus foto profil',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
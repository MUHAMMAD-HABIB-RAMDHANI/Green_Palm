<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    /**
     * Show Profile Page
     */
    public function profil()
    {
        $user = Auth::user();
        $kebun = \App\Models\DataKebun::where('user_id', $user->id)->first();
        
        return view('dashboard.profil', [
            'user' => $user,
            'kebun' => $kebun,
        ]);
    }

    /**
     * Show edit profile form
     */
    public function showEditForm(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();

        if ($request->ajax() || $request->wantsJson()) {
            return view('dashboard.edit-profil-content', compact('user'));
        }
        
        return view('dashboard.edit-profil', compact('user'));
    }

    /**
     * Update profile data
     */
    public function updateProfile(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Sesi berakhir. Silakan login.');
        }

        $user = Auth::user();

        // Validasi
        $validator = Validator::make($request->all(), [
            'username'   => 'required|string|max:100|unique:users,username,' . $user->id,
            'email'      => 'nullable|email|unique:users,email,' . $user->id,
            'phone'      => 'nullable|string|max:20',
            'gender'     => 'nullable|in:Laki-laki,Perempuan',
            'birth_date' => 'nullable|date|before:today',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // ✅ Update menggunakan Eloquent
            $user->update([
                'username'   => $request->username,
                'email'      => $request->email ?? $user->email,
                'phone'      => $request->phone,
                'gender'     => $request->gender,
                'birth_date' => $request->birth_date,
            ]);

            return redirect()
                ->route('dashboard.profil')
                ->with('success', '✓ Profil berhasil diperbarui!');
                
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', '✗ Gagal memperbarui profil: ' . $e->getMessage());
        }
    }

    /**
     * Upload profile picture
     */
    public function uploadPhoto(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'profile_picture.required' => 'Pilih foto terlebih dahulu',
            'profile_picture.image'    => 'File harus berupa gambar',
            'profile_picture.mimes'    => 'Format foto harus JPG, JPEG, atau PNG',
            'profile_picture.max'      => 'Ukuran foto maksimal 2MB',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        try {
            // Hapus foto lama jika ada
            if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
                Storage::disk('public')->delete($user->profile_picture);
            }

            // Upload foto baru
            $file = $request->file('profile_picture');
            $filename = 'profile_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('profile_pictures/' . $user->id, $filename, 'public');

            // Update database
            $user->update([
                'profile_picture' => $path,
            ]);

            return back()->with('success', 'Foto profil berhasil diupload!');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal upload foto: ' . $e->getMessage());
        }
    }

    /**
     * Delete profile picture
     */
    public function deletePhoto()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if (!$user->profile_picture) {
            return back()->with('error', '✗ Tidak ada foto untuk dihapus!');
        }

        try {
            // Hapus file fisik
            if (Storage::disk('public')->exists($user->profile_picture)) {
                Storage::disk('public')->delete($user->profile_picture);
            }

            // Update database
            $user->update([
                'profile_picture' => null,
            ]);

            return back()->with('success', 'Foto profil berhasil dihapus!');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus foto: ' . $e->getMessage());
        }
    }

    /**
     * Change password
     */
    public function changePassword(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password'     => 'required|min:6|confirmed',
        ], [
            'current_password.required' => 'Password lama wajib diisi',
            'new_password.required'     => 'Password baru wajib diisi',
            'new_password.min'          => 'Password baru minimal 6 karakter',
            'new_password.confirmed'    => 'Konfirmasi password tidak cocok',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        try {
            // Cek password lama
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->with('error', '✗ Password lama tidak sesuai!');
            }

            // Cek apakah password baru sama dengan password lama
            if (Hash::check($request->new_password, $user->password)) {
                return back()->with('error', '✗ Password baru tidak boleh sama dengan password lama!');
            }

            // Update password
            $user->update([
                'password' => Hash::make($request->new_password),
            ]);

            return back()->with('success', '✓ Password berhasil diubah!');
            
        } catch (\Exception $e) {
            return back()->with('error', '✗ Gagal mengubah password: ' . $e->getMessage());
        }
    }
}
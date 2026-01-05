<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ProfileController extends Controller
{
    /**
     * GET /api/profile  (auth:sanctum)
     */
    public function show(Request $request)
    {
        $u = $request->user();

        return response()->json([
            'status' => 'success',
            'user'   => $this->serializeUser($u),
        ]);
    }

    /**
     * PUT /api/profile  (auth:sanctum)
     */
    public function update(Request $request)
    {
        $u = $request->user();

        $data = $request->validate([
            'username'   => ['required','string','max:255', Rule::unique('users','username')->ignore($u->id)],
            'phone'      => ['nullable','string','max:20'],
            'gender'     => ['nullable', Rule::in(['Laki-laki','Perempuan'])],
            'birth_date' => ['nullable','date'],
        ]);

        // Normalisasi gender
        if (array_key_exists('gender', $data) && $data['gender'] !== null) {
            $g = strtolower(trim($data['gender']));
            if (in_array($g, ['p', 'perempuan'])) {
                $data['gender'] = 'Perempuan';
            } elseif (in_array($g, ['l', 'laki-laki', 'laki laki'])) {
                $data['gender'] = 'Laki-laki';
            }
        }

        // Normalisasi tanggal
        if (!empty($data['birth_date'])) {
            $data['birth_date'] = Carbon::parse($data['birth_date'])->toDateString();
        }

        $u->fill($data)->save();

        return response()->json([
            'status'  => 'success',
            'message' => 'Profil diperbarui',
            'user'    => $this->serializeUser($u),
        ]);
    }

    /**
     * POST /api/profile/photo  (auth:sanctum)
     */
    public function updatePhoto(Request $request)
    {
        \Log::info('Upload request received', [
            'user_id' => $request->user()->id,
            'has_file' => $request->hasFile('profile_picture'),
            'file_size' => $request->hasFile('profile_picture') ? $request->file('profile_picture')->getSize() : 0
        ]);

        $request->validate([
            'profile_picture' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:5120'],
        ]);

        $user = $request->user();

        try {
            // 1. Hapus foto lama jika ada
            if (!empty($user->profile_picture)) {
                $oldPath = $this->extractStoragePath($user->profile_picture);
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }

            // 2. Proses File Baru
            $file = $request->file('profile_picture');
            $extension = $file->getClientOriginalExtension();
            $filename = 'profile_' . $user->id . '_' . time() . '.' . $extension;
            $folderPath = 'profile_pictures/' . $user->id;

            // 3. Simpan File
            $path = $file->storeAs($folderPath, $filename, 'public');

            // 4. Update Database
            $user->profile_picture = $path;
            $user->save();

            return response()->json([
                'status'  => 'success',
                'message' => 'Foto profil berhasil diperbarui',
                'user'    => $this->serializeUser($user),
            ], 200);

        } catch (\Exception $e) {
            \Log::error('Upload failed', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal upload: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * DELETE /api/profile/photo  (auth:sanctum)
     */
    public function deletePhoto(Request $request)
    {
        $user = $request->user();

        if (!empty($user->profile_picture)) {
            $path = $this->extractStoragePath($user->profile_picture);
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }

        $user->profile_picture = null;
        $user->save();

        return response()->json([
            'status'  => 'success',
            'message' => 'Foto profil berhasil dihapus',
            'user'    => $this->serializeUser($user),
        ]);
    }

    /**
     * GET /api/profile/photo/base64
     */
    public function getPhotoBase64(Request $request)
    {
        $user = $request->user();
        
        if (empty($user->profile_picture)) {
            return response()->json(['status' => 'error', 'message' => 'No profile picture'], 404);
        }
        
        $path = $this->extractStoragePath($user->profile_picture);
        
        if (!Storage::disk('public')->exists($path)) {
            return response()->json(['status' => 'error', 'message' => 'Photo file not found'], 404);
        }
        
        try {
            $fileContents = Storage::disk('public')->get($path);
            $mimeType = Storage::disk('public')->mimeType($path);
            $base64 = base64_encode($fileContents);
            
            return response()->json([
                'status' => 'success',
                'data' => "data:$mimeType;base64,$base64"
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Error processing image'], 500);
        }
    }

    /**
     * Serialisasi User -> array
     * PERBAIKAN UTAMA ADA DI SINI
     */
    private function serializeUser($u): array
    {
        $birth = null;
        if (!empty($u->birth_date)) {
            try {
                $birth = $u->birth_date instanceof Carbon
                    ? $u->birth_date->toDateString()
                    : Carbon::parse($u->birth_date)->toDateString();
            } catch (\Throwable $e) {
                $birth = null;
            }
        }

        // Handle Profile Picture URL
        $hasProfilePicture = !empty($u->profile_picture) && 
                             Storage::disk('public')->exists($this->extractStoragePath($u->profile_picture));

        $profilePictureUrl = null;
        if ($hasProfilePicture) {
            $path = $this->extractStoragePath($u->profile_picture);
            $baseUrl = rtrim(config('app.url'), '/');
            $profilePictureUrl = $baseUrl . '/storage/' . $path;
        }

        // === LOGIKA PREMIUM ===
        // 1. Cek status premium via Model (menangani auto-expire)
        $isPremium = $u->isPremium(); 
        
        // 2. Jika tidak premium (atau sudah expired), set plan jadi null
        $premiumPlan = $isPremium ? $u->premium_plan : null;

        // 3. Format tanggal expired
        $premiumUntil = $u->premium_until 
            ? ( $u->premium_until instanceof Carbon 
                ? $u->premium_until->format('Y-m-d H:i:s') 
                : Carbon::parse($u->premium_until)->format('Y-m-d H:i:s') 
              )
            : null;

        return [
            'id'                  => $u->id,
            'username'            => $u->username ?? $u->name,
            'email'               => $u->email,
            'phone'               => $u->phone ?? null,
            'gender'              => $u->gender ?? null,
            'birth_date'          => $birth,
            'profile_picture'     => $profilePictureUrl,
            'has_profile_picture' => $hasProfilePicture,
            
            // Field Tambahan untuk Android
            'is_premium'          => $isPremium,
            'premium_plan'        => $premiumPlan, // 'monthly' atau 'yearly'
            'premium_until'       => $premiumUntil,

            'updated_at'          => optional($u->updated_at)->toISOString(),
            'created_at'          => optional($u->created_at)->toISOString(),
        ];
    }

    /**
     * Helper: Ekstrak path storage dari URL lengkap atau path relatif
     */
    private function extractStoragePath($pathOrUrl)
    {
        if (empty($pathOrUrl)) return '';

        if (filter_var($pathOrUrl, FILTER_VALIDATE_URL)) {
            $parts = explode('/storage/', $pathOrUrl);
            return isset($parts[1]) ? $parts[1] : $pathOrUrl;
        }

        return $pathOrUrl;
    }
}
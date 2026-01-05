<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\User;
use App\Models\ForgotOtp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;
use Carbon\Carbon;

class ForgotPasswordOtpController extends Controller
{
    public function show()
    {
        return view('auth.forgot');
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        $ttl = 120; // 2 menit
        $now = Carbon::now();

        // 🔎 Cek OTP terakhir di tabel forgot_otps
        $existing = ForgotOtp::where('email', $request->email)
            ->orderByDesc('created_at')
            ->first();

        if ($existing && $existing->expires_at && $existing->expires_at->gt($now)) {
            // OTP lama masih aktif
            $remaining = $existing->expires_at->diffInSeconds($now);

            $msg = "Kode OTP masih aktif. Silakan gunakan kode yang sudah dikirim ke email Anda.";
            if ($remaining > 0) {
                $msg .= " (Sisa waktu: {$remaining} detik)";
            }

            if ($request->wantsJson()) {
                return response()->json([
                    'status'  => 'OK',
                    'message' => $msg,
                    'ttl'     => $remaining,
                ], 200);
            }

            return back()->with('info', $msg);
        }

        // 🔐 Buat OTP baru
        $code      = (string) random_int(100000, 999999);
        $expiresAt = $now->copy()->addSeconds($ttl);

        // Simpan / update OTP di tabel forgot_otps
        ForgotOtp::updateOrCreate(
            ['email' => $request->email],
            [
                'otp'        => $code,
                'created_at' => $now,        // kalau di DB sudah default CURRENT_TIMESTAMP, boleh dihapus
                'expires_at' => $expiresAt,  // 🔴 PENTING: pakai expires_at
            ]
        );

        // Kirim email
        Mail::to($request->email)->send(new OtpMail($code));

        if ($request->wantsJson()) {
            return response()->json([
                'status'  => 'OK',
                'message' => 'Kode OTP baru telah dikirim ke email Anda.',
                'ttl'     => $ttl,
            ], 200);
        }

        return back()->with('success', 'Kode OTP baru telah dikirim ke email Anda.');
    }

    public function resetWithOtp(Request $request)
    {
        $validated = $request->validate([
            'email'    => ['required', 'email', 'exists:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'otp'      => ['required', 'digits:6'],
        ]);

        $now = Carbon::now();

        // 🔎 Ambil OTP terakhir dari tabel untuk email ini
        $otpRecord = ForgotOtp::where('email', $validated['email'])
            ->orderByDesc('created_at')
            ->first();

        // Tidak ada OTP
        if (! $otpRecord) {
            if ($request->wantsJson()) {
                return response()->json([
                    'status'  => 'ERROR',
                    'message' => 'Kode OTP tidak ditemukan.',
                ], 422);
            }

            return back()->withInput()->with('error', 'Kode OTP tidak ditemukan.');
        }

        // Kalau expires_at null atau sudah lewat → kadaluarsa
        if (! $otpRecord->expires_at || $otpRecord->expires_at->lte($now)) {
            if ($request->wantsJson()) {
                return response()->json([
                    'status'  => 'ERROR',
                    'message' => 'Kode OTP kedaluwarsa.',
                ], 422);
            }

            return back()->withInput()->with('error', 'Kode OTP kedaluwarsa.');
        }

        // Kode OTP tidak cocok
        if ($otpRecord->otp !== $validated['otp']) {
            if ($request->wantsJson()) {
                return response()->json([
                    'status'  => 'ERROR',
                    'message' => 'Kode OTP salah.',
                ], 422);
            }

            return back()->withInput()->with('error', 'Kode OTP salah.');
        }

        // ✅ Update password di tabel users
        $user = User::where('email', $validated['email'])->first();
        $user->password = Hash::make($validated['password']);
        $user->save();

        // Nonaktifkan OTP ini (bisa di-expire sekarang)
        $otpRecord->expires_at = $now;
        $otpRecord->save();
        // atau kalau mau benar-benar dihapus:
        // $otpRecord->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'status'  => 'OK',
                'message' => 'Password berhasil diubah dan disimpan ke database.'
            ], 200);
        }

        return redirect()
            ->route('login')
            ->with('success', 'Password berhasil diubah. Silakan login dengan password baru.');
    }
}

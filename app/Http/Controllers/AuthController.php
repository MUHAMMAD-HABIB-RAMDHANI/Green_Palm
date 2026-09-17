<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // FUNGSI UNTUK UNIT TEST: Deteksi Kekuatan Password
    public function cekKekuatanPassword($password)
    {
        // TC-05: Validasi Kosong
        if ($password === null || $password === "") {
            return "Error: Password kosong";
        }

        // TC-04: Validasi Panjang Karakter Minimal
        if (strlen($password) < 8) {
            return "Error: Terlalu Pendek";
        }

        // Mengecek kandungan di dalam string (Regex)
        $adaHurufBesar = preg_match('/[A-Z]/', $password);
        $adaHurufKecil = preg_match('/[a-z]/', $password);
        $adaAngka      = preg_match('/[0-9]/', $password);
        $adaSimbol     = preg_match('/[^a-zA-Z0-9]/', $password); // Selain huruf & angka

        // TC-01: Paling ketat (Kuat)
        if ($adaHurufBesar && $adaHurufKecil && $adaAngka && $adaSimbol) {
            return "Kuat";
        }

        // TC-02: Sedang
        if ($adaHurufBesar && $adaHurufKecil && $adaAngka) {
            return "Sedang";
        }

        // TC-03: Sisanya dianggap lemah
        return "Lemah";
    }
    /**
     * ============================
     * 1. REGISTER
     * ============================
     */
    public function showRegister()
    {
        // Jika sudah login, redirect berdasarkan role
        if (Auth::check()) {
            return $this->redirectBasedOnRole();
        }
        
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:100|unique:users,username',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'username.required' => 'Username wajib diisi',
            'username.unique' => 'Username sudah digunakan',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 6 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // ✅ Buat user menggunakan Eloquent Model
        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // ✅ Auto login setelah register menggunakan Auth
        // Auth::login($user);

        // ✅ PERBAIKAN: Gunakan redirectBasedOnRole() agar konsisten
        return redirect()->route('login')
            ->with('success', 'Registrasi berhasil! Silakan login dengan akun baru Anda.');
    }

    /**
     * ============================
     * 2. LOGIN
     * ============================
     */
    public function showLogin()
    {
        // Jika sudah login, redirect berdasarkan role
        if (Auth::check()) {
            return $this->redirectBasedOnRole();
        }
        
        return view('auth.login');
    }

    public function login(Request $request)
{
    // 1. Validasi input dasar (harus diisi dan format email benar)
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ], [
        'email.required' => 'Email wajib diisi',
        'email.email' => 'Format email tidak valid',
        'password.required' => 'Password wajib diisi',
    ]);

    // 2. Cari user berdasarkan email
    $user = \App\Models\User::where('email', $request->email)->first();

    // 3. Cek apakah user ditemukan
    if (!$user) {
        return back()->withErrors([
            'email' => 'Email tidak terdaftar.',
        ])->onlyInput('email');
    }

    // 4. Cek apakah password cocok
    if (!Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->filled('remember'))) {
        return back()->withErrors([
            'password' => 'Password salah.',
        ])->onlyInput('email');
    }

    // 5. Jika semua benar
    $request->session()->regenerate();
    return $this->redirectBasedOnRole()->with('success', 'Login berhasil!');
}

    /**
     * ============================
     * 3. LOGOUT
     * ============================
     */
    public function logout(Request $request)
    {
        // ✅ Gunakan Auth::logout()
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Berhasil logout!');
    }

    /**
     * ============================
     * 4. LUPA PASSWORD (Show Form)
     * ============================
     */
    public function showForgot()
    {
        return view('auth.forgot');
    }

    /**
     * ============================
     * 5. RESET PASSWORD
     * ============================
     */
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:6|confirmed',
        ], [
            'email.required' => 'Email wajib diisi',
            'email.exists' => 'Email tidak terdaftar',
            'password.required' => 'Password baru wajib diisi',
            'password.min' => 'Password minimal 6 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // ✅ Update password menggunakan Eloquent
        User::where('email', $request->email)->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('login')
            ->with('success', 'Password berhasil diubah! Silakan login.');
    }

    /**
     * ============================
     * HELPER: Redirect Based on Role
     * ============================
     */
    private function redirectBasedOnRole()
    {
        $user = Auth::user();
        
        // ✅ Cek apakah user adalah admin
        if ($user->email === 'admin@gmail.com' && $user->username === 'admin') {
            return redirect()->route('admin.beranda');
        }
        
        // ✅ Cek apakah user adalah Toke Sawit
        if ($user->email === 'tokesawit@gmail.com') {
            return redirect()->route('toke.beranda');
        }
        
        // ✅ User biasa
        return redirect()->route('dashboard.beranda');
    }
}
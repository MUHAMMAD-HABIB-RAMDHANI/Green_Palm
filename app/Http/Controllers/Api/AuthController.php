<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // --- FUNGSI REGISTER ---
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'username' => 'required|string|max:255|unique:users,username', 
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'username' => $validatedData['username'], 
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            // Default user baru bukanlah premium
            'premium_until' => null, 
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Registrasi berhasil. Silakan login.',
        ], 201);
    }

    // --- FUNGSI LOGIN ---
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string', 
            'password' => 'required|string',
        ]);

        // Cek apakah input berupa email atau username
        $field = filter_var($credentials['email'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        // Coba login
        if (!Auth::attempt([$field => $credentials['email'], 'password' => $credentials['password']])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Email/Username atau Password salah'
            ], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        // --- LOGIKA ROLE (Hardcoded sesuai permintaan) ---
        $role = 'user'; // Default
        
        if ($user->email === 'admin@gmail.com' && $user->username === 'admin') {
            $role = 'admin';
        } elseif ($user->email === 'tokesawit@gmail.com') {
            $role = 'toke';
        }

        // --- PREPARE RESPONSE ---
        // Kita kirim status premium disini agar Android langsung tahu
        // Pastikan Model User memiliki method isPremium()
        
        return response()->json([
            'status' => 'success',
            'message' => 'Login berhasil',
            'access_token' => $token,
            'role' => $role,
            'user' => [
                'id' => $user->id,
                'username' => $user->username, 
                'email' => $user->email,
                'role' => $role,
                // [PENTING] Data Premium untuk SessionManager Android
                'is_premium' => $user->isPremium(), 
                'premium_until' => $user->premium_until ? $user->premium_until->format('Y-m-d H:i:s') : null,
            ]
        ]);
    }

    // --- FUNGSI LOGOUT ---
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Logout berhasil'
        ]);
    }
}
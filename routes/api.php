<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// ============ IMPORT CONTROLLERS ============
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\KebunController;
use App\Http\Controllers\Api\VideoEdukasiController;
use App\Http\Controllers\Api\PanenController;
use App\Http\Controllers\Api\PerawatanApiController;
use App\Http\Controllers\Api\WeatherController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Auth\ForgotPasswordOtpController;
use App\Http\Controllers\Api\PenyakitController;
use App\Http\Controllers\Api\RiwayatPerawatanController;
use App\Http\Controllers\Api\NotificationApiController;
use App\Http\Controllers\Api\TokeApiController;
use App\Http\Controllers\Api\KabarSawitController;
use App\Http\Controllers\Api\HelpApiController;
use App\Http\Controllers\Api\RatingApiController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// ====================================================
// 1. PUBLIC ROUTES (Tanpa Login)
// ====================================================

// Auth
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

// Forgot Password
Route::prefix('forgot')->group(function () {
    Route::post('send-otp', [ForgotPasswordOtpController::class, 'sendOtp']);
    Route::post('/',        [ForgotPasswordOtpController::class, 'resetWithOtp']);
});

// Fitur Umum
Route::get('/weather', [WeatherController::class, 'current']);

// Callback Payment
Route::post('/payment/callback', [PaymentController::class, 'callback']);


// ====================================================
// 2. PROTECTED ROUTES (Butuh Token Bearer)
// ====================================================

Route::middleware('auth:sanctum')->group(function () {
    
    // User Info
    Route::get('/user', fn (Request $request) => $request->user());
    Route::post('/logout', [AuthController::class, 'logout']);

    // ============ PROFILE ============
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'show']);
        Route::put('/', [ProfileController::class, 'update']);
        Route::post('/photo', [ProfileController::class, 'updatePhoto']);
        Route::delete('/photo', [ProfileController::class, 'deletePhoto']);
        Route::get('/photo/base64', [ProfileController::class, 'getPhotoBase64']);
    });

    Route::prefix('notifications')->group(function () {
        Route::get('/', [App\Http\Controllers\Api\NotificationApiController::class, 'index']);
        Route::get('/unread-count', [App\Http\Controllers\Api\NotificationApiController::class, 'unreadCount']);
        Route::post('/{id}/read', [App\Http\Controllers\Api\NotificationApiController::class, 'markAsRead']);
        Route::post('/read-all', [App\Http\Controllers\Api\NotificationApiController::class, 'markAllAsRead']);
        Route::delete('/{id}', [App\Http\Controllers\Api\NotificationApiController::class, 'destroy']);
        Route::delete('/clear/read', [App\Http\Controllers\Api\NotificationApiController::class, 'clearRead']);
    });

    // ============ PUSAT BANTUAN ============
    Route::prefix('help')->group(function () {
        Route::get('/', [HelpApiController::class, 'index']); // Lihat riwayat
        Route::post('/', [HelpApiController::class, 'store']); // Kirim pesan baru
        Route::get('/{id}', [HelpApiController::class, 'show']);
    });

    // ============ RATING ============
    Route::prefix('rating')->group(function () {
        Route::get('/', [RatingApiController::class, 'index']); // Get all user ratings
        Route::get('/latest', [RatingApiController::class, 'getLatest']); // Get latest rating
        Route::get('/status', [RatingApiController::class, 'checkStatus']); // Check if user has rated
        Route::post('/', [RatingApiController::class, 'store']); // Submit new rating
    });

    // ============ KEBUN (CRUD) ============
    Route::prefix('kebun')->group(function () {
        Route::get('/', [KebunController::class, 'index']);
        Route::post('/', [KebunController::class, 'store']);
        Route::get('/{id}', [KebunController::class, 'show']);
        Route::put('/{id}', [KebunController::class, 'update']);
        Route::delete('/{id}', [KebunController::class, 'destroy']);
    });

    // ============ EDUKASI & INFORMASI ============
    Route::get('/edukasi', [VideoEdukasiController::class, 'index']);

    Route::get('/kabar-sawit', [KabarSawitController::class, 'index']);
    
    Route::get('/penyakit', [PenyakitController::class, 'getPenyakit']);
    Route::get('/penyakit/{id}', [PenyakitController::class, 'getPenyakitDetail']);
    
    Route::get('/hama', [PenyakitController::class, 'getHama']); 
    Route::get('/hama/{id}', [PenyakitController::class, 'getHamaDetail']);

    Route::get('/harga-sawit', [App\Http\Controllers\Api\HargaSawitController::class, 'index']);
    Route::get('/harga-sawit/{id}', [App\Http\Controllers\Api\HargaSawitController::class, 'show']);
    
    // ============ PANEN ============
    Route::prefix('panen')->group(function () {
        Route::get('/kebun-list', [PanenController::class, 'getKebunList']); // Untuk dropdown
        Route::get('/', [PanenController::class, 'index']); // List panen (dengan filter opsional)
        Route::post('/', [PanenController::class, 'store']); // Simpan panen baru
        Route::get('/{id}', [PanenController::class, 'show']); // Detail panen
        Route::delete('/{id}', [PanenController::class, 'destroy']);
    });

    // ============ CATATAN / PERAWATAN ============
    Route::prefix('catatan')->group(function () {
        Route::get('/', [PerawatanApiController::class, 'index']); 
        
        Route::post('/pemupukan', [PerawatanApiController::class, 'storePemupukan']);
        Route::get('/pemupukan/last/{kebunId}', [PerawatanApiController::class, 'getLastPemupukan']);
        Route::post('/penunasan', [PerawatanApiController::class, 'storePenunasan']);
        Route::post('/penyemprotan', [PerawatanApiController::class, 'storePenyemprotan']);
        Route::post('/sanitasi', [PerawatanApiController::class, 'storeSanitasi']);
        Route::post('/kastrasi', [PerawatanApiController::class, 'storeKastrasi']);

        Route::get('/riwayat-semua', [RiwayatPerawatanController::class, 'semuaRiwayat']);
        Route::get('/{id}/riwayat', [RiwayatPerawatanController::class, 'riwayatPerKebun']);
        Route::delete('/{jenis}/{id}', [App\Http\Controllers\Api\RiwayatPerawatanController::class, 'destroy']);
    });

    // ============ PAYMENT ============
    Route::prefix('payment')->group(function () {
        Route::post('/create', [PaymentController::class, 'createTransaction']);
        Route::get('/history', [PaymentController::class, 'history']);
    });

    // ============ KEUANGAN ============
    Route::prefix('keuangan')->group(function () {
        Route::get('/grafik', [App\Http\Controllers\Api\KeuanganApiController::class, 'getGrafikKeuangan']);
        Route::get('/laporan', [App\Http\Controllers\Api\KeuanganApiController::class, 'getLaporanDetail']);
        Route::get('/ringkasan', [App\Http\Controllers\Api\KeuanganApiController::class, 'getRingkasan']);
    });

    // ============ PREMIUM ============
    Route::prefix('premium')->group(function () {
        Route::get('/status', [App\Http\Controllers\Api\PremiumApiController::class, 'getStatus']);
        Route::get('/packages', [App\Http\Controllers\Api\PremiumApiController::class, 'getPackages']);
        Route::post('/create-transaction', [App\Http\Controllers\Api\PremiumApiController::class, 'createTransaction']);
        Route::get('/transactions', [App\Http\Controllers\Api\PremiumApiController::class, 'getTransactions']);
        Route::get('/check-status/{orderId}', [App\Http\Controllers\Api\PremiumApiController::class, 'checkStatus']);
    });

    // ====================================================
    // ✅ ROUTE KHUSUS TOKE (tokesawit@gmail.com)
    // ====================================================
    Route::prefix('toke')->group(function () {
        
        // 1. Dashboard (Method: getDashboard)
        Route::get('/dashboard', [TokeApiController::class, 'getDashboard']);

        // 2. Manajemen RAM
        // Ambil Data RAM (Method: getRam)
        Route::get('/ram', [TokeApiController::class, 'getRam']);
        
        // Simpan/Update RAM (Method: storeRam)
        // Gunakan POST karena di dalamnya ada upload file (multipart/form-data)
        Route::post('/ram', [TokeApiController::class, 'storeRam']);

        // 3. Manajemen Profil
        // Update Data Diri (Method: updateProfile)
        Route::put('/profile', [TokeApiController::class, 'updateProfile']);
        
        // Update Foto Profil (Method: updateProfilePhoto)
        // Gunakan POST karena upload file
        Route::post('/profile/photo', [TokeApiController::class, 'updateProfilePhoto']);
        
        // Hapus Foto Profil (Method: deleteProfilePhoto)
        Route::delete('/profile/photo', [TokeApiController::class, 'deleteProfilePhoto']);
    });
});
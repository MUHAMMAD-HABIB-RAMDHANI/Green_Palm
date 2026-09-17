<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\ForgotPasswordOtpController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KebunController;
use App\Http\Controllers\PerawatanController;
use App\Http\Controllers\PanenController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminEdukasiController;
use App\Http\Controllers\Admin\AdminHargaSawitController;
use App\Http\Controllers\Admin\AdminPenyakitController;
use App\Http\Controllers\Admin\AdminHamaController;
use App\Http\Controllers\Admin\AdminKabarSawitController;
use App\Http\Controllers\Admin\AdminHelpController; // ✅ UNTUK KELOLA BANTUAN
use App\Http\Controllers\TokeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RatingController; // ✅ TAMBAHAN BARU
use App\Http\Controllers\HelpController; // ✅ TAMBAHAN UNTUK BANTUAN
use App\Models\Rating;
use App\Http\Controllers\BeritaController;

// ============================================================================
// 1. HALAMAN UTAMA & AUTH ROUTES (GUEST)
// ============================================================================

Route::get('/', function () {
    // Ambil 5 rating terbaru dari database
    $reviews = Rating::latest()->take(5)->get();
    
    // Kirim data $reviews ke view 'welcome'
    return view('welcome', compact('reviews'));
})->name('welcome');

Route::get('/berita/{slug}', [BeritaController::class, 'show'])->name('berita.show');

Route::get('/tentang-kami', function () {
    return view('about');
})->name('about');

Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    // Register
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    // Forgot Password (OTP)
    Route::get('forgot', [ForgotPasswordOtpController::class, 'show'])->name('password.forgot');
    Route::post('forgot/send-otp', [ForgotPasswordOtpController::class, 'sendOtp'])->name('otp.send');
    Route::post('forgot', [ForgotPasswordOtpController::class, 'resetWithOtp'])->name('password.otp.reset');
});

// Logout (Harus bisa diakses user yang login)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// ============================================================================
// 2. STORAGE ROUTE (Public Access untuk Gambar Profil)
// ============================================================================

Route::get('/storage/profile_pictures/{userId}/{filename}', function ($userId, $filename) {
    $path = storage_path("app/public/profile_pictures/{$userId}/{$filename}");
    
    if (!file_exists($path)) {
        abort(404, 'Image not found');
    }
    
    $file = file_get_contents($path);
    $type = mime_content_type($path);
    
    return response($file, 200)
        ->header('Content-Type', $type)
        ->header('Content-Length', strlen($file))
        ->header('Content-Disposition', 'inline')
        ->header('Cache-Control', 'public, max-age=31536000');
})->name('storage.profile_picture');


// ============================================================================
// 3. PAYMENT CALLBACK (Tanpa Auth - Untuk Webhook Midtrans)
// ============================================================================

Route::post('/payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');


// ============================================================================
// 4. PROTECTED ROUTES (Memerlukan Login)
// ============================================================================

Route::middleware('auth.session')->group(function () {
    
    // --- ADMIN ROUTES ---
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/beranda', [AdminDashboardController::class, 'beranda'])->name('beranda');
        
        // Manajemen User
        Route::get('/users', [AdminDashboardController::class, 'users'])->name('users');
        Route::get('/users/{id}', [AdminDashboardController::class, 'userDetail'])->name('users.detail');
        Route::delete('/users/{id}', [AdminDashboardController::class, 'deleteUser'])->name('users.delete');
        
        // Manajemen Kebun & Laporan
        Route::get('/kebun', [AdminDashboardController::class, 'kebun'])->name('kebun');
        Route::get('/kebun/{id}', [AdminDashboardController::class, 'kebunDetail'])->name('kebun.detail');
        Route::get('/laporan', [AdminDashboardController::class, 'laporan'])->name('laporan');
        Route::get('/update-data', [AdminDashboardController::class, 'updateData'])->name('update-data');

        // CRUD Edukasi
        Route::prefix('edukasi')->name('edukasi.')->group(function () {
            Route::get('/', [AdminEdukasiController::class, 'index'])->name('index');
            Route::get('/create', [AdminEdukasiController::class, 'create'])->name('create');
            Route::post('/store', [AdminEdukasiController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [AdminEdukasiController::class, 'edit'])->name('edit');
            Route::put('/{id}', [AdminEdukasiController::class, 'update'])->name('update');
            Route::delete('/{id}', [AdminEdukasiController::class, 'destroy'])->name('destroy');
        });

        // CRUD Harga Sawit
        Route::prefix('harga-sawit')->name('harga-sawit.')->group(function () {
            Route::get('/', [AdminHargaSawitController::class, 'index'])->name('index');
            Route::get('/create', [AdminHargaSawitController::class, 'create'])->name('create');
            Route::post('/store', [AdminHargaSawitController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [AdminHargaSawitController::class, 'edit'])->name('edit');
            Route::put('/{id}', [AdminHargaSawitController::class, 'update'])->name('update');
            Route::delete('/{id}', [AdminHargaSawitController::class, 'destroy'])->name('destroy');
        });

        // CRUD Penyakit
        Route::prefix('penyakit')->name('penyakit.')->group(function () {
            Route::get('/', [AdminPenyakitController::class, 'index'])->name('index');
            Route::get('/create', [AdminPenyakitController::class, 'create'])->name('create');
            Route::post('/store', [AdminPenyakitController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [AdminPenyakitController::class, 'edit'])->name('edit');
            Route::put('/{id}', [AdminPenyakitController::class, 'update'])->name('update');
            Route::delete('/{id}', [AdminPenyakitController::class, 'destroy'])->name('destroy');
        });

        // CRUD Hama
        Route::prefix('hama')->name('hama.')->group(function () {
            Route::get('/', [AdminHamaController::class, 'index'])->name('index');
            Route::get('/create', [AdminHamaController::class, 'create'])->name('create');
            Route::post('/store', [AdminHamaController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [AdminHamaController::class, 'edit'])->name('edit');
            Route::put('/{id}', [AdminHamaController::class, 'update'])->name('update');
            Route::delete('/{id}', [AdminHamaController::class, 'destroy'])->name('destroy');
        });

        // CRUD Kabar Sawit
        Route::prefix('kabar-sawit')->name('kabar-sawit.')->group(function () {
            Route::get('/', [AdminKabarSawitController::class, 'index'])->name('index');
            Route::get('/create', [AdminKabarSawitController::class, 'create'])->name('create');
            Route::post('/store', [AdminKabarSawitController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [AdminKabarSawitController::class, 'edit'])->name('edit');
            Route::put('/{id}', [AdminKabarSawitController::class, 'update'])->name('update');
            Route::delete('/{id}', [AdminKabarSawitController::class, 'destroy'])->name('destroy');
        });

        // ✅ Kelola Bantuan User (Menggantikan Laporan)
        Route::prefix('bantuan')->name('bantuan.')->group(function () {
            Route::get('/', [AdminHelpController::class, 'index'])->name('index');
            Route::get('/{id}', [AdminHelpController::class, 'show'])->name('show');
            Route::patch('/{id}/status', [AdminHelpController::class, 'updateStatus'])->name('updateStatus');
            Route::post('/{id}/reply', [AdminHelpController::class, 'reply'])->name('reply');
            Route::delete('/{id}', [AdminHelpController::class, 'destroy'])->name('destroy');
        });
    });
    
    // --- TOKE SAWIT ROUTES ---
    Route::prefix('toke')->name('toke.')->group(function () {
        Route::get('/beranda', [TokeController::class, 'beranda'])->name('beranda');

        // CRUD RAM
        Route::get('/ram/edit', [TokeController::class, 'editRam'])->name('edit-ram');
        Route::post('/ram/simpan', [TokeController::class, 'storeRam'])->name('store-ram');

        // Profil Toke
        Route::get('/profil', [TokeController::class, 'profil'])->name('profil');
        Route::get('/profil/edit', [TokeController::class, 'editProfile'])->name('profile.edit');
        Route::post('/profil/update', [TokeController::class, 'updateProfile'])->name('profile.update');
        Route::post('/profil/photo', [TokeController::class, 'uploadPhoto'])->name('profile.photo');
    });

    // --- PREMIUM & PAYMENT ROUTES ---
    Route::prefix('premium')->name('premium.')->group(function () {
        Route::get('/upgrade', [PaymentController::class, 'upgrade'])->name('upgrade');
    });

    Route::prefix('payment')->name('payment.')->group(function () {
        Route::post('/create', [PaymentController::class, 'createTransaction'])->name('create');
        Route::get('/finish', [PaymentController::class, 'finish'])->name('finish');
        Route::get('/history', [PaymentController::class, 'history'])->name('history');
    });
    
    // --- USER (PETANI) DASHBOARD ROUTES ---
    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::get('/beranda', [DashboardController::class, 'showBeranda'])->name('beranda');
        
        // Notifikasi
        Route::get('/notifikasi', [DashboardController::class, 'showNotifikasi'])->name('notifikasi');
        Route::post('/notifikasi/{id}/read', [DashboardController::class, 'markNotificationAsRead'])->name('notifikasi.read');
        Route::post('/notifikasi/read-all', [DashboardController::class, 'markAllNotificationsAsRead'])->name('notifikasi.read-all');
        Route::delete('/notifikasi/{id}', [DashboardController::class, 'deleteNotification'])->name('notifikasi.delete');

        // Profil
        Route::get('/profil', [DashboardController::class, 'showProfil'])->name('profil');
        
        // Edukasi
        Route::get('/edukasi', [DashboardController::class, 'edukasi'])->name('edukasi');
        
        // Kabar Sawit
        Route::get('/kabar-sawit', [DashboardController::class, 'kabarSawit'])->name('kabar-sawit');
        Route::get('/kabar-sawit/baca/{id}', [DashboardController::class, 'detailKabarSawit'])->name('kabar-sawit.show');

        // Harga Sawit (User View)
        Route::get('/harga-sawit', [DashboardController::class, 'hargaSawit'])->name('harga-sawit');
        Route::get('/harga-sawit/{id}', [DashboardController::class, 'detailRam'])->name('harga-sawit.detail');

        // Penyakit & Hama
        Route::get('/penyakit-sawit', [DashboardController::class, 'penyakitSawit'])->name('penyakit');
        Route::get('/penyakit-sawit/daftar-hama', [DashboardController::class, 'daftarHama'])->name('daftar-hama');
        Route::get('/penyakit-sawit/daftar-hama/{id}', [DashboardController::class, 'detailHama'])->name('detail-hama');
        Route::get('/penyakit-sawit/daftar-penyakit', [DashboardController::class, 'daftarPenyakit'])->name('daftar-penyakit');
        Route::get('/penyakit-sawit/daftar-penyakit/{id}', [DashboardController::class, 'detailPenyakit'])->name('detail-penyakit');
        Route::get('/penyakit-sawit/diagnosa', [DashboardController::class, 'diagnosaSawit'])->name('diagnosa');
        
        // Profile Management Actions
        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/edit', [ProfileController::class, 'showEditForm'])->name('edit');
            Route::post('/update', [ProfileController::class, 'updateProfile'])->name('update');
            Route::post('/photo', [ProfileController::class, 'uploadPhoto'])->name('photo');
            Route::delete('/photo', [ProfileController::class, 'deletePhoto'])->name('photo.delete');
            Route::post('/change-password', [ProfileController::class, 'changePassword'])->name('change-password');
        });

        // ✅ RATING ROUTES (TAMBAHAN BARU)
        Route::prefix('rating')->name('rating.')->group(function () {
            Route::get('/buat', [RatingController::class, 'create'])->name('create');
            Route::post('/simpan', [RatingController::class, 'store'])->name('store');
            Route::get('/riwayat', [RatingController::class, 'history'])->name('history'); // Optional
        });

        // ✅ HELP/BANTUAN ROUTES (TAMBAHAN UNTUK PUSAT BANTUAN)
        Route::prefix('help')->name('help.')->group(function () {
            Route::get('/buat', [HelpController::class, 'create'])->name('create');
            Route::post('/simpan', [HelpController::class, 'store'])->name('store');
            Route::get('/riwayat', [HelpController::class, 'history'])->name('history'); // Optional
            Route::get('/detail/{id}', [HelpController::class, 'show'])->name('show'); // Optional
        });
    });
    
    // --- KEBUN MANAGEMENT ---
    Route::prefix('kebun')->name('kebun.')->group(function () {
        Route::get('/', [KebunController::class, 'index'])->name('daftar');
        Route::get('/buat', [KebunController::class, 'create'])->name('create');
        Route::post('/simpan', [KebunController::class, 'store'])->name('store');
        Route::get('/detail/{id}', [KebunController::class, 'show'])->name('show');
        Route::get('/edit/{id}', [KebunController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [KebunController::class, 'update'])->name('update');
        Route::get('/semua-kebun', [KebunController::class, 'semuaKebun'])->name('semua-kebun');
        Route::get('/semua-riwayat', [KebunController::class, 'semuaRiwayat'])->name('semua-riwayat');
        Route::delete('/{id}', [KebunController::class, 'destroy'])->name('destroy');
    });
    
    // --- PERAWATAN & CATATAN ---
    Route::prefix('catatan')->name('catatan.')->group(function () {
        Route::get('/menu', [PerawatanController::class, 'menu'])->name('menu');
        Route::get('/input/{jenis}', [PerawatanController::class, 'create'])->name('create');
        Route::post('/simpan', [PerawatanController::class, 'store'])->name('store');
        Route::delete('/hapus/{jenis}/{id}', [PerawatanController::class, 'destroy'])->name('destroy');
    });

    // --- PANEN MANAGEMENT ---
    Route::prefix('panen')->name('panen.')->group(function () {
        Route::get('/', [PanenController::class, 'index'])->name('index');
        Route::get('/catat', [PanenController::class, 'create'])->name('create');
        Route::post('/simpan', [PanenController::class, 'store'])->name('store');
        Route::get('/detail/{id}', [PanenController::class, 'show'])->name('show');
        Route::delete('/{id}', [PanenController::class, 'destroy'])->name('destroy');
    });

});


// ============================================================================
// 5. PREMIUM SPECIFIC ROUTES (User Berbayar)
// ============================================================================

Route::middleware(['auth.session', 'premium'])->group(function () {
    Route::get('/dashboard/laporan', [DashboardController::class, 'laporan'])->name('dashboard.laporan');
});
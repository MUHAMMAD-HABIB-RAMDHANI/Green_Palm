@extends('layouts.app')

@section('title', 'Profil')

@section('content')

{{-- =============================================
     STYLE SECTION KHUSUS HALAMAN PROFIL
     ============================================= --}}
<style>
    /* --- HEADER PAGE STYLE --- */
    .profile-page-header {
        margin-bottom: 25px;
        background: #2b7a0b;
        padding: 20px;
        border-radius: 16px;
        color: white;
        display: flex;
        align-items: center;
        gap: 15px;
        box-shadow: 0 4px 12px rgba(43,122,11,0.2);
    }

    .profile-page-title {
        margin: 0;
        font-size: 24px;
        font-weight: 700;
    }

    /* Grid Layout Utama */
    .profile-grid {
        display: grid;
        grid-template-columns: 380px 1fr;
        gap: 25px;
        max-width: 1200px;
        margin: 0 auto;
    }

    /* Kartu Info Profil (Kiri) */
    .profile-info-card {
        background: linear-gradient(135deg, #1e4620 0%, #2b7a0b 100%);
        border-radius: 20px;
        box-shadow: 0 8px 24px rgba(43, 122, 11, 0.25);
        padding: 40px 30px;
        text-align: center;
        position: relative;
        overflow: hidden;
        color: white;
    }

    .profile-info-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: pulse 8s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); opacity: 0.5; }
        50% { transform: scale(1.1); opacity: 0.3; }
    }

    /* Wrapper Foto Profil */
    .profile-picture-wrapper {
        position: relative;
        width: 160px;
        height: 160px;
        margin: 0 auto 25px;
        z-index: 1;
    }

    .profile-avatar {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
        border: 5px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        background: white;
    }

    .profile-picture-wrapper::after {
        content: '';
        position: absolute;
        inset: -8px;
        border-radius: 50%;
        border: 3px solid transparent;
        border-top-color: rgba(255, 255, 255, 0.5);
        border-right-color: rgba(255, 255, 255, 0.5);
        animation: rotate 4s linear infinite;
    }

    @keyframes rotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    /* Tombol Edit Foto */
    .edit-pic-btn {
        position: absolute;
        bottom: 8px;
        right: 8px;
        width: 44px;
        height: 44px;
        background: #ffc107;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        border: 4px solid #1e4620;
        cursor: pointer;
        transition: all 0.3s ease;
        z-index: 2;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        color: #333;
    }

    .edit-pic-btn:hover {
        transform: scale(1.15) rotate(15deg);
        background: #ffca2c;
    }

    /* Tombol Hapus Foto */
    .avatar-delete-form {
        position: absolute; 
        left: 8px;
        bottom: 8px;
        z-index: 2;
        margin: 0;
    }

    .avatar-delete-btn {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: #ff6b6b; 
        color: #fff; 
        border: 4px solid #1e4620;
        display: inline-flex; 
        align-items: center; 
        justify-content: center;
        cursor: pointer; 
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        transition: all 0.3s ease;
        position: relative;
    }

    .avatar-delete-btn::before, .avatar-delete-btn::after {
        content: '';
        position: absolute;
        width: 18px;
        height: 3px;
        background-color: white;
        border-radius: 2px;
    }
    .avatar-delete-btn::before { transform: rotate(45deg); }
    .avatar-delete-btn::after { transform: rotate(-45deg); }

    .avatar-delete-btn:hover { 
        transform: scale(1.15) rotate(-15deg);
        background: #fa5252;
    }

    /* Teks Profil */
    .profile-name {
        font-size: 26px;
        font-weight: 700;
        margin: 0 0 8px 0;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        z-index: 1;
        position: relative;
    }

    .profile-email {
        font-size: 15px;
        color: rgba(255, 255, 255, 0.85);
        margin: 0;
        word-break: break-all;
        z-index: 1;
        position: relative;
    }

    /* Badge Premium Header Style */
    .header-premium-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: linear-gradient(135deg, #FFD700 0%, #FDB931 100%); /* Emas */
        color: #8a6d3b; /* Coklat Emas Gelap */
        font-size: 13px;
        font-weight: 800;
        padding: 6px 16px;
        border-radius: 30px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        margin-left: auto; /* Mendorong badge ke kanan */
        border: 2px solid rgba(255,255,255,0.8);
        animation: shimmer 2s infinite linear;
    }

    @keyframes shimmer {
        0% { filter: brightness(100%); }
        50% { filter: brightness(110%); }
        100% { filter: brightness(100%); }
    }

    /* Statistik Box */
    .profile-stats {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-top: 25px;
        z-index: 1;
        position: relative;
    }

    .stat-box {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        padding: 16px;
        text-align: center;
        transition: all 0.3s ease;
    }

    .stat-box:hover {
        background: rgba(255, 255, 255, 0.25);
        transform: translateY(-3px);
    }

    .stat-value {
        font-size: 28px;
        font-weight: 700;
        margin: 0;
    }

    .stat-label {
        font-size: 12px;
        color: rgba(255, 255, 255, 0.85);
        margin: 5px 0 0 0;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .stat-icon {
        font-size: 24px;
        margin-bottom: 8px;
    }

    /* --- Bagian Kanan (Action List) --- */
    .profile-actions {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    /* Info Box (Kebun) */
    .info-box {
        background: linear-gradient(135deg, #fff9e6 0%, #ffe9b3 100%);
        border-radius: 16px;
        padding: 24px 28px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 4px 12px rgba(214, 170, 77, 0.15);
        border-left: 5px solid #d6aa4d;
        position: relative;
        overflow: hidden;
    }

    .info-box::before {
        content: '🌴';
        position: absolute;
        right: -10px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 80px;
        opacity: 0.1;
    }

    .info-box-text h5 {
        font-size: 18px;
        font-weight: 700;
        margin: 0 0 6px 0;
        color: #856404;
    }

    .info-box-text p {
        font-size: 15px;
        margin: 0;
        color: #856404;
        font-weight: 500;
    }

    .info-box-close {
        text-decoration: none;
        font-size: 24px;
        color: #d6aa4d;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: rgba(214, 170, 77, 0.1);
        transition: 0.3s;
    }
    .info-box-close:hover { background: rgba(214, 170, 77, 0.2); }

    /* Action List Card */
    .action-list-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
    }

    .action-item {
        display: flex;
        align-items: center;
        padding: 20px 25px;
        text-decoration: none;
        color: #222;
        border-bottom: 1px solid #f0f0f0;
        transition: all 0.3s ease;
        position: relative;
    }

    .action-item:last-child { border-bottom: none; }

    .action-item:hover {
        background: #f9fff9;
        padding-left: 30px;
    }

    .action-item .icon {
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, #e6f1e3 0%, #d4e7d0 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        margin-right: 18px;
        transition: 0.3s;
    }

    .action-item:hover .icon {
        transform: scale(1.1) rotate(5deg);
        background: #2b7a0b;
        color: white;
    }

    .action-item .text {
        flex-grow: 1;
        font-size: 16px;
        font-weight: 600;
    }

    .action-item .chevron {
        font-size: 20px;
        color: #ccc;
        font-weight: bold;
    }

    /* Style Khusus Tombol Logout */
    .action-item.logout-item {
        background: #fff5f5;
    }
    .action-item.logout-item .icon {
        background: #ffe0e0;
        color: #dc3545;
    }
    .action-item.logout-item:hover {
        background: #ffecec;
    }
    .action-item.logout-item:hover .icon {
        background: #dc3545;
        color: white;
    }
    .action-item.logout-item .text { color: #dc3545; }


    /* --- RESPONSIVE --- */
    @media (max-width: 992px) {
        .profile-grid { grid-template-columns: 1fr; }
        .profile-info-card { padding: 30px; }
    }
    
    @media (max-width: 768px) {
        /* ✅ HIDE HEADER ON MOBILE */
        .profile-page-header {
            display: none;
        }
    }

    @media (max-width: 480px) {
        .profile-picture-wrapper { width: 130px; height: 130px; }
        .edit-pic-btn, .avatar-delete-btn { width: 38px; height: 38px; }
    }
</style>

{{-- =============================================
     HTML CONTENT
     ============================================= --}}

{{-- Header Page (Judul) - Hidden on Mobile --}}
<div class="profile-page-header">
    <h1 class="profile-page-title">👤 Profil Saya</h1>
    
    @if($user->isPremium())
        <div class="header-premium-badge">
            👑 PREMIUM
        </div>
    @endif
</div>

<div class="profile-grid">

    {{-- KARTU INFO PROFIL (KIRI) --}}
    <div class="profile-info-card">
        <div class="profile-picture-wrapper">
            <img id="profile-avatar"
                 src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : 'https://placehold.co/160x160/e6f1e3/1E4620?text=' . strtoupper(substr($user->username, 0, 1)) }}"
                 alt="Foto Profil"
                 class="profile-avatar">

            <label for="avatar-file" class="edit-pic-btn" title="Ganti Foto">✏️</label>

            @if($user->profile_picture)
                <form class="avatar-delete-form"
                      action="{{ route('dashboard.profile.photo.delete') }}"
                      method="POST"
                      onsubmit="return confirm('Hapus foto profil?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="avatar-delete-btn" title="Hapus Foto"></button>
                </form>
            @endif
        </div>

        <h3 class="profile-name">{{ $user->username }}</h3>
        <p class="profile-email">{{ $user->email }}</p>

        <div class="profile-stats">
            <div class="stat-box">
                <div class="stat-icon">🌴</div>
                <div class="stat-value">{{ \App\Models\DataKebun::where('user_id', $user->id)->count() }}</div>
                <p class="stat-label">Kebun</p>
            </div>
            <div class="stat-box">
                <div class="stat-icon">📝</div>
                <div class="stat-value">
                    {{ 
                        \App\Models\Pemupukan::where('user_id', $user->id)->count() + 
                        \App\Models\Penunasan::where('user_id', $user->id)->count() 
                    }}
                </div>
                <p class="stat-label">Aktivitas</p>
            </div>
        </div>
    </div>

    {{-- KARTU MENU AKSI (KANAN) --}}
    <div class="profile-actions">

        <div class="info-box">
            <div class="info-box-text">
                <h5>🌴 Status Kebun</h5>
                <p>
                    @php
                        $totalKebun = \App\Models\DataKebun::where('user_id', $user->id)->count();
                    @endphp
                    @if($totalKebun > 0)
                        Anda memiliki <strong>{{ $totalKebun }}</strong> kebun aktif yang terdaftar.
                    @else
                        Anda belum memiliki kebun. Yuk tambah sekarang!
                    @endif
                </p>
            </div>
            <a href="#" class="info-box-close" onclick="this.parentElement.style.display='none'; return false;">×</a>
        </div>

        <div class="action-list-card">
            <a href="{{ route('dashboard.profile.edit') }}" class="action-item">
                <span class="icon">⚙️</span>
                <span class="text">Edit Profil</span>
                <span class="chevron">›</span>
            </a>
            
            <a href="{{ route('dashboard.help.create') }}" class="action-item">
                <span class="icon">❓</span>
                <span class="text">Pusat Bantuan</span>
                <span class="chevron">›</span>
            </a>

            <a href="{{ route('dashboard.rating.create') }}" class="action-item">
                <span class="icon">⭐</span>
                <span class="text">Beri Rating Aplikasi</span>
                <span class="chevron">›</span>
            </a>

            {{-- ✅ BUTTON LOGOUT: Menggunakan class 'confirm-logout' --}}
            {{-- Ini akan otomatis memicu modal merah dari app.blade.php --}}
            <a href="#" class="action-item logout-item confirm-logout">
                <span class="icon">🚪</span>
                <span class="text">Keluar (Logout)</span>
                <span class="chevron">›</span>
            </a>
        </div>

    </div>
</div>

{{-- Form Upload Foto (Hidden) --}}
<form id="upload-avatar-form" action="{{ route('dashboard.profile.photo') }}" method="POST" enctype="multipart/form-data" style="display:none">
    @csrf
    <input id="avatar-file" type="file" name="profile_picture" accept="image/*">
</form>

{{-- JAVASCRIPT LOGIC --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    // --- Auto Upload Foto ---
    const fileInput = document.getElementById('avatar-file');
    const uploadForm = document.getElementById('upload-avatar-form');

    if (fileInput && uploadForm) {
        fileInput.addEventListener('change', function () {
            if (fileInput.files.length > 0) {
                if(fileInput.files[0].size > 2 * 1024 * 1024) {
                    alert("Ukuran foto terlalu besar! Maksimal 2MB.");
                    fileInput.value = "";
                    return;
                }
                uploadForm.submit();
            }
        });
    }
});
</script>

@endsection
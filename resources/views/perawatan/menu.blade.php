@extends('layouts.app')
@section('title', 'Catatan Perawatan')

{{-- ============================================================ --}}
{{-- 1. DEFINISI HEADER MOBILE (Fixed Top)                        --}}
{{-- ============================================================ --}}
@section('mobile-header')
    <header class="mobile-header-custom">
        <div class="header-left-content">
            {{-- Tombol Kembali --}}
            <a href="{{ route('kebun.daftar') }}" class="mobile-back-btn">
                ‹
            </a>
            
            {{-- Judul Halaman --}}
            <h2 class="mobile-title">
                Kegiatan Perawatan
            </h2>
        </div>
    </header>
@endsection

@section('content')

<style>
    /* --- VARIABLES --- */
    :root {
        --primary-green: #2b7a0b;
        --dark-green: #1e5607;
        --light-green: #e6f1e3;
        --bg-gray: #f8f9fa;
        --text-dark: #222;
        --text-muted: #777;
        --danger-red: #dc3545;
    }

    /* =========================================
       STYLE MOBILE HEADER (Default: Hidden)
       ========================================= */
    .mobile-header-custom {
        display: none; /* Sembunyikan di Laptop/PC */
    }

    .header-left-content {
        display: flex;
        align-items: center;
        gap: 12px;
        width: 100%;
    }

    .mobile-back-btn {
        width: 38px; 
        height: 38px; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        background: rgba(255, 255, 255, 0.2); 
        backdrop-filter: blur(5px);
        border-radius: 12px; 
        color: white; 
        text-decoration: none; 
        font-size: 22px; 
        border: 1px solid rgba(255, 255, 255, 0.3);
        transition: 0.3s;
        flex-shrink: 0;
        padding-bottom: 2px;
    }

    .mobile-title {
        font-size: 18px; 
        font-weight: 700; 
        color: white; 
        margin: 0; 
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        white-space: nowrap;
    }

    /* --- DESKTOP BASE STYLES --- */
    .care-wrapper {
        background-color: var(--bg-gray);
        padding: 20px 30px;
        font-family: 'Poppins', sans-serif;
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: flex-start;
    }

    .care-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.05);
        width: 100%;
        max-width: 600px;
        margin: 0 auto;
        overflow: hidden;
        animation: slideUp 0.5s ease;
        display: flex;
        flex-direction: column;
    }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Header Desktop */
    .card-header {
        padding: 20px 30px;
        background: linear-gradient(135deg, var(--primary-green) 0%, var(--dark-green) 100%);
        display: flex;
        align-items: center;
        gap: 20px;
        position: relative;
        overflow: hidden;
    }

    .card-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%);
        animation: pulse 8s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); opacity: 0.5; }
        50% { transform: scale(1.1); opacity: 0.3; }
    }

    .back-button {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        color: white;
        font-size: 24px;
        font-weight: 300;
        transition: all 0.3s ease;
        z-index: 1;
        flex-shrink: 0;
    }

    .back-button:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateX(-5px);
    }

    .card-title {
        font-size: 24px;
        font-weight: 700;
        margin: 0;
        color: white;
        z-index: 1;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        line-height: 1.2;
    }

    /* Body Content */
    .card-body {
        padding: 30px;
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .section-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--primary-green);
        margin-bottom: 10px;
        padding-bottom: 10px;
        border-bottom: 2px solid #f0f0f0;
    }

    /* Alerts */
    .alert {
        padding: 12px 16px;
        border-radius: 10px;
        margin-bottom: 10px;
        font-size: 14px;
        animation: slideDown 0.3s ease;
    }

    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
        border-left: 4px solid #28a745;
    }

    .alert-error {
        background: #f8d7da;
        color: #721c24;
        border-left: 4px solid #dc3545;
    }

    /* Menu List Items */
    .menu-list {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .menu-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px 25px;
        background: white;
        border: 2px solid #e0e0e0;
        border-radius: 15px;
        text-decoration: none;
        color: #333;
        font-weight: 600;
        font-size: 15px;
        transition: all 0.3s ease;
        position: relative;
    }

    .menu-item:hover {
        border-color: var(--primary-green);
        background: #f4fff4;
        color: var(--primary-green);
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(43, 122, 11, 0.1);
    }

    .menu-text {
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 15px;
    }

    .menu-item .arrow {
        font-size: 20px;
        color: #999;
        transition: 0.3s;
    }

    .menu-item:hover .arrow {
        color: var(--primary-green);
        transform: translateX(5px);
    }

    /* Footer Text */
    .care-helper {
        text-align: center;
        font-size: 13px;
        color: #888;
        margin-top: 10px;
    }

    /* --- RESPONSIVE STYLES (FIXED CARD LOOK) --- */
    @media (max-width: 768px) {
        /* 1. Hilangkan Header Card Desktop */
        .card-header { display: none !important; }

        /* 2. Tampilkan Header Mobile Custom */
        .mobile-header-custom {
            display: flex;
            align-items: center;
            width: 100%;
            height: 70px;
            padding: 0 20px;
            
            background: linear-gradient(135deg, #2b7a0b 0%, #1e5607 100%);
            box-shadow: 0 4px 15px rgba(43, 122, 11, 0.3);
            
            position: fixed;
            top: 0;
            left: 0;
            z-index: 999;
        }

        /* 3. Wrapper: Kembalikan Background Abu-abu & Atur Posisi */
        .care-wrapper { 
            /* Reset posisi akibat padding bawaan layout utama */
            margin-top: -80px; 
            margin-left: -20px; 
            margin-right: -20px;
            
            /* Pastikan background abu-abu agar kartu putih terlihat */
            background-color: #f8f9fa; 
            min-height: 100vh;
            
            /* Beri padding agar kartu tidak menempel ke tepi layar */
            padding: 0 15px; 
            
            /* Aktifkan Flex agar bisa mengatur margin auto */
            display: flex;
            flex-direction: column;
        }
        
        /* 4. Card Container: Kembalikan Bentuk Kartu */
        .care-card { 
            background: white;
            border-radius: 20px; 
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.05); 
            
            /* PENTING: Beri Jarak Atas agar pas di bawah Header (70px + 20px gap) */
            margin-top: 90px; 
            margin-bottom: 30px;
            width: 100%;
        }
        
        .card-body { 
            padding: 25px 20px; 
        }

        .menu-item {
            padding: 14px 20px;
            font-size: 14px;
        }
    }
</style>

<div class="care-wrapper">
    <div class="care-card">
        
        {{-- Header Desktop (Akan hilang di Mobile) --}}
        <div class="card-header">
            <a href="{{ route('kebun.daftar') }}" class="back-button" title="Kembali">‹</a>
            <h1 class="card-title">Kegiatan Perawatan</h1>
        </div>

        {{-- Body Content --}}
        <div class="card-body">
            
            {{-- Alert jika belum punya kebun --}}
            @if(isset($hasKebun) && !$hasKebun)
                <div class="alert alert-error">
                    <strong>Perhatian!</strong> Anda belum memiliki kebun. Silakan <a href="{{ route('kebun.create') }}" style="color: #721c24; text-decoration: underline; font-weight: 600;">buat kebun terlebih dahulu</a>.
                </div>
            @endif
            
            {{-- Success/Error Messages --}}
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-error">
                    {{ session('error') }}
                </div>
            @endif

            <div class="section-title">Pilih Jenis Kegiatan</div>

            {{-- List Menu: 5 Kegiatan Perawatan --}}
            <div class="menu-list">
                
                <a href="{{ route('catatan.create', ['jenis' => 'pemupukan']) }}" class="menu-item">
                    <span class="menu-text">🌱 Pemupukan</span>
                    <span class="arrow">›</span>
                </a>

                <a href="{{ route('catatan.create', ['jenis' => 'penunasan']) }}" class="menu-item">
                    <span class="menu-text">✂️ Penunasan</span>
                    <span class="arrow">›</span>
                </a>

                <a href="{{ route('catatan.create', ['jenis' => 'penyemprotan']) }}" class="menu-item">
                    <span class="menu-text">🚿 Penyemprotan</span>
                    <span class="arrow">›</span>
                </a>

                <a href="{{ route('catatan.create', ['jenis' => 'sanitasi']) }}" class="menu-item">
                    <span class="menu-text">🧹 Sanitasi</span>
                    <span class="arrow">›</span>
                </a>

                <a href="{{ route('catatan.create', ['jenis' => 'kastrasi']) }}" class="menu-item">
                    <span class="menu-text">🌸 Kastrasi</span>
                    <span class="arrow">›</span>
                </a>

            </div>

            {{-- Helper Text --}}
            <div class="care-helper">Pilih salah satu kegiatan untuk dicatat</div>

        </div>
    </div>
</div>

@endsection
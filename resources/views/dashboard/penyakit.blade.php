@extends('layouts.app')
@section('title', 'Penyakit Sawit')

{{-- ============================================================ --}}
{{-- 1. DEFINISI HEADER MOBILE (Fixed Top)                        --}}
{{-- ============================================================ --}}
@section('mobile-header')
    <header class="mobile-header-custom">
        <div class="header-left-content">
            {{-- Tombol Kembali --}}
            <a href="{{ route('dashboard.beranda') }}" class="mobile-back-btn">
                ‹
            </a>
            
            {{-- Judul Halaman --}}
            <h2 class="mobile-title">
                Penyakit Sawit
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
    .penyakit-wrapper {
        background-color: var(--bg-gray);
        min-height: 100vh;
        padding: 20px 30px;
        font-family: 'Poppins', sans-serif;
    }

    .penyakit-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.05);
        max-width: 600px; /* Lebih kecil karena hanya menu */
        margin: 0 auto;
        overflow: hidden;
        animation: slideUp 0.5s ease;
    }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Header Desktop */
    .card-header {
        background: linear-gradient(135deg, var(--primary-green) 0%, var(--dark-green) 100%);
        padding: 20px 30px;
        display: flex;
        align-items: center;
        gap: 20px;
        color: white;
    }

    .btn-back {
        width: 38px;
        height: 38px;
        background: rgba(255,255,255,0.2);
        border: 1px solid rgba(255,255,255,0.3);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        text-decoration: none;
        font-size: 18px;
        transition: 0.3s;
    }

    .btn-back:hover {
        background: rgba(255,255,255,0.3);
    }

    .card-title {
        font-size: 20px;
        font-weight: 700;
        margin: 0;
    }

    /* Body Content */
    .card-body {
        padding: 30px;
    }

    .section-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f0f0f0;
    }

    /* Menu List */
    .menu-list {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .menu-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 18px 25px;
        background: white;
        border: 2px solid #e0e0e0;
        border-radius: 15px;
        text-decoration: none;
        color: #333;
        font-weight: 600;
        font-size: 15px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
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
    }

    .arrow {
        font-size: 20px;
        color: #999;
        transition: 0.3s;
    }

    .menu-item:hover .arrow {
        color: var(--primary-green);
        transform: translateX(5px);
    }

    /* --- RESPONSIVE STYLES (FIXED CARD LOOK) --- */
    @media (max-width: 768px) {
        
        /* 1. Hilangkan Header Card Desktop di Mobile */
        .card-header { display: none !important; }

        /* 2. Tampilkan Header Mobile Custom (Fixed Top) */
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
        .penyakit-wrapper { 
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
        .penyakit-card { 
            background: white;
            
            /* Kembalikan Sudut Melengkung & Bayangan */
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
            padding: 15px 20px;
            font-size: 14px;
        }
    }
</style>

<div class="penyakit-wrapper">
    <div class="penyakit-card">
        
        {{-- Header Desktop (Akan hilang di Mobile) --}}
        <div class="card-header">
            <a href="{{ route('dashboard.beranda') }}" class="btn-back">‹</a>
            <div>
                <h1 class="card-title">Penyakit Sawit</h1>
            </div>
            <img src="{{ asset('images/logo-small.png') }}" style="height: 30px; margin-left: auto; opacity: 0.8;" alt="">
        </div>

        {{-- Body --}}
        <div class="card-body">
            <div class="section-title">Informasi</div>

            <div class="menu-list">
                
                {{-- 1. Lihat Daftar Penyakit --}}
                <a href="{{ route('dashboard.daftar-penyakit') }}" class="menu-item">
                    <div class="menu-text">
                        <span>🦠</span> Lihat Daftar Penyakit
                    </div>
                    <span class="arrow">›</span>
                </a>

                {{-- 2. Lihat Daftar Hama --}}
                <a href="{{ route('dashboard.daftar-hama') }}" class="menu-item">
                    <div class="menu-text">
                        <span>🐛</span> Lihat Daftar Hama
                    </div>
                    <span class="arrow">›</span>
                </a>

                {{-- 3. Foto Masalah Sawit --}}
                <a href="{{ route('dashboard.diagnosa') }}" class="menu-item">
                    <div class="menu-text">
                        <span>📸</span> Foto Masalah Sawit Anda
                    </div>
                    <span class="arrow">›</span>
                </a>

            </div>
        </div>

    </div>
</div>

@endsection
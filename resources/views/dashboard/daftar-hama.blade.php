@extends('layouts.app')
@section('title', 'Daftar Hama')

{{-- ============================================================ --}}
{{-- 1. DEFINISI HEADER MOBILE (Fixed Top)                        --}}
{{-- ============================================================ --}}
@section('mobile-header')
    <header class="mobile-header-custom">
        <div class="header-left-content">
            {{-- Tombol Kembali ke Menu Penyakit --}}
            <a href="{{ route('dashboard.penyakit') }}" class="mobile-back-btn">
                ‹
            </a>
            
            {{-- Judul Halaman --}}
            <h2 class="mobile-title">
                Daftar Hama
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
    .hama-wrapper {
        background-color: var(--bg-gray);
        min-height: 100vh;
        padding: 20px 30px;
        font-family: 'Poppins', sans-serif;
    }

    .hama-container {
        background: white;
        border-radius: 20px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.05);
        max-width: 800px;
        margin: 0 auto;
        overflow: hidden;
        min-height: 80vh;
        animation: slideUp 0.5s ease;
    }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Header Desktop */
    .hama-header {
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

    .header-title {
        font-size: 20px;
        font-weight: 700;
        margin: 0;
        flex-grow: 1;
    }

    .header-logo {
        height: 30px;
        opacity: 0.8;
    }

    /* GRID LAYOUT */
    .hama-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 20px;
        padding: 25px;
    }

    /* CARD STYLE */
    .hama-card {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 4px 10px rgba(0,0,0,0.08);
        display: flex;
        flex-direction: column;
        text-decoration: none;
        color: inherit;
        transition: all 0.3s ease;
        border: 1px solid #f0f0f0;
    }

    .hama-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(43, 122, 11, 0.15);
        border-color: var(--primary-green);
    }

    .hama-img-wrapper {
        width: 100%;
        aspect-ratio: 1 / 1;
        background: #f8f8f8;
        overflow: hidden;
        position: relative;
    }

    .hama-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .hama-card:hover .hama-img {
        transform: scale(1.1);
    }

    .hama-info {
        background-color: #f9f9f9;
        padding: 12px 15px;
        min-height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        border-top: 1px solid #eee;
    }

    .hama-name {
        font-size: 14px;
        font-weight: 600;
        color: #333;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* --- RESPONSIVE STYLES (FIXED CARD LOOK) --- */
    @media (max-width: 768px) {
        
        /* 1. Hilangkan Header Card Desktop */
        .hama-header { display: none !important; }

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
        .hama-wrapper { 
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
        .hama-container { 
            background: white;
            border-radius: 20px; 
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.05); 
            
            /* PENTING: Beri Jarak Atas agar pas di bawah Header (70px + 20px gap) */
            margin-top: 90px; 
            margin-bottom: 30px;
            width: 100%;
            min-height: auto;
        }
        
        /* Grid Adjustment for Mobile */
        .hama-grid {
            grid-template-columns: 1fr 1fr; /* 2 Kolom di HP */
            gap: 15px;
            padding: 20px;
        }

        .hama-info {
            padding: 10px;
            min-height: 50px;
        }

        .hama-name {
            font-size: 12px;
        }
    }
</style>

<div class="hama-wrapper">
    <div class="hama-container">
        
        {{-- Header Desktop (Akan hilang di Mobile) --}}
        <div class="hama-header">
            <a href="{{ route('dashboard.penyakit') }}" class="btn-back">‹</a>
            <div class="header-title">Daftar Hama</div>
            <img src="{{ asset('images/logo-small.png') }}" class="header-logo" alt="">
        </div>

        {{-- Grid Content --}}
        <div class="hama-grid">
            @forelse($hamas as $hama)
            <a href="{{ route('dashboard.detail-hama', $hama->id) }}" class="hama-card">
                <div class="hama-img-wrapper">
                    <img src="{{ $hama->image_url }}" class="hama-img" alt="{{ $hama->name }}"
                         onerror="this.src='https://placehold.co/300x300/e0e0e0/333?text=Hama'">
                </div>
                <div class="hama-info">
                    <span class="hama-name">{{ $hama->name }}</span>
                </div>
            </a>
            @empty
            <div style="grid-column: 1 / -1; text-align: center; padding: 50px 20px; color: #888;">
                <div style="font-size: 40px; margin-bottom: 10px;">🦗</div>
                <p>Belum ada data hama yang tersedia.</p>
            </div>
            @endforelse
        </div>

    </div>
</div>

@endsection
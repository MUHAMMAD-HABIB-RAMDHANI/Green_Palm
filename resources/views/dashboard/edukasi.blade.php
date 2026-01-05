@extends('layouts.app')
@section('title', 'Video Edukasi')

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
                Video Edukasi
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
    .edukasi-wrapper {
        background-color: var(--bg-gray);
        min-height: 100vh;
        padding: 20px 30px;
        font-family: 'Poppins', sans-serif;
    }

    .edukasi-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        max-width: 1100px;
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
    }

    .back-button:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateX(-5px);
    }

    .card-title {
        font-size: 24px;
        font-weight: 700;
        color: white;
        margin: 0;
        z-index: 1;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    /* Content Styling */
    .card-body {
        padding: 35px 40px;
    }

    .section-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--primary-green);
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f0f0f0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .video-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 25px;
    }

    .video-item {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        border: 1px solid #eee;
        transition: all 0.3s ease;
        position: relative;
        text-decoration: none;
        display: block;
    }

    .video-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(43, 122, 11, 0.15);
        border-color: var(--light-green);
    }

    .thumbnail-wrapper {
        position: relative;
        width: 100%;
        padding-top: 56.25%; /* 16:9 Aspect Ratio */
        background: #000;
        overflow: hidden;
    }

    .thumbnail-img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        opacity: 0.9;
        transition: opacity 0.3s;
    }

    .video-item:hover .thumbnail-img {
        opacity: 1;
    }

    .play-icon {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) scale(1);
        width: 60px;
        height: 60px;
        background: rgba(255, 0, 0, 0.85);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 24px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0,0,0,0.4);
        padding-left: 4px;
    }

    .video-item:hover .play-icon {
        transform: translate(-50%, -50%) scale(1.15);
        background: rgba(255, 0, 0, 1);
        box-shadow: 0 6px 20px rgba(255,0,0,0.5);
    }

    .video-info {
        padding: 16px;
        background: white;
    }

    .video-title {
        font-size: 15px;
        font-weight: 600;
        color: var(--text-dark);
        line-height: 1.5;
        margin-bottom: 10px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        min-height: 45px;
    }

    .video-meta {
        font-size: 12px;
        color: var(--text-muted);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
    }

    .empty-state {
        grid-column: 1/-1;
        text-align: center;
        padding: 60px 20px;
        color: #888;
    }

    .empty-state svg {
        width: 120px;
        height: 120px;
        margin-bottom: 20px;
        opacity: 0.5;
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
        .edukasi-wrapper { 
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
        .edukasi-card { 
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

        /* Grid adjustment */
        .video-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }
        
        .video-title { 
            font-size: 16px; 
        }
    }
</style>

<div class="edukasi-wrapper">
    <div class="edukasi-card">
        
        {{-- Header Desktop (Akan hilang di Mobile) --}}
        <div class="card-header">
            <a href="{{ route('dashboard.beranda') }}" class="back-button" title="Kembali ke Beranda">
                ‹
            </a>
            <h1 class="card-title">Video Edukasi</h1>
        </div>

        <div class="card-body">
            
            <div class="section-title">
                📚 Edukasi Pembudidayaan Kelapa Sawit
            </div>

            <div class="video-grid">
                @forelse($videos as $video)
                    <a href="{{ $video->url }}" target="_blank" class="video-item" rel="noopener noreferrer">
                        
                        <div class="thumbnail-wrapper">
                            {{-- Thumbnail otomatis dari YouTube --}}
                            <img src="{{ $video->thumbnail }}" 
                                 alt="{{ $video->title }}" 
                                 class="thumbnail-img"
                                 loading="lazy">
                            
                            <div class="play-icon">▶</div>
                        </div>

                        <div class="video-info">
                            <h3 class="video-title">{{ $video->title }}</h3>
                            <div class="video-meta">
                                <span>📺 Tonton di YouTube</span>
                                <span>{{ $video->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="empty-state">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                        <h3 style="margin: 0 0 10px; color: #555; font-size: 18px;">Belum Ada Video Edukasi</h3>
                        <p style="margin: 0; font-size: 14px;">Admin belum menambahkan video edukasi. Silakan cek kembali nanti.</p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</div>
@endsection
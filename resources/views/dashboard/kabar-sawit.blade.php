@extends('layouts.app')
@section('title', 'Kabar Sawit')

{{-- ============================================================ --}}
{{-- 1. DEFINISI HEADER MOBILE (Sama seperti halaman lain)        --}}
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
                Kabar Sawit
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
    .kabar-wrapper {
        background-color: var(--bg-gray);
        padding: 20px;
        font-family: 'Poppins', sans-serif;
        min-height: 100vh;
    }

    .kabar-card-container {
        background: white;
        border-radius: 20px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.05);
        max-width: 800px;
        margin: 0 auto;
        overflow: hidden;
        min-height: 80vh;
    }

    /* Header Hijau Desktop */
    .kabar-header {
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

    /* --- SEARCH BAR --- */
    .search-section {
        padding: 20px 25px 10px;
    }

    .search-box {
        position: relative;
        display: flex;
        align-items: center;
    }

    .search-input {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #ccc;
        border-radius: 10px;
        font-size: 14px;
        outline: none;
        transition: border-color 0.3s;
    }

    .search-input:focus {
        border-color: var(--primary-green);
    }

    .search-btn {
        position: absolute;
        right: 15px;
        background: none;
        border: none;
        font-size: 18px;
        color: #666;
        cursor: pointer;
    }

    /* --- POPULAR SECTION --- */
    .section-label {
        font-size: 18px;
        font-weight: 700;
        color: #222;
        margin: 15px 25px 10px;
    }

    .popular-scroll {
        display: flex;
        gap: 15px;
        overflow-x: auto;
        padding: 0 25px 20px;
        scrollbar-width: none;
    }
    
    .popular-scroll::-webkit-scrollbar { display: none; }

    .popular-card {
        min-width: 280px;
        height: 180px;
        border-radius: 15px;
        position: relative;
        overflow: hidden;
        flex-shrink: 0;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        text-decoration: none;
        display: block;
    }

    .popular-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .popular-card:hover .popular-img {
        transform: scale(1.05);
    }

    .popular-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        padding: 15px;
        background: linear-gradient(to top, rgba(0,0,0,0.9), transparent);
        color: white;
    }

    .popular-title {
        font-size: 15px;
        font-weight: 600;
        line-height: 1.4;
        text-shadow: 0 1px 3px rgba(0,0,0,0.5);
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* --- KATEGORI CHIPS --- */
    .category-scroll {
        display: flex;
        gap: 10px;
        overflow-x: auto;
        padding: 0 25px 20px;
        scrollbar-width: none;
    }

    .cat-chip {
        padding: 6px 16px;
        border: 1px solid var(--primary-green);
        border-radius: 20px;
        color: var(--primary-green);
        font-size: 13px;
        font-weight: 500;
        white-space: nowrap;
        text-decoration: none;
        transition: 0.2s;
    }

    .cat-chip:hover, .cat-chip.active {
        background: var(--primary-green);
        color: white;
    }

    /* --- NEWS LIST --- */
    .news-list {
        padding: 0 25px 30px;
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .news-item {
        display: flex;
        background: #e9ecef;
        border-radius: 12px;
        overflow: hidden;
        height: 105px;
        text-decoration: none;
        color: inherit;
        transition: transform 0.2s;
    }

    .news-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }

    .news-thumb {
        width: 105px;
        height: 105px;
        object-fit: cover;
        flex-shrink: 0;
    }

    .news-content {
        padding: 10px 15px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        flex: 1;
    }

    .news-title {
        font-size: 14px;
        font-weight: 600;
        color: #222;
        margin-bottom: 4px;
        line-height: 1.3;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .news-source {
        font-size: 11px;
        color: var(--primary-green);
        font-weight: 500;
        margin-bottom: 4px;
    }

    .news-date {
        font-size: 10px;
        color: #888;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    /* Pagination */
    .pagination-wrapper {
        padding: 0 25px 30px;
        display: flex;
        justify-content: center;
    }

    /* --- RESPONSIVE STYLES (Fixed Card Look) --- */
    @media (max-width: 768px) {
        /* 1. Hilangkan Header Card Desktop */
        .kabar-header { display: none !important; }

        /* 2. Header Mobile Fixed (Tetap di Atas) */
        .mobile-header-custom {
            display: flex;
            align-items: center;
            width: 100%;
            height: 70px;
            padding: 0 20px;
            
            /* Gradient Hijau */
            background: linear-gradient(135deg, #2b7a0b 0%, #1e5607 100%);
            box-shadow: 0 4px 15px rgba(43, 122, 11, 0.3);
            
            position: fixed;
            top: 0;
            left: 0;
            z-index: 999;
        }

        /* 3. Wrapper: Kembalikan Background Abu-abu & Atur Posisi */
        .kabar-wrapper { 
            /* Reset posisi akibat padding bawaan layout utama */
            margin-top: -80px; 
            margin-left: -20px; 
            margin-right: -20px;
            
            /* Pastikan background abu-abu agar kartu putih terlihat */
            background-color: #f8f9fa; 
            min-height: 100vh;
            
            /* Beri padding agar kartu tidak menempel ke tepi layar */
            padding: 0 15px; 
            
            /* Aktifkan Flex agar bisa mengatur margin auto jika perlu */
            display: flex;
            flex-direction: column;
        }
        
        /* 4. Card Container: Kembalikan Bentuk Kartu */
        .kabar-card-container { 
            background: white;
            
            /* Kembalikan Sudut Melengkung & Bayangan */
            border-radius: 20px; 
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.05); 
            
            /* PENTING: Beri Jarak Atas agar pas di bawah Header (70px + 20px gap) */
            margin-top: 90px; 
            margin-bottom: 30px;
            
            min-height: auto;
            width: 100%;
        }

        /* Adjustment Spacing Konten Dalam */
        .search-section { padding: 20px 20px 10px; }
        .popular-scroll, .category-scroll { padding-left: 20px; padding-right: 20px; }
        .section-label { margin: 15px 20px 10px; }
        .news-list { padding: 0 20px 30px; }
    }
</style>

<div class="kabar-wrapper">
    <div class="kabar-card-container">
        
        {{-- Header Desktop (Hilang di Mobile) --}}
        <div class="kabar-header">
            <a href="{{ route('dashboard.beranda') }}" class="btn-back">‹</a>
            <div>
                <h2 style="margin:0; font-size: 20px; font-weight:700;">Kabar Sawit</h2>
            </div>
            <img src="{{ asset('images/logo-small.png') }}" style="height: 30px; margin-left: auto; opacity: 0.8;" alt="">
        </div>

        {{-- 1. Search Bar --}}
        <div class="search-section">
            <form action="{{ route('dashboard.kabar-sawit') }}" method="GET">
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                
                <div class="search-box">
                    <input type="text" name="q" class="search-input" value="{{ request('q') }}" placeholder="Cari artikel atau tips...">
                    <button type="submit" class="search-btn">🔍</button>
                </div>
            </form>
        </div>

        {{-- 2. Section: Terpopuler --}}
        @if(empty(request('q')))
        <div class="section-label">Kabar Terpopuler</div>
        <div class="popular-scroll">
            @forelse($popularNews as $news)
            <a href="{{ $news->url }}" target="_blank" class="popular-card">
                <img src="{{ $news->image_url }}" 
                     class="popular-img" 
                     alt="{{ $news->title }}">
                <div class="popular-overlay">
                    <div class="popular-title">{{ $news->title }}</div>
                    <div style="font-size: 10px; opacity: 0.9; margin-top: 5px;">Baca Selengkapnya ↗</div>
                </div>
            </a>
            @empty
            <div class="popular-card">
                <img src="https://placehold.co/600x400/1E4620/fff?text=GreenPalm+News" class="popular-img">
                <div class="popular-overlay">
                    <div class="popular-title">Belum ada berita populer</div>
                </div>
            </div>
            @endforelse
        </div>
        @endif

        {{-- 3. Section: Kategori --}}
        <div class="section-label">Kategori</div>
        <div class="category-scroll">
            <a href="{{ route('dashboard.kabar-sawit', ['category' => 'Semua']) }}" 
               class="cat-chip {{ request('category') == 'Semua' || !request('category') ? 'active' : '' }}">
               Semua
            </a>
            <a href="{{ route('dashboard.kabar-sawit', ['category' => 'Tips Budidaya']) }}" 
               class="cat-chip {{ request('category') == 'Tips Budidaya' ? 'active' : '' }}">
               Tips Budidaya
            </a>
            <a href="{{ route('dashboard.kabar-sawit', ['category' => 'Industri']) }}" 
               class="cat-chip {{ request('category') == 'Industri' ? 'active' : '' }}">
               Industri
            </a>
            <a href="{{ route('dashboard.kabar-sawit', ['category' => 'Penyakit Sawit']) }}" 
               class="cat-chip {{ request('category') == 'Penyakit Sawit' ? 'active' : '' }}">
               Penyakit Sawit
            </a>
        </div>

        {{-- 4. Section: List Berita --}}
        <div class="section-label">
            {{ request('q') ? 'Hasil Pencarian' : 'Berita Terbaru' }}
        </div>

        <div class="news-list">
            @forelse($recentNews as $news)
            <a href="{{ $news->url }}" target="_blank" class="news-item">
                <img src="{{ $news->image_url }}" 
                     class="news-thumb" 
                     alt="News"
                     onerror="this.src='https://placehold.co/150x150/e6f1e3/1E4620?text=News'">
                
                <div class="news-content">
                    <div class="news-title">{{ $news->title }}</div>
                    <div class="news-source">
                        Sumber: {{ parse_url($news->url, PHP_URL_HOST) }} ↗
                    </div>
                    <div class="news-date">
                        📅 {{ \Carbon\Carbon::parse($news->published_at)->translatedFormat('d F Y') }}
                        | {{ $news->category }}
                    </div>
                </div>
            </a>
            @empty
            <div style="text-align: center; padding: 30px; color: #777;">
                <p style="margin-bottom: 5px; font-weight: 600;">Tidak ada berita ditemukan</p>
                <p style="font-size: 12px;">Coba kata kunci lain atau ubah kategori.</p>
            </div>
            @endforelse
        </div>

        {{-- 5. Pagination --}}
        <div class="pagination-wrapper">
            {{ $recentNews->links('pagination::bootstrap-4') }}
        </div>

    </div>
</div>
@endsection
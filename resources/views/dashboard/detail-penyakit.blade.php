@extends('layouts.app')

@section('title', $penyakit->name)

{{-- ============================================================ --}}
{{-- 1. DEFINISI HEADER MOBILE (Fixed Top)                        --}}
{{-- ============================================================ --}}
@section('mobile-header')
    <header class="mobile-header-custom">
        <div class="header-left-content">
            {{-- Tombol Kembali --}}
            <a href="{{ route('dashboard.daftar-penyakit') }}" class="mobile-back-btn">
                ‹
            </a>
            
            {{-- Judul Halaman --}}
            <h2 class="mobile-title">
                Detail Penyakit
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
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* --- DESKTOP BASE STYLES --- */
    .penyakit-wrapper {
        background-color: var(--bg-gray);
        min-height: 100vh;
        padding: 20px 30px;
        font-family: 'Poppins', sans-serif;
    }

    .penyakit-container {
        background: white;
        border-radius: 20px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.05);
        max-width: 600px;
        margin: 0 auto;
        overflow: hidden;
        animation: slideUp 0.5s ease;
        padding-bottom: 30px;
    }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Header Desktop */
    .penyakit-header {
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
        font-size: 18px;
        font-weight: 700;
        margin: 0;
        flex-grow: 1;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Hero Image */
    .detail-image-wrapper {
        width: 100%;
        height: 250px;
        background: #eee;
        overflow: hidden;
        position: relative;
    }

    .detail-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Content Styling */
    .detail-content {
        padding: 25px;
    }

    .detail-title {
        font-size: 24px;
        font-weight: 700;
        color: var(--primary-green);
        margin-bottom: 5px;
    }

    .detail-latin {
        font-style: italic;
        color: #666;
        font-size: 16px;
        margin-bottom: 20px;
        display: block;
        padding-bottom: 15px;
        border-bottom: 1px solid #eee;
    }

    .section-title {
        font-size: 16px;
        font-weight: 700;
        color: #333;
        margin-top: 20px;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .detail-text {
        font-size: 14px;
        line-height: 1.6;
        color: #444;
        text-align: justify;
        white-space: pre-line;
    }

    /* Kotak Solusi */
    .solution-box {
        background-color: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: 12px;
        padding: 15px;
        margin-top: 10px;
    }

    .solution-text {
        font-size: 14px;
        line-height: 1.6;
        color: #166534;
        white-space: pre-line;
    }

    /* --- RESPONSIVE STYLES (FIXED CARD LOOK) --- */
    @media (max-width: 768px) {
        
        /* 1. Hilangkan Header Card Desktop */
        .penyakit-header { display: none !important; }

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
        .penyakit-container { 
            background: white;
            border-radius: 20px; 
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.05); 
            
            /* PENTING: Beri Jarak Atas agar pas di bawah Header (70px + 20px gap) */
            margin-top: 90px; 
            margin-bottom: 30px;
            width: 100%;
            min-height: auto;
        }

        /* Adjust Image Height for Mobile */
        .detail-image-wrapper {
            height: 200px;
        }
    }
</style>

<div class="penyakit-wrapper">
    <div class="penyakit-container">
        
        {{-- Header Desktop (Akan hilang di Mobile) --}}
        <div class="penyakit-header">
            <a href="{{ route('dashboard.daftar-penyakit') }}" class="btn-back">‹</a>
            <div class="header-title">Detail Penyakit</div>
        </div>

        {{-- Gambar Penyakit Full Width --}}
        <div class="detail-image-wrapper">
            <img src="{{ $penyakit->image_url }}" class="detail-image" alt="{{ $penyakit->name }}"
                 onerror="this.src='https://placehold.co/600x400/e0e0e0/333?text=No+Image'">
        </div>

        {{-- Isi Konten --}}
        <div class="detail-content">
            <h1 class="detail-title">{{ $penyakit->name }}</h1>
            @if($penyakit->latin_name)
                <span class="detail-latin">({{ $penyakit->latin_name }})</span>
            @else
                <div style="margin-bottom: 20px; border-bottom: 1px solid #eee;"></div>
            @endif

            {{-- Deskripsi --}}
            <div class="section-title">
                📝 Deskripsi & Gejala
            </div>
            <div class="detail-text">
                {{ $penyakit->description ?? 'Tidak ada deskripsi tersedia.' }}
            </div>

            {{-- Solusi --}}
            <div class="section-title">
                🛡️ Solusi & Pengendalian
            </div>
            <div class="solution-box">
                <div class="solution-text">
                    {{ $penyakit->solution ?? 'Belum ada data solusi.' }}
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
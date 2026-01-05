@extends('layouts.app')
@section('title', 'Harga Sawit')

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
                Harga Sawit
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
    .page-wrapper {
        background-color: var(--bg-gray);
        min-height: 100vh;
        padding: 20px 30px;
        font-family: 'Poppins', sans-serif;
    }

    .content-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        max-width: 800px;
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
    }

    .back-button {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        color: white;
        font-size: 20px;
        transition: all 0.3s ease;
    }

    .back-button:hover {
        background: rgba(255, 255, 255, 0.3);
    }

    .card-title {
        font-size: 22px;
        font-weight: 700;
        color: white;
        margin: 0;
    }

    .header-subtitle {
        color: rgba(255,255,255,0.8);
        font-size: 13px;
        margin-top: 5px;
    }

    /* Card Body */
    .card-body {
        padding: 30px;
    }

    .location-info {
        margin-bottom: 20px;
        font-weight: 600;
        color: var(--text-dark);
        font-size: 16px;
    }

    /* Price List Styling */
    .price-list {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .price-item {
        display: flex;
        align-items: center;
        background: #fff;
        border: 1px solid #eee;
        border-radius: 15px;
        padding: 15px;
        transition: transform 0.2s ease, border-color 0.2s ease;
        text-decoration: none;
        color: inherit;
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
    }

    .price-item:hover {
        border-color: var(--primary-green);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(43, 122, 11, 0.1);
    }

    .item-icon {
        width: 50px;
        height: 50px;
        margin-right: 15px;
        flex-shrink: 0;
    }
    
    .item-icon img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 10px;
    }

    .item-details {
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .region-name {
        font-size: 15px;
        font-weight: 700;
        color: #000;
        margin-bottom: 4px;
    }

    .sub-info {
        font-size: 12px;
        color: #666;
    }

    .item-price-box {
        text-align: right;
        min-width: 100px;
    }

    .price-value {
        font-size: 16px;
        font-weight: 700;
        color: var(--primary-green);
        display: block;
    }

    .price-unit {
        font-size: 11px;
        color: #888;
        display: block;
    }

    .update-date {
        font-size: 10px;
        color: #999;
        display: block;
        margin-top: 4px;
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
        .page-wrapper { 
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
        .content-card { 
            background: white;
            border-radius: 20px; 
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.05); 
            
            /* PENTING: Beri Jarak Atas agar pas di bawah Header (70px + 20px gap) */
            margin-top: 90px; 
            margin-bottom: 30px;
            width: 100%;
        }
        
        .card-body { 
            padding: 20px 20px 30px 20px; 
        }

        /* Adjustments for Mobile List */
        .price-item { padding: 12px; }
        .item-icon { width: 45px; height: 45px; margin-right: 12px; }
        .region-name { font-size: 14px; }
        .price-value { font-size: 15px; }
    }
</style>

<div class="page-wrapper">
    <div class="content-card">
        
        {{-- Header Desktop (Akan hilang di Mobile) --}}
        <div class="card-header">
            <a href="{{ route('dashboard.beranda') }}" class="back-button">‹</a>
            <div>
                <h1 class="card-title">Harga Sawit</h1>
                <div class="header-subtitle">Daftar Harga TBS Sawit</div>
            </div>
            <img src="{{ asset('images/logo-small.png') }}" style="width: 40px; position: absolute; right: 30px; opacity: 0.8;" alt="">
        </div>

        {{-- Body --}}
        <div class="card-body">
            
            <div class="location-info">
                📍 Indonesia / 1 kg
            </div>

            <div class="price-list">
                @forelse($prices as $ram)
                {{-- Item Harga --}}
                <a href="{{ route('dashboard.harga-sawit.detail', $ram->id) }}" class="price-item">
                    
                    {{-- Foto --}}
                    <div class="item-icon">
                        @if($ram->foto_tampak_depan)
                            <img src="{{ asset('storage/' . $ram->foto_tampak_depan) }}" alt="Foto RAM">
                        @else
                            <img src="https://cdn-icons-png.flaticon.com/512/2674/2674047.png" alt="Icon">
                        @endif
                    </div>

                    {{-- Info --}}
                    <div class="item-details">
                        <span class="region-name">{{ $ram->nama_ram }}</span>
                        
                        <span class="sub-info" style="display: flex; align-items: center; gap: 4px;">
                            📍 {{ \Illuminate\Support\Str::limit($ram->lokasi_ram, 25) }}
                        </span>

                        @if($ram->nomor_wa)
                        <span class="sub-info" style="color: #2b7a0b; font-weight: 500; margin-top: 2px;">
                            📱 {{ $ram->nomor_wa }}
                        </span>
                        @endif

                        <div style="margin-top: 5px; display: flex; gap: 5px; flex-wrap: wrap;">
                            @if($ram->layanan_jemput_buah) 
                                <span style="font-size: 10px; background: #e6f1e3; color: #2b7a0b; padding: 2px 6px; border-radius: 4px;">🚚 Jemput</span> 
                            @endif
                            @if($ram->timbangan_digital) 
                                <span style="font-size: 10px; background: #e3f2fd; color: #0d47a1; padding: 2px 6px; border-radius: 4px;">⚖️ Digital</span> 
                            @endif
                        </div>
                    </div>

                    {{-- Harga --}}
                    <div class="item-price-box">
                        <span class="price-value">{{ $ram->formatted_harga }}</span>
                        <span class="price-unit">per kg</span>
                        <span class="update-date">
                            {{ $ram->updated_at->locale('id')->diffForHumans() }}
                        </span>
                    </div>
                </a>
                @empty
                <div style="text-align: center; padding: 40px; color: #999;">
                    <img src="https://cdn-icons-png.flaticon.com/512/4076/4076432.png" alt="Empty" style="width: 60px; opacity: 0.5; margin-bottom: 10px;">
                    <p>Belum ada data harga RAM.</p>
                </div>
                @endforelse
            </div>

        </div>
    </div>
</div>
@endsection
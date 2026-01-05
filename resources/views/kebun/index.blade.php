@extends('layouts.app')
@section('title', 'Daftar Kebun')

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
                Daftar Kebun
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
        --primary-blue: #1976d2;
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
    .kebun-wrapper {
        background-color: var(--bg-gray);
        min-height: 100vh;
        padding: 20px 30px;
        font-family: 'Poppins', sans-serif;
        padding-bottom: 40px; 
    }

    .kebun-container {
        background: white;
        border-radius: 20px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        max-width: 900px;
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

    /* Banner Promo */
    .promo-banner {
        background: linear-gradient(135deg, #e8f5e9 0%, #f1f8f4 100%);
        padding: 25px 30px;
        margin: 0;
        border-bottom: 1px solid #e0e0e0;
    }

    .promo-title {
        font-size: 17px;
        font-weight: 700;
        color: #1e4620;
        margin-bottom: 8px;
    }

    .promo-text {
        font-size: 14px;
        color: #555;
        margin-bottom: 18px;
        line-height: 1.6;
    }

    .promo-actions {
        display: flex;
        gap: 12px;
        margin-bottom: 0; 
        flex-wrap: wrap; 
    }

    .btn-action-top {
        flex: 1;
        padding: 13px 10px; 
        border-radius: 10px;
        font-size: 13px; 
        font-weight: 600;
        text-align: center;
        cursor: pointer;
        text-decoration: none;
        border: none;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        white-space: nowrap; 
    }

    .btn-panen {
        background: linear-gradient(135deg, var(--primary-green) 0%, var(--dark-green) 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(43, 122, 11, 0.3);
    }
    
    .btn-rawat {
        background-color: white;
        color: var(--primary-green);
        border: 2px solid var(--primary-green);
    }
    
    .btn-history-green {
        background-color: white;
        color: var(--dark-green);
        border: 2px solid var(--dark-green);
    }

    /* List Kebun */
    .card-body {
        padding: 30px;
        flex: 1;
    }

    .section-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--primary-green);
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 2px solid #f0f0f0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .kebun-list {
        display: grid;
        gap: 18px;
    }

    .kebun-card {
        border: 1px solid #eee;
        border-radius: 15px;
        overflow: hidden;
        background: white;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        position: relative;
    }

    .kebun-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--primary-green);
        transform: scaleY(0);
        transition: transform 0.3s ease;
    }

    .kebun-card:hover {
        border-color: var(--light-green);
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(43, 122, 11, 0.15);
    }
    
    .kebun-card:hover::before { transform: scaleY(1); }

    .kebun-link-wrapper {
        text-decoration: none;
        color: inherit;
        display: block;
        width: 100%;
    }

    .card-top {
        padding: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: white;
        padding-right: 70px;
    }

    .kebun-name {
        font-size: 17px;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 8px;
    }

    .kebun-loc {
        font-size: 14px;
        color: var(--text-muted);
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .arrow-icon {
        color: #999;
        font-size: 24px;
        font-weight: 300;
        transition: transform 0.3s ease;
    }

    .kebun-card:hover .arrow-icon { transform: translateX(5px); }

    .card-status {
        background: linear-gradient(135deg, #fff8e1 0%, #fff3cd 100%);
        color: #7d6608;
        padding: 14px 20px;
        font-size: 13px;
        font-weight: 500;
        border-top: 1px solid #ffe082;
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .btn-delete-kebun {
        position: absolute;
        top: 30px;
        right: 15px;
        width: 34px;
        height: 34px;
        background: var(--danger-red); 
        color: white;
        border: none;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 10;
        transition: all 0.2s;
        box-shadow: 0 4px 6px rgba(220, 53, 69, 0.3);
    }

    .btn-delete-kebun:hover {
        background: #b02a37;
        transform: scale(1.1);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 40px 30px;
        color: #999;
    }

    .empty-state-icon {
        font-size: 64px;
        margin-bottom: 20px;
        opacity: 0.5;
        animation: float 3s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }

    /* Bottom Button */
    .bottom-action { margin-top: 30px; width: 100%; }

    .btn-add-new {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        width: 100%;
        background: linear-gradient(135deg, var(--primary-green) 0%, var(--dark-green) 100%);
        color: white;
        text-align: center;
        padding: 16px 28px;
        border-radius: 15px;
        font-size: 16px;
        font-weight: 600;
        text-decoration: none;
        box-shadow: 0 6px 20px rgba(43, 122, 11, 0.35);
        transition: all 0.3s ease;
        border: none;
    }

    .btn-add-new:hover {
        background: linear-gradient(135deg, var(--dark-green) 0%, #0d2410 100%);
        transform: translateY(-3px);
    }

    /* --- RESPONSIVE STYLES (FIXED CARD LOOK) --- */
    @media (max-width: 768px) {
        /* 1. Hilangkan Header Card Desktop */
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
        .kebun-wrapper { 
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
        .kebun-container { 
            background: white;
            
            /* Kembalikan Sudut Melengkung & Bayangan */
            border-radius: 20px; 
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.05); 
            
            /* PENTING: Beri Jarak Atas agar pas di bawah Header (70px + 20px gap) */
            margin-top: 90px; 
            margin-bottom: 30px;
            width: 100%;
        }

        /* Adjustments */
        .promo-banner { padding: 20px; }
        .card-body { padding: 20px; }
        .promo-actions { flex-direction: column; }
    }
</style>

<div class="kebun-wrapper">
    <div class="kebun-container">
        
        {{-- Header Desktop (Akan hilang di Mobile) --}}
        <div class="card-header">
            <a href="{{ route('dashboard.beranda') }}" class="back-button" title="Kembali">‹</a>
            <h1 class="card-title">🌴 Daftar Kebun</h1>
        </div>

        {{-- Banner Promo & Action Buttons --}}
        <div class="promo-banner">
            <div class="promo-title">Optimalkan Kebun Anda</div>
            <div class="promo-text">
                Yuk, catat penjualan panen dan perawatan kebun untuk mengetahui performa kebun Anda.
            </div>
            
            {{-- Tombol Aksi --}}
            <div class="promo-actions">
                <a href="{{ route('panen.index') }}" class="btn-action-top btn-panen">
                    📝 Catat Panen
                </a>
                <a href="{{ route('catatan.menu') }}" class="btn-action-top btn-rawat">
                    🌱 Catat Rawat
                </a>
                <a href="{{ route('kebun.semua-riwayat') }}" class="btn-action-top btn-history-green">
                    📜 Riwayat
                </a>
            </div>
        </div>

        {{-- Pesan Sukses --}}
        @if(session('success'))
            <div style="background: #d1e7dd; color: #0f5132; padding: 10px 20px; margin: 20px 30px 0; border-radius: 10px;">
                ✅ {{ session('success') }}
            </div>
        @endif

        {{-- List Kebun --}}
        <div class="card-body">
            <div class="section-title">
                📋 Kebun Anda
            </div>

            <div class="kebun-list">
                @forelse($kebuns as $kebun)
                
                {{-- CARD WRAPPER --}}
                <div class="kebun-card">
                    
                    {{-- TOMBOL DELETE (GLOBAL MODAL) --}}
                    <button type="button" 
                            class="btn-delete-kebun confirm-delete" 
                            data-action="{{ route('kebun.destroy', $kebun->id) }}"
                            data-name="{{ $kebun->nama_kebun }}"
                            title="Hapus Kebun">
                        🗑️
                    </button>

                    {{-- LINK WRAPPER --}}
                    <a href="{{ route('kebun.show', $kebun->id) }}" class="kebun-link-wrapper">
                        <div class="card-top">
                            <div>
                                <div class="kebun-name">{{ $kebun->nama_kebun }}</div>
                                <div class="kebun-loc">
                                    📍 {{ $kebun->lokasi_kebun ?? 'Lokasi belum diatur' }}
                                </div>
                            </div>
                            <div class="arrow-icon">›</div>
                        </div>
                        <div class="card-status">
                            <span class="status-item">📏 {{ number_format($kebun->luas_lahan, 1) }} Ha</span>
                            <span class="status-separator">•</span>
                            <span class="status-item">📐 {{ $kebun->jumlah_hektar }} Hektar</span>
                            <span class="status-separator">•</span>
                            <span class="status-item">📅 Tanam {{ $kebun->tahun_tanam }}</span>
                        </div>
                    </a>
                </div>

                @empty
                <div class="empty-state">
                    <div class="empty-state-icon">🌴</div>
                    <p>Belum ada kebun yang terdaftar.</p>
                    <p style="font-size: 13px; color: #aaa;">Klik tombol di bawah untuk menambah kebun baru</p>
                </div>
                @endforelse
            </div>

            {{-- Tombol Tambah --}}
            <div class="bottom-action">
                <a href="{{ route('kebun.create') }}" class="btn-add-new">
                    <span style="font-size: 22px;">+</span>
                    <span>Tambah Kebun Baru</span>
                </a>
            </div>

        </div> 

    </div>
</div>

@endsection
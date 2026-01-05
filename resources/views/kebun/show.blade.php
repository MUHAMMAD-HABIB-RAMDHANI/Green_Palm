@extends('layouts.app')
@section('title', 'Detail Kebun')

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
                Detail Kebun
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
    .detail-wrapper {
        background-color: var(--bg-gray);
        min-height: 100vh;
        padding: 20px 30px;
        font-family: 'Poppins', sans-serif;
    }

    .detail-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        max-width: 900px;
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
        justify-content: space-between;
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

    .header-left {
        display: flex;
        align-items: center;
        gap: 20px;
        z-index: 1;
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
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .btn-edit {
        padding: 12px 24px;
        background: linear-gradient(135deg, #FFD700 0%, #FFA000 100%);
        border: none;
        color: #5d4037;
        border-radius: 12px;
        font-size: 15px;
        font-weight: 700;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        z-index: 1;
        box-shadow: 0 4px 15px rgba(255, 160, 0, 0.4);
    }

    .btn-edit:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(255, 160, 0, 0.6);
        filter: brightness(1.05);
    }

    /* Card Body */
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

    .detail-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        margin-bottom: 10px; 
    }

    .detail-item {
        padding: 20px;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 12px;
        border-left: 4px solid var(--primary-green);
        transition: all 0.3s ease;
    }

    .detail-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 15px rgba(43, 122, 11, 0.1);
    }

    .detail-label {
        font-size: 13px;
        font-weight: 600;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 10px;
    }

    .detail-value {
        font-size: 18px;
        font-weight: 600;
        color: var(--text-dark);
    }

    .info-badge {
        display: inline-block;
        padding: 6px 14px;
        background: var(--light-green);
        color: var(--primary-green);
        border-radius: 20px;
        font-size: 14px;
        font-weight: 600;
    }

    .info-badge.warning {
        background: #fff3cd;
        color: #856404;
    }

    .info-badge.info {
        background: #d1ecf1;
        color: #0c5460;
    }

    /* Toast Styles */
    .gp-toast-container { position: fixed; top: 20px; left: 50%; transform: translateX(-50%); z-index: 9999; pointer-events: none; display: flex; flex-direction: column; gap: 10px; }
    .gp-toast { min-width: 300px; max-width: 500px; padding: 14px 20px; border-radius: 12px; font-size: 15px; font-weight: 600; box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15); text-align: center; opacity: 0; transform: translateY(-20px); transition: all 0.3s ease; pointer-events: auto; }
    .gp-toast--success { background: #E9FFF0; border: 2px solid #BDEFCF; color: #0F6B3A; }
    .gp-toast--error { background: #FFECEC; border: 2px solid #FFC0C0; color: #8A1F11; }
    .gp-toast.is-show { opacity: 1; transform: translateY(0); }
    .gp-toast.is-hide { opacity: 0; transform: translateY(-20px); }

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
        .detail-wrapper { 
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
        .detail-card { 
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

        .detail-grid {
            grid-template-columns: 1fr; /* 1 Kolom di HP */
        }

        /* Tambahkan tombol edit melayang di bawah khusus mobile */
        .mobile-edit-btn-container {
            display: block;
            margin-top: 20px;
        }
        .btn-edit {
            width: 100%;
            justify-content: center;
        }
    }

    @media (min-width: 769px) {
        .mobile-edit-btn-container {
            display: none;
        }
    }
</style>

<div class="detail-wrapper">
    <div class="detail-card">
        
        {{-- Header Desktop (Akan hilang di Mobile) --}}
        <div class="card-header">
            <div class="header-left">
                <a href="{{ route('kebun.daftar') }}" class="back-button" title="Kembali">‹</a>
                <h1 class="card-title">{{ $kebun->nama_kebun }}</h1>
            </div>
            <a href="{{ route('kebun.edit', $kebun->id) }}" class="btn-edit">
                ✏️ Edit Data
            </a>
        </div>

        {{-- Card Body --}}
        <div class="card-body">
            <div class="section-title">
                📊 Informasi Kebun
            </div>

            <div class="detail-grid">
                <div class="detail-item">
                    <div class="detail-label">📍 Lokasi Kebun</div>
                    <div class="detail-value">{{ $kebun->lokasi_kebun }}</div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">📏 Luas Lahan</div>
                    <div class="detail-value">{{ number_format($kebun->luas_lahan, 2) }} ha</div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">📐 Jumlah Hektar</div>
                    <div class="detail-value">{{ $kebun->jumlah_hektar }} Hektar</div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">📅 Tahun Tanam</div>
                    <div class="detail-value">{{ $kebun->tahun_tanam }}</div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">🌱 Status Jenis Bibit</div>
                    <div class="detail-value">
                        @if($kebun->tahu_jenis_bibit == 1)
                            <span class="info-badge">✓ Tahu</span>
                        @else
                            <span class="info-badge warning">✗ Tidak Tahu</span>
                        @endif
                    </div>
                </div>

                @if($kebun->tahu_jenis_bibit == 1 && $kebun->jenis_bibit_nama)
                <div class="detail-item">
                    <div class="detail-label">🌿 Nama Jenis Bibit</div>
                    <div class="detail-value">
                        <span class="info-badge info">{{ $kebun->jenis_bibit_nama }}</span>
                    </div>
                </div>
                @endif

                <div class="detail-item">
                    <div class="detail-label">🌍 Jenis Tanah</div>
                    <div class="detail-value">
                        @if($kebun->jenis_tanah)
                            <span class="info-badge">{{ $kebun->jenis_tanah }}</span>
                        @else
                            <span style="color: #999;">Tidak ditentukan</span>
                        @endif
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">📅 Dibuat Pada</div>
                    <div class="detail-value" style="font-size: 15px;">
                        {{ $kebun->created_at->format('d M Y, H:i') }}
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">🔄 Terakhir Diupdate</div>
                    <div class="detail-value" style="font-size: 15px;">
                        {{ $kebun->updated_at->format('d M Y, H:i') }}
                    </div>
                </div>
            </div>

            {{-- Tombol Edit Khusus Mobile (Di Bawah) --}}
            <div class="mobile-edit-btn-container">
                <a href="{{ route('kebun.edit', $kebun->id) }}" class="btn-edit">
                    ✏️ Edit Data Kebun
                </a>
            </div>

        </div>
    </div>
</div>

<div id="toast-container" class="gp-toast-container" aria-live="polite" aria-atomic="true"></div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    @if(session('success'))
        showToast('{{ session('success') }}', 'success');
    @endif
    
    @if(session('error'))
        showToast('{{ session('error') }}', 'error');
    @endif
});

function showToast(message, type = 'success') {
    const container = document.getElementById('toast-container');
    const toast = document.createElement('div');
    toast.className = `gp-toast gp-toast--${type}`;
    toast.textContent = message;
    
    container.appendChild(toast);
    
    requestAnimationFrame(() => {
        toast.classList.add('is-show');
    });
    
    setTimeout(() => {
        toast.classList.remove('is-show');
        toast.classList.add('is-hide');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}
</script>

@endsection
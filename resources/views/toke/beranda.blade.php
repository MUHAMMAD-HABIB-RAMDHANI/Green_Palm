@extends('toke.toke')

@section('title', 'Beranda Toke')

@section('content')

<style>
    .dashboard-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 25px;
        max-width: 1400px;
        margin: 0 auto;
    }

    .card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        padding: 28px;
        transition: all 0.3s ease;
        border: 1px solid rgba(43, 122, 11, 0.08);
        position: relative;
        overflow: hidden;
    }

    .card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, #2b7a0b 0%, #1E4620 100%);
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }

    .card:hover::before {
        transform: scaleX(1);
    }

    .card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 28px rgba(43, 122, 11, 0.15);
        border-color: rgba(43, 122, 11, 0.2);
    }

    .card h3 {
        color: #2b7a0b;
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 18px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .info-header {
        font-size: 17px;
        font-weight: 600;
        color: #222;
        margin-bottom: 12px;
        padding: 12px 16px;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 10px;
        border-left: 4px solid #2b7a0b;
    }

    .weather-info {
        font-size: 16px;
        font-weight: 600;
        color: #1E4620;
        margin-top: 12px;
        padding: 14px 16px;
        background: linear-gradient(135deg, #e6f7ff 0%, #d4f1f4 100%);
        border-radius: 10px;
        display: flex;
        align-items: center;
        gap: 8px;
        border: 1px solid #b3e5fc;
    }

    /* RAM Card Styles */
    .ram-empty {
        padding: 50px 30px;
        background: linear-gradient(135deg, #fff9e6 0%, #ffe9b3 100%);
        border: 2px dashed #ffc107;
        border-radius: 16px;
        text-align: center;
    }

    .ram-empty-icon {
        font-size: 60px;
        margin-bottom: 15px;
    }

    .ram-empty-text {
        color: #856404;
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .ram-empty-desc {
        color: #856404;
        font-size: 14px;
        margin-bottom: 20px;
    }

    .ram-info-card {
        background: linear-gradient(135deg, #1e4620 0%, #2b7a0b 100%);
        border-radius: 20px;
        padding: 30px;
        color: white;
        box-shadow: 0 8px 24px rgba(43, 122, 11, 0.25);
        position: relative;
        overflow: hidden;
    }

    .ram-info-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    }

    .ram-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        position: relative;
        z-index: 1;
    }

    .ram-name {
        font-size: 24px;
        font-weight: 700;
        color: white;
        margin: 0;
    }

    .ram-details {
        position: relative;
        z-index: 1;
    }

    .ram-detail-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    }

    .ram-detail-item:last-child {
        border-bottom: none;
    }

    .ram-detail-label {
        font-size: 14px;
        opacity: 0.9;
        flex: 1;
    }

    .ram-detail-value {
        font-size: 16px;
        font-weight: 600;
    }

    .ram-facilities {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
        margin-top: 20px;
        position: relative;
        z-index: 1;
    }

    .facility-item {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 10px;
        padding: 12px;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .facility-item.active {
        background: rgba(40, 167, 69, 0.3);
        border-color: rgba(40, 167, 69, 0.5);
    }

    .btn-primary {
        padding: 12px 24px;
        background: linear-gradient(135deg, #2b7a0b 0%, #1E4620 100%);
        color: white;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        font-weight: 600;
        font-size: 15px;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 12px rgba(43, 122, 11, 0.3);
        text-decoration: none;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(43, 122, 11, 0.4);
        background: linear-gradient(135deg, #1E4620 0%, #0d2410 100%);
    }

    @media (min-width: 992px) {
        .dashboard-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        .card.full-width {
            grid-column: span 2;
        }
    }

    @media (max-width: 768px) {
        .ram-facilities {
            grid-template-columns: 1fr;
        }

        .ram-name {
            font-size: 20px;
        }
    }
</style>

<header class="main-header">
    <h1>🏠 Beranda Toke</h1>
    <div class="header-username-desktop">
        👋 Halo, {{ $user->username }}
    </div>
</header>

<div class="dashboard-grid">
    
    {{-- Card 1: Informasi Hari Ini --}}
    <div class="card">
        <h3>📅 Informasi Hari Ini</h3>
        <p class="info-header">{{ $tanggal }}</p>
        <p class="weather-info">
            🌤️ Cuaca: {{ ucfirst($kondisi_cuaca) }} ({{ $suhu }}°C)
        </p>
    </div>

    {{-- Card 2: Informasi RAM --}}
    <div class="card">
        <h3>🏭 Informasi RAM</h3>
        
        @if(!$ram)
            <div class="ram-empty">
                <div class="ram-empty-icon">🏭</div>
                <div class="ram-empty-text">Anda Belum Mengatur RAM</div>
                <p class="ram-empty-desc">Silakan tambahkan informasi RAM Anda untuk memulai</p>
                <a href="{{ route('toke.edit-ram') }}" class="btn-primary">
                    ➕ Tambah Data RAM
                </a>
            </div>
        @else
            <div class="ram-info-card">
                <div class="ram-header">
                    <h4 class="ram-name">{{ $ram->nama_ram }}</h4>
                    <a href="{{ route('toke.edit-ram') }}" class="btn-primary" style="font-size: 13px; padding: 8px 16px;">
                        ✏️ Edit
                    </a>
                </div>

                <div class="ram-details">
                    <div class="ram-detail-item">
                        <span class="ram-detail-label">📍 Lokasi</span>
                        <span class="ram-detail-value">{{ $ram->lokasi_ram }}</span>
                    </div>

                    {{-- ✅ TAMBAHAN: Nomor WhatsApp --}}
                    @if($ram->nomor_wa)
                    <div class="ram-detail-item">
                        <span class="ram-detail-label">📱 WhatsApp</span>
                        <span class="ram-detail-value">{{ $ram->nomor_wa }}</span>
                    </div>
                    @endif

                    <div class="ram-detail-item">
                        <span class="ram-detail-label">💰 Harga Beli TBS</span>
                        <span class="ram-detail-value">{{ $ram->formatted_harga }}/kg</span>
                    </div>
                </div>

                <div class="ram-facilities">
                    <div class="facility-item {{ $ram->layanan_jemput_buah ? 'active' : '' }}">
                        <span>{{ $ram->layanan_jemput_buah ? '✅' : '❌' }}</span>
                        <span>Layanan Jemput Buah</span>
                    </div>
                    <div class="facility-item {{ $ram->timbangan_digital ? 'active' : '' }}">
                        <span>{{ $ram->timbangan_digital ? '✅' : '❌' }}</span>
                        <span>Timbangan Digital</span>
                    </div>
                    <div class="facility-item {{ $ram->menerima_berondolan ? 'active' : '' }}">
                        <span>{{ $ram->menerima_berondolan ? '✅' : '❌' }}</span>
                        <span>Menerima Berondolan</span>
                    </div>
                    <div class="facility-item {{ $ram->tidak_ada_pengembalian ? 'active' : '' }}">
                        <span>{{ $ram->tidak_ada_pengembalian ? '✅' : '❌' }}</span>
                        <span>Tidak Ada Pengembalian</span>
                    </div>
                </div>
            </div>
        @endif
    </div>

</div>

{{-- Toast Notifications --}}
@php
    $s = session('success');
    $e = session('error');
    if ($s) $s = trim(str_replace(['✓','✔','×','✖'], '', $s));
    if ($e) $e = trim(str_replace(['✓','✔','×','✖'], '', $e));
@endphp

<div id="toast-container" class="gp-toast-container" aria-live="polite" aria-atomic="true">
    @if($s)
        <div class="gp-toast gp-toast--success">{{ $s }}</div>
    @endif
    @if($e)
        <div class="gp-toast gp-toast--error">{{ $e }}</div>
    @endif
</div>

@endsection
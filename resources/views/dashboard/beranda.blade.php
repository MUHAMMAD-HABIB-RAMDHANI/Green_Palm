@extends('layouts.app')

@section('title', 'Beranda')

@section('content')

{{-- =============================================
     STYLE SECTION
     ============================================= --}}
<style>
    /* --- MAIN HEADER STYLE (Desktop) --- */
    .main-header {
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #2b7a0b;
        padding: 20px 25px;
        border-radius: 16px;
        box-shadow: 0 4px 12px rgba(43, 122, 11, 0.2);
        color: white;
        position: relative;
        overflow: hidden;
    }

    .main-header::after {
        content: '🏠';
        position: absolute;
        right: -10px;
        bottom: -10px;
        font-size: 80px;
        opacity: 0.1;
        transform: rotate(-15deg);
    }

    .header-title {
        font-size: 24px;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
        position: relative;
        z-index: 1;
    }

    .header-user-info {
        font-size: 15px;
        font-weight: 600;
        background: rgba(255, 255, 255, 0.2);
        padding: 8px 16px;
        border-radius: 20px;
        backdrop-filter: blur(5px);
        display: flex;
        align-items: center;
        gap: 10px;
        position: relative;
        z-index: 1;
    }

    .badge-premium {
        background: linear-gradient(135deg, #FFD700 0%, #FDB931 100%);
        color: #8a6d3b;
        font-size: 11px;
        font-weight: 800;
        padding: 4px 10px;
        border-radius: 12px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        border: 1px solid rgba(255,255,255,0.5);
    }

    /* --- LAYOUT GRID --- */
    .dashboard-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 25px;
        max-width: 1400px;
        margin: 0 auto;
    }

    /* --- CARDS GLOBAL STYLE --- */
    .card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        padding: 25px;
        transition: all 0.3s ease;
        border: 1px solid rgba(43, 122, 11, 0.08);
        position: relative;
        overflow: hidden;
    }

    .card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; width: 100%; height: 4px;
        background: linear-gradient(90deg, #2b7a0b 0%, #1E4620 100%);
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }

    .card:hover::before { transform: scaleX(1); }
    .card:hover { transform: translateY(-5px); box-shadow: 0 12px 28px rgba(43, 122, 11, 0.15); }

    .card h3 {
        color: #2b7a0b;
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* --- [UPDATED] INFO & WEATHER WIDGET (VERTIKAL) --- */
    .info-widget-container {
        display: flex;
        flex-direction: column; /* Susunan vertikal (atas-bawah) */
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 12px;
        padding: 0;
        overflow: hidden;
    }

    .info-item {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 20px 25px; /* Spasi yang nyaman */
        border-bottom: 1px dashed #dcdcdc; /* Garis pemisah horizontal */
        transition: background 0.2s ease;
        width: 100%;
        box-sizing: border-box;
    }

    .info-item:hover {
        background: #ffffff;
    }

    /* Hilangkan garis border pada item terakhir (Cuaca) */
    .info-item:last-child {
        border-bottom: none;
    }

    .info-icon-box {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        flex-shrink: 0;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }

    .icon-date { background: #e8f5e9; color: #2b7a0b; }
    .icon-weather { background: #e3f2fd; color: #1976d2; }

    .info-text {
        flex: 1;
    }

    .info-text small {
        display: block;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #888;
        margin-bottom: 4px;
    }

    .info-value {
        font-size: 16px;
        font-weight: 700;
        color: #333;
        line-height: 1.2;
    }

    /* --- KEBUN WIDGET --- */
    .kebun-empty {
        padding: 30px; background: #fff9e6; border: 2px dashed #ffc107;
        border-radius: 16px; text-align: center;
    }

    .kebun-widget {
        background: linear-gradient(135deg, #144418 0%, #2b7a0b 100%);
        border-radius: 20px; padding: 25px; position: relative; overflow: hidden;
        box-shadow: 0 10px 25px rgba(43, 122, 11, 0.25); color: white;
        display: flex; align-items: center; justify-content: space-between;
        min-height: 140px;
    }

    .kebun-widget::before {
        content: ''; position: absolute; top: -50px; right: -50px;
        width: 150px; height: 150px; background: rgba(255, 255, 255, 0.1);
        border-radius: 50%; z-index: 0;
    }

    .kebun-widget::after {
        content: '🌴'; position: absolute; bottom: -20px; right: 10px;
        font-size: 100px; opacity: 0.15; z-index: 0; transform: rotate(-15deg);
    }

    .kebun-widget-content {
        position: relative; z-index: 1; text-align: center; flex: 1; padding: 0 15px;
    }

    .kebun-label {
        font-size: 13px; text-transform: uppercase; letter-spacing: 1px;
        opacity: 0.8; margin-bottom: 5px; font-weight: 500;
    }

    .kebun-name {
        font-size: 26px; font-weight: 800; margin: 5px 0;
        text-shadow: 0 2px 4px rgba(0,0,0,0.2); white-space: nowrap;
        overflow: hidden; text-overflow: ellipsis;
    }

    .kebun-pagination {
        font-size: 12px; background: rgba(0, 0, 0, 0.2);
        display: inline-block; padding: 4px 12px; border-radius: 15px; margin-top: 8px;
    }

    .btn-glass-nav {
        position: relative; z-index: 2; width: 40px; height: 40px;
        border-radius: 50%; background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(5px); border: 1px solid rgba(255, 255, 255, 0.3);
        color: white; font-size: 20px; display: flex; align-items: center;
        justify-content: center; cursor: pointer; transition: all 0.2s ease;
    }
    .btn-glass-nav:hover { background: rgba(255, 255, 255, 0.3); transform: scale(1.1); }
    .btn-glass-nav:active { transform: scale(0.95); }

    .kebun-action-area {
        margin-top: 20px; display: flex; justify-content: center;
    }

    .btn-action-soft {
        background: #f0f7f1; color: #1E4620; border: 1px solid #c3e6cb;
        padding: 12px 30px; border-radius: 12px; font-weight: 700;
        display: inline-flex; align-items: center; gap: 8px;
        text-decoration: none; transition: all 0.3s; cursor: pointer;
    }
    .btn-action-soft:hover {
        background: #2b7a0b; color: white; border-color: #2b7a0b;
        box-shadow: 0 5px 15px rgba(43, 122, 11, 0.2); transform: translateY(-2px);
    }

    /* --- FEATURE GRID --- */
    .feature-grid {
        display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 15px; margin-top: 15px;
    }

    .feature-item {
        display: flex; flex-direction: column; align-items: center;
        justify-content: center; text-align: center; padding: 20px 15px;
        border-radius: 12px; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        font-size: 14px; font-weight: 600; color: #222; text-decoration: none;
        transition: all 0.3s ease; border: 2px solid transparent;
        position: relative; overflow: hidden;
    }
    .feature-item:hover {
        border-color: #2b7a0b; transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(43, 122, 11, 0.2);
    }
    .feature-item .icon {
        width: 52px; height: 52px; object-fit: contain; margin-bottom: 10px;
        transition: transform 0.3s ease;
    }

    /* --- CHART & PREMIUM --- */
    .chart-container { position: relative; height: 300px; margin-bottom: 15px; }
    .stats-placeholder {
        padding: 40px; background: linear-gradient(135deg, #f0f0f0 0%, #e0e0e0 100%);
        border-radius: 12px; text-align: center; border: 2px dashed #ccc;
    }

    .premium-promo-card { background: linear-gradient(135deg, #ffd700 0%, #ffed4e 100%); border-radius: 16px; padding: 30px; text-align: center; position: relative; overflow: hidden; box-shadow: 0 8px 25px rgba(255, 215, 0, 0.3); }
    .promo-badge { display: inline-block; background: #ff6b6b; color: white; padding: 6px 16px; border-radius: 20px; font-size: 12px; font-weight: 700; margin-bottom: 15px; }
    .premium-icon-large { font-size: 60px; margin: 15px 0; }
    .premium-promo-card h4 { font-size: 24px; font-weight: 800; margin-bottom: 10px; color: #222; }
    .price-box { background: white; border-radius: 12px; padding: 20px; margin: 20px 0; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
    .price-strike { font-size: 14px; color: #999; text-decoration: line-through; }
    .price-main { display: flex; align-items: baseline; justify-content: center; gap: 4px; margin: 10px 0; }
    .amount { font-size: 42px; font-weight: 800; color: #2b7a0b; }
    .price-save { display: inline-block; background: #28a745; color: white; padding: 4px 12px; border-radius: 15px; font-size: 12px; font-weight: 700; }
    
    .premium-active-card { background: linear-gradient(135deg, #28a745 0%, #20c997 100%); border-radius: 16px; padding: 30px; text-align: center; color: white; box-shadow: 0 8px 25px rgba(40, 167, 69, 0.3); }
    .premium-icon { font-size: 50px; margin-bottom: 15px; }
    .premium-active-card h4 { font-size: 22px; font-weight: 700; margin-bottom: 10px; color: white; }
    .premium-info-box { background: rgba(255, 255, 255, 0.2); backdrop-filter: blur(10px); border-radius: 10px; padding: 15px; margin: 15px 0; display: flex; flex-direction: column; }

    .btn-primary {
        padding: 12px 24px; background: linear-gradient(135deg, #2b7a0b 0%, #1E4620 100%);
        color: white; border: none; border-radius: 10px; cursor: pointer;
        font-weight: 600; font-size: 15px; transition: all 0.3s ease;
        display: inline-flex; align-items: center; gap: 8px;
        box-shadow: 0 4px 12px rgba(43, 122, 11, 0.3); text-decoration: none;
    }
    .btn-primary:hover { transform: translateY(-2px); background: linear-gradient(135deg, #1E4620 0%, #0d2410 100%); color: white; }
    .btn-centered { width: 100%; max-width: 320px; justify-content: center; }

    /* --- RESPONSIVE --- */
    @media (min-width: 992px) {
        .dashboard-grid { grid-template-columns: repeat(2, 1fr); }
        .card.full-width { grid-column: span 2; }
        .feature-grid { grid-template-columns: repeat(5, 1fr); }
    }

    @media (max-width: 768px) {
        .main-header { display: none; }
        .feature-grid { grid-template-columns: repeat(2, 1fr); }
        .kebun-name { font-size: 20px; }
        /* Widget info sudah otomatis responsif karena vertikal layout */
    }
</style>

{{-- =============================================
     CONTENT HTML
     ============================================= --}}

{{-- Header Page (Akan hilang di Mobile karena CSS) --}}
<header class="main-header">
    <div class="header-title">
        🏠 Beranda
    </div>
    <div class="header-user-info">
        👋 Halo, {{ is_array($user) ? $user['username'] : $user->username }}
        
        @if(Auth::user()->isPremium())
            <span class="badge-premium">👑 PREMIUM</span>
        @endif
    </div>
</header>

<div class="dashboard-grid">
    
    {{-- Card 1: Informasi Hari Ini (LAYOUT VERTIKAL BARU) --}}
    <div class="card">
        <h3>📅 Informasi Hari Ini</h3>
        
        <div class="info-widget-container">
            {{-- Tanggal (Atas) --}}
            <div class="info-item">
                <div class="info-icon-box icon-date">🗓️</div>
                <div class="info-text">
                    <small>Hari & Tanggal</small>
                    <div class="info-value">{{ $tanggal }}</div>
                </div>
            </div>

            {{-- Cuaca (Bawah) --}}
            <div class="info-item">
                <div class="info-icon-box icon-weather">🌤️</div>
                <div class="info-text">
                    <small>Perkiraan Cuaca</small>
                    <div class="info-value">
                        {{ ucfirst($kondisi_cuaca) }} 
                        <span style="font-weight: 400; color: #666; font-size: 14px; margin-left:5px;">({{ $suhu }}°C)</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Card 2: Data Kebun --}}
    <div class="card">
        <h3>🌴 Data Kebun</h3>
        
        @if(!isset($daftarKebun) || $daftarKebun->isEmpty())
            <div class="kebun-empty">
                <div style="font-size: 40px; margin-bottom: 10px;">🤷‍♂️</div>
                <p style="color: #666; font-size: 16px; margin-bottom: 15px;">Anda belum memiliki data kebun.</p>
                <a href="{{ route('kebun.create') }}" class="btn-primary btn-centered">
                    ➕ Tambah Kebun Baru
                </a>
            </div>
        @else
            <div class="kebun-widget">
                <button class="btn-glass-nav" id="prevKebunBtn" onclick="changeKebun(-1)">&#8249;</button>

                <div class="kebun-widget-content">
                    <div class="kebun-label">Kebun Terpilih</div>
                    <div class="kebun-name" id="displayNamaKebun">
                        {{ $daftarKebun->first()->nama_kebun }}
                    </div>
                    @if($daftarKebun->count() > 1)
                        <div class="kebun-pagination">
                            <span id="currentKebunIndex">1</span> / {{ $daftarKebun->count() }}
                        </div>
                    @else
                        <div style="height: 24px;"></div> 
                    @endif
                </div>

                <button class="btn-glass-nav" id="nextKebunBtn" onclick="changeKebun(1)">&#8250;</button>
            </div>

            <div class="kebun-action-area">
                <a href="{{ route('catatan.menu') }}" class="btn-action-soft">
                    <span>📝</span>
                    <span>Buat Catatan Perawatan</span>
                </a>
            </div>
        @endif
    </div>

    {{-- Card 3: Fitur Lain --}}
    <div class="card full-width">
        <h3>📘 Fitur Lain</h3>
        <div class="feature-grid">
            <a href="{{ route('kebun.daftar') }}" class="feature-item">
                <img src="{{ asset('images/icons/catatan.png') }}" alt="Kebun" class="icon">
                <span>Kelola Kebun</span>
            </a>
            <a href="{{ route('dashboard.edukasi') }}" class="feature-item">
                <img src="{{ asset('images/icons/edukasi.png') }}" alt="Edukasi" class="icon">
                <span>Edukasi</span>
            </a>
            <a href="{{ route('dashboard.penyakit') }}" class="feature-item">
                <img src="{{ asset('images/icons/penyakit.png') }}" alt="Penyakit" class="icon">
                <span>Penyakit</span>
            </a>
            <a href="{{ route('dashboard.kabar-sawit') }}" class="feature-item">
                <img src="{{ asset('images/icons/kabar-sawit.png') }}" alt="Kabar Sawit" class="icon">
                <span>Kabar Sawit</span>
            </a>
            <a href="{{ route('dashboard.harga-sawit') }}" class="feature-item">
                <img src="{{ asset('images/icons/harga.png') }}" alt="Harga Sawit" class="icon">
                <span>Harga Sawit</span>
            </a>
        </div>
    </div>

    {{-- Card 4: Grafik Keuangan --}}
    <div class="card">
        <h3>📊 Grafik Pendapatan & Pengeluaran</h3>
        
        @if(isset($keuanganData) && count($keuanganData) > 0)
            <div class="chart-container">
                <canvas id="keuanganChart"></canvas>
            </div>
            <div style="text-align: center; margin-top: 20px;">
                <a href="{{ route('dashboard.laporan') }}" class="btn-primary">
                    📋 Lihat Detail
                </a>
            </div>
        @else
            <div class="stats-placeholder">
                <p>Belum ada data pendapatan & pengeluaran</p>
                <p style="font-size: 13px; margin-top: 10px; color: #999;">
                    Mulai catat panen dan perawatan kebun Anda
                </p>
            </div>
        @endif
    </div>
    
    {{-- Card 5: Status Akun --}}
    <div class="card">
        <h3>🎁 Status Akun</h3>
        
        @if($user->isPremium())
            {{-- TAMPILAN SUDAH PREMIUM (Tetap Sama) --}}
            <div class="premium-active-card">
                <div class="premium-icon">👑</div>
                <h4>Status Premium Aktif</h4>
                <p>Terima kasih telah menjadi member premium!</p>
                <div class="premium-info-box">
                    <span>Aktif hingga:</span>
                    <strong>{{ $user->premium_until->format('d M Y') }}</strong>
                </div>
                <p class="premium-days">{{ ceil($user->getRemainingPremiumDays()) }} hari lagi</p>
            </div>
        @else
            {{-- TAMPILAN BELUM PREMIUM (Menggunakan Gambar Iklan) --}}
            <style>
                /* CSS Khusus untuk Banner Iklan */
                .promo-banner-link {
                    display: block;
                    border-radius: 16px;
                    overflow: hidden;
                    transition: transform 0.3s ease, box-shadow 0.3s ease;
                    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
                }
                .promo-banner-link:hover {
                    transform: translateY(-5px);
                    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
                }
                .promo-banner-img {
                    width: 100%;
                    height: auto;
                    display: block; /* Menghilangkan gap di bawah gambar */
                    object-fit: cover;
                }
            </style>

            <a href="{{ route('premium.upgrade') }}" class="promo-banner-link">
                {{-- Pastikan nama file sesuai dengan yang ada di folder public/images --}}
                <img src="{{ asset('images/iklan-fitur-premium.png') }}" alt="Upgrade Premium Offline" class="promo-banner-img">
            </a>
        @endif
    </div>

</div>

{{-- =============================================
     JAVASCRIPT SECTION
     ============================================= --}}

{{-- 1. Script Carousel Kebun --}}
@if(isset($daftarKebun) && !$daftarKebun->isEmpty())
<script>
    const daftarKebun = @json($daftarKebun ?? []); 
    let currentIndex = 0;

    document.addEventListener('DOMContentLoaded', function() {
        const prevBtn = document.getElementById('prevKebunBtn');
        const nextBtn = document.getElementById('nextKebunBtn');
        
        if (daftarKebun.length <= 1) {
            if(prevBtn) prevBtn.style.visibility = 'hidden';
            if(nextBtn) nextBtn.style.visibility = 'hidden';
        }
    });

    function changeKebun(direction) {
        if (daftarKebun.length === 0) return;
        let newIndex = currentIndex + direction;
        if (newIndex < 0) newIndex = daftarKebun.length - 1;
        else if (newIndex >= daftarKebun.length) newIndex = 0;

        currentIndex = newIndex;
        updateKebunUI();
    }

    function updateKebunUI() {
        const kebun = daftarKebun[currentIndex];
        const namaEl = document.getElementById('displayNamaKebun');
        const counterEl = document.getElementById('currentKebunIndex');

        namaEl.style.opacity = 0;
        setTimeout(() => {
            namaEl.innerText = kebun.nama_kebun;
            namaEl.style.opacity = 1;
            if (counterEl) counterEl.innerText = currentIndex + 1;
        }, 150);
    }
</script>
@endif

{{-- 2. Script Chart Keuangan --}}
@if(isset($keuanganData) && count($keuanganData) > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('keuanganChart');
        if (!ctx) return;
        
        const keuanganData = @json($keuanganData);
        const labels = keuanganData.map(item => item.bulan);
        const pendapatanData = keuanganData.map(item => item.pendapatan);
        const pengeluaranData = keuanganData.map(item => item.pengeluaran);
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Pendapatan',
                    data: pendapatanData,
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#28a745',
                    pointRadius: 4,
                },
                {
                    label: 'Pengeluaran',
                    data: pengeluaranData,
                    borderColor: '#ff6b6b',
                    backgroundColor: 'rgba(255, 107, 107, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#ff6b6b',
                    pointRadius: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                if (value >= 1000000) return 'Rp ' + (value/1000000).toFixed(1) + 'jt';
                                if (value >= 1000) return 'Rp ' + (value/1000).toFixed(0) + 'k';
                                return value;
                            }
                        }
                    },
                    x: { grid: { display: false } }
                }
            }
        });
    });
</script>
@endif

@endsection
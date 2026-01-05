@extends('layouts.app')
@section('title', 'Laporan Keuangan')

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
                Laporan Keuangan
            </h2>
        </div>
    </header>
@endsection

@section('content')

<style>
    /* --- VARIABLES & BASE --- */
    :root {
        --primary-green: #2b7a0b;
        --dark-green: #1e5607;
        --bg-gray: #f8f9fa;
        --text-dark: #222;
        --text-muted: #777;
        --success-green: #28a745;
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
    .laporan-wrapper {
        background-color: var(--bg-gray);
        min-height: 100vh;
        padding: 20px 30px;
        font-family: 'Poppins', sans-serif;
    }

    .laporan-container {
        background: white;
        border-radius: 20px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        max-width: 1400px;
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
        padding: 24px 30px;
        background: linear-gradient(135deg, var(--primary-green) 0%, var(--dark-green) 100%);
        display: flex;
        align-items: center;
        gap: 20px;
        position: relative;
        overflow: hidden;
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
        transition: all 0.3s ease;
        z-index: 1;
    }

    .back-button:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateX(-3px);
    }

    .card-title {
        font-size: 24px;
        font-weight: 700;
        color: white;
        margin: 0;
        z-index: 1;
    }

    /* Body */
    .card-body {
        padding: 40px;
    }

    /* Filter & Periode */
    .filter-periode-wrapper {
        display: grid;
        grid-template-columns: 300px 1fr;
        gap: 30px;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 2px solid #f0f0f0;
    }

    .form-control-custom {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #eee;
        border-radius: 12px;
        background-color: #fcfcfc;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .form-control-custom:focus {
        outline: none;
        border-color: var(--primary-green);
        box-shadow: 0 0 0 4px rgba(43, 122, 11, 0.1);
    }

    .periode-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
    }

    .periode-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--text-dark);
        margin: 0;
    }

    .periode-range {
        font-size: 14px;
        color: var(--text-muted);
        background: #f0f0f0;
        padding: 6px 14px;
        border-radius: 10px;
        white-space: nowrap;
        font-weight: 600;
    }

    /* Top Summary Grid */
    .top-summary-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-bottom: 30px;
    }

    .pendapatan-bersih-card {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        padding: 25px;
        border-radius: 16px;
        color: white;
        box-shadow: 0 6px 20px rgba(220, 53, 69, 0.3);
        transition: all 0.3s ease;
    }

    .pendapatan-bersih-card.positive {
        background: linear-gradient(135deg, var(--success-green) 0%, #218838 100%);
        box-shadow: 0 6px 20px rgba(40, 167, 69, 0.3);
    }

    .pendapatan-bersih-label {
        font-size: 13px;
        opacity: 0.95;
        margin-bottom: 8px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .pendapatan-bersih-amount {
        font-size: 28px;
        font-weight: 700;
        margin: 0;
    }

    /* Summary Cards */
    .summary-card {
        padding: 25px;
        border-radius: 16px;
        border: 2px solid #e0e0e0;
        transition: all 0.3s ease;
    }

    .summary-card.pendapatan {
        background: linear-gradient(135deg, #e6f7e6 0%, #d4edda 100%);
        border-color: var(--success-green);
    }

    .summary-card.pengeluaran {
        background: linear-gradient(135deg, #ffe6e6 0%, #ffd4d4 100%);
        border-color: var(--danger-red);
    }

    .summary-title {
        font-size: 13px;
        color: var(--text-muted);
        margin-bottom: 8px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .summary-amount {
        font-size: 24px;
        font-weight: 700;
        margin: 0;
    }

    .summary-card.pendapatan .summary-amount { color: var(--success-green); }
    .summary-card.pengeluaran .summary-amount { color: var(--danger-red); }

    /* Detail Grid */
    .detail-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 25px;
        margin-bottom: 25px;
    }

    .detail-section-title {
        font-size: 16px;
        font-weight: 700;
        color: var(--primary-green);
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .detail-list {
        background: #f8f9fa;
        border-radius: 14px;
        padding: 20px;
        border: 1px solid #eee;
    }

    .detail-item {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px dashed #ddd;
        font-size: 14px;
        align-items: center;
    }

    .detail-item:last-child { border-bottom: none; }

    .detail-label { color: var(--text-dark); flex: 1; font-weight: 500; }
    .detail-value { font-weight: 700; color: var(--text-dark); text-align: right; }

    .detail-item.sub-item {
        padding-left: 15px;
        background-color: rgba(0,0,0,0.02);
        margin: 0 -20px;
        padding-right: 20px;
        border-bottom: 1px solid #eee;
    }
    
    .detail-item.sub-item .detail-label { font-size: 13px; color: #666; }
    .detail-item.sub-item .detail-value { font-size: 13px; }

    /* Chart Section */
    .chart-wrapper {
        background: #f8f9fa;
        border-radius: 14px;
        padding: 20px;
        margin-top: 30px;
    }

    .chart-container {
        position: relative;
        height: 350px;
        background: white;
        border-radius: 12px;
        padding: 15px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.03);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 30px;
        color: #999;
    }

    .empty-state-icon { font-size: 64px; margin-bottom: 20px; display: block; opacity: 0.5; }

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
        .laporan-wrapper { 
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
        .laporan-container { 
            background: white;
            border-radius: 20px; 
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.05); 
            
            /* PENTING: Beri Jarak Atas agar pas di bawah Header (70px + 20px gap) */
            margin-top: 90px; 
            margin-bottom: 30px;
            width: 100%;
        }
        
        .card-body { padding: 25px 20px; }

        /* Responsive Grids */
        .filter-periode-wrapper { grid-template-columns: 1fr; gap: 15px; }
        
        .periode-header { flex-direction: column; align-items: flex-start; gap: 5px; }
        
        .top-summary-grid { grid-template-columns: 1fr; gap: 15px; }
        
        .pendapatan-bersih-card { grid-column: auto; }
        
        .detail-grid { grid-template-columns: 1fr; gap: 20px; }
        
        .chart-container { height: 280px; }
    }
</style>

<div class="laporan-wrapper">
    <div class="laporan-container">
        
        {{-- Header Desktop (Akan hilang di Mobile) --}}
        <div class="card-header">
            <a href="{{ route('dashboard.beranda') }}" class="back-button">‹</a>
            <h1 class="card-title">📊 Laporan Keuangan</h1>
        </div>

        {{-- Body --}}
        <div class="card-body">
            
            @if(isset($laporanData['total_pendapatan']) && ($laporanData['total_pendapatan'] > 0 || $laporanData['total_pengeluaran'] > 0))
                
                {{-- Filter & Periode Section --}}
                <div class="filter-periode-wrapper">
                    {{-- Filter Section --}}
                    <div class="filter-section">
                        <form method="GET" action="{{ route('dashboard.laporan') }}">
                            <select name="kebun_id" class="form-control-custom" onchange="this.form.submit()">
                                <option value="">Semua Kebun</option>
                                @foreach($kebuns as $kebun)
                                    <option value="{{ $kebun->id }}" {{ request('kebun_id') == $kebun->id ? 'selected' : '' }}>
                                        {{ $kebun->nama_kebun }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>

                    {{-- Periode Header --}}
                    <div class="periode-header">
                        <h3 class="periode-title">Ringkasan Tahun Ini</h3>
                        <span class="periode-range">{{ $laporanData['periode'] }}</span>
                    </div>
                </div>

                {{-- Top Summary Grid (3 Columns) --}}
                <div class="top-summary-grid">
                    {{-- Pendapatan Bersih (Laba/Rugi) --}}
                    <div class="pendapatan-bersih-card {{ $laporanData['pendapatan_bersih'] >= 0 ? 'positive' : '' }}">
                        <div class="pendapatan-bersih-label">Pendapatan Bersih (Laba/Rugi)</div>
                        <h2 class="pendapatan-bersih-amount">
                            {{ $laporanData['pendapatan_bersih'] < 0 ? '-' : '' }} 
                            Rp {{ number_format(abs($laporanData['pendapatan_bersih']), 0, ',', '.') }}
                        </h2>
                    </div>

                    {{-- Total Pendapatan --}}
                    <div class="summary-card pendapatan">
                        <div class="summary-title">Total Pendapatan (Penjualan TBS)</div>
                        <h3 class="summary-amount">Rp {{ number_format($laporanData['total_pendapatan'], 0, ',', '.') }}</h3>
                    </div>

                    {{-- Total Pengeluaran --}}
                    <div class="summary-card pengeluaran">
                        <div class="summary-title">Total Pengeluaran (Panen & Perawatan)</div>
                        <h3 class="summary-amount">Rp {{ number_format($laporanData['total_pengeluaran'], 0, ',', '.') }}</h3>
                    </div>
                </div>

                {{-- Detail Grid (2 Columns) --}}
                <div class="detail-grid">
                    {{-- Detail Pendapatan --}}
                    <div class="detail-section">
                        <h3 class="detail-section-title">💰 Detail Pemasukan</h3>
                        <div class="detail-list">
                            <div class="detail-item">
                                <span class="detail-label">Total Berat TBS Terjual</span>
                                <span class="detail-value">{{ number_format($laporanData['total_berat_tbs'], 0, ',', '.') }} kg</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Rata-rata Harga TBS</span>
                                <span class="detail-value">Rp {{ number_format($laporanData['rata_harga_tbs'], 0, ',', '.') }}/kg</span>
                            </div>
                        </div>
                    </div>

                    {{-- Detail Pengeluaran --}}
                    <div class="detail-section">
                        <h3 class="detail-section-title">💸 Detail Pengeluaran</h3>
                        <div class="detail-list">
                            <div class="detail-item">
                                <span class="detail-label">Upah Panen (Tenaga Kerja)</span>
                                <span class="detail-value">Rp {{ number_format($laporanData['upah_panen'], 0, ',', '.') }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Biaya Perawatan Kebun (Upah + Bahan)</span>
                                <span class="detail-value">Rp {{ number_format($laporanData['upah_perawatan'], 0, ',', '.') }}</span>
                            </div>
                            {{-- Info detail pupuk --}}
                            <div class="detail-item sub-item">
                                <span class="detail-label">↳ Termasuk Pembelian Pupuk</span>
                                <span class="detail-value">Rp {{ number_format($laporanData['biaya_pupuk'], 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Chart Section --}}
                <div class="detail-section">
                    <h3 class="detail-section-title">📈 Grafik Keuangan Bulanan</h3>
                    <div class="chart-wrapper">
                        <div class="chart-container">
                            <canvas id="laporanChart"></canvas>
                        </div>
                    </div>
                </div>

            @else
                {{-- Empty State --}}
                <div class="filter-section">
                    <form method="GET" action="{{ route('dashboard.laporan') }}">
                        <select name="kebun_id" class="form-control-custom" onchange="this.form.submit()">
                            <option value="">Semua Kebun</option>
                            @foreach($kebuns as $kebun)
                                <option value="{{ $kebun->id }}" {{ request('kebun_id') == $kebun->id ? 'selected' : '' }}>
                                    {{ $kebun->nama_kebun }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>

                <div class="empty-state">
                    <span class="empty-state-icon">📊</span>
                    <p><strong>Belum Ada Data Laporan</strong></p>
                    <p style="font-size: 14px; color: #aaa;">
                        Mulai catat panen dan kegiatan perawatan kebun untuk melihat laporan keuangan Anda.
                    </p>
                </div>
            @endif

        </div> {{-- End Card Body --}}
    </div>
</div>

{{-- Chart.js Script --}}
@if(isset($laporanData['grafik_data']) && count($laporanData['grafik_data']) > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('laporanChart');
        if (!ctx) return;
        
        const grafikData = @json($laporanData['grafik_data']);
        
        const labels = grafikData.map(item => item.bulan);
        const pendapatanData = grafikData.map(item => item.pendapatan);
        const pengeluaranData = grafikData.map(item => item.pengeluaran);
        
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
                    borderWidth: 3,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    pointBackgroundColor: '#28a745',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                },
                {
                    label: 'Pengeluaran',
                    data: pengeluaranData,
                    borderColor: '#dc3545',
                    backgroundColor: 'rgba(220, 53, 69, 0.1)',
                    tension: 0.4,
                    fill: true,
                    borderWidth: 3,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    pointBackgroundColor: '#dc3545',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { 
                    mode: 'index', 
                    intersect: false 
                },
                plugins: {
                    legend: { 
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            font: { size: 13, family: 'Poppins', weight: '600' },
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: { size: 14, family: 'Poppins', weight: '600' },
                        bodyFont: { size: 13, family: 'Poppins' },
                        borderColor: 'rgba(255, 255, 255, 0.1)',
                        borderWidth: 1,
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) label += ': ';
                                return label + 'Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed.y);
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0, 0, 0, 0.05)', drawBorder: false },
                        ticks: {
                            padding: 10,
                            font: { size: 12, family: 'Poppins' },
                            callback: function(value) {
                                if (value >= 1000000) return 'Rp ' + (value / 1000000).toFixed(1) + 'jt';
                                if (value >= 1000) return 'Rp ' + (value / 1000).toFixed(0) + 'k';
                                return 'Rp ' + value;
                            }
                        }
                    },
                    x: {
                        grid: { display: false, drawBorder: false },
                        ticks: { padding: 10, font: { size: 12, family: 'Poppins', weight: '500' } }
                    }
                }
            }
        });
    });
</script>
@endif

@endsection
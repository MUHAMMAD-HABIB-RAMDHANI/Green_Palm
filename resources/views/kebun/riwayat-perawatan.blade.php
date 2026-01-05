@extends('layouts.app')
@section('title', 'Riwayat Perawatan - ' . $kebun->nama_kebun)

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
                📜 Riwayat Perawatan
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
    .riwayat-wrapper {
        background-color: var(--bg-gray);
        min-height: 100vh;
        padding: 20px 30px;
        font-family: 'Poppins', sans-serif;
    }

    .riwayat-container {
        background: white;
        border-radius: 20px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        max-width: 1000px;
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
        padding: 25px 30px;
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
        transition: all 0.3s ease;
        z-index: 1;
    }

    .back-button:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateX(-5px);
    }

    .header-content h1 {
        font-size: 24px;
        font-weight: 700;
        color: white;
        margin: 0 0 5px 0;
        z-index: 1;
        position: relative;
    }

    .header-content p {
        font-size: 14px;
        color: rgba(255, 255, 255, 0.9);
        margin: 0;
        z-index: 1;
        position: relative;
    }

    /* Card Body */
    .card-body {
        padding: 30px;
    }

    /* Summary Cards */
    .summary-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 15px;
        margin-bottom: 30px;
    }

    .summary-card {
        background: linear-gradient(135deg, #f5f5f5 0%, #e8e8e8 100%);
        padding: 15px;
        border-radius: 12px;
        text-align: center;
        border: 1px solid #e0e0e0;
    }

    .summary-number {
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 5px;
        color: var(--primary-green);
    }

    .summary-label {
        font-size: 13px;
        font-weight: 500;
        color: var(--text-muted);
    }

    /* Tab Navigation */
    .tab-navigation {
        display: flex;
        gap: 10px;
        margin-bottom: 25px;
        border-bottom: 2px solid #f0f0f0;
        overflow-x: auto;
        padding-bottom: 10px;
        scrollbar-width: none; /* Firefox */
    }
    
    .tab-navigation::-webkit-scrollbar { display: none; /* Chrome */ }

    .tab-btn {
        padding: 10px 20px;
        border: none;
        background: transparent;
        color: var(--text-muted);
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        border-radius: 8px 8px 0 0;
        transition: all 0.3s ease;
        white-space: nowrap;
    }

    .tab-btn:hover {
        background: var(--light-green);
        color: var(--primary-green);
    }

    .tab-btn.active {
        background: var(--primary-green);
        color: white;
    }

    /* Timeline Style */
    .timeline {
        position: relative;
        padding-left: 30px;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 8px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: linear-gradient(to bottom, var(--primary-green), #ddd);
    }
    
    .timeline-item {
        position: relative;
        padding: 20px;
        margin-bottom: 20px;
        background: white;
        border: 1px solid #eee;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        transition: all 0.3s ease;
    }

    .timeline-item:hover {
        transform: translateX(5px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        border-color: var(--light-green);
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: -29px;
        top: 25px;
        width: 14px;
        height: 14px;
        border-radius: 50%;
        background: white;
        border: 3px solid var(--primary-green);
        z-index: 1;
    }

    /* Activity Header */
    .activity-header { 
        display: flex; 
        justify-content: space-between; 
        align-items: flex-start; 
        margin-bottom: 15px; 
        border-bottom: 1px dashed #eee;
        padding-bottom: 10px;
    }

    .activity-type {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
    }

    .type-pemupukan { background: #e8f5e9; color: #2e7d32; }
    .type-penunasan { background: #e3f2fd; color: #1565c0; }
    .type-penyemprotan { background: #fff3e0; color: #e65100; }
    .type-sanitasi { background: #f3e5f5; color: #6a1b9a; }
    .type-kastrasi { background: #fce4ec; color: #c2185b; }

    .activity-meta {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 5px;
    }

    .activity-date {
        font-size: 13px;
        color: var(--text-muted);
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .btn-delete-mini {
        background: #fee2e2;
        border: 1px solid #fecaca;
        color: var(--danger-red);
        font-size: 11px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        border-radius: 6px;
        transition: background 0.2s;
    }

    .btn-delete-mini:hover {
        background: #fca5a5;
        color: #7f1d1d;
    }

    /* Activity Details */
    .activity-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 15px;
    }

    .detail-item {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .detail-label {
        font-size: 11px;
        color: var(--text-muted);
        font-weight: 600;
        text-transform: uppercase;
    }

    .detail-value {
        font-size: 14px;
        color: var(--text-dark);
        font-weight: 600;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 30px;
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
        .riwayat-wrapper { 
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
        .riwayat-container { 
            background: white;
            border-radius: 20px; 
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.05); 
            
            /* PENTING: Beri Jarak Atas agar pas di bawah Header (70px + 20px gap) */
            margin-top: 90px; 
            margin-bottom: 30px;
            width: 100%;
        }
        
        .card-body { 
            padding: 20px; 
        }

        .timeline { padding-left: 20px; }
        .timeline-item::before { left: -26px; }
        
        .activity-details { grid-template-columns: 1fr; gap: 10px; }
        
        .summary-cards { 
            grid-template-columns: repeat(2, 1fr); 
            gap: 10px; 
        }
        
        .summary-number { font-size: 20px; }
    }
</style>

<div class="riwayat-wrapper">
    <div class="riwayat-container">
        
        {{-- Header Desktop (Akan hilang di Mobile) --}}
        <div class="card-header">
            <a href="{{ route('kebun.daftar') }}" class="back-button" title="Kembali">‹</a>
            <div class="header-content">
                <h1>📜 Riwayat Perawatan</h1>
                <p>Data kegiatan perawatan di Kebun: {{ $kebun->nama_kebun }}</p>
            </div>
        </div>

        <div class="card-body">
            
            @if(session('success'))
                <div style="background: #d1e7dd; color: #0f5132; padding: 12px; border-radius: 10px; margin-bottom: 20px; font-size: 14px; border: 1px solid #badbcc;">
                    ✅ {{ session('success') }}
                </div>
            @endif

            {{-- Summary Cards --}}
            <div class="summary-cards">
                <div class="summary-card"><div class="summary-number">{{ $allActivities->count() }}</div><div class="summary-label">Total</div></div>
                <div class="summary-card"><div class="summary-number">{{ $pemupukan->count() }}</div><div class="summary-label">🌾 Pupuk</div></div>
                <div class="summary-card"><div class="summary-number">{{ $penunasan->count() }}</div><div class="summary-label">✂️ Tunas</div></div>
                <div class="summary-card"><div class="summary-number">{{ $penyemprotan->count() }}</div><div class="summary-label">💧 Semprot</div></div>
                <div class="summary-card"><div class="summary-number">{{ $sanitasi->count() }}</div><div class="summary-label">🧹 Sanitasi</div></div>
                <div class="summary-card"><div class="summary-number">{{ $kastrasi->count() }}</div><div class="summary-label">🌿 Kastrasi</div></div>
            </div>

            {{-- Tab Navigation --}}
            <div class="tab-navigation">
                <button class="tab-btn active" onclick="filterActivity('semua')">📊 Semua</button>
                <button class="tab-btn" onclick="filterActivity('pemupukan')">🌾 Pemupukan</button>
                <button class="tab-btn" onclick="filterActivity('penunasan')">✂️ Penunasan</button>
                <button class="tab-btn" onclick="filterActivity('penyemprotan')">💧 Penyemprotan</button>
                <button class="tab-btn" onclick="filterActivity('sanitasi')">🧹 Sanitasi</button>
                <button class="tab-btn" onclick="filterActivity('kastrasi')">🌿 Kastrasi</button>
            </div>

            @if($allActivities->isEmpty())
                <div class="empty-state">
                    <div class="empty-state-icon">📝</div>
                    <p>Belum ada kegiatan perawatan yang tercatat</p>
                </div>
            @else
                <div class="timeline">
                    @foreach($allActivities as $activity)
                        <div class="timeline-item activity-{{ $activity['type'] }}">
                            
                            <div class="activity-header">
                                <span class="activity-type type-{{ $activity['type'] }}">
                                    @switch($activity['type'])
                                        @case('pemupukan') 🌾 Pemupukan @break
                                        @case('penunasan') ✂️ Penunasan @break
                                        @case('penyemprotan') 💧 Penyemprotan @break
                                        @case('sanitasi') 🧹 Sanitasi @break
                                        @case('kastrasi') 🌿 Kastrasi @break
                                    @endswitch
                                </span>

                                <div class="activity-meta">
                                    <span class="activity-date">📅 {{ \Carbon\Carbon::parse($activity['date'])->format('d M Y') }}</span>
                                    
                                    {{-- Tombol Delete dengan SweetAlert/Confirm --}}
                                    <button type="button" class="btn-delete-mini confirm-delete" 
                                            data-action="{{ route('catatan.destroy', ['jenis' => $activity['type'], 'id' => $activity['data']->id]) }}"
                                            title="Hapus">
                                        🗑️ Hapus
                                    </button>
                                </div>
                            </div>

                            <div class="activity-details">
                                {{-- Detail Umum --}}
                                <div class="detail-item">
                                    <span class="detail-label">Kebun</span>
                                    <span class="detail-value" style="color: var(--primary-green);">
                                        {{ $activity['data']->kebun->nama_kebun ?? '-' }}
                                    </span>
                                </div>

                                {{-- Detail Spesifik per Tipe --}}
                                @if($activity['type'] === 'pemupukan')
                                    <div class="detail-item">
                                        <span class="detail-label">Info Pupuk</span>
                                        <span class="detail-value">{{ $activity['data']->jenis_pupuk }} ({{ $activity['data']->total_pupuk }} kg)</span>
                                    </div>
                                @elseif($activity['type'] === 'penyemprotan')
                                    <div class="detail-item">
                                        <span class="detail-label">Pestisida</span>
                                        <span class="detail-value">{{ $activity['data']->jenis_pestisida_racun ?? '-' }} ({{ $activity['data']->penggunaan_pestisida }} L)</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Luas Lahan</span>
                                        <span class="detail-value">{{ $activity['data']->luas_lahan_disemprot }} Ha</span>
                                    </div>
                                @else
                                    <div class="detail-item">
                                        <span class="detail-label">Jumlah Pokok</span>
                                        <span class="detail-value">
                                            @if($activity['type'] === 'penunasan') {{ $activity['data']->jumlah_pokok_ditunas }}
                                            @elseif($activity['type'] === 'sanitasi') {{ $activity['data']->jumlah_pokok_disanitasi }}
                                            @elseif($activity['type'] === 'kastrasi') {{ $activity['data']->jumlah_pokok_dikastrasi }}
                                            @endif
                                            pokok
                                        </span>
                                    </div>
                                @endif

                                {{-- Kolom Kanan: Rincian Biaya --}}
                                <div class="detail-item" style="grid-column: 1 / -1;">
                                    <span class="detail-label">Rincian Pengeluaran</span>
                                    <div style="background: #f8f9fa; padding: 12px; border-radius: 8px; margin-top: 5px; border: 1px dashed #ddd;">
                                        
                                        {{-- 1. RINCIAN UPAH DINAMIS --}}
                                        @if(!empty($activity['data']->rincian_upah) && is_array($activity['data']->rincian_upah))
                                            <ul style="list-style: none; padding: 0; margin: 0; font-size: 13px;">
                                                @foreach($activity['data']->rincian_upah as $biaya)
                                                    <li style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                                                        <span class="text-muted">{{ $biaya['jenis'] ?? 'Biaya Lain' }}</span>
                                                        <span style="font-weight: 600;">Rp {{ number_format($biaya['jumlah'], 0, ',', '.') }}</span>
                                                    </li>
                                                @endforeach
                                                <li style="border-top: 1px solid #ddd; margin-top: 5px; padding-top: 5px; display: flex; justify-content: space-between;">
                                                    <strong>Subtotal Upah</strong>
                                                    <strong style="color: var(--primary-green);">Rp {{ number_format($activity['data']->total_upah, 0, ',', '.') }}</strong>
                                                </li>
                                            </ul>
                                        @else
                                            {{-- Fallback Data Lama --}}
                                            <div style="display: flex; justify-content: space-between; font-size: 13px;">
                                                <span class="text-muted">Total Upah (Lama)</span>
                                                <strong style="color: var(--primary-green);">Rp {{ number_format($activity['data']->total_upah, 0, ',', '.') }}</strong>
                                            </div>
                                        @endif

                                        {{-- 2. BIAYA LAIN / BELI BARANG --}}
                                        @php 
                                            $biayaLain = 0;
                                            $labelBiaya = 'Biaya Lain';
                                            
                                            if($activity['type'] === 'pemupukan') {
                                                $biayaLain = $activity['data']->biaya_pembelian ?? 0;
                                                $labelBiaya = '🛒 Beli Pupuk';
                                            } elseif($activity['type'] === 'penyemprotan') {
                                                $biayaLain = $activity['data']->biaya_lain ?? 0;
                                                $labelBiaya = '🛒 Beli Racun/Pestisida';
                                            } else {
                                                $biayaLain = $activity['data']->biaya_lain ?? 0;
                                                $labelBiaya = '🛠️ Biaya Alat/Lain';
                                            }
                                        @endphp

                                        @if($biayaLain > 0)
                                            <div style="display: flex; justify-content: space-between; font-size: 13px; margin-top: 8px; padding-top: 8px; border-top: 1px dashed #ccc;">
                                                <span class="text-dark">{{ $labelBiaya }}</span>
                                                <strong style="color: #d63384;">Rp {{ number_format($biayaLain, 0, ',', '.') }}</strong>
                                            </div>
                                        @endif

                                        {{-- 3. GRAND TOTAL --}}
                                        @php $grandTotal = $activity['data']->total_upah + $biayaLain; @endphp
                                        @if($grandTotal > 0)
                                            <div style="display: flex; justify-content: space-between; font-size: 14px; margin-top: 8px; padding-top: 8px; border-top: 2px solid #ddd;">
                                                <strong>TOTAL</strong>
                                                <strong style="color: var(--danger-red);">Rp {{ number_format($grandTotal, 0, ',', '.') }}</strong>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function filterActivity(type) {
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    event.target.classList.add('active');

    const items = document.querySelectorAll('.timeline-item');
    items.forEach(item => {
        if (type === 'semua') {
            item.style.display = 'block';
        } else {
            if (item.classList.contains(`activity-${type}`)) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        }
    });
}
</script>

@endsection
@extends('layouts.app')

@section('title', 'Detail Panen')

@section('content')

<style>
    /* --- VARIABLES --- */
    :root {
        --primary-green: #2b7a0b;
        --dark-green: #1e5607;
        --bg-gray: #f8f9fa;
        --text-dark: #333;
        --text-muted: #666;
        --danger-red: #d32f2f;
    }

    /* --- LAYOUT --- */
    .detail-wrapper {
        background-color: var(--bg-gray);
        min-height: 100vh;
        padding: 20px 30px;
        font-family: 'Poppins', sans-serif;
        display: flex;
        justify-content: center;
    }

    .detail-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        width: 100%;
        max-width: 600px;
        overflow: hidden;
        animation: fadeIn 0.5s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* --- HEADER --- */
    .card-header {
        background: linear-gradient(135deg, var(--primary-green) 0%, var(--dark-green) 100%);
        padding: 20px 25px;
        display: flex;
        align-items: center;
        gap: 15px;
        color: white;
    }

    .btn-back {
        width: 38px;
        height: 38px;
        background: rgba(255,255,255,0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        text-decoration: none;
        font-size: 20px;
        transition: 0.3s;
    }

    .btn-back:hover {
        background: rgba(255,255,255,0.3);
    }

    .header-title h1 {
        font-size: 18px;
        font-weight: 700;
        margin: 0;
    }

    .header-title p {
        font-size: 13px;
        margin: 0;
        opacity: 0.9;
    }

    /* --- LABA BERSIH CARD (Highlight) --- */
    .profit-card {
        background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
        margin: 25px;
        padding: 20px;
        border-radius: 16px;
        text-align: center;
        border: 1px solid #a5d6a7;
    }

    .profit-label {
        font-size: 14px;
        color: var(--dark-green);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .profit-value {
        font-size: 32px;
        font-weight: 800;
        color: var(--primary-green);
        margin: 5px 0;
    }

    /* --- GRID STATS (Pendapatan vs Pengeluaran) --- */
    .stats-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
        padding: 0 25px 25px;
    }

    .stat-item {
        background: #fff;
        border: 1px solid #eee;
        padding: 15px;
        border-radius: 12px;
    }

    .stat-label {
        font-size: 12px;
        color: var(--text-muted);
        margin-bottom: 5px;
    }

    .stat-value {
        font-size: 16px;
        font-weight: 700;
        color: var(--text-dark);
    }

    .text-green { color: var(--primary-green); }
    .text-red { color: var(--danger-red); }

    /* --- INFO SECTION --- */
    .info-section {
        padding: 0 25px 25px;
    }

    .section-title {
        font-size: 15px;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 15px;
        border-bottom: 2px solid #f0f0f0;
        padding-bottom: 10px;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 12px;
        font-size: 14px;
    }

    .info-label { color: var(--text-muted); }
    .info-data { font-weight: 600; color: var(--text-dark); text-align: right;}

    /* --- BIAYA LIST --- */
    .biaya-list {
        background: #fafafa;
        border-radius: 12px;
        padding: 15px;
        border: 1px dashed #ddd;
    }

    .biaya-item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 8px;
        font-size: 13px;
        color: #555;
    }
    
    .biaya-item:last-child { margin-bottom: 0; }

    /* --- ACTION BUTTONS --- */
    .action-buttons {
        padding: 20px 25px;
        background: #fff;
        border-top: 1px solid #eee;
        display: flex;
        gap: 10px;
    }

    .btn {
        flex: 1;
        padding: 12px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        text-align: center;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: 0.2s;
    }

    .btn-edit {
        background: #fff3cd;
        color: #856404;
    }
    .btn-edit:hover { background: #ffeeba; }

    .btn-delete {
        background: #ffebee;
        color: #c62828;
    }
    .btn-delete:hover { background: #ffcdd2; }

    /* Responsive */
    @media (max-width: 480px) {
        .detail-wrapper { padding: 0; }
        .detail-card { border-radius: 0; max-width: 100%; box-shadow: none; }
        .stats-grid { grid-template-columns: 1fr; }
    }
</style>

<div class="detail-wrapper">
    <div class="detail-card">
        
        {{-- HEADER --}}
        <div class="card-header">
            <a href="{{ route('panen.index') }}" class="btn-back">‹</a>
            <div class="header-title">
                <h1>Detail Panen</h1>
                <p>{{ \Carbon\Carbon::parse($panen->tanggal_panen)->format('d F Y') }}</p>
            </div>
        </div>

        {{-- CALCULATIONS --}}
        @php
            $pendapatan = $panen->pendapatan;
            $pengeluaran = $panen->total_upah_panen;
            $labaBersih = $pendapatan - $pengeluaran;
            // Hitung balik estimasi harga per kg (jika ada berat)
            $estimasiHarga = $panen->berat_total_tbs > 0 ? ($pendapatan / $panen->berat_total_tbs) : 0;
        @endphp

        {{-- LABA BERSIH CARD --}}
        <div class="profit-card">
            <div class="profit-label">Keuntungan Bersih</div>
            
            {{-- LOGIKA WARNA: Jika < 0 (Minus) pakai text-red, jika tidak pakai text-green --}}
            <div class="profit-value {{ $labaBersih < 0 ? 'text-red' : 'text-green' }}">
                Rp {{ number_format($labaBersih, 0, ',', '.') }}
            </div>

            <div style="font-size: 12px; color: #555;">
                (Pendapatan - Pengeluaran)
            </div>
        </div>

        {{-- GRID PENDAPATAN & PENGELUARAN --}}
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-label">Total Pendapatan</div>
                <div class="stat-value text-green">+ Rp {{ number_format($pendapatan, 0, ',', '.') }}</div>
            </div>
            <div class="stat-item">
                <div class="stat-label">Total Pengeluaran</div>
                <div class="stat-value text-red">- Rp {{ number_format($pengeluaran, 0, ',', '.') }}</div>
            </div>
        </div>

        {{-- INFORMASI KEBUN & HASIL --}}
        <div class="info-section">
            <div class="section-title">Informasi Panen</div>
            
            <div class="info-row">
                <span class="info-label">Lokasi Kebun</span>
                <span class="info-data">{{ $panen->kebun->nama_kebun }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Berat Total TBS</span>
                <span class="info-data">{{ $panen->berat_total_tbs }} Kg</span>
            </div>
            <div class="info-row">
                <span class="info-label">Harga TBS /kg</span>
                <span class="info-data">Rp {{ number_format($estimasiHarga, 0, ',', '.') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Jumlah Janjang</span>
                <span class="info-data">{{ $panen->jumlah_tbs ?? '-' }} Tandan</span>
            </div>
            <div class="info-row">
                <span class="info-label">Berat Brondolan</span>
                <span class="info-data">{{ $panen->berat_brondolan ?? '0' }} Kg</span>
            </div>
            <div class="info-row">
                <span class="info-label">Panen Berikutnya</span>
                <span class="info-data">
                    @if($panen->tanggal_panen_berikutnya)
                        {{ \Carbon\Carbon::parse($panen->tanggal_panen_berikutnya)->format('d M Y') }}
                    @else
                        -
                    @endif
                </span>
            </div>
        </div>

        {{-- RINCIAN PENGELUARAN --}}
        @if($panen->total_upah_panen > 0)
        <div class="info-section">
            <div class="section-title">Rincian Pengeluaran</div>
            <div class="biaya-list">
                
                {{-- FIX: Pastikan data berupa array sebelum di-loop --}}
                @php
                    $listBiaya = $panen->biaya_lainnya;
                    
                    // Jika terdeteksi string (kasus error double encode), decode manual
                    if (is_string($listBiaya)) {
                        $listBiaya = json_decode($listBiaya, true);
                    }

                    // Pastikan null safe agar tidak error
                    if (!is_array($listBiaya)) {
                        $listBiaya = [];
                    }
                @endphp

                {{-- Loop menggunakan variabel baru $listBiaya --}}
                @foreach($listBiaya as $biaya)
                    <div class="biaya-item">
                        <span>{{ $biaya['jenis'] ?? 'Biaya Lain' }}</span>
                        <span>Rp {{ number_format($biaya['jumlah'] ?? 0, 0, ',', '.') }}</span>
                    </div>
                @endforeach
                
                {{-- Garis total --}}
                <div style="border-top: 1px dashed #ccc; margin-top: 8px; padding-top: 8px; display:flex; justify-content:space-between; font-weight:700;">
                    <span>Total</span>
                    <span>Rp {{ number_format($pengeluaran, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
        @endif

        {{-- ACTION BUTTONS (Optional) --}}
        {{-- Jika Anda ingin menambahkan fitur Edit/Hapus --}}
        {{-- 
        <div class="action-buttons">
            <a href="#" class="btn btn-edit">Edit Data</a>
            <form action="#" method="POST" style="flex:1;">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-delete" onclick="return confirm('Hapus data panen ini?')">Hapus</button>
            </form>
        </div> 
        --}}

    </div>
</div>

@endsection
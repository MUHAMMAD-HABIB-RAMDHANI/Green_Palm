@extends('layouts.app')

@section('title', 'Catat Panen')

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

    /* ... (Style wrapper, container, header tetap sama) ... */
    .panen-wrapper { background-color: var(--bg-gray); min-height: 100vh; padding: 20px 30px; font-family: 'Poppins', sans-serif; padding-bottom: 40px; }
    
    .panen-container { 
        background: white; 
        border-radius: 20px; 
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08); 
        max-width: 900px; 
        margin: 0 auto; 
        overflow: hidden; 
        display: flex; 
        flex-direction: column;
        
        /* --- ANIMASI POP UP (SLIDE UP) --- */
        animation: slideUp 0.5s ease;
    }

    /* Definisi Keyframes Animasi */
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .card-header { padding: 20px 30px; background: linear-gradient(135deg, var(--primary-green) 0%, var(--dark-green) 100%); display: flex; align-items: center; gap: 20px; position: relative; overflow: hidden; }
    .back-button { width: 44px; height: 44px; border-radius: 12px; background: rgba(255, 255, 255, 0.2); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.3); display: flex; align-items: center; justify-content: center; text-decoration: none; color: white; font-size: 24px; transition: all 0.3s ease; z-index: 1; }
    .card-title { font-size: 24px; font-weight: 700; color: white; margin: 0; z-index: 1; }
    .card-body { padding: 30px; flex: 1; }
    .filter-section { margin-bottom: 25px; border-bottom: 2px solid #f0f0f0; padding-bottom: 25px; }
    .form-control-custom { width: 100%; padding: 12px 15px; border: 1px solid #eee; border-radius: 12px; background-color: #fcfcfc; margin-bottom: 15px; cursor: pointer; }
    .analisa-card { background: linear-gradient(135deg, var(--primary-green) 0%, var(--dark-green) 100%); padding: 15px 20px; border-radius: 15px; color: white; display: flex; justify-content: space-between; align-items: center; text-decoration: none; margin-bottom: 20px; }
    
    /* --- STYLE BARU UNTUK SELECT TAHUN --- */
    .year-select-wrapper {
        position: relative;
        display: inline-block;
    }
    
    .year-select {
        appearance: none;
        -webkit-appearance: none;
        background-color: #f0f0f0;
        color: var(--text-muted);
        font-size: 14px;
        font-weight: 600;
        padding: 4px 30px 4px 12px; /* Padding kanan lebih besar untuk panah */
        border-radius: 8px;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
    }

    .year-select:hover, .year-select:focus {
        background-color: #e2e6ea;
        color: var(--primary-green);
        outline: none;
    }

    /* Custom Arrow untuk Select Tahun */
    .year-select-arrow {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none;
        font-size: 10px;
        color: var(--text-muted);
    }

    /* ... (Style List Panen, Tombol Delete, dll sama) ... */
    .panen-list { display: grid; gap: 18px; }
    .panen-card { border: 1px solid #eee; border-radius: 15px; overflow: hidden; background: white; transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(0,0,0,0.04); position: relative; }
    .panen-card:hover { border-color: var(--light-green); transform: translateY(-5px); box-shadow: 0 10px 25px rgba(43, 122, 11, 0.15); }
    .panen-link-wrapper { text-decoration: none; color: inherit; display: block; }
    .panen-card-top { padding: 15px 20px; display: flex; justify-content: space-between; align-items: center; padding-right: 60px; }
    .panen-kebun-name { font-size: 13px; font-weight: 600; color: var(--primary-green); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; display: flex; align-items: center; gap: 5px; }
    .panen-date { font-size: 16px; font-weight: 700; color: var(--text-dark); }
    .panen-arrow { color: var(--primary-green); font-weight: 600; font-size: 14px; }
    .panen-card-details { background: linear-gradient(135deg, #fff8e1 0%, #fff3cd 100%); padding: 12px 20px; border-top: 1px solid #ffe082; }
    .finance-row { display: flex; justify-content: space-between; font-size: 13px; color: #7d6608; margin-bottom: 4px; }
    .finance-total { font-weight: 700; font-size: 15px; }

    /* Tombol Delete Merah & Posisi Diperbaiki */
    .btn-delete-panen {
        position: absolute;
        top: 24px; 
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
    .btn-delete-panen:hover { background: #b02a37; transform: scale(1.1); box-shadow: 0 6px 10px rgba(220, 53, 69, 0.4); }
    
    .bottom-action { margin-top: 30px; width: 100%; }
    .btn-add-new { display: flex; align-items: center; justify-content: center; gap: 10px; width: 100%; background: linear-gradient(135deg, var(--primary-green) 0%, var(--dark-green) 100%); color: white; padding: 16px 28px; border-radius: 15px; font-weight: 600; text-decoration: none; border: none; }
</style>

<div class="panen-wrapper">
    <div class="panen-container">
        
        {{-- Header --}}
        <div class="card-header">
            <a href="{{ route('kebun.daftar') }}" class="back-button">‹</a>
            <h1 class="card-title">📝 Catat Panen</h1>
        </div>

        {{-- Body --}}
        <div class="card-body">
            
            {{-- FORM FILTER UTAMA (Membungkus Logic Dropdown) --}}
            <form id="filterForm" action="{{ route('panen.index') }}" method="GET">
                
                {{-- Filter Kebun --}}
                <div class="filter-section">
                    <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 15px; color: var(--text-dark);">
                        Pilih Kebun
                    </h3>
                    
                    {{-- Dropdown Kebun (Otomatis Submit saat berubah) --}}
                    <select name="kebun_id" class="form-control-custom" onchange="document.getElementById('filterForm').submit()">
                        <option value="">Semua Kebun Saya</option>
                        @foreach($kebuns as $kebun)
                            <option value="{{ $kebun->id }}" {{ $selectedKebun == $kebun->id ? 'selected' : '' }}>
                                {{ $kebun->nama_kebun }}
                            </option>
                        @endforeach
                    </select>

                    <a href="{{ route('dashboard.laporan') }}" class="analisa-card">
                        <div style="display: flex; align-items: center;">
                            <span style="font-size: 20px; margin-right: 12px;">🧑‍🌾</span>
                            <span style="font-size: 16px; font-weight: 600;">Laporan Analisa</span>
                        </div>
                        <span style="font-size: 18px;">›</span>
                    </a>
                </div>

                {{-- Pesan Sukses --}}
                @if(session('success'))
                    <div style="background: #d1e7dd; color: #0f5132; padding: 10px; border-radius: 10px; margin-bottom: 15px;">
                        ✅ {{ session('success') }}
                    </div>
                @endif

                {{-- Header List & FILTER TAHUN --}}
                <div class="section-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h3 style="font-size: 18px; font-weight: 700; color: var(--primary-green);">Periode Panen</h3>
                    
                    {{-- Dropdown Tahun (Custom Style seperti Badge) --}}
                    <div class="year-select-wrapper">
                        <select name="tahun" class="year-select" onchange="document.getElementById('filterForm').submit()">
                            @foreach($years as $year)
                                <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                        <span class="year-select-arrow">▼</span>
                    </div>
                </div>

            </form> {{-- End Form Filter --}}

            <div class="panen-list">
                @forelse($panens as $panen)
                
                <div class="panen-card">
                    
                    {{-- TOMBOL DELETE (MENGGUNAKAN GLOBAL MODAL) --}}
                    <button type="button" 
                            class="btn-delete-panen confirm-delete" 
                            data-action="{{ route('panen.destroy', $panen->id) }}"
                            data-name="Panen {{ \Carbon\Carbon::parse($panen->tanggal_panen)->format('d M Y') }}"
                            title="Hapus Data">
                        🗑️
                    </button>

                    <a href="{{ route('panen.show', $panen->id) }}" class="panen-link-wrapper">
                        <div class="panen-card-top">
                            <div>
                                <div class="panen-kebun-name">
                                    🌿 {{ $panen->kebun->nama_kebun ?? 'Kebun Tidak Dikenal' }}
                                </div>
                                <div class="panen-date">
                                    📅 {{ \Carbon\Carbon::parse($panen->tanggal_panen)->format('d M Y') }}
                                </div>
                            </div>
                            <div class="panen-arrow">Detail ›</div>
                        </div>
                        
                        <div class="panen-card-details">
                            <div class="finance-row">
                                <span>Total Pendapatan</span>
                                <span class="finance-total">Rp {{ number_format($panen->pendapatan ?? 0, 0, ',', '.') }}</span>
                            </div>
                            <div class="finance-row" style="opacity: 0.8;">
                                <span>Total Pengeluaran</span>
                                <span>Rp {{ number_format($panen->total_upah_panen ?? 0, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </a>
                </div>

                @empty
                <div style="text-align: center; padding: 40px; color: #999;">
                    <div style="font-size: 64px; margin-bottom: 20px; opacity: 0.5;">📦</div>
                    <p>Tidak ada data panen di periode/kebun ini.</p>
                </div>
                @endforelse
            </div>

            <div class="bottom-action">
                <a href="{{ route('panen.create') }}" class="btn-add-new">
                    <span style="font-size: 22px;">+</span>
                    <span>Catat Panen</span>
                </a>
            </div>

        </div> 
    </div>
</div>
@endsection
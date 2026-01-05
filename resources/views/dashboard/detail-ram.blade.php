{{-- FILE: resources/views/dashboard/detail-ram.blade.php --}}
@extends('layouts.app')

@section('title', $ram->nama_ram)

@section('content')
<style>
    /* Styling khusus detail page */
    .detail-wrapper {
        background-color: #f8f9fa;
        min-height: 100vh;
        padding: 20px;
        font-family: 'Poppins', sans-serif;
    }
    .detail-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        overflow: hidden;
        max-width: 800px;
        margin: 0 auto;
    }
    /* Header Image */
    .ram-hero {
        width: 100%;
        height: 250px;
        background-color: #eee;
        position: relative;
    }
    .ram-hero img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .back-btn {
        position: absolute;
        top: 20px;
        left: 20px;
        background: rgba(0,0,0,0.5);
        color: white;
        width: 40px;
        height: 40px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        backdrop-filter: blur(5px);
        font-size: 24px;
        z-index: 10;
    }
    /* Content */
    .ram-content {
        padding: 25px;
        margin-top: -30px;
        border-radius: 30px 30px 0 0;
        background: white;
        position: relative;
    }
    .ram-title {
        font-size: 24px;
        font-weight: 700;
        color: #222;
        margin-bottom: 5px;
    }
    .ram-toke {
        font-size: 14px;
        color: #666;
        margin-bottom: 20px;
        line-height: 1.6;
    }
    /* Price Card */
    .price-card {
        background: linear-gradient(135deg, #2b7a0b 0%, #1e5607 100%);
        color: white;
        padding: 20px;
        border-radius: 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        box-shadow: 0 8px 20px rgba(43, 122, 11, 0.3);
    }
    .price-label { font-size: 13px; opacity: 0.9; display: block; margin-bottom: 5px; }
    .price-amount { font-size: 28px; font-weight: 700; }
    .price-unit { font-size: 14px; font-weight: 400; }
    .last-update { font-size: 11px; opacity: 0.8; margin-top: 5px; display: block;}

    /* Facilities Grid */
    .facilities-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
        margin-bottom: 25px;
    }
    .facility-box {
        padding: 15px;
        border-radius: 12px;
        background: #f8f9fa;
        border: 1px solid #eee;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .facility-box.active {
        background: #e6f1e3;
        border-color: #2b7a0b;
        color: #1e5607;
    }
    .facility-icon { font-size: 20px; }
    .facility-text { font-size: 13px; font-weight: 600; }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-top: 20px;
    }

    .btn-action {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        padding: 16px;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 600;
        text-align: center;
        transition: transform 0.2s;
    }
    .btn-action:hover { transform: translateY(-2px); }

    .map-btn {
        background: #4285f4;
        color: white;
        box-shadow: 0 4px 15px rgba(66, 133, 244, 0.3);
    }
    .map-btn:hover { background: #3367d6; color: white; }

    /* Style Tombol WhatsApp */
    .wa-btn {
        background: #25D366;
        color: white;
        box-shadow: 0 4px 15px rgba(37, 211, 102, 0.3);
    }
    .wa-btn:hover { background: #128C7E; color: white; }
</style>

<div class="detail-wrapper">
    <div class="detail-card">
        
        {{-- Hero Image --}}
        <div class="ram-hero">
            <a href="{{ route('dashboard.harga-sawit') }}" class="back-btn">‹</a>
            @if($ram->foto_tampak_depan)
                <img src="{{ asset('storage/' . $ram->foto_tampak_depan) }}" alt="{{ $ram->nama_ram }}">
            @else
                <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; background:#e0e0e0; color:#999; flex-direction:column;">
                    <span style="font-size: 40px;">🏭</span>
                    <span>Tidak ada foto</span>
                </div>
            @endif
        </div>

        <div class="ram-content">
            <h1 class="ram-title">{{ $ram->nama_ram }}</h1>
            
            <div class="ram-toke">
                <div>👤 Pemilik: {{ $ram->user->username ?? 'Toke Sawit' }}</div>
                <div>📍 {{ $ram->lokasi_ram }}</div>
                
                {{-- ✅ TAMBAHAN: Tampilkan Nomor WA di Info --}}
                @if($ram->nomor_wa)
                    <div style="color: #2b7a0b; font-weight: 500; margin-top: 4px;">
                        📱 WhatsApp: {{ $ram->nomor_wa }}
                    </div>
                @endif
            </div>

            {{-- Price Card --}}
            <div class="price-card">
                <div>
                    <span class="price-label">Harga Beli TBS Hari Ini</span>
                    <span class="price-amount">{{ $ram->formatted_harga }}</span>
                    <span class="price-unit">/kg</span>
                </div>
                <div style="text-align: right;">
                    <span style="font-size: 30px;">💰</span>
                </div>
            </div>
            <span class="last-update" style="text-align: center; margin-bottom: 20px; color: #777;">
                Diperbarui: {{ $ram->updated_at->locale('id')->isoFormat('dddd, D MMMM Y • HH:mm') }}
            </span>

            {{-- Facilities --}}
            <h4 style="font-size: 16px; font-weight: 700; margin-bottom: 15px;">Fasilitas & Layanan</h4>
            <div class="facilities-grid">
                
                {{-- Jemput Buah --}}
                <div class="facility-box {{ $ram->layanan_jemput_buah ? 'active' : '' }}">
                    <span class="facility-icon">{{ $ram->layanan_jemput_buah ? '✅' : '❌' }}</span>
                    <span class="facility-text">Jemput Buah</span>
                </div>

                {{-- Timbangan Digital --}}
                <div class="facility-box {{ $ram->timbangan_digital ? 'active' : '' }}">
                    <span class="facility-icon">{{ $ram->timbangan_digital ? '✅' : '❌' }}</span>
                    <span class="facility-text">Timbangan Digital</span>
                </div>

                {{-- Terima Berondolan --}}
                <div class="facility-box {{ $ram->menerima_berondolan ? 'active' : '' }}">
                    <span class="facility-icon">{{ $ram->menerima_berondolan ? '✅' : '❌' }}</span>
                    <span class="facility-text">Terima Berondolan</span>
                </div>

                {{-- Tidak Ada Pengembalian --}}
                <div class="facility-box {{ $ram->tidak_ada_pengembalian ? 'active' : '' }}">
                    <span class="facility-icon">{{ $ram->tidak_ada_pengembalian ? '✅' : '❌' }}</span>
                    <span class="facility-text">Tanpa Sortasi Ketat</span>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="action-buttons">
                {{-- Maps Button --}}
                @if($ram->latitude && $ram->longitude)
                    <a href="https://www.google.com/maps?q={{ $ram->latitude }},{{ $ram->longitude }}" 
                       target="_blank" 
                       class="btn-action map-btn">
                        🗺️ Lihat Lokasi di Google Maps
                    </a>
                @else
                    <button class="btn-action map-btn" style="background:#ccc; cursor:not-allowed;" disabled>
                        🗺️ Lokasi Peta Belum Tersedia
                    </button>
                @endif

                {{-- ✅ TAMBAHAN: Tombol WhatsApp --}}
                @if($ram->nomor_wa)
                    @php
                        // Format nomor HP agar bisa diklik (hilangkan 0 di depan, tambah 62)
                        $waNumber = $ram->nomor_wa;
                        if (substr($waNumber, 0, 1) == '0') {
                            $waNumber = '62' . substr($waNumber, 1);
                        }
                    @endphp
                    <a href="https://wa.me/{{ $waNumber }}?text=Halo,%20saya%20melihat%20info%20RAM%20Anda%20di%20Aplikasi%20Sawit.%20Apakah%20buka%20hari%20ini?" 
                       target="_blank" 
                       class="btn-action wa-btn">
                        💬 Hubungi via WhatsApp
                    </a>
                @endif
            </div>

        </div>
    </div>
</div>
@endsection
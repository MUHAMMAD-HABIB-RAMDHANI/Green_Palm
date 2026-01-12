@extends('admin.layouts.app')

@section('title', 'Detail Kebun')
@section('header-title', 'Detail Kebun')

@section('content')
<div style="max-width: 1200px; margin: 0 auto;">

    {{-- Tombol Kembali yang Diperbagus --}}
    <div style="margin-bottom: 25px;">
        <a href="{{ route('admin.kebun') }}" class="btn-back">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 12H5M12 19l-7-7 7-7"/>
            </svg>
            <span>Kembali ke Daftar Kebun</span>
        </a>
    </div>

    {{-- Grid Layout: Info Kebun & Statistik Ringkas --}}
    <div class="detail-grid">
        
        {{-- Card 1: Informasi Utama Kebun --}}
        <div class="card card-info">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px;">
                <div>
                    <h2 style="margin: 0; color: #1E4620; font-size: 24px; font-weight: 700;">
                        🌳 {{ $kebun->nama_kebun ?? 'Kebun Tanpa Nama' }}
                    </h2>
                    <p style="margin: 5px 0 0; color: #64748b; font-size: 14px;">
                        Terdaftar pada: {{ $kebun->created_at->format('d M Y') }}
                    </p>
                </div>
                <span class="badge-id">
                    #{{ $kebun->id }}
                </span>
            </div>

            <div class="info-grid">
                {{-- Info Pemilik --}}
                <div class="info-item">
                    <label>Pemilik</label>
                    <div style="display: flex; align-items: center; gap: 12px; margin-top: 8px;">
                        <div class="avatar-circle">
                            👤
                        </div>
                        <div>
                            <div style="font-weight: 700; color: #1e293b; font-size: 15px;">{{ $kebun->user->username ?? 'Unknown' }}</div>
                            <div style="font-size: 13px; color: #64748b;">{{ $kebun->user->email ?? '-' }}</div>
                        </div>
                    </div>
                </div>

                {{-- Info Lokasi --}}
                <div class="info-item">
                    <label>Lokasi & Luas</label>
                    <div style="margin-top: 8px; font-size: 15px; color: #334155;">
                        <div style="margin-bottom: 6px; display: flex; align-items: center; gap: 6px;">
                            <span>📍</span> {{ $kebun->lokasi }}
                        </div>
                        <div style="font-weight: 700; color: #2b7a0b; background: #f0fdf4; display: inline-block; padding: 4px 10px; border-radius: 6px;">
                            📏 {{ $kebun->luas }} Hektar
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 2: Statistik Ringkas --}}
        <div class="card card-stats">
            <h3 style="color: white; font-size: 16px; margin-bottom: 25px; opacity: 0.95; font-weight: 600; border-bottom: 1px solid rgba(255,255,255,0.2); padding-bottom: 10px;">
                📊 Statistik Panen
            </h3>
            
            <div style="display: flex; flex-direction: column; gap: 25px;">
                <div>
                    <div style="font-size: 36px; font-weight: 800; line-height: 1;">
                        {{ $kebun->panens->count() }}
                    </div>
                    <div style="font-size: 13px; opacity: 0.8; margin-top: 5px;">Total Kali Panen</div>
                </div>

                <div>
                    <div style="font-size: 28px; font-weight: 800; line-height: 1;">
                        {{ number_format($kebun->panens->sum('berat_total_tbs'), 0, ',', '.') }} 
                        <span style="font-size: 16px; font-weight: 500; opacity: 0.8;">kg</span>
                    </div>
                    <div style="font-size: 13px; opacity: 0.8; margin-top: 5px;">Total Produksi TBS</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel Riwayat Panen --}}
    <div class="card">
        <h3 style="margin: 0 0 25px 0; color: #1E4620; font-size: 18px; font-weight: 700; display: flex; align-items: center; gap: 10px;">
            <span style="background: #e6f1e3; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 8px;">📄</span> 
            Riwayat Panen
        </h3>

        @if($kebun->panens->isEmpty())
            <div style="text-align: center; padding: 50px; background: #f8fafc; border-radius: 12px; border: 2px dashed #e2e8f0;">
                <p style="font-size: 40px; margin-bottom: 10px;">🌾</p>
                <p style="color: #64748b; margin: 0; font-weight: 500;">Belum ada data panen tercatat untuk kebun ini.</p>
            </div>
        @else
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                            <th style="padding: 15px; text-align: left; color: #1E4620; font-weight: 600;">Tanggal Panen</th>
                            <th style="padding: 15px; text-align: left; color: #1E4620; font-weight: 600;">Berat Total (kg)</th>
                            <th style="padding: 15px; text-align: left; color: #1E4620; font-weight: 600;">Jml Tandan</th>
                            <th style="padding: 15px; text-align: left; color: #1E4620; font-weight: 600;">Estimasi Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($kebun->panens as $panen)
                        <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s;" onmouseover="this.style.background='#fafffb'" onmouseout="this.style.background='white'">
                            <td style="padding: 15px; font-weight: 500; color: #334155;">
                                {{ \Carbon\Carbon::parse($panen->tanggal_panen)->format('d M Y') }}
                            </td>
                            <td style="padding: 15px; color: #334155;">
                                <strong>{{ $panen->berat_total_tbs }}</strong> kg
                            </td>
                            <td style="padding: 15px; color: #334155;">
                                {{ $panen->jumlah_tbs }}
                            </td>
                            <td style="padding: 15px;">
                                <span style="color: #15803d; font-weight: 700; background: #dcfce7; padding: 4px 10px; border-radius: 20px; font-size: 13px;">
                                    Rp {{ number_format($panen->pendapatan, 0, ',', '.') }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</div>

<style>
    /* Styling Tombol Kembali - Green Palm Theme */
    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        background-color: #f0fdf4; /* Latar Hijau Sangat Muda */
        color: #166534; /* Teks Hijau Tua */
        border: 1px solid #bbf7d0; /* Border Hijau Halus */
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0,0,0,0.03);
    }

    .btn-back:hover {
        background-color: #dcfce7; /* Latar sedikit lebih gelap saat hover */
        border-color: #2b7a0b; /* Border berubah jadi Hijau Utama */
        color: #14532d; /* Teks makin gelap */
        transform: translateX(-4px); /* Efek geser ke kiri */
        box-shadow: 0 4px 10px rgba(43, 122, 11, 0.15); /* Bayangan hijau halus */
    }

    .btn-back svg {
        stroke: currentColor; /* Ikon mengikuti warna teks */
        transition: transform 0.3s ease;
    }

    .btn-back:hover svg {
        transform: translateX(-3px); /* Ikon panah bergerak sedikit */
    }

    /* Grid Layout */
    .detail-grid {
        display: grid; 
        grid-template-columns: 2fr 1fr; 
        gap: 25px; 
        margin-bottom: 30px;
    }

    /* Card Info */
    .card-info {
        border-left: 5px solid #2b7a0b;
    }

    .badge-id {
        background: #dcfce7; 
        color: #14532d; 
        padding: 6px 12px; 
        border-radius: 8px; 
        font-weight: 700; 
        font-size: 14px;
    }

    .info-grid {
        display: grid; 
        grid-template-columns: 1fr 1fr; 
        gap: 20px; 
        border-top: 1px solid #f1f5f9; 
        padding-top: 25px;
    }

    .info-item label {
        font-size: 12px; 
        color: #94a3b8; 
        font-weight: 700; 
        text-transform: uppercase; 
        letter-spacing: 0.5px;
    }

    .avatar-circle {
        width: 45px; 
        height: 45px; 
        background: #f0fdf4; /* Ubah background avatar jadi hijau muda juga */
        color: #15803d;
        border-radius: 50%; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        font-size: 22px;
        border: 2px solid #dcfce7;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }

    /* Card Stats */
    .card-stats {
        background: linear-gradient(135deg, #1E4620 0%, #2b7a0b 100%); 
        color: white; 
        border: none;
        box-shadow: 0 10px 20px rgba(43, 122, 11, 0.2);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .detail-grid {
            grid-template-columns: 1fr;
        }
        .info-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection
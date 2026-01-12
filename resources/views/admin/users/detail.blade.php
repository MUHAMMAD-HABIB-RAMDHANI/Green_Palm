@extends('admin.layouts.app')

@section('title', 'Detail Pengguna')
@section('header-title', 'Detail Pengguna')

@section('content')
<div style="max-width: 1200px; margin: 0 auto;">

    {{-- Tombol Kembali --}}
    <div style="margin-bottom: 25px;">
        <a href="{{ route('admin.users') }}" class="btn-back">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 12H5M12 19l-7-7 7-7"/>
            </svg>
            <span>Kembali ke Daftar Pengguna</span>
        </a>
    </div>

    {{-- Grid Layout: Info User & Ringkasan --}}
    <div class="detail-grid">
        
        {{-- Card 1: Informasi Profil User --}}
        <div class="card card-info">
            <div style="display: flex; align-items: center; gap: 20px; margin-bottom: 25px;">
                <div class="avatar-large">
                    {{ strtoupper(substr($user->username ?? 'U', 0, 1)) }}
                </div>
                <div>
                    <h2 style="margin: 0; color: #1E4620; font-size: 24px; font-weight: 700;">
                        {{ $user->username }}
                    </h2>
                    <p style="margin: 5px 0 0; color: #64748b; font-size: 14px;">
                        Member sejak: {{ $user->created_at->format('d M Y') }}
                    </p>
                </div>
            </div>

            <div class="info-list">
                <div class="info-item">
                    <label>Email</label>
                    <div class="value">{{ $user->email }}</div>
                </div>
                <div class="info-item">
                    <label>Role</label>
                    <div class="value">
                        <span class="role-badge">{{ $user->role ?? 'Petani' }}</span>
                    </div>
                </div>
                <div class="info-item">
                    <label>Status Akun</label>
                    <div class="value" style="color: #15803d; font-weight: 600;">✅ Aktif</div>
                </div>
            </div>
        </div>

        {{-- Card 2: Statistik Ringkas --}}
        <div class="card card-stats">
            <h3 style="color: white; font-size: 16px; margin-bottom: 25px; opacity: 0.95; font-weight: 600; border-bottom: 1px solid rgba(255,255,255,0.2); padding-bottom: 10px;">
                📊 Statistik Aktivitas
            </h3>
            
            <div style="display: flex; flex-direction: column; gap: 20px;">
                <div class="stat-row">
                    <div class="stat-value">{{ $user->kebuns->count() }}</div>
                    <div class="stat-label">Jumlah Kebun</div>
                </div>
                <div class="stat-row">
                    <div class="stat-value">{{ $user->panens->count() }}</div>
                    <div class="stat-label">Kali Panen</div>
                </div>
                <div class="stat-row">
                    <div class="stat-value">{{ number_format($user->panens->sum('berat_total_tbs'), 0, ',', '.') }} <span style="font-size: 14px;">kg</span></div>
                    <div class="stat-label">Total Produksi</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tab Section: Daftar Kebun & Riwayat Panen --}}
    <div class="card" style="padding: 0; overflow: hidden;">
        <div class="tab-header">
            <h3 style="margin: 0; padding: 20px; color: #1E4620; font-size: 18px; font-weight: 700; border-bottom: 1px solid #f1f5f9; background: #f8fafc;">
                🏡 Daftar Kebun Milik {{ $user->username }}
            </h3>
        </div>

        @if($user->kebuns->isEmpty())
            <div style="text-align: center; padding: 50px; color: #94a3b8;">
                <p style="font-size: 40px; margin-bottom: 10px;">🌳</p>
                <p>User ini belum memiliki data kebun.</p>
            </div>
        @else
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                            <th style="padding: 15px 20px; text-align: left; color: #1E4620; font-weight: 600;">Nama Kebun</th>
                            <th style="padding: 15px 20px; text-align: left; color: #1E4620; font-weight: 600;">Lokasi</th>
                            <th style="padding: 15px 20px; text-align: left; color: #1E4620; font-weight: 600;">Luas (Ha)</th>
                            <th style="padding: 15px 20px; text-align: center; color: #1E4620; font-weight: 600;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($user->kebuns as $kebun)
                        <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s;" onmouseover="this.style.background='#fafffb'" onmouseout="this.style.background='white'">
                            <td style="padding: 15px 20px; font-weight: 600; color: #334155;">
                                {{ $kebun->nama_kebun }}
                            </td>
                            <td style="padding: 15px 20px; color: #64748b;">
                                📍 {{ $kebun->lokasi }}
                            </td>
                            <td style="padding: 15px 20px;">
                                <span style="background: #dcfce7; color: #15803d; padding: 4px 10px; border-radius: 6px; font-weight: 600; font-size: 13px;">
                                    {{ $kebun->luas }} Ha
                                </span>
                            </td>
                            <td style="padding: 15px 20px; text-align: center;">
                                <a href="{{ route('admin.kebun.detail', $kebun->id) }}" style="text-decoration: none; color: #2b7a0b; font-weight: 600; font-size: 13px; border: 1px solid #bbf7d0; padding: 6px 12px; border-radius: 6px; transition: 0.2s; display: inline-block;">
                                    Lihat Detail ↗
                                </a>
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
    /* Styling Tombol Kembali (Green Palm) */
    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        background-color: #f0fdf4;
        color: #166534;
        border: 1px solid #bbf7d0;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0,0,0,0.03);
    }

    .btn-back:hover {
        background-color: #dcfce7;
        border-color: #2b7a0b;
        color: #14532d;
        transform: translateX(-4px);
        box-shadow: 0 4px 10px rgba(43, 122, 11, 0.15);
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
        padding: 30px;
    }

    .avatar-large {
        width: 80px; 
        height: 80px; 
        background: linear-gradient(135deg, #2b7a0b 0%, #1E4620 100%);
        color: white;
        border-radius: 50%; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        font-size: 32px;
        font-weight: 700;
        box-shadow: 0 4px 10px rgba(43, 122, 11, 0.3);
    }

    .info-list {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        border-top: 1px solid #f1f5f9;
        padding-top: 25px;
    }

    .info-item label {
        display: block;
        font-size: 12px;
        color: #94a3b8;
        font-weight: 700;
        text-transform: uppercase;
        margin-bottom: 5px;
    }

    .info-item .value {
        font-size: 15px;
        color: #334155;
        font-weight: 500;
    }

    .role-badge {
        background: #e0f2fe;
        color: #0369a1;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
    }

    /* Card Stats */
    .card-stats {
        background: linear-gradient(135deg, #1E4620 0%, #2b7a0b 100%); 
        color: white; 
        border: none;
        box-shadow: 0 10px 20px rgba(43, 122, 11, 0.2);
        padding: 30px;
    }

    .stat-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        border-bottom: 1px solid rgba(255,255,255,0.1);
        padding-bottom: 10px;
    }
    
    .stat-row:last-child {
        border-bottom: none;
    }

    .stat-value {
        font-size: 24px;
        font-weight: 700;
    }

    .stat-label {
        font-size: 13px;
        opacity: 0.8;
        padding-bottom: 4px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .detail-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection
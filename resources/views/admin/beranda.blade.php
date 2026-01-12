@extends('admin.layouts.app')

@section('title', 'Dashboard Admin')
@section('header-title', 'Dashboard Admin')

@section('content')
<div style="max-width: 1400px; margin: 0 auto;">
    
    {{-- Welcome Card --}}
    <div class="card" style="background: linear-gradient(135deg, #1E4620 0%, #2b7a0b 100%); border: none; margin-bottom: 30px;">
        <div style="display: flex; align-items: center; gap: 20px; flex-wrap: wrap;">
            <div style="width: 80px; height: 80px; background: rgba(255,255,255,0.15); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 40px; border: 2px solid rgba(255,255,255,0.2);">
                👑
            </div>
            <div style="flex-grow: 1; min-width: 250px;">
                <h2 style="color: white; font-size: 28px; margin: 0 0 8px 0; font-weight: 700;">
                    Selamat Datang, {{ Auth::user()->username }}!
                </h2>
                <p style="color: rgba(255,255,255,0.9); margin: 0; font-size: 16px;">
                    Anda login sebagai <strong>Administrator</strong> • Email: {{ Auth::user()->email }}
                </p>
            </div>
            <div style="text-align: right;">
                <div style="background: #fbbf24; color: #1E4620; padding: 8px 16px; border-radius: 8px; font-weight: 700; font-size: 14px; white-space: nowrap; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                    🕐 {{ now()->format('d M Y, H:i') }}
                </div>
            </div>
        </div>
    </div>

    {{-- Statistics Grid (Updated to Match Report Page) --}}
    <div class="stats-grid">
        
        {{-- Card Users - Blue --}}
        <div class="stat-card" style="border-left-color: #3b82f6;">
            <div class="stat-content">
                <div class="stat-text">
                    <span class="stat-label">Total Pengguna</span>
                    <h4 class="stat-value">{{ $totalUsers ?? 0 }}</h4>
                </div>
                <div class="stat-icon-wrapper" style="background: linear-gradient(135deg, #3b82f6 0%, #1e3a8a 100%); color: white;">
                    👥
                </div>
            </div>
        </div>

        {{-- Card Kebun - Green --}}
        <div class="stat-card" style="border-left-color: #2b7a0b;">
            <div class="stat-content">
                <div class="stat-text">
                    <span class="stat-label">Total Kebun</span>
                    <h4 class="stat-value">{{ $totalKebun ?? 0 }}</h4>
                </div>
                <div class="stat-icon-wrapper" style="background: linear-gradient(135deg, #2b7a0b 0%, #1E4620 100%); color: white;">
                    🌴
                </div>
            </div>
        </div>

        {{-- Card Panen - Gold/Orange --}}
        <div class="stat-card" style="border-left-color: #f59e0b;">
            <div class="stat-content">
                <div class="stat-text">
                    <span class="stat-label">Total Panen</span>
                    <h4 class="stat-value">{{ $totalPanen ?? 0 }}</h4>
                </div>
                <div class="stat-icon-wrapper" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white;">
                    📊
                </div>
            </div>
        </div>

        {{-- Card Perawatan - Light Blue --}}
        <div class="stat-card" style="border-left-color: #0ea5e9;">
            <div class="stat-content">
                <div class="stat-text">
                    <span class="stat-label">Total Perawatan</span>
                    <h4 class="stat-value">{{ $totalPerawatan ?? 0 }}</h4>
                </div>
                <div class="stat-icon-wrapper" style="background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%); color: white;">
                    ✅
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; margin-bottom: 30px;">
        
        <a href="{{ route('admin.users') }}" style="text-decoration: none;">
            <div class="card" style="cursor: pointer; border-left-color: #2b7a0b; height: 100%;">
                <div style="display: flex; align-items: center; gap: 15px;">
                    <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%); color: #166534; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 24px; flex-shrink: 0;">
                        👥
                    </div>
                    <div>
                        <h3 style="margin: 0 0 5px 0; font-size: 18px; color: #1E4620;">Kelola Pengguna</h3>
                        <p style="margin: 0; font-size: 13px; color: #64748b;">Lihat & kelola data user</p>
                    </div>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.kebun') }}" style="text-decoration: none;">
            <div class="card" style="cursor: pointer; border-left-color: #1E4620; height: 100%;">
                <div style="display: flex; align-items: center; gap: 15px;">
                    <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); color: #14532d; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 24px; flex-shrink: 0;">
                        🌴
                    </div>
                    <div>
                        <h3 style="margin: 0 0 5px 0; font-size: 18px; color: #1E4620;">Data Kebun</h3>
                        <p style="margin: 0; font-size: 13px; color: #64748b;">Monitor semua kebun sawit</p>
                    </div>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.laporan') }}" style="text-decoration: none;">
            <div class="card" style="cursor: pointer; border-left-color: #f59e0b; height: 100%;">
                <div style="display: flex; align-items: center; gap: 15px;">
                    <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); color: #92400e; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 24px; flex-shrink: 0;">
                        📈
                    </div>
                    <div>
                        <h3 style="margin: 0 0 5px 0; font-size: 18px; color: #1E4620;">Laporan Lengkap</h3>
                        <p style="margin: 0; font-size: 13px; color: #64748b;">Analisis & statistik</p>
                    </div>
                </div>
            </div>
        </a>
    </div>

    {{-- Recent Activity --}}
    <div class="card">
        <h3 style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px; color: #1E4620;">
            <span style="font-size: 24px;">🕒</span> Aktivitas Terbaru
        </h3>
        
        <div style="border: 2px dashed #cbd5e1; border-radius: 12px; padding: 40px; text-align: center; background-color: #f8fafc;">
            <div style="font-size: 60px; margin-bottom: 15px; opacity: 0.5; filter: grayscale(100%);">📋</div>
            <p style="color: #64748b; font-size: 16px; margin: 0;">
                Fitur aktivitas akan segera tersedia
            </p>
        </div>
    </div>

    {{-- System Info --}}
    <div style="margin-top: 30px; padding: 20px; background: linear-gradient(135deg, #ecfccb 0%, #d9f99d 100%); border-radius: 12px; border-left: 4px solid #65a30d;">
        <div style="display: flex; align-items: center; gap: 15px; flex-wrap: wrap;">
            <div style="font-size: 32px;">ℹ️</div>
            <div style="flex-grow: 1; min-width: 200px;">
                <h4 style="margin: 0 0 5px 0; color: #365314; font-size: 16px; font-weight: 600;">Informasi Sistem</h4>
                <p style="margin: 0; color: #3f6212; font-size: 14px;">
                    Laravel Version: {{ app()->version() }} • PHP Version: {{ phpversion() }} • Logged in as: <strong>{{ Auth::user()->email }}</strong>
                </p>
            </div>
        </div>
    </div>

</div>

{{-- Styles --}}
<style>
    /* Stats Grid Layout */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    /* Stat Card Styling (Matching Report Page) */
    .stat-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        padding: 24px;
        transition: transform 0.2s, box-shadow 0.2s;
        border-left: 4px solid transparent; /* Color set inline */
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.15);
    }

    .stat-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .stat-text {
        display: flex;
        flex-direction: column;
    }

    .stat-label {
        font-size: 13px;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 5px;
    }

    .stat-value {
        font-size: 32px;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
        line-height: 1.2;
    }

    .stat-icon-wrapper {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }

    @media (max-width: 768px) {
        .card h3 { font-size: 16px !important; }
        .stats-grid { grid-template-columns: 1fr; }
    }
</style>
@endsection
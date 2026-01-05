@extends('admin.layouts.app')

@section('title', 'Dashboard Admin')
@section('header-title', 'Dashboard Admin')

@section('content')
<div style="max-width: 1400px; margin: 0 auto;">
    
    {{-- Welcome Card --}}
    <div class="card" style="background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%); border: none; margin-bottom: 30px;">
        <div style="display: flex; align-items: center; gap: 20px; flex-wrap: wrap;">
            <div style="width: 80px; height: 80px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 40px;">
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
                <div style="background: #fbbf24; color: #1e293b; padding: 8px 16px; border-radius: 8px; font-weight: 700; font-size: 14px; white-space: nowrap;">
                    🕐 {{ now()->format('d M Y, H:i') }}
                </div>
            </div>
        </div>
    </div>

    {{-- Statistics Grid --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">👥</div>
            <div class="stat-info">
                <h4>{{ $totalUsers ?? 0 }}</h4>
                <p>Total Pengguna</p>
            </div>
        </div>

        <div class="stat-card" style="border-left-color: #10b981;">
            <div class="stat-icon" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">🌴</div>
            <div class="stat-info">
                <h4>{{ $totalKebun ?? 0 }}</h4>
                <p>Total Kebun</p>
            </div>
        </div>

        <div class="stat-card" style="border-left-color: #f59e0b;">
            <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">📊</div>
            <div class="stat-info">
                <h4>{{ $totalPanen ?? 0 }}</h4>
                <p>Total Panen</p>
            </div>
        </div>

        <div class="stat-card" style="border-left-color: #8b5cf6;">
            <div class="stat-icon" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);">✅</div>
            <div class="stat-info">
                <h4>{{ $totalPerawatan ?? 0 }}</h4>
                <p>Total Perawatan</p>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; margin-bottom: 30px;">
        
        <a href="{{ route('admin.users') }}" style="text-decoration: none;">
            <div class="card" style="cursor: pointer; border-left-color: #3b82f6; height: 100%;">
                <div style="display: flex; align-items: center; gap: 15px;">
                    <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 24px; flex-shrink: 0;">
                        👥
                    </div>
                    <div>
                        <h3 style="margin: 0 0 5px 0; font-size: 18px; color: #1e3a8a;">Kelola Pengguna</h3>
                        <p style="margin: 0; font-size: 13px; color: #64748b;">Lihat & kelola data user</p>
                    </div>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.kebun') }}" style="text-decoration: none;">
            <div class="card" style="cursor: pointer; border-left-color: #10b981; height: 100%;">
                <div style="display: flex; align-items: center; gap: 15px;">
                    <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 24px; flex-shrink: 0;">
                        🌴
                    </div>
                    <div>
                        <h3 style="margin: 0 0 5px 0; font-size: 18px; color: #1e3a8a;">Data Kebun</h3>
                        <p style="margin: 0; font-size: 13px; color: #64748b;">Monitor semua kebun sawit</p>
                    </div>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.laporan') }}" style="text-decoration: none;">
            <div class="card" style="cursor: pointer; border-left-color: #f59e0b; height: 100%;">
                <div style="display: flex; align-items: center; gap: 15px;">
                    <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 24px; flex-shrink: 0;">
                        📈
                    </div>
                    <div>
                        <h3 style="margin: 0 0 5px 0; font-size: 18px; color: #1e3a8a;">Laporan Lengkap</h3>
                        <p style="margin: 0; font-size: 13px; color: #64748b;">Analisis & statistik</p>
                    </div>
                </div>
            </div>
        </a>
    </div>

    {{-- Recent Activity --}}
    <div class="card">
        <h3 style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px;">
            <span style="font-size: 24px;">🕒</span> Aktivitas Terbaru
        </h3>
        
        <div style="border: 2px dashed #e2e8f0; border-radius: 12px; padding: 40px; text-align: center;">
            <div style="font-size: 60px; margin-bottom: 15px; opacity: 0.3;">📋</div>
            <p style="color: #64748b; font-size: 16px; margin: 0;">
                Fitur aktivitas akan segera tersedia
            </p>
        </div>
    </div>

    {{-- System Info --}}
    <div style="margin-top: 30px; padding: 20px; background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border-radius: 12px; border-left: 4px solid #f59e0b;">
        <div style="display: flex; align-items: center; gap: 15px; flex-wrap: wrap;">
            <div style="font-size: 32px;">ℹ️</div>
            <div style="flex-grow: 1; min-width: 200px;">
                <h4 style="margin: 0 0 5px 0; color: #92400e; font-size: 16px; font-weight: 600;">Informasi Sistem</h4>
                <p style="margin: 0; color: #78350f; font-size: 14px;">
                    Laravel Version: {{ app()->version() }} • PHP Version: {{ phpversion() }} • Logged in as: <strong>{{ Auth::user()->email }}</strong>
                </p>
            </div>
        </div>
    </div>

</div>

{{-- Additional CSS for Mobile --}}
<style>
    @media (max-width: 768px) {
        .card h3 {
            font-size: 16px !important;
        }
        
        .stat-info h4 {
            font-size: 24px !important;
        }
        
        .stat-icon {
            width: 50px !important;
            height: 50px !important;
            font-size: 24px !important;
        }
    }
</style>
@endsection
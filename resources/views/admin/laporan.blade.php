@extends('admin.layouts.app')

@section('title', 'Laporan')
@section('header-title', 'Laporan & Statistik')

@section('content')
<div style="max-width: 1400px; margin: 0 auto;">
    
    {{-- Header Section (Green Gradient) --}}
    <div class="card" style="margin-bottom: 25px; background: linear-gradient(135deg, #1E4620 0%, #2b7a0b 100%); border: none;">
        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 15px;">
            <div>
                <h2 style="color: white; font-size: 24px; margin: 0 0 8px 0; font-weight: 700;">
                    📈 Laporan Sistem
                </h2>
                <p style="color: rgba(255,255,255,0.9); margin: 0; font-size: 15px;">
                    Data lengkap aktivitas dan statistik platform
                </p>
            </div>
            <div style="text-align: right;">
                {{-- Glassmorphism Badge --}}
                <div style="background: rgba(255,255,255,0.2); color: white; padding: 8px 16px; border-radius: 8px; font-weight: 600; font-size: 14px; backdrop-filter: blur(5px);">
                    📅 {{ now()->format('d M Y') }}
                </div>
            </div>
        </div>
    </div>

    {{-- Main Statistics --}}
    {{-- FIXED: Added class 'stats-grid' for consistent styling --}}
    <div class="stats-grid">
        
        {{-- Total Users (Blue - Tetap sebagai pembeda) --}}
        {{-- FIXED: Changed class to 'stat-card' and adjusted internal structure --}}
        <div class="stat-card" style="border-left-color: #3b82f6;">
            <div class="stat-content">
                <div class="stat-text">
                    <span class="stat-label">Total Pengguna</span>
                    <h4 class="stat-value">{{ $stats['total_users'] }}</h4>
                </div>
                <div class="stat-icon-wrapper" style="background: linear-gradient(135deg, #3b82f6 0%, #1e3a8a 100%); color: white;">
                    👥
                </div>
            </div>
        </div>

        {{-- Total Kebun (Green - Utama) --}}
        <div class="stat-card" style="border-left-color: #2b7a0b;">
            <div class="stat-content">
                <div class="stat-text">
                    <span class="stat-label">Total Kebun</span>
                    <h4 class="stat-value">{{ $stats['total_kebun'] }}</h4>
                </div>
                <div class="stat-icon-wrapper" style="background: linear-gradient(135deg, #2b7a0b 0%, #1E4620 100%); color: white;">
                    🌴
                </div>
            </div>
        </div>

        {{-- Total Luas (Purple) --}}
        <div class="stat-card" style="border-left-color: #8b5cf6;">
            <div class="stat-content">
                <div class="stat-text">
                    <span class="stat-label">Total Luas (Ha)</span>
                    <h4 class="stat-value">{{ number_format($stats['total_luas'], 2) }}</h4>
                </div>
                <div class="stat-icon-wrapper" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white;">
                    📏
                </div>
            </div>
        </div>

        {{-- Total Panen (Gold/Orange - Buah Sawit) --}}
        <div class="stat-card" style="border-left-color: #f59e0b;">
            <div class="stat-content">
                <div class="stat-text">
                    <span class="stat-label">Total Panen</span>
                    <h4 class="stat-value">{{ $stats['total_panen'] }}</h4>
                </div>
                <div class="stat-icon-wrapper" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white;">
                    📦
                </div>
            </div>
        </div>

        {{-- Total Produksi (Red/Danger - Berat/Skala) --}}
        <div class="stat-card" style="border-left-color: #ef4444;">
            <div class="stat-content">
                <div class="stat-text">
                    <span class="stat-label">Total Produksi (Kg)</span>
                    <h4 class="stat-value">{{ number_format($stats['total_produksi'], 0) }}</h4>
                </div>
                <div class="stat-icon-wrapper" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white;">
                    ⚖️
                </div>
            </div>
        </div>
    </div>

    {{-- Chart Section --}}
    <div class="card" style="margin-bottom: 30px;">
        <h3 style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px; color: #1E4620;">
            <span style="font-size: 24px;">📊</span> Panen Per Bulan (Tahun {{ date('Y') }})
        </h3>
        
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 2px solid #e2e8f0;">
                        <th style="padding: 12px; text-align: left; font-weight: 700; color: #1E4620;">Bulan</th>
                        <th style="padding: 12px; text-align: center; font-weight: 700; color: #1E4620;">Jumlah Panen</th>
                        <th style="padding: 12px; text-align: left; font-weight: 700; color: #1E4620;">Grafik</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $bulanNama = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                        $maxPanen = $panenPerBulan->max('total') ?: 1;
                    @endphp
                    
                    @forelse($panenPerBulan as $data)
                    <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s;" onmouseover="this.style.background='#f0fdf4'" onmouseout="this.style.background='white'">
                        <td style="padding: 12px; color: #334155;">
                            <strong>{{ $bulanNama[$data->bulan] }}</strong>
                        </td>
                        <td style="padding: 12px; text-align: center;">
                            <span style="background: #dcfce7; color: #14532d; padding: 6px 14px; border-radius: 8px; font-weight: 600; font-size: 13px;">
                                {{ $data->total }}
                            </span>
                        </td>
                        <td style="padding: 12px;">
                            <div style="background: #e2e8f0; height: 24px; border-radius: 6px; overflow: hidden; max-width: 300px;">
                                <div style="background: linear-gradient(90deg, #2b7a0b 0%, #1E4620 100%); height: 100%; width: {{ ($data->total / $maxPanen) * 100 }}%; transition: width 0.5s ease; border-radius: 6px;"></div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" style="padding: 40px; text-align: center; color: #94a3b8;">
                            <div style="font-size: 40px; margin-bottom: 10px;">📉</div>
                            <p style="margin: 0;">Belum ada data panen tahun ini</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Additional Info (Green Theme) --}}
    <div class="card" style="background: linear-gradient(135deg, #ecfccb 0%, #d9f99d 100%); border-left: 4px solid #65a30d;">
        <div style="display: flex; align-items: center; gap: 15px;">
            <div style="font-size: 32px;">💡</div>
            <div>
                <h4 style="margin: 0 0 5px 0; color: #365314; font-size: 16px; font-weight: 700;">Tips Analisis</h4>
                <p style="margin: 0; color: #3f6212; font-size: 14px;">
                    Gunakan data laporan ini untuk mengidentifikasi tren produksi dan meningkatkan efisiensi sistem manajemen kebun sawit.
                </p>
            </div>
        </div>
    </div>

</div>

{{-- Add this CSS block if it's not already in your layout --}}
<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        padding: 20px;
        transition: transform 0.2s, box-shadow 0.2s;
        border-left: 4px solid transparent; 
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -2px rgba(0, 0, 0, 0.04);
    }

    .stat-content {
        display: flex;
        justify-content: space-between;
        align-items: center; /* Changed to center for better alignment */
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
        font-size: 28px;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
        line-height: 1.2;
    }

    .stat-icon-wrapper {
        width: 50px; /* Slightly larger icon wrapper */
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }
</style>
@endsection
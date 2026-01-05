@extends('admin.layouts.app')

@section('title', 'Laporan')
@section('header-title', 'Laporan & Statistik')

@section('content')
<div style="max-width: 1400px; margin: 0 auto;">
    
    {{-- Header Section --}}
    <div class="card" style="margin-bottom: 25px; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border: none;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <h2 style="color: white; font-size: 24px; margin: 0 0 8px 0; font-weight: 700;">
                    📈 Laporan Sistem
                </h2>
                <p style="color: rgba(255,255,255,0.9); margin: 0; font-size: 15px;">
                    Data lengkap aktivitas dan statistik platform
                </p>
            </div>
            <div style="text-align: right;">
                <div style="background: white; color: #d97706; padding: 8px 16px; border-radius: 8px; font-weight: 700; font-size: 14px;">
                    📅 {{ now()->format('d M Y') }}
                </div>
            </div>
        </div>
    </div>

    {{-- Main Statistics --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px; margin-bottom: 30px;">
        
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #3b82f6 0%, #1e3a8a 100%);">👥</div>
            <div class="stat-info">
                <h4>{{ $stats['total_users'] }}</h4>
                <p>Total Pengguna</p>
            </div>
        </div>

        <div class="stat-card" style="border-left-color: #10b981;">
            <div class="stat-icon" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">🌴</div>
            <div class="stat-info">
                <h4>{{ $stats['total_kebun'] }}</h4>
                <p>Total Kebun</p>
            </div>
        </div>

        <div class="stat-card" style="border-left-color: #8b5cf6;">
            <div class="stat-icon" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);">📏</div>
            <div class="stat-info">
                <h4>{{ number_format($stats['total_luas'], 2) }}</h4>
                <p>Total Luas (Ha)</p>
            </div>
        </div>

        <div class="stat-card" style="border-left-color: #f59e0b;">
            <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">📦</div>
            <div class="stat-info">
                <h4>{{ $stats['total_panen'] }}</h4>
                <p>Total Panen</p>
            </div>
        </div>

        <div class="stat-card" style="border-left-color: #ef4444;">
            <div class="stat-icon" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);">⚖️</div>
            <div class="stat-info">
                <h4>{{ number_format($stats['total_produksi'], 0) }}</h4>
                <p>Total Produksi (Kg)</p>
            </div>
        </div>

        {{--
        <div class="stat-card" style="border-left-color: #06b6d4;">
            <div class="stat-icon" style="background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);">✅</div>
            <div class="stat-info">
                <h4>{{ $stats['total_perawatan'] }}</h4>
                <p>Total Perawatan</p>
            </div>
        </div>
        --}}
    </div>

    {{-- Chart Section --}}
    <div class="card" style="margin-bottom: 30px;">
        <h3 style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px;">
            <span style="font-size: 24px;">📊</span> Panen Per Bulan (Tahun {{ date('Y') }})
        </h3>
        
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 2px solid #e2e8f0;">
                        <th style="padding: 12px; text-align: left; font-weight: 600; color: #1e3a8a;">Bulan</th>
                        <th style="padding: 12px; text-align: center; font-weight: 600; color: #1e3a8a;">Jumlah Panen</th>
                        <th style="padding: 12px; text-align: left; font-weight: 600; color: #1e3a8a;">Grafik</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $bulanNama = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                        $maxPanen = $panenPerBulan->max('total') ?: 1;
                    @endphp
                    
                    @forelse($panenPerBulan as $data)
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 12px;">
                            <strong>{{ $bulanNama[$data->bulan] }}</strong>
                        </td>
                        <td style="padding: 12px; text-align: center;">
                            <span style="background: #dbeafe; color: #1e3a8a; padding: 6px 14px; border-radius: 8px; font-weight: 600;">
                                {{ $data->total }}
                            </span>
                        </td>
                        <td style="padding: 12px;">
                            <div style="background: #e2e8f0; height: 24px; border-radius: 6px; overflow: hidden;">
                                <div style="background: linear-gradient(90deg, #3b82f6 0%, #1e3a8a 100%); height: 100%; width: {{ ($data->total / $maxPanen) * 100 }}%; transition: width 0.3s ease;"></div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" style="padding: 30px; text-align: center; color: #94a3b8;">
                            <div style="font-size: 40px; margin-bottom: 10px;">📊</div>
                            <p style="margin: 0;">Belum ada data panen tahun ini</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Additional Info --}}
    <div class="card" style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border-left: 4px solid #f59e0b;">
        <div style="display: flex; align-items: center; gap: 15px;">
            <div style="font-size: 32px;">💡</div>
            <div>
                <h4 style="margin: 0 0 5px 0; color: #92400e; font-size: 16px; font-weight: 600;">Tips Analisis</h4>
                <p style="margin: 0; color: #78350f; font-size: 14px;">
                    Gunakan data laporan ini untuk mengidentifikasi tren produksi dan meningkatkan efisiensi sistem manajemen kebun sawit.
                </p>
            </div>
        </div>
    </div>

</div>
@endsection
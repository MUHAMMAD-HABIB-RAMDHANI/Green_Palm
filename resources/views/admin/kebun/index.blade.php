@extends('admin.layouts.app')

@section('title', 'Kelola Kebun')
@section('header-title', 'Data Perkebunan')

@section('content')
<div style="max-width: 1400px; margin: 0 auto;">
    
    {{-- Header Section --}}
    <div class="card" style="margin-bottom: 25px; background: linear-gradient(135deg, #059669 0%, #064e3b 100%); border: none;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <h2 style="color: white; font-size: 24px; margin: 0 0 8px 0; font-weight: 700;">
                    🌴 Manajemen Kebun
                </h2>
                <p style="color: rgba(255,255,255,0.9); margin: 0; font-size: 15px;">
                    Total: <strong>{{ $kebuns->total() }}</strong> lahan perkebunan terdaftar
                </p>
            </div>
        </div>
    </div>

    {{-- Kebun Table --}}
    <div class="card">
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 2px solid #e2e8f0;">
                        <th style="padding: 15px; text-align: left; font-weight: 600; color: #064e3b;">ID</th>
                        <th style="padding: 15px; text-align: left; font-weight: 600; color: #064e3b;">Info Kebun</th>
                        <th style="padding: 15px; text-align: left; font-weight: 600; color: #064e3b;">Pemilik</th>
                        <th style="padding: 15px; text-align: left; font-weight: 600; color: #064e3b;">Luas & Lokasi</th>
                        <th style="padding: 15px; text-align: left; font-weight: 600; color: #064e3b;">Terdaftar</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #064e3b;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kebuns as $kebun)
                    <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s ease;" 
                        onmouseover="this.style.background='#f0fdf4'" 
                        onmouseout="this.style.background='white'">
                        
                        {{-- Kolom ID --}}
                        <td style="padding: 15px;">
                            <span style="background: #d1fae5; color: #065f46; padding: 4px 10px; border-radius: 6px; font-weight: 600; font-size: 13px;">
                                #{{ $kebun->id }}
                            </span>
                        </td>

                        {{-- Kolom Info Kebun (Nama) --}}
                        <td style="padding: 15px;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <span style="font-size: 20px;">🌳</span>
                                <div>
                                    {{-- Pastikan nama kolom di database sesuai (misal: nama_kebun atau nama) --}}
                                    <strong style="color: #1f2937;">{{ $kebun->nama_kebun ?? 'Kebun Tanpa Nama' }}</strong>
                                </div>
                            </div>
                        </td>

                        {{-- Kolom Pemilik (Relasi ke User) --}}
                        <td style="padding: 15px;">
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <span style="font-size: 16px;">👤</span>
                                <span style="color: #4b5563; font-weight: 500;">
                                    {{ $kebun->user->username ?? 'User Tidak Ditemukan' }}
                                </span>
                            </div>
                            <div style="font-size: 12px; color: #9ca3af; margin-left: 28px;">
                                {{ $kebun->user->email ?? '-' }}
                            </div>
                        </td>

                        {{-- Kolom Luas & Lokasi --}}
                        <td style="padding: 15px;">
                            <div style="margin-bottom: 4px; font-size: 14px; font-weight: 600; color: #374151;">
                                📏 {{ $kebun->luas ?? '0' }} Ha
                            </div>
                            <div style="font-size: 13px; color: #6b7280;">
                                📍 {{ Str::limit($kebun->lokasi ?? 'Lokasi tidak ada', 20) }}
                            </div>
                        </td>

                        {{-- Kolom Tanggal --}}
                        <td style="padding: 15px; color: #64748b; font-size: 14px;">
                            {{ $kebun->created_at->format('d M Y') }}
                        </td>

                        {{-- Kolom Aksi --}}
                        <td style="padding: 15px; text-align: center;">
                            <a href="{{ route('admin.kebun.detail', $kebun->id) }}" 
                               style="display: inline-block; padding: 8px 16px; background: #10b981; color: white; text-decoration: none; border-radius: 8px; font-size: 14px; font-weight: 600; transition: all 0.3s ease;"
                               onmouseover="this.style.background='#047857'"
                               onmouseout="this.style.background='#10b981'">
                                📊 Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="padding: 40px; text-align: center; color: #94a3b8;">
                            <div style="font-size: 48px; margin-bottom: 15px;">🌱</div>
                            <p style="margin: 0; font-size: 16px;">Belum ada data kebun terdaftar</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($kebuns->hasPages())
        <div style="margin-top: 25px; padding-top: 20px; border-top: 2px solid #f1f5f9;">
            {{ $kebuns->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
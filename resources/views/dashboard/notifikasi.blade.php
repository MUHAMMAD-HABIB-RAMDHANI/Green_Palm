@extends('layouts.app')

@section('title', 'Notifikasi')

@section('content')

{{-- =============================================
     STYLE SECTION
     ============================================= --}}
<style>
    /* --- PAGE HEADER STYLE --- */
    .page-header {
        margin-bottom: 25px;
        background: #2b7a0b; /* Primary Green */
        padding: 20px 25px;
        border-radius: 16px;
        color: white;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 4px 12px rgba(43,122,11,0.2);
        position: relative;
        overflow: hidden;
    }

    .page-header::after {
        content: '🔔';
        position: absolute;
        right: -10px;
        bottom: -10px;
        font-size: 80px;
        opacity: 0.1;
        transform: rotate(-15deg);
    }

    .page-title {
        margin: 0;
        font-size: 24px;
        font-weight: 700;
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .header-actions {
        position: relative;
        z-index: 1;
    }

    /* --- NOTIFICATION LIST STYLE --- */
    .notification-list {
        display: flex;
        flex-direction: column;
        gap: 15px;
        max-width: 1000px;
        margin: 0 auto;
        padding-bottom: 40px; 
    }

    .notification-item {
        background: white;
        border-radius: 16px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        display: flex;
        gap: 18px;
        transition: all 0.3s ease;
        border: 1px solid #f0f0f0;
        position: relative;
        overflow: hidden;
    }

    .notification-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.08);
        border-color: #e0e0e0;
    }

    /* Status Indicator Strip */
    .status-strip {
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 5px;
        background: #e0e0e0; 
    }

    .notification-item.unread .status-strip { background: #ef4444; }
    .notification-item.broadcast .status-strip { background: #f59e0b; }

    /* Icon Styling */
    .notif-icon-box {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        flex-shrink: 0;
    }

    .notification-item.unread .notif-icon-box { background: #fee2e2; color: #dc2626; }
    .notification-item.broadcast .notif-icon-box { background: #fef3c7; color: #d97706; }

    /* Content Styling */
    .notif-content {
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .notif-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 5px;
    }

    .notif-title {
        font-size: 16px;
        font-weight: 700;
        color: #1f2937;
        margin: 0;
    }

    .notif-title a {
        color: inherit;
        text-decoration: none;
        background-image: linear-gradient(to right, #2b7a0b, #2b7a0b);
        background-size: 0% 2px;
        background-repeat: no-repeat;
        background-position: left bottom;
        transition: background-size 0.3s;
    }
    .notif-title a:hover {
        color: #2b7a0b;
        background-size: 100% 2px;
    }

    .notif-time {
        font-size: 12px;
        color: #9ca3af;
        white-space: nowrap;
        margin-left: 10px;
    }

    .notif-message {
        font-size: 14px;
        color: #4b5563;
        line-height: 1.5;
        margin: 0;
    }

    /* Badges */
    .badge-status {
        display: inline-block;
        padding: 3px 8px;
        border-radius: 6px;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        margin-bottom: 5px;
        letter-spacing: 0.5px;
    }
    .badge-new { background: #fee2e2; color: #dc2626; }
    .badge-info { background: #fef3c7; color: #d97706; }

    /* Actions */
    .notif-actions {
        display: flex;
        flex-direction: column;
        justify-content: center;
        gap: 8px;
        padding-left: 10px;
        border-left: 1px solid #f0f0f0;
    }

    .btn-icon {
        width: 34px;
        height: 34px;
        border-radius: 8px;
        border: none;
        background: transparent;
        color: #9ca3af;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 16px;
        text-decoration: none;
    }

    .btn-icon:hover { background: #f3f4f6; color: #374151; }
    .btn-icon.check:hover { color: #2b7a0b; background: #e6f1e3; }
    .btn-icon.delete:hover { color: #ef4444; background: #fee2e2; }
    .btn-icon.link:hover { color: #2563eb; background: #dbeafe; }

    .btn-read-all {
        background: rgba(255,255,255,0.2);
        border: 1px solid rgba(255,255,255,0.4);
        color: white;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        backdrop-filter: blur(5px);
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .btn-read-all:hover { background: white; color: #2b7a0b; }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.03);
    }
    .empty-icon {
        font-size: 60px;
        margin-bottom: 15px;
        opacity: 0.5;
        display: block;
    }

    /* --- [PAGINATION STYLE - FINAL FIX] --- */
    .custom-pagination {
        margin-top: 30px;
        display: flex;
        justify-content: center;
        width: 100%;
    }

    /* Wadah Navigasi */
    .custom-pagination nav {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        background: white;
        padding: 8px 12px;
        border-radius: 50px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        border: 1px solid #e5e7eb;
        flex-wrap: wrap;
    }

    /* Tombol Angka dan Panah (Kondisi Normal) */
    .custom-pagination a, 
    .custom-pagination span {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 36px;
        height: 36px;
        padding: 0 10px;
        border-radius: 50%;
        font-size: 13px;
        font-weight: 700; 
        text-decoration: none;
        color: #111; /* Hitam Default */
        transition: all 0.2s ease;
        border: 1px solid transparent;
        background: transparent;
    }

    /* Hover Effect */
    .custom-pagination a:hover {
        background: #f0fdf4;
        color: #2b7a0b;
        transform: translateY(-1px);
    }

    /* === BAGIAN INI YANG MEMAKSA WARNA PUTIH === */
    
    /* 1. Pembungkus Luar (Lingkaran Hijau) */
    .custom-pagination span[aria-current="page"] {
        background-color: #2b7a0b !important;
        border-color: #2b7a0b !important;
        color: #ffffff !important;
        box-shadow: 0 2px 8px rgba(43, 122, 11, 0.3);
    }

    /* 2. Elemen Teks DI DALAM Lingkaran (Solusi Utama) */
    .custom-pagination span[aria-current="page"] span,
    .custom-pagination span[aria-current="page"] a {
        color: #ffffff !important; /* Paksa Putih */
        background: transparent !important;
    }

    /* Perbaikan SVG (Panah) */
    .custom-pagination svg {
        width: 16px !important;
        height: 16px !important;
        fill: currentColor; 
    }

    /* Menyembunyikan text "Showing results" */
    .custom-pagination p {
        display: none; 
    }
    
    /* Responsive Styling */
    @media (max-width: 768px) {
        .page-header { display: none; }
        .notification-item { flex-direction: column; gap: 12px; padding-left: 20px; }
        .status-strip { width: 4px; }
        .notif-header { flex-direction: column; gap: 4px; }
        .notif-time { margin-left: 0; font-size: 11px; }
        .notif-actions { 
            flex-direction: row; border-left: none; border-top: 1px solid #f0f0f0; 
            padding-top: 10px; padding-left: 0; justify-content: flex-end; 
        }
        
        .custom-pagination nav { gap: 3px; padding: 6px; }
        .custom-pagination a, .custom-pagination span { min-width: 30px; height: 30px; font-size: 12px; }
    }
</style>

{{-- =============================================
     CONTENT HTML
     ============================================= --}}

{{-- HEADER HALAMAN (Desktop Only) --}}
<div class="page-header">
    <div class="page-title">
        <span>🔔</span> Notifikasi Saya
    </div>
    
    <div class="header-actions">
        @if($unreadCount > 0)
            <form action="{{ route('dashboard.notifikasi.read-all') }}" method="POST" style="margin:0;">
                @csrf
                <button type="submit" class="btn-read-all">
                    <span>✓</span> Tandai Semua Dibaca
                </button>
            </form>
        @else
            <div style="font-size: 13px; opacity: 0.9;">Semua notifikasi telah dibaca</div>
        @endif
    </div>
</div>

{{-- DAFTAR NOTIFIKASI --}}
<div class="notification-list">
    @forelse($notifications as $notification)
        @php
            $isBroadcast = is_null($notification->user_id) || $notification->user_id == 0;
            $isUnread = !$notification->isRead();
            
            // Kelas untuk styling
            $itemClass = '';
            if ($isUnread) $itemClass .= ' unread';
            if ($isBroadcast) $itemClass .= ' broadcast';
        @endphp

        <div class="notification-item {{ $itemClass }}">
            {{-- Strip Warna Indikator --}}
            <div class="status-strip"></div>

            {{-- Ikon --}}
            <div class="notif-icon-box">
                {{ $notification->icon ?? '📢' }}
            </div>

            {{-- Konten Teks --}}
            <div class="notif-content">
                <div class="notif-header">
                    <div class="notif-title-wrap">
                        @if($isUnread)
                            <span class="badge-status {{ $isBroadcast ? 'badge-info' : 'badge-new' }}">
                                {{ $isBroadcast ? 'Info' : 'Baru' }}
                            </span>
                        @endif
                        
                        <h4 class="notif-title">
                            @if(!empty($notification->link))
                                <a href="{{ $notification->link }}">{{ $notification->title }}</a>
                            @else
                                {{ $notification->title }}
                            @endif
                        </h4>
                    </div>
                    <span class="notif-time">{{ $notification->time_ago }}</span>
                </div>
                
                <p class="notif-message">{{ $notification->message }}</p>
            </div>

            {{-- Tombol Aksi --}}
            <div class="notif-actions">
                {{-- Link Action (Jika ada) --}}
                @if(!empty($notification->link))
                    <a href="{{ $notification->link }}" class="btn-icon link" title="Lihat Detail">
                        🚀
                    </a>
                @endif

                @if(!$isBroadcast)
                    {{-- Tandai Baca --}}
                    @if($isUnread)
                        <form action="{{ route('dashboard.notifikasi.read', $notification->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-icon check" title="Tandai Dibaca">
                                ✓
                            </button>
                        </form>
                    @endif

                    {{-- Hapus --}}
                    <form action="{{ route('dashboard.notifikasi.delete', $notification->id) }}" method="POST" onsubmit="return confirm('Hapus notifikasi ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-icon delete" title="Hapus">
                            🗑️
                        </button>
                    </form>
                @endif
            </div>
        </div>

    @empty
        <div class="empty-state">
            <span class="empty-icon">📭</span>
            <h3 style="color: #2b7a0b; font-weight: 700; margin-bottom: 10px;">Belum Ada Notifikasi</h3>
            <p style="color: #666; font-size: 14px; max-width: 400px; margin: 0 auto;">
                Saat ini belum ada pemberitahuan baru untuk Anda. Aktivitas terbaru akan muncul di sini.
            </p>
        </div>
    @endforelse

    {{-- PAGINATION --}}
    @if($notifications->hasPages())
        <div class="custom-pagination">
            {{ $notifications->links() }}
        </div>
    @endif
</div>

@endsection
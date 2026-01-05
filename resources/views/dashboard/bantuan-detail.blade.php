@extends('layouts.app')

@section('title', 'Detail Bantuan')

{{-- ============================================================ --}}
{{-- 1. HEADER MOBILE (Fixed Top)                                 --}}
{{-- ============================================================ --}}
@section('mobile-header')
    <header class="mobile-header-custom">
        <div class="header-left-content">
            {{-- Tombol Kembali ke Halaman List --}}
            <a href="{{ route('dashboard.help.create') }}" class="mobile-back-btn">
                ‹
            </a>
            
            <h2 class="mobile-title">
                Detail Pesan
            </h2>
        </div>
    </header>
@endsection

@section('content')

<style>
    /* --- VARIABLES (Disamakan dengan file sebelumnya) --- */
    :root {
        --primary-green: #2b7a0b;
        --dark-green: #1e5607;
        --light-green: #e6f1e3;
        --bg-gray: #f8f9fa;
        --border-color: #e5e5e5;
        --text-dark: #222;
        --text-muted: #777;
    }

    /* --- MOBILE HEADER STYLE --- */
    .mobile-header-custom { display: none; }
    
    .header-left-content {
        display: flex; align-items: center; gap: 12px; width: 100%;
    }

    .mobile-back-btn {
        width: 38px; height: 38px; display: flex; align-items: center; justify-content: center;
        background: rgba(255, 255, 255, 0.2); backdrop-filter: blur(5px);
        border-radius: 12px; color: white; text-decoration: none; font-size: 22px;
        border: 1px solid rgba(255, 255, 255, 0.3); transition: 0.3s; flex-shrink: 0; padding-bottom: 2px;
    }

    .mobile-title {
        font-size: 18px; font-weight: 700; color: white; margin: 0;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    /* --- DESKTOP WRAPPER --- */
    .help-wrapper {
        background-color: var(--bg-gray); min-height: 100vh; padding: 30px; font-family: 'Poppins', sans-serif;
    }

    .help-card {
        background: white; border-radius: 24px; box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        max-width: 900px; margin: 0 auto; overflow: hidden; animation: slideUp 0.5s ease;
    }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* --- HEADER DESKTOP --- */
    .card-header {
        padding: 30px 40px;
        background: linear-gradient(135deg, var(--primary-green) 0%, var(--dark-green) 100%);
        display: flex; align-items: center; gap: 20px; position: relative; overflow: hidden;
    }

    .back-button {
        width: 48px; height: 48px; border-radius: 14px;
        background: rgba(255, 255, 255, 0.2); backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3); display: flex; align-items: center; justify-content: center;
        text-decoration: none; color: white; font-size: 28px; font-weight: 300; transition: all 0.3s ease; z-index: 1;
    }
    .back-button:hover { background: rgba(255, 255, 255, 0.3); transform: translateX(-5px); }

    .card-title {
        font-size: 24px; font-weight: 700; color: white; margin: 0; z-index: 1;
    }

    /* --- CONTENT BODY --- */
    .card-body { padding: 40px 50px; }

    /* Metadata Strip (Tanggal & Status) */
    .meta-strip {
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 30px; padding-bottom: 20px; border-bottom: 2px solid #f0f0f0;
    }

    .date-info {
        display: flex; flex-direction: column; gap: 4px;
    }
    .date-label { font-size: 12px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; }
    .date-value { font-size: 14px; font-weight: 600; color: var(--text-dark); }

    .status-badge {
        padding: 6px 16px; border-radius: 20px; font-size: 12px; font-weight: 700; text-transform: uppercase;
    }
    .status-pending { background: #fff3cd; color: #856404; border: 1px solid #ffeeba; }
    .status-in_progress { background: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; }
    .status-resolved { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }

    /* Chat/Message Boxes */
    .message-box {
        padding: 25px; border-radius: 16px; margin-bottom: 25px; position: relative;
    }

    .box-label {
        font-size: 13px; font-weight: 700; text-transform: uppercase; margin-bottom: 12px; display: block;
        letter-spacing: 0.5px; display: flex; align-items: center; gap: 8px;
    }

    /* User Message Style */
    .user-box {
        background: #f8f9fa; border: 2px solid #e9ecef; color: var(--text-dark);
    }
    .user-box .box-label { color: #6c757d; }

    /* Admin Reply Style */
    .admin-box {
        background: #f0fdf4; border: 2px solid #bbf7d0; color: #14532d;
        animation: fadeIn 0.5s ease;
    }
    .admin-box .box-label { color: var(--primary-green); }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .message-text {
        font-size: 15px; line-height: 1.6; white-space: pre-wrap; margin: 0;
    }

    .reply-time {
        display: block; text-align: right; font-size: 11px; margin-top: 15px; opacity: 0.7; font-weight: 600;
    }

    /* Empty State for Reply */
    .waiting-state {
        text-align: center; padding: 40px 20px; background: #fff; 
        border: 2px dashed #e0e0e0; border-radius: 16px; color: var(--text-muted);
    }
    .waiting-icon { font-size: 40px; margin-bottom: 10px; display: block; opacity: 0.5; }

    /* Action Buttons (if needed in future) */
    .action-footer { margin-top: 30px; text-align: center; }
    .btn-home {
        display: inline-flex; align-items: center; justify-content: center; gap: 8px;
        padding: 12px 24px; background: white; border: 2px solid var(--border-color);
        border-radius: 12px; color: var(--text-dark); font-weight: 600; text-decoration: none;
        transition: all 0.3s;
    }
    .btn-home:hover { background: #f8f9fa; border-color: #ced4da; }

    /* --- RESPONSIVE --- */
    @media (max-width: 768px) {
        .card-header { display: none !important; }

        .mobile-header-custom {
            display: flex; align-items: center; width: 100%; height: 70px; padding: 0 20px;
            background: linear-gradient(135deg, #2b7a0b 0%, #1e5607 100%);
            box-shadow: 0 4px 15px rgba(43, 122, 11, 0.3); position: fixed; top: 0; left: 0; z-index: 999;
        }

        .help-wrapper { 
            margin-top: -80px; margin-left: -20px; margin-right: -20px;
            background-color: #f8f9fa; min-height: 100vh; padding: 0 15px;
            display: flex; flex-direction: column;
        }
        
        .help-card { 
            background: white; border-radius: 20px; box-shadow: 0 4px 25px rgba(0, 0, 0, 0.05); 
            margin-top: 90px; margin-bottom: 30px; width: 100%;
        }
        
        .card-body { padding: 25px 20px; }
        .meta-strip { flex-direction: column; align-items: flex-start; gap: 15px; }
        .status-badge { align-self: flex-start; }
    }
</style>

<div class="help-wrapper">
    <div class="help-card">
        
        {{-- Header Card (Desktop Only) --}}
        <div class="card-header">
            {{-- Tombol kembali mengarah ke halaman form/list bantuan --}}
            <a href="{{ route('dashboard.help.create') }}" class="back-button" title="Kembali">‹</a>
            <div style="color: white;">
                <h1 class="card-title">Detail Bantuan</h1>
                <span style="font-size: 13px; opacity: 0.9;">ID Tiket: #{{ $helpRequest->id }}</span>
            </div>
        </div>

        {{-- Body --}}
        <div class="card-body">

            {{-- 1. Metadata (Status & Tanggal) --}}
            <div class="meta-strip">
                <div class="date-info">
                    <span class="date-label">Dikirim Pada</span>
                    <span class="date-value">{{ $helpRequest->created_at->format('l, d F Y • H:i') }} WIB</span>
                </div>

                {{-- Logic Badge Status --}}
                @php
                    $statusLabel = 'Menunggu';
                    if($helpRequest->status == 'in_progress') $statusLabel = 'Diproses';
                    if($helpRequest->status == 'resolved') $statusLabel = 'Selesai';
                @endphp

                <span class="status-badge status-{{ $helpRequest->status }}">
                    {{ $statusLabel }}
                </span>
            </div>

            {{-- 2. Pesan Dari User --}}
            <div class="message-box user-box">
                <span class="box-label">👤 Pesan Anda</span>
                <p class="message-text">{{ $helpRequest->message }}</p>
            </div>

            {{-- 3. Balasan Admin (Conditional) --}}
            @if($helpRequest->hasReply())
                <div class="message-box admin-box">
                    <span class="box-label">💬 Balasan Admin</span>
                    <p class="message-text">{{ $helpRequest->admin_reply }}</p>
                    
                    <span class="reply-time">
                        Dibalas pada: {{ $helpRequest->replied_at ? \Carbon\Carbon::parse($helpRequest->replied_at)->format('d M Y, H:i') : '-' }}
                    </span>
                </div>
            @else
                <div class="waiting-state">
                    <span class="waiting-icon">⏳</span>
                    <h4 style="margin: 0 0 5px 0; font-size: 16px;">Menunggu Balasan</h4>
                    <p style="margin: 0; font-size: 13px;">Tim admin kami sedang meninjau pesan Anda. Mohon kesediaannya untuk menunggu.</p>
                </div>
            @endif

            {{-- Footer Action --}}
            <div class="action-footer">
                <a href="{{ route('dashboard.help.create') }}" class="btn-home">
                    📂 Lihat Riwayat Lainnya
                </a>
            </div>

        </div>
    </div>
</div>

@endsection
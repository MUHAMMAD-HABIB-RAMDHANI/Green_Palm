{{-- INDEX: resources/views/admin/bantuan/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Kelola Bantuan User')
@section('header-title', 'Kelola Bantuan User')

@section('content')
<div style="max-width: 1400px; margin: 0 auto;">
    
    {{-- Header Section --}}
    <div class="card" style="margin-bottom: 25px; background: linear-gradient(135deg, #1E4620 0%, #2b7a0b 100%); border: none;">
        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 15px;">
            <div>
                <h2 style="color: white; font-size: 24px; margin: 0 0 8px 0; font-weight: 700;">
                    💬 Kelola Bantuan User
                </h2>
                <p style="color: rgba(255,255,255,0.9); margin: 0; font-size: 15px;">
                    Kelola dan balas pesan bantuan dari pengguna aplikasi
                </p>
            </div>
            <div style="text-align: right;">
                <div style="background: rgba(255,255,255,0.2); color: white; padding: 8px 16px; border-radius: 8px; font-weight: 600; font-size: 14px; backdrop-filter: blur(5px);">
                    📅 {{ now()->format('d M Y') }}
                </div>
            </div>
        </div>
    </div>

    {{-- Statistics Cards (Updated Design) --}}
    <div class="stats-grid">
        
        {{-- Card: Total Pesan --}}
        <div class="stat-card" style="border-left-color: #2b7a0b;">
            <div class="stat-content">
                <div class="stat-text">
                    <span class="stat-label">Total Pesan</span>
                    <h4 class="stat-value">{{ $stats['total'] }}</h4>
                </div>
                <div class="stat-icon-wrapper" style="background: #dcfce7; color: #166534;">
                    📩
                </div>
            </div>
        </div>

        {{-- Card: Menunggu --}}
        <div class="stat-card" style="border-left-color: #f59e0b;">
            <div class="stat-content">
                <div class="stat-text">
                    <span class="stat-label">Menunggu</span>
                    <h4 class="stat-value">{{ $stats['pending'] }}</h4>
                </div>
                <div class="stat-icon-wrapper" style="background: #fffbeb; color: #d97706;">
                    ⏳
                </div>
            </div>
        </div>

        {{-- Card: Diproses --}}
        <div class="stat-card" style="border-left-color: #0ea5e9;">
            <div class="stat-content">
                <div class="stat-text">
                    <span class="stat-label">Diproses</span>
                    <h4 class="stat-value">{{ $stats['in_progress'] }}</h4>
                </div>
                <div class="stat-icon-wrapper" style="background: #f0f9ff; color: #0284c7;">
                    🔄
                </div>
            </div>
        </div>

        {{-- Card: Selesai --}}
        <div class="stat-card" style="border-left-color: #10b981;">
            <div class="stat-content">
                <div class="stat-text">
                    <span class="stat-label">Selesai</span>
                    <h4 class="stat-value">{{ $stats['resolved'] }}</h4>
                </div>
                <div class="stat-icon-wrapper" style="background: #ecfdf5; color: #059669;">
                    ✅
                </div>
            </div>
        </div>
    </div>

    {{-- Filter Tabs --}}
    <div class="card" style="margin-bottom: 20px; padding: 15px 20px;">
        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            <a href="{{ route('admin.bantuan.index') }}" 
               class="filter-tab {{ !request('status') ? 'active' : '' }}">
                📋 Semua
            </a>
            <a href="{{ route('admin.bantuan.index', ['status' => 'pending']) }}" 
               class="filter-tab {{ request('status') == 'pending' ? 'active' : '' }}">
                ⏳ Menunggu
            </a>
            <a href="{{ route('admin.bantuan.index', ['status' => 'in_progress']) }}" 
               class="filter-tab {{ request('status') == 'in_progress' ? 'active' : '' }}">
                🔄 Diproses
            </a>
            <a href="{{ route('admin.bantuan.index', ['status' => 'resolved']) }}" 
               class="filter-tab {{ request('status') == 'resolved' ? 'active' : '' }}">
                ✅ Selesai
            </a>
        </div>
    </div>

    {{-- Help Requests List --}}
    <div class="card">
        @forelse($helpRequests as $help)
        <div class="help-item">
            <div class="help-header">
                <div class="help-user-info">
                    <div class="user-avatar">
                        {{ strtoupper(substr($help->username, 0, 1)) }}
                    </div>
                    <div>
                        <h4 class="user-name">{{ $help->username }}</h4>
                        <p class="help-date">{{ $help->created_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>
                
                <div style="display: flex; align-items: center; gap: 10px;">
                    <span class="status-badge" style="background-color: {{ $help->status_color }};">
                        {{ $help->status_label }}
                    </span>
                </div>
            </div>

            <div class="help-message">
                {{ $help->message }}
            </div>

            @if($help->hasReply())
            <div class="admin-reply-box">
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                    <span style="font-weight: 700; color: #166534;">💬 Balasan Admin</span>
                    <span style="font-size: 12px; color: #64748b;">
                        {{ $help->replied_at->format('d M Y, H:i') }}
                    </span>
                </div>
                <p style="margin: 0; color: #14532d;">{{ $help->admin_reply }}</p>
            </div>
            @endif

            <div class="help-actions">
                @if(!$help->hasReply())
                <button onclick="openReplyModal({{ $help->id }}, '{{ $help->username }}')" class="btn-reply">
                    💬 Balas
                </button>
                @endif

                <form action="{{ route('admin.bantuan.updateStatus', $help->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('PATCH')
                    <select name="status" onchange="this.form.submit()" class="status-select">
                        <option value="pending" {{ $help->status == 'pending' ? 'selected' : '' }}>⏳ Menunggu</option>
                        <option value="in_progress" {{ $help->status == 'in_progress' ? 'selected' : '' }}>🔄 Diproses</option>
                        <option value="resolved" {{ $help->status == 'resolved' ? 'selected' : '' }}>✅ Selesai</option>
                    </select>
                </form>

                {{-- Global Delete Modal Trigger --}}
                <button type="button" 
                        class="btn-delete confirm-delete"
                        data-action="{{ route('admin.bantuan.destroy', $help->id) }}"
                        data-name="Pesan dari {{ $help->username }}">
                    🗑️ Hapus
                </button>
            </div>
        </div>
        @empty
        <div style="text-align: center; padding: 60px 20px; color: #94a3b8;">
            <div style="font-size: 48px; margin-bottom: 15px;">📭</div>
            <h3 style="margin: 0 0 8px 0; color: #64748b;">Tidak Ada Pesan</h3>
            <p style="margin: 0;">Belum ada pesan bantuan dari user</p>
        </div>
        @endforelse

        {{-- Pagination --}}
        @if($helpRequests->hasPages())
        <div style="margin-top: 25px; padding-top: 20px; border-top: 2px solid #f1f5f9;">
            {{ $helpRequests->links() }}
        </div>
        @endif
    </div>
</div>



<style>
    /* Stats Grid & Cards (MATCHING REPORT PAGE) */
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
        font-size: 28px;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
        line-height: 1.2;
    }

    .stat-icon-wrapper {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }

    /* Filter Tabs */
    .filter-tab {
        padding: 10px 20px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        color: #64748b;
        background: #f8fafc;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }
    
    .filter-tab:hover {
        background: #f0fdf4;
        color: #166534;
    }
    
    .filter-tab.active {
        background: linear-gradient(135deg, #2b7a0b 0%, #1E4620 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(43, 122, 11, 0.3);
    }

    /* Help Item */
    .help-item {
        padding: 20px;
        border-bottom: 2px solid #f1f5f9;
        transition: all 0.3s ease;
    }
    
    .help-item:last-child {
        border-bottom: none;
    }
    
    .help-item:hover {
        background: #f0fdf4;
    }

    .help-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

    .help-user-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .user-avatar {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: linear-gradient(135deg, #2b7a0b 0%, #1E4620 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 18px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .user-name {
        margin: 0;
        font-size: 16px;
        font-weight: 700;
        color: #1E4620;
    }

    .help-date {
        margin: 0;
        font-size: 12px;
        color: #94a3b8;
    }

    .status-badge {
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        color: white;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .help-message {
        background: white;
        padding: 15px;
        border-radius: 12px;
        margin-bottom: 15px;
        font-size: 14px;
        line-height: 1.6;
        color: #334155;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    }

    .admin-reply-box {
        background: #dcfce7;
        border-left: 4px solid #166534;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 15px;
    }

    .help-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .btn-reply, .btn-delete {
        padding: 8px 16px;
        border-radius: 8px;
        border: none;
        font-weight: 600;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-reply {
        background: linear-gradient(135deg, #2b7a0b 0%, #1E4620 100%);
        color: white;
    }

    .btn-reply:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(43, 122, 11, 0.3);
    }

    .btn-delete {
        background: #fef2f2;
        color: #991b1b;
        border: 1px solid #fca5a5;
    }

    .btn-delete:hover {
        background: #ef4444;
        color: white;
        border-color: #ef4444;
    }

    .status-select {
        padding: 8px 12px;
        border-radius: 8px;
        border: 2px solid #e2e8f0;
        font-weight: 600;
        font-size: 13px;
        cursor: pointer;
        background: white;
        color: #334155;
        transition: 0.2s;
    }
    
    .status-select:focus {
        border-color: #2b7a0b;
        outline: none;
    }
</style>


@endsection
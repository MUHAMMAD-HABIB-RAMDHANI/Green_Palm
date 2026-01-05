@extends('admin.layouts.app')

@section('title', 'Kelola Bantuan User')
@section('header-title', 'Kelola Bantuan User')

@section('content')
<div style="max-width: 1400px; margin: 0 auto;">
    
    {{-- Header Section --}}
    <div class="card" style="margin-bottom: 25px; background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); border: none;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <h2 style="color: white; font-size: 24px; margin: 0 0 8px 0; font-weight: 700;">
                    💬 Kelola Bantuan User
                </h2>
                <p style="color: rgba(255,255,255,0.9); margin: 0; font-size: 15px;">
                    Kelola dan balas pesan bantuan dari pengguna aplikasi
                </p>
            </div>
            <div style="text-align: right;">
                <div style="background: white; color: #7c3aed; padding: 8px 16px; border-radius: 8px; font-weight: 700; font-size: 14px;">
                    📅 {{ now()->format('d M Y') }}
                </div>
            </div>
        </div>
    </div>

    {{-- Statistics Cards --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px; margin-bottom: 30px;">
        
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #3b82f6 0%, #1e3a8a 100%);">📩</div>
            <div class="stat-info">
                <h4>{{ $stats['total'] }}</h4>
                <p>Total Pesan</p>
            </div>
        </div>

        <div class="stat-card" style="border-left-color: #fbbf24;">
            <div class="stat-icon" style="background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);">⏳</div>
            <div class="stat-info">
                <h4>{{ $stats['pending'] }}</h4>
                <p>Menunggu</p>
            </div>
        </div>

        <div class="stat-card" style="border-left-color: #06b6d4;">
            <div class="stat-icon" style="background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);">🔄</div>
            <div class="stat-info">
                <h4>{{ $stats['in_progress'] }}</h4>
                <p>Diproses</p>
            </div>
        </div>

        <div class="stat-card" style="border-left-color: #10b981;">
            <div class="stat-icon" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">✅</div>
            <div class="stat-info">
                <h4>{{ $stats['resolved'] }}</h4>
                <p>Selesai</p>
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
                    <span class="status-badge status-{{ $help->status }}" style="background-color: {{ $help->status_color }};">
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
                    <span style="font-weight: 700; color: #1e3a8a;">💬 Balasan Admin</span>
                    <span style="font-size: 12px; color: #94a3b8;">
                        {{ $help->replied_at->format('d M Y, H:i') }}
                    </span>
                </div>
                <p style="margin: 0; color: #334155;">{{ $help->admin_reply }}</p>
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

                <button onclick="confirmDelete({{ $help->id }}, '{{ $help->username }}')" class="btn-delete">
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

{{-- Modal Reply --}}
<div id="replyModal" class="modal-overlay" style="display: none;">
    <div class="modal-box">
        <div class="modal-header">
            <h3>💬 Balas Pesan</h3>
            <button onclick="closeReplyModal()" class="modal-close">×</button>
        </div>
        
        <form id="replyForm" method="POST">
            @csrf
            <div class="modal-body">
                <p style="margin-bottom: 15px; color: #64748b;">
                    Balas pesan dari: <strong id="replyUsername"></strong>
                </p>
                
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #1e293b;">
                    Balasan Anda
                </label>
                <textarea 
                    name="admin_reply" 
                    id="adminReply" 
                    required 
                    maxlength="1000"
                    style="width: 100%; min-height: 150px; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-family: 'Poppins', sans-serif; font-size: 14px;"
                    placeholder="Tulis balasan untuk user..."></textarea>
                <div style="text-align: right; font-size: 12px; color: #94a3b8; margin-top: 5px;">
                    <span id="replyCharCount">0</span> / 1000 karakter
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" onclick="closeReplyModal()" class="btn-cancel">Batal</button>
                <button type="submit" class="btn-submit">📤 Kirim Balasan</button>
            </div>
        </form>
    </div>
</div>

{{-- Delete Confirmation Modal --}}
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<style>
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
        background: #e0f2fe;
        color: #0284c7;
    }
    
    .filter-tab.active {
        background: linear-gradient(135deg, #3b82f6 0%, #1e3a8a 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
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
        background: #f8fafc;
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
        background: linear-gradient(135deg, #3b82f6 0%, #1e3a8a 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 18px;
    }

    .user-name {
        margin: 0;
        font-size: 16px;
        font-weight: 600;
        color: #1e293b;
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
    }

    .help-message {
        background: #f8fafc;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 15px;
        font-size: 14px;
        line-height: 1.6;
        color: #334155;
        border-left: 4px solid #cbd5e1;
    }

    .admin-reply-box {
        background: #dbeafe;
        border-left: 4px solid #3b82f6;
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
        background: linear-gradient(135deg, #3b82f6 0%, #1e3a8a 100%);
        color: white;
    }

    .btn-reply:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .btn-delete {
        background: #fee2e2;
        color: #991b1b;
    }

    .btn-delete:hover {
        background: #ef4444;
        color: white;
    }

    .status-select {
        padding: 8px 12px;
        border-radius: 8px;
        border: 2px solid #e2e8f0;
        font-weight: 600;
        font-size: 13px;
        cursor: pointer;
        background: white;
    }

    /* Modal Styles */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        backdrop-filter: blur(4px);
    }

    .modal-box {
        background: white;
        border-radius: 16px;
        width: 90%;
        max-width: 600px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        animation: modalSlideUp 0.3s ease;
    }

    @keyframes modalSlideUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .modal-header {
        padding: 20px 25px;
        border-bottom: 2px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-header h3 {
        margin: 0;
        font-size: 20px;
        color: #1e293b;
    }

    .modal-close {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        border: none;
        background: #f1f5f9;
        font-size: 24px;
        cursor: pointer;
        color: #64748b;
        transition: 0.3s;
    }

    .modal-close:hover {
        background: #ef4444;
        color: white;
    }

    .modal-body {
        padding: 25px;
    }

    .modal-footer {
        padding: 20px 25px;
        border-top: 2px solid #f1f5f9;
        display: flex;
        gap: 10px;
        justify-content: flex-end;
    }

    .btn-cancel, .btn-submit {
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: 0.3s;
        border: none;
    }

    .btn-cancel {
        background: #f1f5f9;
        color: #64748b;
    }

    .btn-cancel:hover {
        background: #e2e8f0;
    }

    .btn-submit {
        background: linear-gradient(135deg, #3b82f6 0%, #1e3a8a 100%);
        color: white;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    @media (max-width: 768px) {
        .help-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }

        .help-actions {
            flex-direction: column;
        }

        .btn-reply, .btn-delete, .status-select {
            width: 100%;
        }
    }
</style>

<script>
    let currentHelpId = null;

    function openReplyModal(helpId, username) {
        currentHelpId = helpId;
        document.getElementById('replyUsername').textContent = username;
        document.getElementById('replyForm').action = `/admin/bantuan/${helpId}/reply`;
        document.getElementById('replyModal').style.display = 'flex';
    }

    function closeReplyModal() {
        document.getElementById('replyModal').style.display = 'none';
        document.getElementById('adminReply').value = '';
        document.getElementById('replyCharCount').textContent = '0';
    }

    // Character counter
    document.getElementById('adminReply').addEventListener('input', function() {
        document.getElementById('replyCharCount').textContent = this.value.length;
    });

    // Delete confirmation
    function confirmDelete(helpId, username) {
        if (confirm(`Hapus pesan dari ${username}?\n\nTindakan ini tidak dapat dibatalkan.`)) {
            const form = document.getElementById('deleteForm');
            form.action = `/admin/bantuan/${helpId}`;
            form.submit();
        }
    }

    // Close modal on outside click
    document.getElementById('replyModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeReplyModal();
        }
    });
</script>

@endsection
@extends('layouts.app')

@section('title', 'Pusat Bantuan')

{{-- ============================================================ --}}
{{-- 1. HEADER MOBILE --}}
{{-- ============================================================ --}}
@section('mobile-header')
    <header class="mobile-header-custom">
        <div class="header-left-content">
            <a href="{{ route('dashboard.profil') }}" class="mobile-back-btn">
                ‹
            </a>
            <h2 class="mobile-title">
                Pusat Bantuan
            </h2>
        </div>
    </header>
@endsection

@section('content')

<style>
    /* --- VARIABLES --- */
    :root {
        --primary-green: #2b7a0b;
        --dark-green: #1e5607;
        --light-green: #e6f1e3;
        --bg-gray: #f8f9fa;
        --text-dark: #222;
        --text-muted: #777;
    }

    /* =========================================
       STYLE MOBILE HEADER
       ========================================= */
    .mobile-header-custom { display: none; }
    .header-left-content { display: flex; align-items: center; gap: 12px; width: 100%; }
    .mobile-back-btn {
        width: 38px; height: 38px; display: flex; align-items: center; justify-content: center; 
        background: rgba(255, 255, 255, 0.2); backdrop-filter: blur(5px);
        border-radius: 12px; color: white; text-decoration: none; font-size: 22px; 
        border: 1px solid rgba(255, 255, 255, 0.3); transition: 0.3s; flex-shrink: 0; padding-bottom: 2px;
    }
    .mobile-title {
        font-size: 18px; font-weight: 700; color: white; margin: 0; 
        text-shadow: 0 2px 4px rgba(0,0,0,0.1); white-space: nowrap;
    }

    /* --- DESKTOP STYLES --- */
    .help-wrapper {
        background-color: var(--bg-gray); min-height: 100vh; padding: 30px; font-family: 'Poppins', sans-serif;
    }
    .help-card {
        background: white; border-radius: 24px; box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        max-width: 900px; margin: 0 auto; overflow: hidden; animation: slideUp 0.5s ease;
    }
    @keyframes slideUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }

    .card-header {
        padding: 30px 40px; background: linear-gradient(135deg, var(--primary-green) 0%, var(--dark-green) 100%);
        display: flex; align-items: center; gap: 20px; position: relative; overflow: hidden;
    }
    .card-header::before {
        content: ''; position: absolute; top: -50%; right: -10%; width: 400px; height: 400px;
        background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%); animation: pulse 8s ease-in-out infinite;
    }
    @keyframes pulse { 0%, 100% { transform: scale(1); opacity: 0.5; } 50% { transform: scale(1.2); opacity: 0.3; } }

    .back-button {
        width: 48px; height: 48px; border-radius: 14px; background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.3);
        display: flex; align-items: center; justify-content: center; text-decoration: none;
        color: white; font-size: 28px; font-weight: 300; transition: all 0.3s ease; z-index: 1;
    }
    .back-button:hover { background: rgba(255, 255, 255, 0.3); transform: translateX(-5px); }
    .card-title { font-size: 28px; font-weight: 700; color: white; margin: 0; z-index: 1; }
    .card-body { padding: 45px 50px; }

    /* Info & Form */
    .info-banner {
        border-radius: 16px; padding: 20px 25px; margin-bottom: 25px; display: flex; align-items: center; gap: 15px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05); background: linear-gradient(135deg, var(--light-green) 0%, #d4edda 100%);
        border-left: 5px solid var(--primary-green);
    }
    .info-icon { font-size: 32px; flex-shrink: 0; }
    .info-content h3 { font-size: 16px; font-weight: 700; color: var(--text-dark); margin: 0 0 4px 0; }
    .info-content p { font-size: 13px; color: var(--text-muted); margin: 0; line-height: 1.4; }

    /* Alerts */
    .alert { padding: 15px 20px; border-radius: 12px; margin-bottom: 20px; display: flex; align-items: center; gap: 12px; font-size: 14px; animation: slideDown 0.3s ease; }
    .alert-success { background: #d4edda; color: #155724; border-left: 4px solid #28a745; }
    .alert-danger { background: #f8d7da; color: #721c24; border-left: 4px solid #dc3545; }

    /* Form */
    .form-group { margin-bottom: 25px; }
    .form-label { font-size: 14px; font-weight: 600; color: var(--text-dark); margin-bottom: 12px; display: flex; align-items: center; gap: 8px; }
    .form-textarea {
        width: 100%; padding: 16px 18px; border: 2px solid #e8e8e8; border-radius: 12px; font-size: 15px;
        font-family: 'Poppins', sans-serif; transition: all 0.3s ease; resize: vertical; min-height: 150px;
    }
    .form-textarea:focus { outline: none; border-color: var(--primary-green); box-shadow: 0 0 0 5px rgba(43, 122, 11, 0.1); }
    .char-counter { text-align: right; font-size: 12px; color: var(--text-muted); margin-top: 6px; }
    .btn-submit {
        width: 100%; padding: 16px 30px; border-radius: 12px; font-weight: 600; font-size: 16px; cursor: pointer; border: none;
        background: linear-gradient(135deg, var(--primary-green) 0%, var(--dark-green) 100%); color: white;
        box-shadow: 0 5px 20px rgba(43, 122, 11, 0.3); display: flex; align-items: center; justify-content: center; gap: 10px; transition: all 0.3s ease;
    }
    .btn-submit:hover { transform: translateY(-3px); box-shadow: 0 8px 30px rgba(43, 122, 11, 0.4); }

    /* History Section */
    .history-section { margin-top: 40px; padding-top: 30px; border-top: 2px solid #f0f0f0; }
    .history-title { font-size: 18px; font-weight: 700; color: var(--text-dark); margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
    .history-list { display: flex; flex-direction: column; gap: 15px; }

    /* ✅ NEW: Link Style for History Item */
    .history-link {
        text-decoration: none; 
        color: inherit; 
        display: block; 
        transition: transform 0.2s ease;
    }
    .history-link:hover {
        transform: translateY(-3px);
    }
    .history-link:hover .history-item {
        border-left-color: var(--primary-green);
        background: #f1f3f5;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }

    .history-item {
        background: #f8f9fa; border-radius: 12px; padding: 18px 20px;
        border-left: 4px solid #ddd; transition: all 0.3s ease;
        position: relative; /* For arrow positioning */
    }

    .history-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; padding-right: 20px; }
    .history-date { font-size: 12px; color: var(--text-muted); }
    
    .status-badge { font-size: 11px; font-weight: 700; padding: 4px 12px; border-radius: 20px; text-transform: uppercase; }
    .status-pending { background: #fff3cd; color: #856404; }
    .status-in_progress { background: #d1ecf1; color: #0c5460; }
    .status-resolved { background: #d4edda; color: #155724; }

    .history-message { font-size: 14px; color: var(--text-dark); line-height: 1.5; margin-bottom: 8px; }
    .history-reply {
        background: white; border-left: 3px solid var(--primary-green); padding: 12px 15px;
        margin-top: 10px; border-radius: 8px; font-size: 13px; color: #495057;
    }
    .reply-label { font-weight: 700; color: var(--primary-green); margin-bottom: 5px; display: block; }
    .empty-history { text-align: center; padding: 40px 20px; background: #fdfdfd; border: 2px dashed #e0e0e0; border-radius: 16px; color: var(--text-muted); }

    /* Arrow Icon for history item */
    .arrow-icon {
        position: absolute; right: 15px; top: 20px; color: #ccc; font-size: 20px; font-weight: bold;
    }

    @media (max-width: 768px) {
        .card-header { display: none !important; }
        .mobile-header-custom {
            display: flex; align-items: center; width: 100%; height: 70px; padding: 0 20px;
            background: linear-gradient(135deg, #2b7a0b 0%, #1e5607 100%);
            box-shadow: 0 4px 15px rgba(43, 122, 11, 0.3); position: fixed; top: 0; left: 0; z-index: 999;
        }
        .help-wrapper { 
            margin-top: -80px; margin-left: -20px; margin-right: -20px;
            background-color: #f8f9fa; min-height: 100vh; padding: 0 15px; display: flex; flex-direction: column;
        }
        .help-card { 
            background: white; border-radius: 20px; box-shadow: 0 4px 25px rgba(0, 0, 0, 0.05); 
            margin-top: 90px; margin-bottom: 30px; width: 100%;
        }
        .card-body { padding: 25px 20px; }
        .info-banner { padding: 15px; margin-bottom: 20px; }
        .history-item { padding: 15px; }
    }
</style>

<div class="help-wrapper">
    <div class="help-card">
        
        <div class="card-header">
            <a href="{{ route('dashboard.profil') }}" class="back-button" title="Kembali">‹</a>
            <h1 class="card-title">❓ Pusat Bantuan</h1>
        </div>

        <div class="card-body">
            
            @if(session('success'))
                <div class="alert alert-success"><span>✓</span> {{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger"><span>✗</span> {{ session('error') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">
                    <span>✗</span>
                    <div>@foreach($errors->all() as $error) <div>{{ $error }}</div> @endforeach</div>
                </div>
            @endif

            <div class="info-banner">
                <div class="info-icon">👤</div>
                <div class="info-content">
                    <h3>{{ $user->username }}</h3>
                    <p>Ceritakan masalah atau pertanyaan Anda, tim kami siap membantu!</p>
                </div>
            </div>

            <form action="{{ route('dashboard.help.store') }}" method="POST" id="helpForm">
                @csrf
                <div class="form-group">
                    <label class="form-label"><span>💬</span> Pesan Keluhan / Pertanyaan <span style="color: #dc3545;">*</span></label>
                    <textarea name="message" id="message" class="form-textarea" placeholder="Tulis masalah Anda di sini..." maxlength="1000" required>{{ old('message') }}</textarea>
                    <div class="char-counter"><span id="charCount">0</span> / 1000 karakter</div>
                </div>
                <button type="submit" class="btn-submit" id="submitBtn">📤 Kirim Pesan</button>
            </form>

            <div class="history-section">
                <h2 class="history-title"><span>📋</span> Riwayat Bantuan Terbaru</h2>

                <div class="history-list">
                    @forelse($helpHistory as $help)
                    {{-- ✅ ITEM DIBUNGKUS LINK AGAR BISA DIKLIK --}}
                    <a href="{{ route('dashboard.help.show', $help->id) }}" class="history-link">
                        <div class="history-item">
                            <span class="arrow-icon">›</span>
                            <div class="history-header">
                                <span class="history-date">📅 {{ $help->created_at->format('d M Y, H:i') }}</span>
                                <span class="status-badge status-{{ $help->status }}">{{ $help->status_label }}</span>
                            </div>
                            <div class="history-message">{{ Str::limit($help->message, 100) }}</div>
                            @if($help->hasReply())
                            <div class="history-reply">
                                <span class="reply-label">💬 Balasan Admin:</span>
                                {{ Str::limit($help->admin_reply, 80) }}
                            </div>
                            @endif
                        </div>
                    </a>
                    @empty
                    <div class="empty-history">
                        <div style="font-size: 40px; margin-bottom: 10px;">📭</div>
                        <p style="margin: 0;">Belum ada riwayat pesan bantuan.</p>
                    </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const message = document.getElementById('message');
    const charCount = document.getElementById('charCount');
    const form = document.getElementById('helpForm');
    const submitBtn = document.getElementById('submitBtn');

    if (message) {
        message.addEventListener('input', function() { charCount.textContent = this.value.length; });
        if (message.value) charCount.textContent = message.value.length;
    }
    if (form) {
        form.addEventListener('submit', function() {
            submitBtn.innerHTML = '⏳ Mengirim...';
            submitBtn.style.opacity = '0.8';
            submitBtn.disabled = true;
        });
    }
});
</script>

@endsection
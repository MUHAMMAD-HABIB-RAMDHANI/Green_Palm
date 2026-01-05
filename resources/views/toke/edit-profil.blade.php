@extends('toke.toke')

@section('title', 'Edit Profil Toke')

@section('content')

<style>
    /* --- VARIABLES --- */
    :root {
        --primary-green: #2b7a0b;
        --dark-green: #1e5607;
        --light-green: #e6f1e3;
        --bg-gray: #f8f9fa;
        --border-color: #e5e5e5;
        --text-dark: #222;
        --text-muted: #777;
    }

    /* --- DESKTOP BASE STYLES --- */
    .edit-profile-wrapper {
        background-color: var(--bg-gray);
        min-height: 100vh;
        padding: 30px;
        font-family: 'Poppins', sans-serif;
    }

    .edit-profile-card {
        background: white;
        border-radius: 24px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        max-width: 800px;
        margin: 0 auto;
        overflow: hidden;
        animation: slideUp 0.5s ease;
    }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Header Section */
    .card-header {
        padding: 30px 40px;
        background: linear-gradient(135deg, var(--primary-green) 0%, var(--dark-green) 100%);
        display: flex;
        align-items: center;
        gap: 20px;
        position: relative;
        overflow: hidden;
    }

    .card-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%);
        animation: pulse 8s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); opacity: 0.5; }
        50% { transform: scale(1.2); opacity: 0.3; }
    }

    .back-button {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        color: white;
        font-size: 28px;
        font-weight: 300;
        transition: all 0.3s ease;
        z-index: 1;
    }

    .back-button:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateX(-5px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .back-button:active {
        transform: translateX(-3px) scale(0.95);
    }

    .card-title {
        font-size: 28px;
        font-weight: 700;
        color: white;
        margin: 0;
        z-index: 1;
        text-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        letter-spacing: -0.5px;
    }

    /* Card Body */
    .card-body {
        padding: 45px 50px;
    }

    /* User Info Banner */
    .user-info-banner {
        background: linear-gradient(135deg, var(--light-green) 0%, #d4edda 100%);
        border-radius: 16px;
        padding: 20px 25px;
        margin-bottom: 35px;
        display: flex;
        align-items: center;
        gap: 15px;
        border-left: 5px solid var(--primary-green);
        box-shadow: 0 4px 15px rgba(43, 122, 11, 0.1);
    }

    .user-info-icon {
        font-size: 32px;
        flex-shrink: 0;
    }

    .user-info-content h3 {
        font-size: 18px;
        font-weight: 700;
        color: var(--text-dark);
        margin: 0 0 5px 0;
    }

    .user-info-content p {
        font-size: 13px;
        color: var(--text-muted);
        margin: 0;
        line-height: 1.5;
    }

    /* Form Grid System */
    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 25px;
        margin-bottom: 30px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group.full-width {
        grid-column: span 2;
    }

    .form-label {
        font-size: 14px;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .form-label-icon {
        font-size: 16px;
    }

    .required-mark {
        color: #dc3545;
        font-size: 14px;
    }

    .form-input, .form-select {
        width: 100%;
        padding: 14px 18px;
        border: 2px solid #e8e8e8;
        border-radius: 12px;
        font-size: 15px;
        font-family: 'Poppins', sans-serif;
        transition: all 0.3s ease;
        background-color: white;
    }

    .form-input:focus, .form-select:focus {
        outline: none;
        border-color: var(--primary-green);
        box-shadow: 0 0 0 5px rgba(43, 122, 11, 0.1);
        transform: translateY(-2px);
    }

    .form-input:hover:not(:focus):not([readonly]), 
    .form-select:hover:not(:focus) {
        border-color: #c0c0c0;
    }

    .form-input[readonly] {
        background: linear-gradient(135deg, #fafafa 0%, #f2f2f2 100%);
        color: #999;
        cursor: not-allowed;
        border-color: #e8e8e8;
    }

    .form-helper {
        font-size: 12px;
        color: var(--text-muted);
        margin-top: 6px;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .form-helper::before {
        content: 'ℹ️';
        font-size: 14px;
    }

    /* Button Section (Updated) */
    .button-section {
        display: flex;
        margin-top: 35px;
        padding-top: 30px;
        border-top: 2px solid #f0f0f0;
    }

    .btn {
        padding: 16px 30px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        border: none;
        position: relative;
        overflow: hidden;
    }

    .btn-save {
        background: linear-gradient(135deg, var(--primary-green) 0%, var(--dark-green) 100%);
        color: white;
        box-shadow: 0 5px 20px rgba(43, 122, 11, 0.3);
        width: 100%; /* Full Width */
    }

    .btn-save::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }

    .btn-save:hover::before {
        width: 800px;
        height: 800px;
    }

    .btn-save:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 30px rgba(43, 122, 11, 0.4);
    }

    .btn-save:active {
        transform: translateY(-1px);
    }

    .btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        pointer-events: none;
    }

    /* Alerts - Enhanced Toast Style */
    .alert {
        padding: 16px 20px;
        border-radius: 14px;
        margin-bottom: 25px;
        font-size: 14px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 12px;
        animation: slideDown 0.4s ease;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
    }

    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-15px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .alert::before {
        font-size: 22px;
        flex-shrink: 0;
    }

    .alert-success { 
        background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        color: #155724;
        border-left: 5px solid #28a745;
    }

    .alert-success::before {
        content: '✅';
    }

    .alert-error { 
        background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
        color: #721c24;
        border-left: 5px solid #dc3545;
    }

    .alert-error::before {
        content: '❌';
    }

    /* --- MOBILE RESPONSIVE STYLES --- */
    @media (max-width: 768px) {
        .edit-profile-wrapper {
            padding: 0;
            background: white;
        }

        .edit-profile-card {
            box-shadow: none;
            border-radius: 0;
        }

        .card-header {
            padding: 20px;
            border-radius: 0 0 25px 25px;
        }

        .card-header::before {
            width: 300px;
            height: 300px;
        }

        .back-button {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            font-size: 24px;
        }

        .card-title {
            font-size: 22px;
        }

        .card-body {
            padding: 30px 20px;
        }

        .user-info-banner {
            padding: 18px 20px;
            margin-bottom: 30px;
        }

        .user-info-icon {
            font-size: 28px;
        }

        .user-info-content h3 {
            font-size: 16px;
        }

        .user-info-content p {
            font-size: 12px;
        }

        .form-grid {
            grid-template-columns: 1fr;
            gap: 22px;
        }
        
        .form-group.full-width {
            grid-column: auto;
        }

        .form-label {
            font-size: 13px;
            margin-bottom: 8px;
        }

        .form-input, .form-select {
            padding: 13px 16px;
            font-size: 14px;
        }

        .button-section {
            margin-top: 30px;
            padding-top: 25px;
        }

        .btn {
            padding: 15px;
        }

        .alert {
            font-size: 13px;
            padding: 14px 18px;
        }
    }

    @media (max-width: 480px) {
        .card-header {
            padding: 18px 16px;
        }

        .back-button {
            width: 40px;
            height: 40px;
            font-size: 22px;
        }

        .card-title {
            font-size: 20px;
        }

        .card-body {
            padding: 25px 16px;
        }

        .user-info-banner {
            padding: 15px 18px;
            gap: 12px;
        }

        .user-info-icon {
            font-size: 24px;
        }

        .user-info-content h3 {
            font-size: 15px;
        }
    }
</style>

<div class="edit-profile-wrapper">
    <div class="edit-profile-card">
        
        {{-- Header --}}
        <div class="card-header">
            {{-- Tombol Back mengarah ke Profil Toke --}}
            <a href="{{ route('toke.profil') }}" class="back-button" title="Kembali">
                ‹
            </a>
            <h1 class="card-title">Edit Profil Toke</h1>
        </div>

        {{-- Card Body --}}
        <div class="card-body">
            
            {{-- User Info Banner --}}
            <div class="user-info-banner">
                <div class="user-info-icon">👤</div>
                <div class="user-info-content">
                    <h3>{{ $user->username }}</h3>
                    <p>{{ $user->email }} • Perbarui informasi profil Anda di bawah ini</p>
                </div>
            </div>

            {{-- Alert Messages --}}
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-error">
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-error">
                    <div>
                        @foreach($errors->all() as $error)
                            <div>• {{ $error }}</div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Form mengarah ke route update profil Toke --}}
            <form action="{{ route('toke.profile.update') }}" method="POST" id="editProfileForm">
                @csrf
                
                <div class="form-grid">
                    {{-- Username --}}
                    <div class="form-group full-width">
                        <label class="form-label">
                            <span class="form-label-icon">👤</span>
                            Nama Pengguna
                            <span class="required-mark">*</span>
                        </label>
                        <input type="text" 
                               class="form-input" 
                               name="username" 
                               value="{{ old('username', $user->username) }}" 
                               placeholder="Masukkan nama pengguna"
                               required>
                    </div>

                    {{-- Email (Read Only) --}}
                    <div class="form-group full-width">
                        <label class="form-label">
                            <span class="form-label-icon">📧</span>
                            Email
                        </label>
                        <input type="email" 
                               class="form-input" 
                               value="{{ $user->email }}" 
                               readonly
                               title="Email tidak dapat diubah">
                        <div class="form-helper">Email tidak dapat diubah setelah registrasi</div>
                    </div>

                    {{-- No Handphone --}}
                    <div class="form-group">
                        <label class="form-label">
                            <span class="form-label-icon">📱</span>
                            No Handphone
                        </label>
                        <input type="text" 
                               class="form-input" 
                               name="phone" 
                               value="{{ old('phone', $user->phone ?? '') }}" 
                               placeholder="08xxxxxxxxxx"
                               pattern="[0-9]*"
                               maxlength="15">
                    </div>

                    {{-- Jenis Kelamin --}}
                    <div class="form-group">
                        <label class="form-label">
                            <span class="form-label-icon">⚧️</span>
                            Jenis Kelamin
                        </label>
                        <select name="gender" class="form-select">
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="Laki-laki" {{ old('gender', $user->gender ?? '') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ old('gender', $user->gender ?? '') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>

                    {{-- Tanggal Lahir --}}
                    <div class="form-group full-width">
                        <label class="form-label">
                            <span class="form-label-icon">🎂</span>
                            Tanggal Lahir
                        </label>
                        <input type="date" 
                               class="form-input" 
                               name="birth_date" 
                               value="{{ old('birth_date', $user->birth_date ?? '') }}" 
                               max="{{ date('Y-m-d') }}">
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="button-section">
                    <button type="submit" class="btn btn-save" id="saveBtn">
                        💾 Simpan Perubahan
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

{{-- Toast Container --}}
@php
    $s = session('success');
    $e = session('error');
    if ($s) $s = trim(str_replace(['✓','✔','×','✖'], '', $s));
    if ($e) $e = trim(str_replace(['✓','✔','×','✖'], '', $e));
@endphp
<div id="toast-container" class="gp-toast-container" aria-live="polite" aria-atomic="true">
    @if($s)
        <div class="gp-toast gp-toast--success">{{ $s }}</div>
    @endif
    @if($e)
        <div class="gp-toast gp-toast--error">{{ $e }}</div>
    @endif
    @if($errors->any())
        <div class="gp-toast gp-toast--error">
            @if($errors->count() === 1)
                {{ $errors->first() }}
            @else
                Terjadi {{ $errors->count() }} kesalahan. Periksa input Anda.
            @endif
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide inline alerts
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-10px)';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });

    // Toast notification system
    const container = document.getElementById('toast-container');
    if (container) {
        container.querySelectorAll('.gp-toast').forEach((el, idx) => {
            requestAnimationFrame(() => el.classList.add('is-show'));
            setTimeout(() => hideToast(el), 2000 + idx * 150);
        });
    }

    function hideToast(el) {
        el.classList.remove('is-show');
        el.classList.add('is-hide');
        el.addEventListener('transitionend', () => el.remove(), { once: true });
    }

    // Form validation enhancement
    const form = document.getElementById('editProfileForm');
    const phoneInput = document.querySelector('input[name="phone"]');
    
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    }

    if (form) {
        form.addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('saveBtn');
            submitBtn.innerHTML = '⏳ Menyimpan...';
            submitBtn.disabled = true;
        });
    }
});
</script>
@endsection
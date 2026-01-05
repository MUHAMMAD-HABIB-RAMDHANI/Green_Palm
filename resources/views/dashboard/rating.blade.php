@extends('layouts.app')

@section('title', 'Beri Rating Aplikasi')

{{-- ============================================================ --}}
{{-- 1. DEFINISI HEADER MOBILE (Fixed Top)                        --}}
{{-- ============================================================ --}}
@section('mobile-header')
    <header class="mobile-header-custom">
        <div class="header-left-content">
            {{-- Tombol Kembali --}}
            <a href="{{ route('dashboard.profil') }}" class="mobile-back-btn">
                ‹
            </a>
            
            {{-- Judul Halaman --}}
            <h2 class="mobile-title">
                Beri Rating
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
        --border-color: #e5e5e5;
        --text-dark: #222;
        --text-muted: #777;
        --star-gold: #ffc107;
    }

    /* =========================================
       STYLE MOBILE HEADER (Default: Hidden)
       ========================================= */
    .mobile-header-custom {
        display: none; /* Sembunyikan di Laptop/PC */
    }

    .header-left-content {
        display: flex;
        align-items: center;
        gap: 12px;
        width: 100%;
    }

    .mobile-back-btn {
        width: 38px; 
        height: 38px; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        background: rgba(255, 255, 255, 0.2); 
        backdrop-filter: blur(5px);
        border-radius: 12px; 
        color: white; 
        text-decoration: none; 
        font-size: 22px; 
        border: 1px solid rgba(255, 255, 255, 0.3);
        transition: 0.3s;
        flex-shrink: 0;
        padding-bottom: 2px;
    }

    .mobile-title {
        font-size: 18px; 
        font-weight: 700; 
        color: white; 
        margin: 0; 
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        white-space: nowrap;
    }

    /* --- DESKTOP BASE STYLES --- */
    .rating-wrapper {
        background-color: var(--bg-gray);
        min-height: 100vh;
        padding: 30px;
        font-family: 'Poppins', sans-serif;
    }

    .rating-card {
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

    /* Header Desktop */
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
    }

    .card-title {
        font-size: 28px;
        font-weight: 700;
        color: white;
        margin: 0;
        z-index: 1;
        text-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }

    /* Content Styles */
    .card-body {
        padding: 45px 50px;
    }

    .info-banner {
        border-radius: 16px;
        padding: 20px 25px;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 15px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }

    .info-banner--user {
        background: linear-gradient(135deg, var(--light-green) 0%, #d4edda 100%);
        border-left: 5px solid var(--primary-green);
    }
    
    .info-banner--rating {
        background: linear-gradient(135deg, #fff9e6 0%, #ffe9b3 100%);
        border-left: 5px solid var(--star-gold);
    }

    .info-icon { font-size: 32px; flex-shrink: 0; }
    
    .info-content h3 {
        font-size: 16px; font-weight: 700; color: var(--text-dark); margin: 0 0 4px 0;
    }
    
    .info-content p {
        font-size: 13px; color: var(--text-muted); margin: 0; line-height: 1.4;
    }

    /* Form Styles */
    .form-group { margin-bottom: 25px; }
    
    .form-label {
        font-size: 14px; font-weight: 600; color: var(--text-dark); margin-bottom: 12px;
        display: flex; align-items: center; gap: 8px;
    }

    .star-rating-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 25px 20px;
        background: #fff;
        border: 2px dashed #e8e8e8;
        border-radius: 16px;
        margin-bottom: 25px;
        transition: 0.3s;
    }

    .star-rating-container:hover {
        border-color: var(--primary-green);
        background: #fafafa;
    }

    .stars-wrapper {
        display: flex;
        flex-direction: row-reverse;
        gap: 12px;
        margin-bottom: 5px;
    }

    .star-input { display: none; }

    .star-label {
        font-size: 45px;
        color: #ddd;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .star-label:hover,
    .star-label:hover ~ .star-label,
    .star-input:checked ~ .star-label {
        color: var(--star-gold);
        transform: scale(1.15);
    }

    .rating-text-display {
        margin-top: 15px;
        font-size: 15px;
        transition: all 0.3s ease;
        text-align: center;
    }

    .rating-text-display.prompt {
        color: #999;
        font-weight: 500;
        font-style: italic;
    }

    .rating-text-display.rated {
        color: var(--primary-green);
        font-weight: 700;
        transform: scale(1.05);
    }

    .form-textarea {
        width: 100%;
        padding: 16px 18px;
        border: 2px solid #e8e8e8;
        border-radius: 12px;
        font-size: 15px;
        font-family: 'Poppins', sans-serif;
        transition: all 0.3s ease;
        resize: vertical;
        min-height: 120px;
    }

    .form-textarea:focus {
        outline: none;
        border-color: var(--primary-green);
        box-shadow: 0 0 0 5px rgba(43, 122, 11, 0.1);
    }

    .char-counter {
        text-align: right; font-size: 12px; color: var(--text-muted); margin-top: 6px;
    }

    .button-section {
        margin-top: 35px; padding-top: 30px; border-top: 2px solid #f0f0f0;
    }

    .btn-submit {
        width: 100%;
        padding: 16px 30px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 16px;
        cursor: pointer;
        border: none;
        background: linear-gradient(135deg, var(--primary-green) 0%, var(--dark-green) 100%);
        color: white;
        box-shadow: 0 5px 20px rgba(43, 122, 11, 0.3);
        display: flex; align-items: center; justify-content: center; gap: 10px;
        transition: all 0.3s ease;
    }

    .btn-submit:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 30px rgba(43, 122, 11, 0.4);
    }

    .btn-submit:disabled {
        background: #ccc; cursor: not-allowed; transform: none; box-shadow: none;
    }

    /* --- RESPONSIVE STYLES (FIXED CARD LOOK) --- */
    @media (max-width: 768px) {
        /* 1. Hilangkan Header Card Desktop */
        .card-header { display: none !important; }

        /* 2. Header Mobile Fixed (Tetap di Atas) */
        .mobile-header-custom {
            display: flex;
            align-items: center;
            width: 100%;
            height: 70px;
            padding: 0 20px;
            
            /* Gradient Hijau */
            background: linear-gradient(135deg, #2b7a0b 0%, #1e5607 100%);
            box-shadow: 0 4px 15px rgba(43, 122, 11, 0.3);
            
            position: fixed;
            top: 0;
            left: 0;
            z-index: 999;
        }

        /* 3. Wrapper: Kembalikan Background Abu-abu & Atur Posisi */
        .rating-wrapper { 
            /* Reset posisi akibat padding bawaan layout utama */
            margin-top: -80px; 
            margin-left: -20px; 
            margin-right: -20px;
            
            /* Pastikan background abu-abu agar kartu putih terlihat */
            background-color: #f8f9fa; 
            min-height: 100vh;
            
            /* Beri padding agar kartu tidak menempel ke tepi layar */
            padding: 0 15px; 
            
            /* Aktifkan Flex agar bisa mengatur margin auto */
            display: flex;
            flex-direction: column;
        }
        
        /* 4. Card Container: Kembalikan Bentuk Kartu */
        .rating-card { 
            background: white;
            
            /* Kembalikan Sudut Melengkung & Bayangan */
            border-radius: 20px; 
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.05); 
            
            /* PENTING: Beri Jarak Atas agar pas di bawah Header (70px + 20px gap) */
            margin-top: 90px; 
            margin-bottom: 30px;
            width: 100%;
        }
        
        .card-body { 
            padding: 25px 20px; 
        }

        .star-label { font-size: 38px; }
        .info-banner { padding: 15px; margin-bottom: 20px; }
    }
</style>

<div class="rating-wrapper">
    <div class="rating-card">
        
        {{-- Header Card (Desktop Only) --}}
        <div class="card-header">
            <a href="{{ route('dashboard.profil') }}" class="back-button" title="Kembali">‹</a>
            <h1 class="card-title">Beri Rating</h1>
        </div>

        {{-- Body --}}
        <div class="card-body">
            
            {{-- User Banner --}}
            <div class="info-banner info-banner--user">
                <div class="info-icon">👤</div>
                <div class="info-content">
                    <h3>{{ $user->username }}</h3>
                    <p>{{ $user->email }}</p>
                </div>
            </div>

            {{-- Existing Rating Banner --}}
            @if($existingRating)
            <div class="info-banner info-banner--rating">
                <div class="info-icon">📋</div>
                <div class="info-content">
                    <h3>Rating Terakhir Anda</h3>
                    <p>
                        <span style="color: #d69e00;">{{ str_repeat('⭐', $existingRating->rating) }}</span>
                        @if($existingRating->comment)
                            <br><i>"{{ Str::limit($existingRating->comment, 60) }}"</i>
                        @endif
                    </p>
                </div>
            </div>
            @endif

            <form action="{{ route('dashboard.rating.store') }}" method="POST" id="ratingForm">
                @csrf

                {{-- Star Rating --}}
                <div class="form-group">
                    <label class="form-label">
                        <span>⭐</span> Tingkat Kepuasan <span style="color: #dc3545;">*</span>
                    </label>
                    
                    <div class="star-rating-container">
                        <div class="stars-wrapper">
                            <input type="radio" name="rating" value="5" id="star5" class="star-input" {{ old('rating') == 5 ? 'checked' : '' }}>
                            <label for="star5" class="star-label" title="Sangat Bagus">★</label>
                            
                            <input type="radio" name="rating" value="4" id="star4" class="star-input" {{ old('rating') == 4 ? 'checked' : '' }}>
                            <label for="star4" class="star-label" title="Bagus">★</label>
                            
                            <input type="radio" name="rating" value="3" id="star3" class="star-input" {{ old('rating') == 3 ? 'checked' : '' }}>
                            <label for="star3" class="star-label" title="Cukup">★</label>
                            
                            <input type="radio" name="rating" value="2" id="star2" class="star-input" {{ old('rating') == 2 ? 'checked' : '' }}>
                            <label for="star2" class="star-label" title="Buruk">★</label>
                            
                            <input type="radio" name="rating" value="1" id="star1" class="star-input" {{ old('rating') == 1 ? 'checked' : '' }}>
                            <label for="star1" class="star-label" title="Sangat Buruk">★</label>
                        </div>
                        
                        <div class="rating-text-display prompt" id="ratingText">
                            👋 Bagaimana kepuasan Anda terhadap layanan kami?
                        </div>
                    </div>
                </div>

                {{-- Komentar --}}
                <div class="form-group">
                    <label class="form-label">
                        <span>📝</span> Komentar & Saran (Opsional)
                    </label>
                    <textarea 
                        name="comment" 
                        id="comment" 
                        class="form-textarea" 
                        placeholder="Ceritakan pengalaman Anda menggunakan aplikasi..."
                        maxlength="500">{{ old('comment') }}</textarea>
                    <div class="char-counter">
                        <span id="charCount">0</span> / 500 karakter
                    </div>
                </div>

                {{-- Submit Button --}}
                <div class="button-section">
                    <button type="submit" class="btn-submit" id="submitBtn" disabled>
                        🚀 Kirim Rating
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

{{-- Toast Notification --}}
<div id="toast-container" class="gp-toast-container">
    @if(session('success'))
        <div class="gp-toast gp-toast--success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="gp-toast gp-toast--error">{{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="gp-toast gp-toast--error">Terjadi kesalahan pada input.</div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const stars = document.querySelectorAll('.star-input');
    const ratingText = document.getElementById('ratingText');
    const submitBtn = document.getElementById('submitBtn');
    const comment = document.getElementById('comment');
    const charCount = document.getElementById('charCount');
    
    // Deskripsi Rating
    const descriptions = {
        1: 'Sangat Buruk 😞',
        2: 'Buruk 😕',
        3: 'Cukup 😐',
        4: 'Bagus 🙂',
        5: 'Sangat Bagus 🤩'
    };

    // Fungsi update teks
    function updateRatingText(value) {
        if(descriptions[value]) {
            ratingText.textContent = descriptions[value];
            ratingText.classList.remove('prompt'); // Hapus warna abu-abu
            ratingText.classList.add('rated');     // Tambah warna hijau & bold
        }
    }

    // Logic saat bintang diklik
    stars.forEach(star => {
        star.addEventListener('change', function() {
            if(this.checked) {
                updateRatingText(this.value);
                submitBtn.disabled = false;
            }
        });
    });

    // Cek jika ada input lama (misal validasi gagal)
    const checkedStar = document.querySelector('.star-input:checked');
    if(checkedStar) {
        updateRatingText(checkedStar.value);
        submitBtn.disabled = false;
    }

    // Char Counter
    comment.addEventListener('input', function() {
        charCount.textContent = this.value.length;
    });
    if (comment.value) charCount.textContent = comment.value.length;

    // Toast & Submit Loading
    const form = document.getElementById('ratingForm');
    if (form) {
        form.addEventListener('submit', function() {
            submitBtn.innerHTML = '⏳ Mengirim...';
            submitBtn.style.opacity = '0.8';
        });
    }

    const container = document.getElementById('toast-container');
    if (container) {
        container.querySelectorAll('.gp-toast').forEach((el, idx) => {
            requestAnimationFrame(() => el.classList.add('is-show'));
            setTimeout(() => {
                el.classList.remove('is-show');
                el.classList.add('is-hide');
                el.addEventListener('transitionend', () => el.remove(), { once: true });
            }, 3000 + idx * 150);
        });
    }
});
</script>

@endsection
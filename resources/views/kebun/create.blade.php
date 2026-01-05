@extends('layouts.app')
@section('title', 'Buat Kebun Baru')

{{-- ============================================================ --}}
{{-- 1. DEFINISI HEADER MOBILE (Fixed Top)                        --}}
{{-- ============================================================ --}}
@section('mobile-header')
    <header class="mobile-header-custom">
        <div class="header-left-content">
            {{-- Tombol Kembali --}}
            <a href="{{ route('kebun.daftar') }}" class="mobile-back-btn">
                ‹
            </a>
            
            {{-- Judul Halaman --}}
            <h2 class="mobile-title">
                Buat Kebun Baru
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
        --text-muted: #666;
        --border-color: #e0e0e0;
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
    .kebun-create-wrapper {
        background-color: var(--bg-gray);
        min-height: 100vh;
        padding: 20px 30px; 
        font-family: 'Poppins', sans-serif;
        display: flex;
        justify-content: center;
        align-items: flex-start;
    }

    .kebun-create-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.05);
        width: 100%;
        max-width: 850px;
        overflow: hidden;
        animation: slideUp 0.4s ease-out;
        margin-top: 0; 
    }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Header Desktop */
    .card-header {
        padding: 20px 30px;
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
        top: -20%;
        right: -5%;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        border-radius: 50%;
    }

    .back-button {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(5px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        color: white;
        font-size: 22px;
        transition: all 0.2s ease;
        z-index: 2;
    }

    .back-button:hover {
        background: rgba(255, 255, 255, 0.35);
        transform: translateX(-3px);
    }

    .card-title {
        font-size: 20px;
        font-weight: 700;
        color: white;
        margin: 0;
        z-index: 2;
        letter-spacing: 0.5px;
    }

    /* Body Section */
    .card-body {
        padding: 30px 40px;
    }

    .form-intro {
        text-align: center;
        margin-bottom: 25px;
    }

    .form-intro h2 {
        font-size: 24px;
        font-weight: 700;
        color: var(--primary-green);
        margin-bottom: 8px;
    }

    .form-intro p {
        color: var(--text-muted);
        font-size: 14px;
        margin: 0;
    }

    /* Tips Banner */
    .tips-banner {
        background: #fff8e1;
        border: 1px solid #ffe082;
        border-left: 5px solid #ffc107;
        padding: 12px 20px;
        border-radius: 8px;
        margin-bottom: 30px;
        display: flex;
        gap: 15px;
        align-items: flex-start;
    }

    .tips-icon { font-size: 20px; }

    .tips-content p {
        margin: 0;
        font-size: 13px;
        color: #795548;
        line-height: 1.5;
    }

    /* Form Grid */
    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 25px;
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
        margin-bottom: 8px;
        display: flex;
        align-items: center;
    }

    .required-dot {
        color: #dc3545;
        margin-left: 4px;
        font-size: 12px;
    }

    .form-input {
        padding: 12px 15px;
        border: 1px solid #ddd;
        border-radius: 10px;
        font-size: 14px;
        font-family: inherit;
        transition: all 0.3s;
        background-color: #fff;
        width: 100%; /* Ensure full width */
    }

    .form-input:focus {
        outline: none;
        border-color: var(--primary-green);
        box-shadow: 0 0 0 3px rgba(43, 122, 11, 0.1);
    }

    .helper-text {
        font-size: 12px;
        color: #888;
        margin-top: 6px;
    }

    .form-error {
        color: #dc3545;
        font-size: 12px;
        margin-top: 5px;
        font-weight: 500;
    }

    /* Question Section (Jenis Bibit) */
    .question-box {
        background-color: #f4f9f4;
        border: 1px solid #e0ece0;
        border-radius: 12px;
        padding: 20px;
        grid-column: span 2;
    }

    .question-title {
        font-size: 15px;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 15px;
    }

    /* Styling untuk dropdown jenis bibit */
    #pilihan-bibit-container {
        animation: slideDown 0.3s ease-out;
    }

    @keyframes slideDown {
        from { opacity: 0; max-height: 0; overflow: hidden; }
        to { opacity: 1; max-height: 200px; }
    }

    #pilihan-bibit-container select { cursor: pointer; }

    /* Radio & Checkbox Styles */
    .options-container {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
    }

    .option-item {
        flex: 1;
        min-width: 140px;
        position: relative;
    }

    .hidden-input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
    }

    .option-label {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 12px;
        background: white;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        color: var(--text-muted);
        cursor: pointer;
        transition: all 0.2s;
        text-align: center;
    }

    .hidden-input:checked + .option-label {
        background-color: var(--light-green);
        border-color: var(--primary-green);
        color: var(--primary-green);
        font-weight: 700;
        box-shadow: 0 2px 5px rgba(43, 122, 11, 0.1);
    }

    /* Button Action */
    .form-actions {
        grid-column: span 2;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid #eee;
    }

    .btn-submit {
        width: 100%;
        background: linear-gradient(135deg, var(--primary-green) 0%, var(--dark-green) 100%);
        color: white;
        border: none;
        padding: 16px;
        border-radius: 12px;
        font-size: 16px;
        font-weight: 700;
        cursor: pointer;
        transition: transform 0.2s, box-shadow 0.2s;
        box-shadow: 0 4px 15px rgba(43, 122, 11, 0.25);
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(43, 122, 11, 0.35);
    }

    /* Toast Styling */
    .gp-toast-container { position: fixed; top: 20px; left: 50%; transform: translateX(-50%); z-index: 9999; pointer-events: none; display: flex; flex-direction: column; gap: 10px; }
    .gp-toast { min-width: 300px; padding: 12px 20px; border-radius: 8px; background: #333; color: white; font-size: 14px; opacity: 0; transform: translateY(-20px); transition: all 0.3s; box-shadow: 0 5px 15px rgba(0,0,0,0.2); pointer-events: auto; text-align: center;}
    .gp-toast--success { background: #2b7a0b; }
    .gp-toast--error { background: #d32f2f; }
    .gp-toast.is-show { opacity: 1; transform: translateY(0); }
    .gp-toast.is-hide { opacity: 0; transform: translateY(-20px); }

    /* --- RESPONSIVE STYLES (FIXED CARD LOOK) --- */
    @media (max-width: 768px) {
        
        /* 1. Hilangkan Header Card Desktop */
        .card-header { display: none !important; }

        /* 2. Tampilkan Header Mobile Custom (Fixed Top) */
        .mobile-header-custom {
            display: flex;
            align-items: center;
            width: 100%;
            height: 70px;
            padding: 0 20px;
            
            background: linear-gradient(135deg, #2b7a0b 0%, #1e5607 100%);
            box-shadow: 0 4px 15px rgba(43, 122, 11, 0.3);
            
            position: fixed;
            top: 0;
            left: 0;
            z-index: 999;
        }

        /* 3. Wrapper: Kembalikan Background Abu-abu & Atur Posisi */
        .kebun-create-wrapper { 
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
        .kebun-create-card { 
            background: white;
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

        /* Form Adjustments for Mobile */
        .form-grid { grid-template-columns: 1fr; gap: 20px; }
        .form-group.full-width { grid-column: auto; }
        .question-box { grid-column: auto; padding: 15px; }
        .options-container { flex-direction: column; gap: 10px; }
        .form-actions { grid-column: auto; }
        
        .form-intro h2 { font-size: 20px; }
    }
</style>

<div class="kebun-create-wrapper">
    <div class="kebun-create-card">
        
        {{-- Header Desktop (Akan hilang di Mobile) --}}
        <div class="card-header">
            <a href="{{ route('kebun.daftar') }}" class="back-button" title="Kembali">‹</a>
            <h1 class="card-title">Buat Kebun Baru</h1>
        </div>

        {{-- Card Body --}}
        <div class="card-body">
            
            {{-- Intro & Banner --}}
            <div class="form-intro">
                <h2>Data Kebun Kelapa Sawit</h2>
                <p>Lengkapi informasi di bawah ini untuk mulai memonitoring kebun Anda.</p>
            </div>

            <div class="tips-banner">
                <div class="tips-icon">💡</div>
                <div class="tips-content">
                    <p><strong>Tips:</strong> Pastikan data luas lahan dan tahun tanam akurat. Informasi ini sangat penting untuk perhitungan estimasi panen dan kebutuhan pupuk nantinya.</p>
                </div>
            </div>

            {{-- Form --}}
            <form action="{{ route('kebun.store') }}" method="POST" id="kebunForm">
                @csrf
                
                <div class="form-grid">
                    
                    {{-- Nama Kebun --}}
                    <div class="form-group">
                        <label class="form-label">Nama Kebun <span class="required-dot">*</span></label>
                        <input type="text" name="nama" class="form-input" 
                               placeholder="Contoh: Kebun Sawit Blok A"
                               value="{{ old('nama') }}" required>
                        @error('nama') <span class="form-error">{{ $message }}</span> @enderror
                    </div>

                    {{-- Lokasi Kebun --}}
                    <div class="form-group">
                        <label class="form-label">Lokasi Kebun <span class="required-dot">*</span></label>
                        <input type="text" name="lokasi" class="form-input" 
                               placeholder="Desa / Kecamatan"
                               value="{{ old('lokasi') }}" required>
                        @error('lokasi') <span class="form-error">{{ $message }}</span> @enderror
                    </div>

                    {{-- Luas Lahan --}}
                    <div class="form-group">
                        <label class="form-label">Luas Lahan (Hektar) <span class="required-dot">*</span></label>
                        <input type="number" name="luas_lahan" class="form-input" 
                               placeholder="0.0"
                               value="{{ old('luas_lahan') }}" 
                               step="0.01" min="0.01" required>
                        <span class="helper-text">Gunakan titik (.) untuk desimal, contoh: 2.5</span>
                        @error('luas_lahan') <span class="form-error">{{ $message }}</span> @enderror
                    </div>

                    {{-- Jumlah Hektar --}}
                    <div class="form-group">
                        <label class="form-label">Jumlah Hektar Terhitung <span class="required-dot">*</span></label>
                        <input type="number" name="jumlah_hektar" class="form-input" 
                               placeholder="Total satuan hektar"
                               value="{{ old('jumlah_hektar') }}" 
                               min="1" required>
                        @error('jumlah_hektar') <span class="form-error">{{ $message }}</span> @enderror
                    </div>

                    {{-- Tahun Tanam --}}
                    <div class="form-group full-width">
                        <label class="form-label">Tahun Tanam <span class="required-dot">*</span></label>
                        <input type="number" name="tahun_tanam" class="form-input" 
                               placeholder="Tahun (Contoh: 2018)"
                               value="{{ old('tahun_tanam') }}" 
                               min="1980" max="{{ date('Y') }}" required>
                        @error('tahun_tanam') <span class="form-error">{{ $message }}</span> @enderror
                    </div>

                    {{-- Jenis Bibit --}}
                    <div class="question-box">
                        <p class="question-title">Apakah Anda mengetahui jenis bibit yang digunakan?</p>
                        <div class="options-container">
                            <div class="option-item">
                                <input type="radio" name="tahu_jenis_bibit" value="Ya" id="bibit-ya" class="hidden-input" {{ old('tahu_jenis_bibit') == 'Ya' ? 'checked' : '' }} required>
                                <label for="bibit-ya" class="option-label">Ya, Saya Tahu</label>
                            </div>
                            <div class="option-item">
                                <input type="radio" name="tahu_jenis_bibit" value="Tidak" id="bibit-tidak" class="hidden-input" {{ old('tahu_jenis_bibit') == 'Tidak' ? 'checked' : '' }}>
                                <label for="bibit-tidak" class="option-label">Tidak Tahu</label>
                            </div>
                        </div>

                        {{-- Dropdown Pilihan Bibit (Muncul jika pilih 'Ya') --}}
                        <div id="pilihan-bibit-container" style="margin-top: 20px; {{ old('tahu_jenis_bibit') == 'Ya' ? '' : 'display: none;' }}">
                            <label class="form-label">Pilih Jenis Bibit <span class="required-dot">*</span></label>
                            <select name="nama_jenis_bibit" id="select-bibit" class="form-input" style="width: 100%;">
                                <option value="" disabled {{ old('nama_jenis_bibit') ? '' : 'selected' }}>-- Pilih Jenis Bibit --</option>
                                <option value="Tenera" {{ old('nama_jenis_bibit') == 'Tenera' ? 'selected' : '' }}>Tenera</option>
                                <option value="Dura" {{ old('nama_jenis_bibit') == 'Dura' ? 'selected' : '' }}>Dura</option>
                                <option value="Pisifera" {{ old('nama_jenis_bibit') == 'Pisifera' ? 'selected' : '' }}>Pisifera</option>
                                <option value="Socfindo" {{ old('nama_jenis_bibit') == 'Socfindo' ? 'selected' : '' }}>Socfindo</option>
                                <option value="Marihat" {{ old('nama_jenis_bibit') == 'Marihat' ? 'selected' : '' }}>Marihat</option>
                                <option value="Topaz" {{ old('nama_jenis_bibit') == 'Topaz' ? 'selected' : '' }}>Topaz</option>
                                <option value="PPKS" {{ old('nama_jenis_bibit') == 'PPKS' ? 'selected' : '' }}>PPKS (Pusat Penelitian Kelapa Sawit)</option>
                                <option value="Lainnya" {{ old('nama_jenis_bibit') == 'Lainnya' ? 'selected' : '' }}>Lainnya / Tidak Terdaftar</option>
                            </select>
                            @error('nama_jenis_bibit') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                        @error('tahu_jenis_bibit') <span class="form-error">{{ $message }}</span> @enderror
                    </div>

                    {{-- Jenis Tanah --}}
                    <div class="form-group full-width">
                        <label class="form-label">Jenis Tanah (Opsional)</label>
                        <div class="options-container">
                            <div class="option-item">
                                <input type="radio" name="jenis_tanah" value="Mineral Berpasir" id="tanah-1" class="hidden-input" {{ old('jenis_tanah') == 'Mineral Berpasir' ? 'checked' : '' }}>
                                <label for="tanah-1" class="option-label">Mineral Berpasir</label>
                            </div>
                            <div class="option-item">
                                <input type="radio" name="jenis_tanah" value="Mineral Volkanik" id="tanah-2" class="hidden-input" {{ old('jenis_tanah') == 'Mineral Volkanik' ? 'checked' : '' }}>
                                <label for="tanah-2" class="option-label">Mineral Volkanik</label>
                            </div>
                            <div class="option-item">
                                <input type="radio" name="jenis_tanah" value="Gambut" id="tanah-3" class="hidden-input" {{ old('jenis_tanah') == 'Gambut' ? 'checked' : '' }}>
                                <label for="tanah-3" class="option-label">Lahan Gambut</label>
                            </div>
                        </div>
                        @error('jenis_tanah') <span class="form-error">{{ $message }}</span> @enderror
                    </div>

                    {{-- Actions --}}
                    <div class="form-actions">
                        <button type="submit" class="btn-submit">
                            💾 Simpan Data Kebun
                        </button>
                    </div>
                    
                </div>
            </form>

        </div>
    </div>
</div>

{{-- Toast Container --}}
<div id="toast-container" class="gp-toast-container" aria-live="polite" aria-atomic="true"></div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toast notifications
    @if(session('success'))
        showToast('{{ session('success') }}', 'success');
    @endif
    
    @if(session('error'))
        showToast('{{ session('error') }}', 'error');
    @endif

    // Toggle pilihan bibit berdasarkan radio button
    const radioBibitYa = document.getElementById('bibit-ya');
    const radioBibitTidak = document.getElementById('bibit-tidak');
    const pilihanBibitContainer = document.getElementById('pilihan-bibit-container');
    const selectBibit = document.getElementById('select-bibit');

    function togglePilihanBibit() {
        if (radioBibitYa && radioBibitYa.checked) {
            pilihanBibitContainer.style.display = 'block';
            selectBibit.setAttribute('required', 'required');
        } else {
            pilihanBibitContainer.style.display = 'none';
            selectBibit.removeAttribute('required');
            selectBibit.value = ''; // Reset pilihan
        }
    }

    // Event listeners untuk radio buttons
    if (radioBibitYa) {
        radioBibitYa.addEventListener('change', togglePilihanBibit);
    }
    if (radioBibitTidak) {
        radioBibitTidak.addEventListener('change', togglePilihanBibit);
    }

    // Jalankan saat pertama load untuk handle old() values
    togglePilihanBibit();
});

function showToast(message, type = 'success') {
    const container = document.getElementById('toast-container');
    const toast = document.createElement('div');
    toast.className = `gp-toast gp-toast--${type}`;
    toast.textContent = message;
    
    container.appendChild(toast);
    
    requestAnimationFrame(() => {
        toast.classList.add('is-show');
    });
    
    setTimeout(() => {
        toast.classList.remove('is-show');
        toast.classList.add('is-hide');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}
</script>

@endsection
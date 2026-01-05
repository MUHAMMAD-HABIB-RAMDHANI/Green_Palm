@extends('layouts.app')
@section('title', 'Edit Data Kebun')

{{-- ============================================================ --}}
{{-- 1. DEFINISI HEADER MOBILE (Fixed Top)                        --}}
{{-- ============================================================ --}}
@section('mobile-header')
    <header class="mobile-header-custom">
        <div class="header-left-content">
            {{-- Tombol Kembali ke Detail --}}
            <a href="{{ route('kebun.show', $kebun->id) }}" class="mobile-back-btn">
                ‹
            </a>
            
            {{-- Judul Halaman --}}
            <h2 class="mobile-title">
                Edit Data Kebun
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
    .form-wrapper {
        background-color: var(--bg-gray);
        min-height: 100vh;
        padding: 20px 30px;
        font-family: 'Poppins', sans-serif;
        display: flex;
        justify-content: center;
        align-items: flex-start;
    }

    .form-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        width: 100%;
        max-width: 900px;
        overflow: hidden;
        animation: slideUp 0.5s ease;
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
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%);
        animation: pulse 8s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); opacity: 0.5; }
        50% { transform: scale(1.1); opacity: 0.3; }
    }

    .back-button {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        color: white;
        font-size: 24px;
        font-weight: 300;
        transition: all 0.3s ease;
        z-index: 1;
    }

    .back-button:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateX(-5px);
    }

    .card-title {
        font-size: 24px;
        font-weight: 700;
        color: white;
        margin: 0;
        z-index: 1;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    /* Card Body */
    .card-body {
        padding: 35px 40px;
    }

    .form-header {
        text-align: center;
        margin-bottom: 35px;
    }

    .form-header h2 {
        font-size: 22px;
        font-weight: 700;
        color: var(--primary-green);
        margin-bottom: 8px;
    }

    .form-header p {
        font-size: 15px;
        color: var(--text-muted);
        margin: 0;
    }

    /* Form Groups */
    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 24px;
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
        gap: 6px;
    }

    .form-label .required {
        color: #dc3545;
        margin-left: 2px;
        font-size: 12px;
    }

    .form-input, #select-bibit {
        width: 100%;
        padding: 14px 18px;
        border: 2px solid #e8e8e8;
        border-radius: 12px;
        font-size: 15px;
        font-family: 'Poppins', sans-serif;
        transition: all 0.3s ease;
        background: #fafafa;
    }

    .form-input:focus, #select-bibit:focus {
        outline: none;
        border-color: var(--primary-green);
        background: white;
        box-shadow: 0 0 0 4px rgba(43, 122, 11, 0.1);
        transform: translateY(-2px);
    }

    .form-error {
        color: #dc3545;
        font-size: 13px;
        margin-top: 6px;
    }

    .helper-text {
        font-size: 12px;
        color: var(--text-muted);
        margin-top: 6px;
    }

    /* Input with Clear Button */
    .input-with-clear {
        position: relative;
    }

    .clear-btn {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        width: 26px;
        height: 26px;
        border-radius: 50%;
        background: #e0e0e0;
        border: none;
        cursor: pointer;
        display: none;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        color: #666;
        transition: all 0.2s ease;
    }

    /* Question Section */
    .question-section {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-left: 4px solid var(--primary-green);
        padding: 20px;
        border-radius: 12px;
        grid-column: span 2;
    }

    .question-text {
        font-size: 15px;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 14px;
    }

    /* Dropdown Animation */
    #pilihan-bibit-container {
        margin-top: 20px;
        animation: slideDown 0.3s ease-out;
    }

    @keyframes slideDown {
        from { opacity: 0; max-height: 0; overflow: hidden; }
        to { opacity: 1; max-height: 200px; }
    }

    /* Radio & Checkbox Groups */
    .radio-group, .checkbox-group {
        display: flex;
        gap: 12px;
        margin-top: 10px;
        flex-wrap: wrap;
    }

    .radio-option, .checkbox-option {
        flex: 1;
        min-width: 140px;
    }

    .radio-label, .checkbox-label {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 13px 20px;
        border: 2px solid #e8e8e8;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.3s ease;
        background: #fafafa;
        font-size: 14px;
        font-weight: 500;
        text-align: center;
    }

    .radio-input, .checkbox-input {
        display: none;
    }

    .radio-input:checked + .radio-label,
    .checkbox-input:checked + .checkbox-label {
        border-color: var(--primary-green);
        background: var(--light-green);
        color: var(--primary-green);
        font-weight: 600;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(43, 122, 11, 0.15);
    }

    /* Form Actions */
    .form-actions {
        display: flex;
        margin-top: 35px;
        padding-top: 25px;
        border-top: 2px solid #f0f0f0;
        grid-column: span 2;
    }

    .btn-submit {
        width: 100%;
        padding: 16px 28px;
        background: linear-gradient(135deg, var(--primary-green) 0%, var(--dark-green) 100%);
        color: white;
        border: none;
        border-radius: 12px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(43, 122, 11, 0.3);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .btn-submit:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(43, 122, 11, 0.4);
    }

    /* Toast */
    .gp-toast-container { position: fixed; top: 20px; left: 50%; transform: translateX(-50%); z-index: 9999; pointer-events: none; display: flex; flex-direction: column; gap: 10px; }
    .gp-toast { min-width: 300px; padding: 14px 20px; border-radius: 12px; font-size: 15px; font-weight: 600; box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15); text-align: center; opacity: 0; transform: translateY(-20px); transition: all 0.3s ease; pointer-events: auto; }
    .gp-toast--success { background: #E9FFF0; border: 2px solid #BDEFCF; color: #0F6B3A; }
    .gp-toast--error { background: #FFECEC; border: 2px solid #FFC0C0; color: #8A1F11; }
    .gp-toast.is-show { opacity: 1; transform: translateY(0); }
    .gp-toast.is-hide { opacity: 0; transform: translateY(-20px); }

    /* --- RESPONSIVE STYLES (FIXED CARD LOOK) --- */
    @media (max-width: 768px) {
        /* 1. Hilangkan Header Card Desktop */
        .card-header { display: none !important; }

        /* 2. Tampilkan Header Mobile Custom */
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
        .form-wrapper { 
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
        .form-card { 
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
        .question-section { grid-column: auto; padding: 15px; }
        .radio-group, .checkbox-group { flex-direction: column; gap: 10px; }
        .form-actions { grid-column: auto; }
        
        .form-header h2 { font-size: 20px; }
    }
</style>

<div class="form-wrapper">
    <div class="form-card">
        
        {{-- Header Desktop (Akan hilang di Mobile) --}}
        <div class="card-header">
            <a href="{{ route('kebun.show', $kebun->id) }}" class="back-button" title="Kembali">‹</a>
            <h1 class="card-title">✏️ Edit Data Kebun</h1>
        </div>

        {{-- Card Body --}}
        <div class="card-body">
            
            <div class="form-header">
                <h2>Perbarui Informasi Kebun</h2>
                <p>Ubah data kebun kelapa sawit Anda dengan benar</p>
            </div>

            <form action="{{ route('kebun.update', $kebun->id) }}" method="POST" id="kebunForm">
                @csrf
                @method('PUT')
                
                <div class="form-grid">
                    
                    {{-- Nama Kebun --}}
                    <div class="form-group">
                        <label class="form-label">Nama kebun <span class="required">*</span></label>
                        <div class="input-with-clear">
                            <input type="text" name="nama" class="form-input" 
                                   placeholder="Masukkan nama kebun anda"
                                   value="{{ old('nama', $kebun->nama_kebun) }}" 
                                   required id="input-nama">
                            <button type="button" class="clear-btn" onclick="clearInput('input-nama')">×</button>
                        </div>
                        @error('nama')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Lokasi Kebun --}}
                    <div class="form-group">
                        <label class="form-label">Lokasi kebun <span class="required">*</span></label>
                        <div class="input-with-clear">
                            <input type="text" name="lokasi" class="form-input" 
                                   placeholder="Cth: Desa Kebon, Kec. Sawit"
                                   value="{{ old('lokasi', $kebun->lokasi_kebun) }}" 
                                   required id="input-lokasi">
                            <button type="button" class="clear-btn" onclick="clearInput('input-lokasi')">×</button>
                        </div>
                        @error('lokasi')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Luas Lahan --}}
                    <div class="form-group">
                        <label class="form-label">Luas Lahan (ha) <span class="required">*</span></label>
                        <div class="input-with-clear">
                            <input type="number" name="luas_lahan" class="form-input" 
                                   placeholder="Masukkan luas lahan"
                                   value="{{ old('luas_lahan', $kebun->luas_lahan) }}" 
                                   step="0.01" min="0.01" required id="input-luas">
                            <button type="button" class="clear-btn" onclick="clearInput('input-luas')">×</button>
                        </div>
                        <span class="helper-text">Contoh: 2.5 untuk 2.5 hektar</span>
                        @error('luas_lahan')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Jumlah Hektar --}}
                    <div class="form-group">
                        <label class="form-label">Jumlah Hektar <span class="required">*</span></label>
                        <div class="input-with-clear">
                            <input type="number" name="jumlah_hektar" class="form-input" 
                                   placeholder="Masukkan hektar kebun anda"
                                   value="{{ old('jumlah_hektar', $kebun->jumlah_hektar) }}" 
                                   min="1" required id="input-hektar">
                            <button type="button" class="clear-btn" onclick="clearInput('input-hektar')">×</button>
                        </div>
                        @error('jumlah_hektar')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Tahun Tanam --}}
                    <div class="form-group full-width">
                        <label class="form-label">Tahun Tanam <span class="required">*</span></label>
                        <div class="input-with-clear">
                            <input type="number" name="tahun_tanam" class="form-input" 
                                   placeholder="Masukkan tahun tanam"
                                   value="{{ old('tahun_tanam', $kebun->tahun_tanam) }}" 
                                   min="1900" max="{{ date('Y') }}" required id="input-tahun">
                            <button type="button" class="clear-btn" onclick="clearInput('input-tahun')">×</button>
                        </div>
                        <span class="helper-text">Contoh: 2020</span>
                        @error('tahun_tanam')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Jenis Bibit --}}
                    <div class="question-section">
                        <p class="question-text">Apakah Anda mengetahui jenis bibit yang digunakan?</p>
                        <div class="radio-group">
                            <div class="radio-option">
                                <input type="radio" name="tahu_jenis_bibit" value="Ya" 
                                       id="bibit-ya" class="radio-input" 
                                       {{ old('tahu_jenis_bibit', $kebun->tahu_jenis_bibit == 1 ? 'Ya' : 'Tidak') == 'Ya' ? 'checked' : '' }} required>
                                <label for="bibit-ya" class="radio-label">Ya, Saya Tahu</label>
                            </div>
                            <div class="radio-option">
                                <input type="radio" name="tahu_jenis_bibit" value="Tidak" 
                                       id="bibit-tidak" class="radio-input" 
                                       {{ old('tahu_jenis_bibit', $kebun->tahu_jenis_bibit == 1 ? 'Ya' : 'Tidak') == 'Tidak' ? 'checked' : '' }}>
                                <label for="bibit-tidak" class="radio-label">Tidak Tahu</label>
                            </div>
                        </div>

                        {{-- Dropdown Pilihan Bibit --}}
                        <div id="pilihan-bibit-container" style="{{ old('tahu_jenis_bibit', $kebun->tahu_jenis_bibit == 1 ? 'Ya' : 'Tidak') == 'Ya' ? '' : 'display: none;' }}">
                            <label class="form-label" style="margin-top: 15px;">Pilih Jenis Bibit <span class="required">*</span></label>
                            <select name="nama_jenis_bibit" id="select-bibit">
                                <option value="" disabled {{ old('nama_jenis_bibit', $kebun->jenis_bibit_nama) ? '' : 'selected' }}>-- Pilih Jenis Bibit --</option>
                                <option value="Tenera" {{ old('nama_jenis_bibit', $kebun->jenis_bibit_nama) == 'Tenera' ? 'selected' : '' }}>Tenera</option>
                                <option value="Dura" {{ old('nama_jenis_bibit', $kebun->jenis_bibit_nama) == 'Dura' ? 'selected' : '' }}>Dura</option>
                                <option value="Pisifera" {{ old('nama_jenis_bibit', $kebun->jenis_bibit_nama) == 'Pisifera' ? 'selected' : '' }}>Pisifera</option>
                                <option value="Socfindo" {{ old('nama_jenis_bibit', $kebun->jenis_bibit_nama) == 'Socfindo' ? 'selected' : '' }}>Socfindo</option>
                                <option value="Marihat" {{ old('nama_jenis_bibit', $kebun->jenis_bibit_nama) == 'Marihat' ? 'selected' : '' }}>Marihat</option>
                                <option value="Topaz" {{ old('nama_jenis_bibit', $kebun->jenis_bibit_nama) == 'Topaz' ? 'selected' : '' }}>Topaz</option>
                                <option value="PPKS" {{ old('nama_jenis_bibit', $kebun->jenis_bibit_nama) == 'PPKS' ? 'selected' : '' }}>PPKS (Pusat Penelitian Kelapa Sawit)</option>
                                <option value="Lainnya" {{ old('nama_jenis_bibit', $kebun->jenis_bibit_nama) == 'Lainnya' ? 'selected' : '' }}>Lainnya / Tidak Terdaftar</option>
                            </select>
                            @error('nama_jenis_bibit') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                        @error('tahu_jenis_bibit')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Jenis Tanah --}}
                    <div class="form-group full-width">
                        <label class="form-label">Jenis Tanah</label>
                        <div class="checkbox-group">
                            <div class="checkbox-option">
                                <input type="radio" name="jenis_tanah" value="Mineral Berpasir" 
                                       id="tanah-1" class="checkbox-input" 
                                       {{ old('jenis_tanah', $kebun->jenis_tanah) == 'Mineral Berpasir' ? 'checked' : '' }}>
                                <label for="tanah-1" class="checkbox-label">Mineral Berpasir</label>
                            </div>
                            <div class="checkbox-option">
                                <input type="radio" name="jenis_tanah" value="Mineral Volkanik" 
                                       id="tanah-2" class="checkbox-input" 
                                       {{ old('jenis_tanah', $kebun->jenis_tanah) == 'Mineral Volkanik' ? 'checked' : '' }}>
                                <label for="tanah-2" class="checkbox-label">Mineral Volkanik</label>
                            </div>
                            <div class="checkbox-option">
                                <input type="radio" name="jenis_tanah" value="Gambut" 
                                       id="tanah-3" class="checkbox-input" 
                                       {{ old('jenis_tanah', $kebun->jenis_tanah) == 'Gambut' ? 'checked' : '' }}>
                                <label for="tanah-3" class="checkbox-label">Gambut</label>
                            </div>
                        </div>
                        <span class="helper-text">Pilih salah satu jenis tanah (opsional)</span>
                        @error('jenis_tanah')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Form Actions --}}
                    <div class="form-actions">
                        <button type="submit" class="btn-submit">
                            💾 Simpan Perubahan
                        </button>
                    </div>
                    
                </div>
            </form>
        </div>
    </div>
</div>

<div id="toast-container" class="gp-toast-container" aria-live="polite" aria-atomic="true"></div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('.input-with-clear input');
    
    inputs.forEach(input => {
        const clearBtn = input.nextElementSibling;
        
        input.addEventListener('input', function() {
            if (this.value.length > 0) {
                clearBtn.style.display = 'flex';
            } else {
                clearBtn.style.display = 'none';
            }
        });
        
        if (input.value.length > 0) {
            clearBtn.style.display = 'flex';
        }
    });

    // Toggle pilihan bibit
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
            selectBibit.value = ''; 
        }
    }

    if (radioBibitYa) {
        radioBibitYa.addEventListener('change', togglePilihanBibit);
    }
    if (radioBibitTidak) {
        radioBibitTidak.addEventListener('change', togglePilihanBibit);
    }

    togglePilihanBibit();
    
    @if(session('success'))
        showToast('{{ session('success') }}', 'success');
    @endif
    
    @if(session('error'))
        showToast('{{ session('error') }}', 'error');
    @endif
});

function clearInput(inputId) {
    const input = document.getElementById(inputId);
    const clearBtn = input.nextElementSibling;
    input.value = '';
    clearBtn.style.display = 'none';
    input.focus();
}

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
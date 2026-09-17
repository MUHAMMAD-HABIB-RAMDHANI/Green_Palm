@extends('layouts.app')
@section('title', 'Catat Rawat - Sanitasi')

{{-- ============================================================ --}}
{{-- 1. DEFINISI HEADER MOBILE (Fixed Top)                        --}}
{{-- ============================================================ --}}
@section('mobile-header')
    <header class="mobile-header-custom">
        <div class="header-left-content">
            {{-- Tombol Kembali --}}
            <a href="{{ route('catatan.menu') }}" class="mobile-back-btn confirm-exit">
                ‹
            </a>
            
            {{-- Judul Halaman --}}
            <h2 class="mobile-title">
                Sanitasi
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
        --text-dark: #333;
        --border-gray: #ccc;
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
        min-height: 90vh;
        padding: 20px 30px;
        font-family: 'Poppins', sans-serif;
        display: flex;
        justify-content: center;
        align-items: flex-start;
    }

    .form-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.05);
        width: 100%;
        max-width: 600px;
        margin: 0 auto;
        overflow: hidden;
        animation: slideUp 0.5s ease;
        display: flex;
        flex-direction: column;
        position: relative;
        z-index: 1;
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
        color: white;
        position: relative;
    }

    .btn-back {
        width: 38px;
        height: 38px;
        background: rgba(255,255,255,0.2);
        border: 1px solid rgba(255,255,255,0.3);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        text-decoration: none;
        font-size: 18px;
        transition: 0.3s;
        flex-shrink: 0;
    }

    .btn-back:hover {
        background: rgba(255,255,255,0.3);
    }

    .card-title {
        font-size: 20px;
        font-weight: 700;
        margin: 0;
        color: white;
    }

    /* Body Content */
    .card-body {
        padding: 30px;
    }

    /* Step Indicator */
    .step-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 25px;
        border-bottom: 1px solid #eee;
        padding-bottom: 15px;
    }

    .step-title h2 {
        font-size: 18px;
        font-weight: 700;
        color: #000;
        margin: 0 0 5px 0;
    }

    .step-subtitle {
        font-size: 13px;
        color: #888;
    }

    .info-box {
        background: #e6f1e3;
        border-left: 4px solid var(--primary-green);
        padding: 12px 15px;
        border-radius: 10px;
        font-size: 12.5px;
        color: #444;
        line-height: 1.5;
        margin-bottom: 20px;
    }

    .info-box strong {
        color: var(--dark-green);
    }

    .step-circle {
        width: 40px;
        height: 40px;
        border: 2px solid var(--primary-green);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        color: var(--primary-green);
        font-size: 14px;
    }

    /* Auto-fill Badge */
    .autofill-badge {
        display: inline-block;
        background: linear-gradient(135deg, #2b7a0b 0%, #1e5607 100%);
        color: white;
        font-size: 11px;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 12px;
        margin-left: 8px;
        box-shadow: 0 2px 6px rgba(43, 122, 11, 0.3);
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }

    /* Form Styles */
    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: #000;
        margin-bottom: 8px;
    }

    .form-control {
        width: 100%;
        padding: 14px 16px;
        font-size: 14px;
        color: #333;
        background-color: #fff;
        border: 1px solid var(--border-gray);
        border-radius: 12px;
        outline: none;
        transition: border-color 0.2s;
        box-sizing: border-box;
    }

    .form-control:focus {
        border-color: var(--primary-green);
    }

    .form-control.autofilled {
        background-color: #f0f9f0;
        border-color: var(--primary-green);
    }

    .text-danger {
        color: #dc3545;
        font-size: 12px;
        margin-top: 5px;
        display: block;
    }

    /* Section Divider */
    .section-divider {
        margin: 30px 0 20px;
        padding-top: 20px;
        border-top: 2px solid #f0f0f0;
    }

    .section-title-form {
        font-size: 16px;
        font-weight: 700;
        color: var(--primary-green);
        margin-bottom: 15px;
    }

    /* Upah Item Style */
    .upah-row {
        display: flex;
        gap: 10px;
        margin-bottom: 15px;
        padding: 15px;
        background: #f9f9f9;
        border-radius: 10px;
        border-left: 4px solid var(--primary-green);
        align-items: center;
    }

    .btn-remove {
        background: #ffebeb;
        color: #dc3545;
        border: 1px solid #dc3545;
        border-radius: 12px;
        width: 45px;
        height: 45px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        font-weight: bold;
        transition: 0.2s;
        flex-shrink: 0;
    }

    .btn-remove:hover {
        background: #dc3545;
        color: white;
    }

    /* Add Button */
    .btn-add {
        background: transparent;
        border: 2px dashed var(--primary-green);
        color: var(--primary-green);
        width: 100%;
        padding: 12px;
        border-radius: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: 0.2s;
        margin-top: 5px;
    }

    .btn-add:hover {
        background: #f0fdf0;
    }

    /* Total Display */
    .total-display {
        background: #f8f9fa;
        border: 1px solid #eee;
        padding: 15px;
        border-radius: 12px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 20px;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 15px;
        margin-top: 30px;
    }

    .btn {
        flex: 1;
        padding: 15px;
        border-radius: 25px;
        font-size: 15px;
        font-weight: 600;
        text-align: center;
        cursor: pointer;
        text-decoration: none;
        border: none;
        transition: 0.3s;
    }

    .btn-outline {
        background: transparent;
        border: 2px solid var(--primary-green);
        color: var(--primary-green);
    }

    .btn-solid {
        background: var(--primary-green);
        color: white;
    }

    select.form-control {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23333' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 16px center;
        padding-right: 40px;
    }

    /* --- RESPONSIVE STYLES (FIXED CARD LOOK) --- */
    @media (min-width: 768px) {
        .form-card { max-width: 800px; box-shadow: 0 10px 40px rgba(0,0,0,0.08); }
        .card-body { padding: 40px; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    }
    
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
            padding-bottom: 40px; 
            
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

        .upah-row { flex-direction: column; gap: 10px; align-items: stretch; }
        .btn-remove { margin-top: 5px; width: 100%; border-radius: 8px; }
    }
</style>

<div class="form-wrapper">
    <div class="form-card">
        
        {{-- Header Desktop (Akan hilang di Mobile) --}}
        <div class="card-header">
            <a href="{{ route('catatan.menu') }}" class="btn-back confirm-exit">‹</a> 
            <h1 class="card-title">Catat Rawat</h1>
        </div>

        {{-- Body Content --}}
        <div class="card-body">
            
            {{-- Step Title --}}
            <div class="step-header">
                <div class="step-title">
                    <h2>
                        Perawatan Sanitasi
                        @if(isset($lastData))
                            <span class="autofill-badge">✨ Data Otomatis Terisi</span>
                        @endif
                    </h2>
                    <div class="step-subtitle">Selanjutnya: Selesai</div>
                </div>
                <div class="step-circle">2/2</div>
            </div>

            <div class="info-box">
                <strong>Apa itu Sanitasi?</strong> Kegiatan membersihkan area kebun dari gulma, sampah organik, brondolan busuk, atau sisa tanaman yang berpotensi menjadi sarang hama dan penyakit, guna menjaga kebersihan dan kesehatan lingkungan kebun.
            </div>

            {{-- Form Start --}}
            <form id="sanitasiForm" action="{{ route('catatan.store') }}" method="POST">
                @csrf
                <input type="hidden" name="jenis_kegiatan" value="sanitasi">

                {{-- 1. PILIH KEBUN --}}
                <div class="form-group">
                    <label class="form-label">Pilih Kebun <span style="color: red;">*</span></label>
                    <select name="kebun_id" class="form-control {{ isset($lastData) ? 'autofilled' : '' }}" required>
                        <option value="" disabled {{ (old('kebun_id') || isset($lastData)) ? '' : 'selected' }}>-- Pilih Kebun --</option>
                        @foreach($kebunList as $kebun)
                            <option value="{{ $kebun->id }}" 
                                {{ (old('kebun_id') == $kebun->id || (isset($lastData) && $lastData->kebun_id == $kebun->id)) ? 'selected' : '' }}>
                                {{ $kebun->nama_kebun }} - {{ $kebun->lokasi_kebun }}
                            </option>
                        @endforeach
                    </select>
                    @error('kebun_id') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                {{-- Row 1: Tanggal & Jumlah Pokok --}}
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Tanggal Sanitasi <span style="color: red;">*</span></label>
                        <input type="date" name="tanggal_sanitasi" class="form-control {{ isset($lastData) ? 'autofilled' : '' }}" 
                            value="{{ old('tanggal_sanitasi', isset($lastData) ? $lastData->tanggal_sanitasi : now()->format('Y-m-d')) }}" required>
                        @error('tanggal_sanitasi') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Jumlah Pokok Disanitasi <span style="color: red;">*</span></label>
                        <input type="number" name="jumlah_pokok_disanitasi" class="form-control" 
                            placeholder="Contoh: 50" value="{{ old('jumlah_pokok_disanitasi') }}" required>
                        @error('jumlah_pokok_disanitasi') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                {{-- Biaya Lainnya (WAJIB) --}}
                <div class="form-group">
                    <label class="form-label">Biaya Lainnya (Rp) <span style="color: red;">*</span></label>
                    <input type="text" inputmode="numeric" name="biaya_lain" 
                        class="form-control rupiah-input" placeholder="Isi 0 jika tidak ada biaya tambahan" 
                        value="{{ old('biaya_lain') }}" required>
                    <small style="color: #888; font-size: 12px; margin-top: 4px; display: block;">Contoh: Beli Perlengkapan, Solar, dll.</small>
                    @error('biaya_lain') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                {{-- Divider --}}
                <hr style="border-top: 1px dashed #ccc; margin: 20px 0;">

                {{-- Bagian Upah (Opsional & Dinamis) --}}
                <div class="form-group">
                    <label class="form-label">Rincian Upah / Biaya Tenaga Kerja (Opsional)</label>
                    <p style="font-size: 13px; color: #666; margin-bottom: 10px;">
                        Klik tombol di bawah jika ada biaya upah pekerja.
                    </p>

                    <div id="upah-container">
                        {{-- Container KOSONG secara default --}}
                    </div>
                    
                    <button type="button" class="btn-add" onclick="addUpahRow()">
                        + Tambah Biaya/Upah
                    </button>
                </div>

                {{-- Total Display --}}
                <div class="total-display">
                    <span style="font-weight: 600; color: #666;">Total Upah Keluar:</span>
                    <span id="displayTotal" style="font-weight: 800; font-size: 18px; color: var(--primary-green);">Rp 0</span>
                </div>

                {{-- Buttons Footer --}}
                <div class="action-buttons">
                    <a href="{{ route('catatan.menu') }}" class="btn btn-outline confirm-exit">Kembali</a>
                    <button type="submit" class="btn btn-solid">Simpan Data</button>
                </div>

            </form>

        </div>
    </div>
</div>

{{-- SCRIPT --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let counter = 0;

        // --- Fungsi Format Rupiah ---
        window.formatRupiah = function(angka) {
            if (!angka) return '';
            var number_string = angka.replace(/[^,\d]/g, '').toString(),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }
            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return rupiah;
        }

        // --- Init Listener untuk input statis (biaya lain) ---
        const inputs = document.querySelectorAll('.rupiah-input');
        inputs.forEach(input => initInputListener(input));

        function initInputListener(input) {
            if(input.value) input.value = formatRupiah(input.value);
            input.addEventListener('keyup', function(e) { 
                this.value = formatRupiah(this.value); 
                // Jika input ini adalah bagian dari upah (dinamis), hitung total
                if(this.classList.contains('upah-amount')) hitungTotal();
            });
            input.addEventListener('input', function(e) { 
                this.value = formatRupiah(this.value);
                if(this.classList.contains('upah-amount')) hitungTotal();
            });
        }

        // --- Tambah Baris Dinamis ---
        window.addUpahRow = function() {
            const container = document.getElementById('upah-container');
            const div = document.createElement('div');
            div.className = 'upah-row';
            div.id = `row-${counter}`;
            div.style.animation = "slideUp 0.3s ease";
            
            div.innerHTML = `
                <div style="flex: 1;">
                    <input type="text" name="rincian_upah[${counter}][jenis]" class="form-control" placeholder="Jenis (Cth: Upah Sanitasi)">
                </div>
                <div style="flex: 1;">
                    <input type="text" name="rincian_upah[${counter}][jumlah]" class="form-control rupiah-input upah-amount" placeholder="Rp 0">
                </div>
                <button type="button" class="btn-remove" onclick="removeRow(${counter})">&times;</button>
            `;
            
            container.appendChild(div);
            
            const newInput = div.querySelector('.rupiah-input');
            initInputListener(newInput);
            
            counter++;
        }

        // --- Hapus Baris ---
        window.removeRow = function(id) {
            const row = document.getElementById(`row-${id}`);
            if (row) {
                row.remove();
                hitungTotal();
            }
        }

        // --- Fungsi Hitung Total Upah ---
        window.hitungTotal = function() {
            let total = 0;
            document.querySelectorAll('.upah-amount').forEach(input => {
                let cleanValue = input.value.replace(/\./g, '').replace(/,/g, '.');
                total += parseFloat(cleanValue) || 0;
            });
            
            const formatted = new Intl.NumberFormat('id-ID', { 
                style: 'currency', currency: 'IDR', minimumFractionDigits: 0 
            }).format(total);
            
            document.getElementById('displayTotal').innerText = formatted;
        }
    });
</script>

@endsection
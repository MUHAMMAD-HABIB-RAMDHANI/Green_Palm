@extends('layouts.app')

@section('title', 'Catat Panen')

@section('content')

<style>
    /* --- VARIABLES & BASE --- */
    :root {
        --primary-green: #2b7a0b;
        --dark-green: #1e5607;
        --bg-gray: #f8f9fa;
        --text-dark: #333;
        --border-gray: #ccc;
    }

    /* --- WRAPPER STYLE --- */
    .form-wrapper {
        background-color: var(--bg-gray);
        padding: 20px;
        font-family: 'Poppins', sans-serif;
        min-height: 90vh;
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
    }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* --- HEADER SECTION --- */
    .card-header {
        background: linear-gradient(135deg, var(--primary-green) 0%, var(--dark-green) 100%);
        padding: 20px 30px;
        display: flex;
        align-items: center;
        gap: 20px;
        color: white;
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

    /* --- BODY CONTENT --- */
    .card-body {
        padding: 30px;
    }

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

    /* --- FORM STYLES --- */
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

    .form-control::placeholder {
        color: #ccc;
    }

    /* Readonly input style */
    .form-control[readonly] {
        background-color: #e9ecef;
        cursor: not-allowed;
    }

    select.form-control {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23333' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 16px center;
        padding-right: 40px;
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

    /* Add Upah Button */
    .btn-add-upah {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        background: white;
        border: 2px solid var(--primary-green);
        color: var(--primary-green);
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-top: 15px;
    }

    .btn-add-upah:hover {
        background: #f4fff4;
    }

    /* Upah Item */
    .upah-item {
        display: flex;
        gap: 10px;
        margin-bottom: 15px;
        padding: 15px;
        background: #f9f9f9;
        border-radius: 10px;
        border-left: 4px solid var(--primary-green);
    }

    .upah-item .form-group {
        flex: 1;
        margin-bottom: 0;
    }

    .btn-remove-upah {
        align-self: flex-start;
        margin-top: 30px;
        width: 36px;
        height: 36px;
        background: #ff6b6b;
        border: none;
        border-radius: 8px;
        color: white;
        cursor: pointer;
        font-size: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: 0.3s;
    }

    .btn-remove-upah:hover {
        background: #dc3545;
    }

    /* --- ACTION BUTTONS --- */
    .action-buttons {
        display: flex;
        gap: 15px;
        margin-top: 20px;
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

    .btn-outline:hover {
        background: #f0fdf0;
    }

    .btn-solid {
        background: var(--primary-green);
        color: white;
    }

    .btn-solid:hover {
        background: var(--dark-green);
    }

    .btn-solid:active {
        transform: scale(0.98);
    }

    /* --- RESPONSIVE --- */
    @media (max-width: 768px) {
        .form-wrapper {
            padding: 15px;
        }

        .card-body {
            padding: 25px 20px;
        }

        .upah-item {
            flex-direction: column;
        }

        .btn-remove-upah {
            margin-top: 0;
            width: 100%;
        }

        .action-buttons {
            flex-direction: column;
        }
    }
</style>

<div class="form-wrapper">
    <div class="form-card">
        
        {{-- Header --}}
        <div class="card-header">
            <a href="{{ route('panen.index') }}" class="btn-back">‹</a> 
            <h1 class="card-title">Catat Panen</h1>
        </div>

        {{-- Body --}}
        <div class="card-body">
            
            {{-- Step Header --}}
            <div class="step-header">
                <div class="step-title">
                    <h2>Hasil & Pendapatan</h2>
                    <div class="step-subtitle">Masukkan data hasil panen dan harga</div>
                </div>
                <div class="step-circle">1/2</div>
            </div>

            {{-- Form Start --}}
            <form action="{{ route('panen.store') }}" method="POST" id="form-panen">
                @csrf

                {{-- 1. Pilih Kebun --}}
                <div class="form-group">
                    <label class="form-label">Pilih Kebun <span style="color: red;">*</span></label>
                    <select name="kebun_id" class="form-control" required>
                        <option value="" disabled {{ old('kebun_id') ? '' : 'selected' }}>-- Pilih Kebun --</option>
                        @foreach($kebunList as $kebun)
                            <option value="{{ $kebun->id }}" {{ old('kebun_id') == $kebun->id ? 'selected' : '' }}>
                                {{ $kebun->nama_kebun }} - {{ $kebun->lokasi_kebun }}
                            </option>
                        @endforeach
                    </select>
                    @error('kebun_id')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Section: Hasil Panen --}}
                <div class="section-divider">
                    <div class="section-title-form">Hasil Panen & Penjualan</div>
                </div>

                {{-- 2. Tanggal Panen --}}
                <div class="form-group">
                    <label class="form-label">Tanggal Panen <span style="color: red;">*</span></label>
                    <input type="date" name="tanggal_panen" class="form-control" value="{{ old('tanggal_panen') }}" required>
                    @error('tanggal_panen')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                {{-- 3. Berat Total TBS (kg) --}}
                <div class="form-group">
                    <label class="form-label">Berat Total TBS (kg) <span style="color: red;">*</span></label>
                    <input type="number" step="0.01" name="berat_total_tbs" id="berat_total" class="form-control" 
                           placeholder="Masukkan total berat TBS" value="{{ old('berat_total_tbs') }}" required>
                    @error('berat_total_tbs')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                {{-- 4. Harga TBS per Kg (Input Baru) --}}
                <div class="form-group">
                    <label class="form-label">Harga TBS saat ini (Rp/kg) <span style="color: red;">*</span></label>
                    <input type="number" step="1" name="harga_tbs" id="harga_tbs" class="form-control" 
                           placeholder="Contoh: 2500" value="{{ old('harga_tbs') }}" required>
                    @error('harga_tbs')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                {{-- 5. Estimasi Pendapatan (Readonly - Auto Calculate) --}}
                <div class="form-group">
                    <label class="form-label">Estimasi Pendapatan Kotor</label>
                    <input type="text" id="estimasi_pendapatan" class="form-control" 
                           style="background-color: #e9ecef; font-weight: bold; color: var(--dark-green);" 
                           value="Rp 0" readonly>
                    <small class="text-muted">Otomatis dihitung (Berat Total × Harga)</small>
                </div>

                <div class="section-divider">
                    <div class="section-title-form">Detail Tambahan</div>
                </div>

                {{-- 6. Jumlah TBS (Jika Ada) --}}
                <div class="form-group">
                    <label class="form-label">Jumlah Tandan (Janjang) (Opsional)</label>
                    <input type="number" name="jumlah_tbs" class="form-control" placeholder="Masukkan jumlah janjang" value="{{ old('jumlah_tbs') }}">
                    @error('jumlah_tbs')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                {{-- 7. Berat Brondolan (kg) (Jika Ada) --}}
                <div class="form-group">
                    <label class="form-label">Berat Brondolan (kg) (Opsional)</label>
                    <input type="number" step="0.01" name="berat_brondolan" class="form-control" placeholder="Masukkan berat brondolan" value="{{ old('berat_brondolan') }}">
                    @error('berat_brondolan')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                {{-- 8. Tanggal Panen Berikutnya --}}
                <div class="form-group">
                    <label class="form-label">Perkiraan Panen Berikutnya</label>
                    <input type="date" name="tanggal_panen_berikutnya" class="form-control" value="{{ old('tanggal_panen_berikutnya') }}">
                    @error('tanggal_panen_berikutnya')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Section: Upah Panen & Biaya Lainnya --}}
                <div class="section-divider">
                    <div class="section-title-form">Pengeluaran / Upah Panen</div>
                    <p style="font-size: 13px; color: #666; margin-bottom: 15px;">
                        Catat upah atau biaya tambahan selama panen (Mengurangi laba bersih).
                    </p>
                </div>

                <div id="upah-container">
                    {{-- Dynamic upah items akan ditambahkan di sini --}}
                </div>

                <button type="button" class="btn-add-upah" onclick="addUpahItem()">
                    <span style="font-size: 18px;">+</span>
                    <span>Tambah Biaya/Upah</span>
                </button>

                {{-- Action Buttons --}}
                <div class="action-buttons">
                    <a href="{{ route('panen.index') }}" class="btn btn-outline">Kembali</a>
                    <button type="submit" class="btn btn-solid">Simpan Data</button>
                </div>

            </form>
            {{-- Form End --}}

        </div>
    </div>
</div>

{{-- JavaScript untuk Dynamic Upah & Kalkulasi Pendapatan --}}
<script>
    /* --- LOGIKA UPAH DINAMIS --- */
    let upahCounter = 0;

    function addUpahItem() {
        upahCounter++;
        const container = document.getElementById('upah-container');
        
        const upahItem = document.createElement('div');
        upahItem.className = 'upah-item';
        upahItem.id = `upah-${upahCounter}`;
        upahItem.innerHTML = `
            <div class="form-group">
                <label class="form-label">Jenis Pengeluaran</label>
                <input type="text" name="upah_panen[${upahCounter}][jenis]" class="form-control" placeholder="Contoh: Upah Angkut">
            </div>
            <div class="form-group">
                <label class="form-label">Biaya (Rp)</label>
                <input type="number" step="0.01" name="upah_panen[${upahCounter}][jumlah]" class="form-control" placeholder="0">
            </div>
            <button type="button" class="btn-remove-upah" onclick="removeUpahItem(${upahCounter})">×</button>
        `;
        
        container.appendChild(upahItem);
    }

    function removeUpahItem(id) {
        const item = document.getElementById(`upah-${id}`);
        if (item) {
            item.remove();
        }
    }

    /* --- LOGIKA KALKULASI PENDAPATAN REAL-TIME --- */
    document.addEventListener('DOMContentLoaded', function() {
        const beratInput = document.getElementById('berat_total');
        const hargaInput = document.getElementById('harga_tbs');
        const estimasiOutput = document.getElementById('estimasi_pendapatan');

        function hitungPendapatan() {
            const berat = parseFloat(beratInput.value) || 0;
            const harga = parseFloat(hargaInput.value) || 0;
            const total = berat * harga;

            // Format ke Rupiah
            estimasiOutput.value = new Intl.NumberFormat('id-ID', { 
                style: 'currency', 
                currency: 'IDR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(total);
        }

        // Pasang event listener
        if(beratInput && hargaInput) {
            beratInput.addEventListener('input', hitungPendapatan);
            hargaInput.addEventListener('input', hitungPendapatan);
        }
    });
</script>

@endsection
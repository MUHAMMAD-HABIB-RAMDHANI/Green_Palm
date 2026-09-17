@extends('layouts.app')
@section('title', 'Catat Panen')

{{-- ============================================================ --}}
{{-- 1. DEFINISI HEADER MOBILE (Fixed Top)                        --}}
{{-- ============================================================ --}}
@section('mobile-header')
    <header class="mobile-header-custom">
        <div class="header-left-content">
            <a href="{{ route('panen.index') }}" class="mobile-back-btn confirm-exit">
                ‹
            </a>
            <h2 class="mobile-title">
                Catat Panen
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
        --bg-gray: #f8f9fa;
        --text-dark: #333;
        --border-gray: #ccc;
    }

    /* =========================================
       STYLE MOBILE HEADER (Default: Hidden)
       ========================================= */
    .mobile-header-custom {
        display: none;
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

    .form-control::placeholder {
        color: #ccc;
    }

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

    /* --- FOTO UPLOAD --- */
    .photo-upload-buttons {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 15px;
    }

    .btn-photo {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 20px;
        background: white;
        border: 2px solid var(--primary-green);
        color: var(--primary-green);
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        flex: 1;
        justify-content: center;
        min-width: 140px;
    }

    .btn-photo:hover {
        background: #f4fff4;
    }

    .btn-photo:active {
        transform: scale(0.98);
    }

    .photo-hint {
        font-size: 12px;
        color: #888;
        margin-bottom: 15px;
    }

    .photo-preview-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
        gap: 12px;
        margin-top: 10px;
    }

    .photo-preview-item {
        position: relative;
        width: 100%;
        aspect-ratio: 1 / 1;
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #eee;
        background: #f4f4f4;
    }

    .photo-preview-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .photo-preview-remove {
        position: absolute;
        top: 6px;
        right: 6px;
        width: 26px;
        height: 26px;
        background: rgba(220, 53, 69, 0.9);
        color: white;
        border: none;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        cursor: pointer;
        line-height: 1;
    }

    .photo-preview-remove:hover {
        background: #dc3545;
    }

    .photo-empty-state {
        text-align: center;
        padding: 25px 15px;
        border: 2px dashed #ddd;
        border-radius: 12px;
        color: #aaa;
        font-size: 13px;
    }

    /* Upah Item Style */
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

    /* --- RESPONSIVE (Wide layout on desktop, like Pemupukan) --- */
    @media (min-width: 768px) {
        .form-card { max-width: 800px; box-shadow: 0 10px 40px rgba(0,0,0,0.08); }
        .card-body { padding: 40px; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    }

    @media (max-width: 768px) {
        .card-header { display: none !important; }

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

        .form-wrapper {
            margin-top: -80px;
            margin-left: -20px;
            margin-right: -20px;
            background-color: #f8f9fa;
            min-height: 100vh;
            padding: 0 15px;
            padding-bottom: 40px;
            display: flex;
            flex-direction: column;
        }

        .form-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.05);
            margin-top: 90px;
            margin-bottom: 30px;
            width: 100%;
        }

        .card-body {
            padding: 25px 20px;
        }

        .upah-item { flex-direction: column; gap: 0; }
        .btn-remove-upah { margin-top: 10px; width: 100%; border-radius: 8px; }

        .photo-upload-buttons { flex-direction: column; }
    }
</style>

<div class="form-wrapper">
    <div class="form-card">

        {{-- Header Desktop (Akan hilang di Mobile) --}}
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
            <form action="{{ route('panen.store') }}" method="POST" id="form-panen" enctype="multipart/form-data">
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

                {{-- Row: Tanggal Panen & Berat Total TBS --}}
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Tanggal Panen <span style="color: red;">*</span></label>
                        <input type="date" name="tanggal_panen" class="form-control" value="{{ old('tanggal_panen') }}" required>
                        @error('tanggal_panen')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Berat Total TBS (kg) <span style="color: red;">*</span></label>
                        <input type="number" step="0.01" name="berat_total_tbs" id="berat_total" class="form-control"
                               placeholder="Masukkan total berat TBS" value="{{ old('berat_total_tbs') }}" required>
                        @error('berat_total_tbs')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Row: Harga TBS & Estimasi Pendapatan --}}
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Harga TBS saat ini (Rp/kg) <span style="color: red;">*</span></label>
                        <input type="number" step="1" name="harga_tbs" id="harga_tbs" class="form-control"
                               placeholder="Contoh: 2500" value="{{ old('harga_tbs') }}" required>
                        @error('harga_tbs')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Estimasi Pendapatan Kotor</label>
                        <input type="text" id="estimasi_pendapatan" class="form-control"
                               style="background-color: #e9ecef; font-weight: bold; color: var(--dark-green);"
                               value="Rp 0" readonly>
                    </div>
                </div>

                <div class="section-divider">
                    <div class="section-title-form">Detail Tambahan</div>
                </div>

                {{-- Row: Jumlah Tandan & Berat Brondolan --}}
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Jumlah Tandan (Janjang) (Opsional)</label>
                        <input type="number" name="jumlah_tbs" class="form-control" placeholder="Masukkan jumlah janjang" value="{{ old('jumlah_tbs') }}">
                        @error('jumlah_tbs')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Berat Brondolan (kg) (Opsional)</label>
                        <input type="number" step="0.01" name="berat_brondolan" class="form-control" placeholder="Masukkan berat brondolan" value="{{ old('berat_brondolan') }}">
                        @error('berat_brondolan')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Tanggal Panen Berikutnya --}}
                <div class="form-group">
                    <label class="form-label">Perkiraan Panen Berikutnya</label>
                    <input type="date" name="tanggal_panen_berikutnya" class="form-control" value="{{ old('tanggal_panen_berikutnya') }}">
                    @error('tanggal_panen_berikutnya')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Section: Foto Bukti Panen --}}
                <div class="section-divider">
                    <div class="section-title-form">Foto Bukti Panen (Opsional)</div>
                    <p style="font-size: 13px; color: #666; margin-bottom: 15px;">
                        Unggah foto hasil panen sebagai dokumentasi. Mendukung format JPG, PNG, WEBP, GIF, HEIC/HEIF, dan BMP.
                    </p>
                </div>

                <div class="photo-upload-buttons">
                    <button type="button" class="btn-photo" id="btnCamera" style="display:none;">
                        <span>📷</span><span>Ambil Foto</span>
                    </button>
                    <button type="button" class="btn-photo" id="btnGallery">
                        <span id="galleryIcon">🖼️</span><span id="galleryLabel">Pilih dari Galeri</span>
                    </button>
                </div>
                <div class="photo-hint" id="photoHint"></div>

                {{-- Input tersembunyi untuk kamera --}}
                <input type="file" id="cameraInput" accept="image/*" capture="environment" style="display:none;">

                {{-- Input tersembunyi untuk galeri / file explorer --}}
                <input type="file" id="galleryInput" accept="image/*" multiple style="display:none;">

                {{-- Input final yang benar-benar dikirim ke server --}}
                <input type="file" name="foto_panen[]" id="finalFileInput" multiple style="display:none;">

                <div id="photoPreviewContainer">
                    <div class="photo-empty-state" id="photoEmptyState">Belum ada foto dipilih</div>
                    <div class="photo-preview-grid" id="photoPreviewGrid"></div>
                </div>

                {{-- Section: Upah Panen & Biaya Lainnya --}}
                <div class="section-divider">
                    <div class="section-title-form">Pengeluaran / Upah Panen</div>
                    <p style="font-size: 13px; color: #666; margin-bottom: 15px;">
                        Catat upah atau biaya tambahan selama panen (Mengurangi laba bersih).
                    </p>
                </div>

                <div id="upah-container"></div>

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

        </div>
    </div>
</div>

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

            estimasiOutput.value = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(total);
        }

        if (beratInput && hargaInput) {
            beratInput.addEventListener('input', hitungPendapatan);
            hargaInput.addEventListener('input', hitungPendapatan);
        }
    });

    /* =========================================================
       LOGIKA UPLOAD FOTO (Kamera + Galeri, Multi-format)
       ========================================================= */
    (function() {
        const isMobile = /Android|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini|Mobile/i.test(navigator.userAgent);

        const btnCamera = document.getElementById('btnCamera');
        const btnGallery = document.getElementById('btnGallery');
        const galleryLabel = document.getElementById('galleryLabel');
        const galleryIcon = document.getElementById('galleryIcon');
        const cameraInput = document.getElementById('cameraInput');
        const galleryInput = document.getElementById('galleryInput');
        const finalFileInput = document.getElementById('finalFileInput');
        const previewGrid = document.getElementById('photoPreviewGrid');
        const emptyState = document.getElementById('photoEmptyState');
        const photoHint = document.getElementById('photoHint');

        const MAX_FILES = 5;
        const MAX_SIZE_MB = 10;
        let selectedFiles = [];

        // Tampilkan tombol kamera hanya di perangkat mobile.
        // Di laptop/PC, browser tidak mendukung akses kamera langsung via input file,
        // sehingga hanya tombol "Pilih File" yang ditampilkan.
        if (isMobile) {
            btnCamera.style.display = 'inline-flex';
            galleryLabel.textContent = 'Pilih dari Galeri';
            galleryIcon.textContent = '🖼️';
            photoHint.textContent = `Maksimal ${MAX_FILES} foto, ukuran masing-masing di bawah ${MAX_SIZE_MB}MB.`;
        } else {
            galleryLabel.textContent = 'Pilih File';
            galleryIcon.textContent = '📁';
            photoHint.textContent = `Pilih dari file explorer. Maksimal ${MAX_FILES} foto, ukuran masing-masing di bawah ${MAX_SIZE_MB}MB.`;
        }

        btnCamera.addEventListener('click', () => cameraInput.click());
        btnGallery.addEventListener('click', () => galleryInput.click());

        cameraInput.addEventListener('change', (e) => handleNewFiles(e.target.files));
        galleryInput.addEventListener('change', (e) => handleNewFiles(e.target.files));

        function handleNewFiles(fileList) {
            const incoming = Array.from(fileList || []);

            incoming.forEach(file => {
                if (!file.type.startsWith('image/')) return;

                if (file.size > MAX_SIZE_MB * 1024 * 1024) {
                    alert(`File "${file.name}" melebihi ${MAX_SIZE_MB}MB dan dilewati.`);
                    return;
                }

                if (selectedFiles.length >= MAX_FILES) {
                    alert(`Maksimal ${MAX_FILES} foto.`);
                    return;
                }

                selectedFiles.push(file);
            });

            // Reset input agar file yang sama bisa dipilih ulang jika perlu
            cameraInput.value = '';
            galleryInput.value = '';

            syncFinalInput();
            renderPreviews();
        }

        function syncFinalInput() {
            const dataTransfer = new DataTransfer();
            selectedFiles.forEach(file => dataTransfer.items.add(file));
            finalFileInput.files = dataTransfer.files;
        }

        function renderPreviews() {
            previewGrid.innerHTML = '';

            if (selectedFiles.length === 0) {
                emptyState.style.display = 'block';
                return;
            }
            emptyState.style.display = 'none';

            selectedFiles.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const item = document.createElement('div');
                    item.className = 'photo-preview-item';
                    item.innerHTML = `
                        <img src="${e.target.result}" alt="Preview ${index + 1}">
                        <button type="button" class="photo-preview-remove" data-index="${index}">×</button>
                    `;
                    previewGrid.appendChild(item);

                    item.querySelector('.photo-preview-remove').addEventListener('click', function() {
                        const idx = parseInt(this.getAttribute('data-index'));
                        selectedFiles.splice(idx, 1);
                        syncFinalInput();
                        renderPreviews();
                    });
                };
                reader.readAsDataURL(file);
            });
        }
    })();
</script>

@endsection
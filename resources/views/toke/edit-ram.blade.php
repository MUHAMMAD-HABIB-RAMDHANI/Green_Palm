@extends('toke.toke')

@section('title', 'Edit Data RAM')

@section('content')

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />

<style>
    .form-container {
        max-width: 800px;
        margin: 0 auto;
        background: white;
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .form-header {
        text-align: center;
        margin-bottom: 30px;
    }

    .form-header h2 {
        font-size: 28px;
        font-weight: 700;
        color: #2b7a0b;
        margin-bottom: 8px;
    }

    .form-header p {
        color: #666;
        font-size: 14px;
    }

    .form-group {
        margin-bottom: 24px;
    }

    .form-label {
        display: block;
        font-weight: 600;
        color: #222;
        margin-bottom: 8px;
        font-size: 15px;
    }

    .form-input {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        font-size: 15px;
        transition: all 0.3s ease;
        font-family: 'Poppins', sans-serif;
    }

    .form-input:focus {
        outline: none;
        border-color: #2b7a0b;
        box-shadow: 0 0 0 3px rgba(43, 122, 11, 0.1);
    }

    .form-input.error {
        border-color: #dc3545;
    }

    .error-message {
        color: #dc3545;
        font-size: 13px;
        margin-top: 5px;
    }

    .location-group {
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 10px;
        align-items: end;
    }

    /* --- TOMBOL PETA DIPERBAIKI (Konsisten dengan btn-primary) --- */
    .btn-map {
        padding: 0 24px;
        background: linear-gradient(135deg, #2b7a0b 0%, #1E4620 100%);
        color: white;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
        white-space: nowrap;
        font-family: 'Poppins', sans-serif;
        box-shadow: 0 4px 15px rgba(43, 122, 11, 0.3);
        height: 49px; /* Samakan tinggi dengan input form */
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-map:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(43, 122, 11, 0.4);
        background: linear-gradient(135deg, #1E4620 0%, #0d2410 100%);
    }
    
    /* Pastikan input lokasi tingginya konsisten */
    #lokasi_ram {
        height: 49px; 
    }

    .map-container {
        width: 100%;
        height: 300px;
        border-radius: 12px;
        overflow: hidden;
        margin-top: 15px;
        border: 2px solid #e0e0e0;
        display: none;
        position: relative;
        z-index: 1;
    }

    .map-container.show {
        display: block;
    }

    .coordinates-display {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        margin-top: 10px;
    }

    .coordinate-box {
        padding: 8px 12px;
        background: #f8f9fa;
        border-radius: 8px;
        font-size: 13px;
        color: #666;
    }

    .facilities-section {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 24px;
    }

    .facilities-section h4 {
        font-size: 16px;
        font-weight: 600;
        color: #2b7a0b;
        margin-bottom: 15px;
    }

    .checkbox-group {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 12px;
    }

    .checkbox-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px;
        background: white;
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .checkbox-item:hover {
        border-color: #2b7a0b;
        background: #f0f7f1;
    }

    .checkbox-item input[type="checkbox"] {
        width: 20px;
        height: 20px;
        cursor: pointer;
        accent-color: #2b7a0b;
    }

    .checkbox-item label {
        cursor: pointer;
        font-size: 14px;
        color: #222;
        flex: 1;
    }

    /* --- CSS AREA FOTO (Fixed Layout) --- */
    .photo-upload-section {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 100%;
        box-sizing: border-box;
        border: 2px dashed #2b7a0b;
        border-radius: 12px;
        padding: 40px 20px;
        text-align: center;
        background: #f0f7f1;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-top: 5px;
    }

    .photo-upload-section:hover {
        background: #e6f1e3;
        border-color: #1E4620;
        transform: translateY(-2px);
    }

    .photo-upload-section input[type="file"] {
        display: none;
    }

    .upload-icon {
        font-size: 52px;
        margin-bottom: 10px;
        line-height: 1;
    }

    .upload-text {
        font-size: 16px;
        color: #2b7a0b;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .upload-hint {
        font-size: 13px;
        color: #666;
    }

    .photo-preview {
        margin-top: 15px;
        display: none;
        width: 100%;
        text-align: center;
        background: #fff;
        padding: 10px;
        border-radius: 12px;
        border: 1px solid #eee;
    }

    .photo-preview img {
        max-width: 100%;
        max-height: 350px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        object-fit: contain;
    }

    .form-actions {
        display: flex;
        gap: 15px;
        margin-top: 30px;
    }

    .btn {
        flex: 1;
        padding: 14px 24px;
        border: none;
        border-radius: 12px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        font-family: 'Poppins', sans-serif;
    }

    .btn-primary {
        background: linear-gradient(135deg, #2b7a0b 0%, #1E4620 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(43, 122, 11, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(43, 122, 11, 0.4);
    }

    .btn-secondary {
        background: #f0f0f0;
        color: #555;
    }

    .btn-secondary:hover {
        background: #e0e0e0;
    }

    /* === STYLE MODAL KONFIRMASI === */
    .logout-modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(4px);
        z-index: 10000;
        animation: fadeIn 0.3s ease;
    }

    .logout-modal-overlay.show {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .logout-modal {
        background: white;
        border-radius: 20px;
        padding: 0;
        max-width: 420px;
        width: 90%;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        animation: slideUp 0.3s ease;
        overflow: hidden;
    }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(30px) scale(0.95); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }

    .logout-modal-header {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        padding: 30px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .logout-modal-icon {
        width: 80px;
        height: 80px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
        font-size: 40px;
        position: relative;
        z-index: 1;
    }

    .logout-modal-title {
        font-size: 24px;
        font-weight: 700;
        color: white;
        margin: 0;
        position: relative;
        z-index: 1;
    }

    .logout-modal-body {
        padding: 30px;
        text-align: center;
    }

    .logout-modal-message {
        font-size: 16px;
        color: #555;
        line-height: 1.6;
        margin: 0;
    }

    .logout-modal-footer {
        padding: 0 30px 30px;
        display: flex;
        gap: 12px;
    }

    .logout-btn {
        flex: 1;
        padding: 14px 24px;
        border: none;
        border-radius: 12px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        font-family: 'Poppins', sans-serif;
    }

    .logout-btn-cancel {
        background: #f0f0f0;
        color: #555;
    }

    .logout-btn-cancel:hover {
        background: #e0e0e0;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .logout-btn-confirm {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
    }

    .logout-btn-confirm:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(220, 53, 69, 0.4);
    }

    .leaflet-control-geocoder {
        z-index: 999 !important;
    }

    @media (max-width: 768px) {
        .form-container {
            padding: 25px 20px;
        }

        .location-group {
            grid-template-columns: 1fr; /* Tombol pindah ke bawah di HP */
        }

        .btn-map {
            width: 100%; /* Tombol penuh di HP */
        }

        .checkbox-group {
            grid-template-columns: 1fr;
        }

        .form-actions {
            flex-direction: column-reverse;
        }
    }
</style>

<header class="main-header">
    <h1>✏️ {{ $ram ? 'Edit' : 'Tambah' }} Data RAM</h1>
    <div class="header-username-desktop">
        👋 Halo, {{ $user->username }}
    </div>
</header>

<div class="form-container">
    <div class="form-header">
        <h2>{{ $ram ? 'Edit' : 'Tambah' }} Data RAM</h2>
        <p>Lengkapi informasi RAM Anda</p>
    </div>

    <form action="{{ route('toke.store-ram') }}" method="POST" enctype="multipart/form-data" id="ramForm">
        @csrf

        {{-- Nama RAM --}}
        <div class="form-group">
            <label class="form-label">Nama RAM <span style="color: #dc3545;">*</span></label>
            <input 
                type="text" 
                name="nama_ram" 
                class="form-input @error('nama_ram') error @enderror" 
                placeholder="Contoh: RAM Sumber Rejeki"
                value="{{ old('nama_ram', $ram->nama_ram ?? '') }}"
                required>
            @error('nama_ram')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        {{-- ✅ TAMBAHAN: Nomor WhatsApp --}}
        <div class="form-group">
            <label class="form-label">Nomor WhatsApp (Aktif) <span style="color: #dc3545;">*</span></label>
            <input 
                type="number" 
                name="nomor_wa" 
                class="form-input @error('nomor_wa') error @enderror" 
                placeholder="Contoh: 081234567890"
                value="{{ old('nomor_wa', $ram->nomor_wa ?? '') }}"
                required>
            <p style="font-size: 12px; color: #666; margin-top: 5px;">*Nomor ini akan digunakan petani untuk menghubungi Anda.</p>
            @error('nomor_wa')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        {{-- Lokasi RAM --}}
        <div class="form-group">
            <label class="form-label">Lokasi RAM <span style="color: #dc3545;">*</span></label>
            <div class="location-group">
                <input 
                    type="text" 
                    name="lokasi_ram" 
                    id="lokasi_ram"
                    class="form-input @error('lokasi_ram') error @enderror" 
                    placeholder="Masukkan lokasi RAM atau pilih dari peta"
                    value="{{ old('lokasi_ram', $ram->lokasi_ram ?? '') }}"
                    required>
                
                {{-- Tombol Peta dengan Style Baru --}}
                <button type="button" class="btn-map" onclick="toggleMap()">
                    📍 Pilih dari Peta
                </button>
            </div>
            @error('lokasi_ram')
                <div class="error-message">{{ $message }}</div>
            @enderror

            {{-- Map Container --}}
            <div id="mapContainer" class="map-container">
                <div id="map" style="width: 100%; height: 100%;"></div>
            </div>

            {{-- Coordinates Display --}}
            <div class="coordinates-display" id="coordinatesDisplay" style="display: none;">
                <div class="coordinate-box">
                    📍 Lat: <span id="latDisplay">-</span>
                </div>
                <div class="coordinate-box">
                    📍 Long: <span id="lngDisplay">-</span>
                </div>
            </div>

            {{-- Hidden Inputs for Coordinates --}}
            <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude', $ram->latitude ?? '') }}">
            <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude', $ram->longitude ?? '') }}">
        </div>

        {{-- Harga Beli TBS --}}
        <div class="form-group">
            <label class="form-label">Harga Beli TBS (per kg) <span style="color: #dc3545;">*</span></label>
            
            <input 
                type="text" 
                id="harga_display"
                class="form-input @error('harga_beli_tbs') error @enderror" 
                placeholder="Contoh: 2.500"
                value="{{ old('harga_beli_tbs', isset($ram) ? number_format($ram->harga_beli_tbs, 0, ',', '.') : '') }}"
                onkeyup="formatHarga(this)"
                required>

            <input 
                type="hidden" 
                name="harga_beli_tbs" 
                id="harga_beli_tbs" 
                value="{{ old('harga_beli_tbs', isset($ram) ? intval($ram->harga_beli_tbs) : '') }}">

            @error('harga_beli_tbs')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        {{-- Fasilitas --}}
        <div class="facilities-section">
            <h4>Fasilitas yang Anda Berikan</h4>
            <div class="checkbox-group">
                <div class="checkbox-item">
                    <input 
                        type="checkbox" 
                        name="layanan_jemput_buah" 
                        id="layanan_jemput_buah"
                        {{ old('layanan_jemput_buah', $ram->layanan_jemput_buah ?? false) ? 'checked' : '' }}>
                    <label for="layanan_jemput_buah">Layanan Jemput Buah ke Kebun</label>
                </div>

                <div class="checkbox-item">
                    <input 
                        type="checkbox" 
                        name="timbangan_digital" 
                        id="timbangan_digital"
                        {{ old('timbangan_digital', $ram->timbangan_digital ?? false) ? 'checked' : '' }}>
                    <label for="timbangan_digital">Timbangan Digital yang Akurat</label>
                </div>

                <div class="checkbox-item">
                    <input 
                        type="checkbox" 
                        name="menerima_berondolan" 
                        id="menerima_berondolan"
                        {{ old('menerima_berondolan', $ram->menerima_berondolan ?? false) ? 'checked' : '' }}>
                    <label for="menerima_berondolan">Menerima Berondolan</label>
                </div>

                <div class="checkbox-item">
                    <input 
                        type="checkbox" 
                        name="tidak_ada_pengembalian" 
                        id="tidak_ada_pengembalian"
                        {{ old('tidak_ada_pengembalian', $ram->tidak_ada_pengembalian ?? false) ? 'checked' : '' }}>
                    <label for="tidak_ada_pengembalian">Tidak Ada Pengembalian</label>
                </div>
            </div>
        </div>

        {{-- Foto Tampak Depan --}}
        <div class="form-group">
            <label class="form-label">Foto Tampak Depan RAM</label>
            
            <label for="foto_tampak_depan" class="photo-upload-section">
                <div class="upload-icon">📸</div>
                <div class="upload-text">Klik di sini untuk upload foto</div>
                <div class="upload-hint">Format: JPG, PNG (Max: 2MB)</div>
                <input 
                    type="file" 
                    name="foto_tampak_depan" 
                    id="foto_tampak_depan"
                    accept="image/jpeg,image/png,image/jpg"
                    onchange="previewPhoto(this)">
            </label>
            
            <div class="photo-preview" id="photoPreview">
                <p style="font-size: 13px; color: #666; margin-bottom: 10px;">Preview Foto:</p>
                <img id="photoPreviewImg" 
                     src="{{ $ram && $ram->foto_tampak_depan ? asset('storage/' . $ram->foto_tampak_depan) : '' }}" 
                     alt="Preview">
            </div>
            
            @error('foto_tampak_depan')
                <div class="error-message" style="text-align: center;">{{ $message }}</div>
            @enderror
        </div>

        {{-- Form Actions --}}
        <div class="form-actions">
            <button type="button" class="btn btn-secondary" id="triggerCancelModal">
                Batal
            </button>
            <button type="submit" class="btn btn-primary">
                💾 Simpan Data RAM
            </button>
        </div>
    </form>
</div>

{{-- MODAL KONFIRMASI BATAL --}}
<div class="logout-modal-overlay" id="cancelModal">
    <div class="logout-modal">
        <div class="logout-modal-header">
            <div class="logout-modal-icon">⚠️</div>
            <h2 class="logout-modal-title">Batalkan Perubahan?</h2>
        </div>
        <div class="logout-modal-body">
            <p class="logout-modal-message">
                Apakah Anda yakin ingin membatalkan? <br>
                Semua perubahan yang belum disimpan akan hilang.
            </p>
        </div>
        <div class="logout-modal-footer">
            <button type="button" class="logout-btn logout-btn-cancel" id="closeCancelModal">
                Tidak
            </button>
            <button type="button" class="logout-btn logout-btn-confirm" id="confirmCancelAction">
                Ya, Batalkan
            </button>
        </div>
    </div>
</div>

{{-- Toast Notifications --}}
@php
    $s = session('success');
    $e = session('error');
    if ($s) $s = trim(str_replace(['✓','✔','×','✖'], '', $s));
    if ($e) $e = trim(str_replace(['✓','✔','×','✖'], '', $e));
@endphp

<div id="toast-container" class="gp-toast-container">
    @if($s)
        <div class="gp-toast gp-toast--success">{{ $s }}</div>
    @endif
    @if($e)
        <div class="gp-toast gp-toast--error">{{ $e }}</div>
    @endif
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

<script>
let map;
let marker;

// --- Fungsi Format Harga ---
function formatHarga(element) {
    let value = element.value;
    let number = value.replace(/[^0-9]/g, '');
    document.getElementById('harga_beli_tbs').value = number;
    let formatted = number.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    element.value = formatted;
}

document.addEventListener('DOMContentLoaded', function() {
    // 1. Format Harga Awal
    const displayInput = document.getElementById('harga_display');
    const hiddenInput = document.getElementById('harga_beli_tbs');
    if (hiddenInput && hiddenInput.value) {
        let number = hiddenInput.value;
        let formatted = number.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        displayInput.value = formatted;
    }

    // 2. Logic Modal Konfirmasi Batal
    const triggerBtn = document.getElementById('triggerCancelModal');
    const modal = document.getElementById('cancelModal');
    const closeBtn = document.getElementById('closeCancelModal');
    const confirmBtn = document.getElementById('confirmCancelAction');

    if (triggerBtn && modal) {
        triggerBtn.onclick = function() {
            modal.classList.add('show');
            document.body.style.overflow = 'hidden';
        };
        closeBtn.onclick = function() {
            modal.classList.remove('show');
            document.body.style.overflow = '';
        };
        modal.onclick = function(e) {
            if (e.target === modal) {
                modal.classList.remove('show');
                document.body.style.overflow = '';
            }
        };
        confirmBtn.onclick = function() {
            window.location.href = "{{ route('toke.beranda') }}";
        };
    }

    // 3. Preview Foto
    const existingPhotoImg = document.getElementById('photoPreviewImg');
    if (existingPhotoImg && existingPhotoImg.getAttribute('src') !== '') {
        const src = existingPhotoImg.getAttribute('src');
        if(src && src.length > 10) { 
            document.getElementById('photoPreview').style.display = 'block';
        }
    }
});

function toggleMap() {
    const mapContainer = document.getElementById('mapContainer');
    const coordinatesDisplay = document.getElementById('coordinatesDisplay');
    
    mapContainer.classList.toggle('show');
    
    if (mapContainer.classList.contains('show')) {
        setTimeout(() => {
            if (!map) {
                initMap();
            } else {
                map.invalidateSize(); 
            }
            coordinatesDisplay.style.display = 'grid';
        }, 100);
    }
}

function initMap() {
    const defaultLat = 0.5071;
    const defaultLng = 101.4478;
    const existingLat = parseFloat(document.getElementById('latitude').value);
    const existingLng = parseFloat(document.getElementById('longitude').value);
    const initialLat = (existingLat) ? existingLat : defaultLat;
    const initialLng = (existingLng) ? existingLng : defaultLng;

    map = L.map('map').setView([initialLat, initialLng], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    marker = L.marker([initialLat, initialLng], {
        draggable: true
    }).addTo(map);

    if (!existingLat && !existingLng && navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (position) => {
                const userLat = position.coords.latitude;
                const userLng = position.coords.longitude;
                map.setView([userLat, userLng], 16);
                marker.setLatLng([userLat, userLng]);
                updateCoordinates(userLat, userLng);
                reverseGeocode(userLat, userLng);
            },
            (error) => console.log("GPS Error"),
            { enableHighAccuracy: true }
        );
    }

    marker.on('dragend', function(event) {
        var position = marker.getLatLng();
        updateCoordinates(position.lat, position.lng);
        reverseGeocode(position.lat, position.lng);
    });

    map.on('click', function(event) {
        var lat = event.latlng.lat;
        var lng = event.latlng.lng;
        marker.setLatLng([lat, lng]);
        updateCoordinates(lat, lng);
        reverseGeocode(lat, lng);
    });

    const geocoder = L.Control.geocoder({ defaultMarkGeocode: false })
    .on('markgeocode', function(e) {
        var center = e.geocode.center;
        map.setView(center, 16);
        marker.setLatLng(center);
        updateCoordinates(center.lat, center.lng);
        document.getElementById('lokasi_ram').value = e.geocode.name;
    })
    .addTo(map);

    if (existingLat && existingLng) {
        updateCoordinatesDisplay(existingLat, existingLng);
    }
}

function updateCoordinates(lat, lng) {
    document.getElementById('latitude').value = lat.toFixed(8);
    document.getElementById('longitude').value = lng.toFixed(8);
    updateCoordinatesDisplay(lat, lng);
}

function updateCoordinatesDisplay(lat, lng) {
    document.getElementById('latDisplay').textContent = lat.toFixed(6);
    document.getElementById('lngDisplay').textContent = lng.toFixed(6);
}

async function reverseGeocode(lat, lng) {
    const url = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`;
    try {
        const response = await fetch(url);
        const data = await response.json();
        if (data && data.display_name) {
            document.getElementById('lokasi_ram').value = data.display_name;
        }
    } catch (error) {}
}

function previewPhoto(input) {
    const preview = document.getElementById('photoPreview');
    const previewImg = document.getElementById('photoPreviewImg');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

@endsection
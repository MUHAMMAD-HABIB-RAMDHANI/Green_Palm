@extends('layouts.app')

@section('title', 'Diagnosa Sawit')

@section('content')

<style>
    /* --- WRAPPER STYLE (Sama persis dengan penyakit.blade.php) --- */
    .diagnosa-wrapper {
        background-color: #f8f9fa;
        padding: 20px;
        font-family: 'Poppins', sans-serif;
        min-height: 85vh;
        display: flex;
        justify-content: center;
    }

    .diagnosa-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.05);
        width: 100%;
        max-width: 600px; /* Lebar konsisten dengan menu lain */
        margin: 0 auto;
        overflow: hidden;
        min-height: 80vh;
        display: flex;
        flex-direction: column;
        /* ANIMASI SLIDE UP */
        animation: slideUp 0.5s ease;
    }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* --- HEADER (Gradient Hijau) --- */
    .diagnosa-header {
        background: linear-gradient(135deg, #2b7a0b 0%, #1e5607 100%);
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
    }

    .btn-back:hover {
        background: rgba(255,255,255,0.3);
    }

    .card-title {
        font-size: 20px;
        font-weight: 700;
        margin: 0;
    }

    /* --- BODY CONTENT --- */
    .card-body {
        padding: 0; /* Reset padding agar preview full width di area tertentu */
        display: flex;
        flex-direction: column;
        flex: 1;
    }

    /* Area Preview (Hijau Muda Halus) */
    .preview-area {
        background-color: #f4fff4;
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 40px 30px;
        text-align: center;
        border-bottom: 2px dashed #c3e6cb; /* Garis putus-putus pemisah */
    }

    .placeholder-content {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .icon-placeholder {
        font-size: 50px;
        color: #2b7a0b;
        background: rgba(43, 122, 11, 0.1);
        width: 100px;
        height: 100px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
        border: 2px solid white;
        box-shadow: 0 4px 15px rgba(43, 122, 11, 0.1);
    }

    .text-placeholder {
        color: #555;
        font-size: 14px;
        line-height: 1.6;
    }

    /* Preview Gambar */
    .image-preview {
        max-width: 100%;
        max-height: 350px;
        border-radius: 15px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        display: none;
        border: 5px solid white;
    }

    /* --- ACTION AREA --- */
    .action-area {
        padding: 30px;
        background: white;
        text-align: center;
    }

    /* Tombol Utama (Gradient) */
    .btn-camera {
        background: linear-gradient(135deg, #2b7a0b 0%, #1e5607 100%);
        color: white;
        width: 100%;
        padding: 16px;
        border-radius: 15px;
        font-size: 16px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        box-shadow: 0 8px 20px rgba(43, 122, 11, 0.2);
        transition: all 0.3s ease;
    }

    .btn-camera:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 25px rgba(43, 122, 11, 0.3);
    }

    .btn-camera:active {
        transform: scale(0.98);
    }

    /* Tombol Submit (Outline) */
    .btn-submit {
        background: white;
        color: #2b7a0b;
        border: 2px solid #2b7a0b;
        margin-top: 15px;
        box-shadow: none;
    }

    .btn-submit:hover {
        background: #f4fff4;
        transform: translateY(-2px);
    }

    /* Hidden Input */
    #cameraInput { display: none; }
</style>

<div class="diagnosa-wrapper">
    <div class="diagnosa-card">
        
        {{-- Header (Sama dengan Penyakit) --}}
        <div class="diagnosa-header">
            <a href="{{ route('dashboard.penyakit') }}" class="btn-back">‹</a>
            <h1 class="card-title">Foto Masalah Sawit</h1>
        </div>

        {{-- Body Content --}}
        <div class="card-body">
            <form action="#" method="POST" enctype="multipart/form-data" id="diagnosaForm" style="display: flex; flex-direction: column; flex: 1;">
                @csrf
                
                {{-- Area Preview --}}
                <div class="preview-area">
                    <div id="placeholderState" class="placeholder-content">
                        <div class="icon-placeholder">
                            📷
                        </div>
                        <div class="text-placeholder">
                            Belum ada foto yang diambil.<br>
                            <strong>Silakan ambil foto bagian sawit yang sakit.</strong>
                        </div>
                    </div>

                    <img id="imagePreview" class="image-preview" src="" alt="Preview">
                </div>

                {{-- Area Tombol --}}
                <div class="action-area">
                    <label for="cameraInput" class="btn-camera">
                        <span>📷</span> Ambil Foto
                    </label>
                    <input type="file" id="cameraInput" name="foto_sawit" accept="image/*" capture="environment">

                    <button type="submit" class="btn-camera btn-submit" id="submitBtn" style="display: none;">
                        🚀 Analisis Penyakit
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>

<script>
    const cameraInput = document.getElementById('cameraInput');
    const imagePreview = document.getElementById('imagePreview');
    const placeholderState = document.getElementById('placeholderState');
    const submitBtn = document.getElementById('submitBtn');
    const btnLabel = document.querySelector('label[for="cameraInput"]');
    const btnIcon = btnLabel.querySelector('span');

    cameraInput.addEventListener('change', function(event) {
        const file = event.target.files[0];
        
        if (file) {
            const reader = new FileReader();

            reader.onload = function(e) {
                // Tampilkan gambar
                imagePreview.src = e.target.result;
                imagePreview.style.display = 'block';
                
                // Sembunyikan placeholder
                placeholderState.style.display = 'none';

                // Tampilkan tombol submit
                submitBtn.style.display = 'flex';
                
                // Ubah teks tombol kamera jadi 'Ambil Ulang' dengan style outline
                btnLabel.innerHTML = '🔄 Ambil Ulang Foto';
                btnLabel.style.background = '#fff';
                btnLabel.style.color = '#555';
                btnLabel.style.border = '2px solid #eee';
                btnLabel.style.boxShadow = 'none';
            }

            reader.readAsDataURL(file);
        }
    });
</script>

@endsection
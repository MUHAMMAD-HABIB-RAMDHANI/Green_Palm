{{-- CREATE: resources/views/admin/penyakit/create.blade.php --}}
@extends('admin.layouts.app')
@section('title', 'Tambah Penyakit Sawit')
@section('header-title', 'Tambah Penyakit Sawit')

@section('content')
<div class="card" style="max-width: 800px; margin: 0 auto; border-left: 5px solid #2b7a0b;">
    <h3 style="color: #1E4620; font-weight: 700;">🦠 Tambah Penyakit Sawit Baru</h3>
    
    <form action="{{ route('admin.penyakit.store') }}" method="POST" enctype="multipart/form-data" style="margin-top: 20px;">
        @csrf
        
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #1E4620;">Nama Penyakit</label>
            <input type="text" name="name" required 
                   class="custom-input"
                   placeholder="Contoh: Busuk Pangkal Batang">
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #1E4620;">Nama Latin</label>
            <input type="text" name="latin_name" 
                   class="custom-input"
                   placeholder="Contoh: Ganoderma boninense">
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #1E4620;">Foto Penyakit</label>
            <input type="file" name="image" accept="image/*" 
                   class="custom-input" style="padding: 9px;">
            <small style="color: #64748b; font-size: 12px;">Format: JPG, JPEG, PNG (Maks. 2MB)</small>
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #1E4620;">Deskripsi</label>
            <textarea name="description" rows="4" 
                      class="custom-input"
                      placeholder="Jelaskan gejala dan ciri-ciri penyakit..."></textarea>
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #1E4620;">Solusi/Penanganan</label>
            <textarea name="solution" rows="4" 
                      class="custom-input"
                      placeholder="Cara penanganan dan pencegahan..."></textarea>
        </div>

        <div style="display: flex; gap: 10px; margin-top: 30px;">
            <button type="submit" class="btn-primary">💾 Simpan Data</button>
            {{-- Tambahkan class 'confirm-exit' jika ingin menggunakan modal konfirmasi global yang saya buat sebelumnya --}}
            <a href="{{ route('admin.penyakit.index') }}" class="btn-secondary confirm-exit">← Kembali</a>
        </div>
    </form>
</div>

<style>
    /* Styling Input agar fokus berwarna Hijau */
    .custom-input {
        width: 100%;
        padding: 12px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        transition: all 0.3s ease;
        font-family: 'Poppins', sans-serif;
    }

    .custom-input:focus {
        outline: none;
        border-color: #2b7a0b; /* Hijau Utama */
        box-shadow: 0 0 0 3px rgba(43, 122, 11, 0.1); /* Glow Hijau Halus */
    }

    /* Styling Buttons */
    .btn-primary, .btn-secondary {
        padding: 12px 24px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-primary { 
        background: #2b7a0b; /* Hijau Utama */
        color: white; 
        box-shadow: 0 4px 12px rgba(43, 122, 11, 0.2);
    }
    
    .btn-primary:hover {
        background: #1E4620; /* Hijau Gelap saat Hover */
        transform: translateY(-2px);
    }

    .btn-secondary { 
        background: #f1f5f9; 
        color: #64748b; 
    }

    .btn-secondary:hover {
        background: #e2e8f0;
        color: #1e293b;
    }
</style>
@endsection
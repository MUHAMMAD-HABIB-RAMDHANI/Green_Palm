{{-- CREATE: resources/views/admin/kabar-sawit/create.blade.php --}}
@extends('admin.layouts.app')
@section('title', 'Tambah Kabar Sawit')
@section('header-title', 'Tambah Kabar Sawit')

@section('content')
<div class="card" style="max-width: 800px; margin: 0 auto; border-left: 5px solid #2b7a0b;">
    <h3 style="color: #1E4620; font-weight: 700;">📰 Tambah Berita Baru</h3>
    
    {{-- PENTING: enctype="multipart/form-data" wajib ada untuk upload gambar --}}
    <form action="{{ route('admin.kabar-sawit.store') }}" method="POST" enctype="multipart/form-data" style="margin-top: 20px;">
        @csrf
        
        {{-- Input Judul --}}
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #1E4620;">Judul Berita</label>
            <input type="text" name="title" required 
                   class="custom-input"
                   placeholder="Contoh: Harga TBS Riau Periode Minggu Ini Naik">
        </div>

        {{-- Input URL (Link Berita) --}}
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #1E4620;">Link Berita (URL)</label>
            <input type="url" name="url" required 
                   class="custom-input"
                   placeholder="https://infosawit.com/news/...">
            <small style="color: #64748b; font-size: 12px; margin-top: 5px; display: block;">
                *Masukkan link lengkap ke sumber berita asli.
            </small>
        </div>

        {{-- Input Kategori --}}
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #1E4620;">Kategori</label>
            <select name="category" required class="custom-input" style="background-color: white;">
                <option value="Umum">Umum</option>
                <option value="Tips Budidaya">Tips Budidaya</option>
                <option value="Industri">Industri</option>
                <option value="Penyakit Sawit">Penyakit Sawit</option>
            </select>
        </div>

        {{-- Input Gambar --}}
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #1E4620;">Gambar Header (Thumbnail)</label>
            <input type="file" name="image" accept="image/*" class="custom-input" style="padding: 9px;">
            <small style="color: #64748b; font-size: 12px; margin-top: 5px; display: block;">
                Format: JPG, PNG. Maksimal 2MB. (Opsional)
            </small>
        </div>

        {{-- Checkbox Populer --}}
        <div style="margin-bottom: 25px; padding: 15px; background: #f0fdf4; border-radius: 8px; border: 1px solid #dcfce7; display: flex; align-items: center; gap: 10px;">
            <input type="checkbox" name="is_popular" id="is_popular" value="1" 
                   style="width: 18px; height: 18px; accent-color: #2b7a0b; cursor: pointer;">
            <label for="is_popular" style="font-weight: 600; cursor: pointer; color: #15803d;">
                ⭐ Jadikan Berita Populer (Tampil di Slider Atas)
            </label>
        </div>

        <div style="display: flex; gap: 10px; margin-top: 30px;">
            <button type="submit" class="btn-primary">💾 Simpan Berita</button>
            <a href="{{ route('admin.kabar-sawit.index') }}" class="btn-secondary confirm-exit">← Kembali</a>
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
        font-size: 14px;
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
        font-size: 14px;
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
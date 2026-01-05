{{-- CREATE: resources/views/admin/kabar-sawit/create.blade.php --}}
@extends('admin.layouts.app')
@section('title', 'Tambah Kabar Sawit')
@section('header-title', 'Tambah Kabar Sawit')

@section('content')
<div class="card" style="max-width: 800px; margin: 0 auto;">
    <h3>📰 Tambah Berita Baru</h3>
    
    {{-- PENTING: enctype="multipart/form-data" wajib ada untuk upload gambar --}}
    <form action="{{ route('admin.kabar-sawit.store') }}" method="POST" enctype="multipart/form-data" style="margin-top: 20px;">
        @csrf
        
        {{-- Input Judul --}}
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600;">Judul Berita</label>
            <input type="text" name="title" required 
                   style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px;" 
                   placeholder="Contoh: Harga TBS Riau Periode Minggu Ini Naik">
        </div>

        {{-- Input URL (Link Berita) --}}
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600;">Link Berita (URL)</label>
            <input type="url" name="url" required 
                   style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px;" 
                   placeholder="https://infosawit.com/news/...">
            <small style="color: #888;">Masukkan link lengkap ke sumber berita asli.</small>
        </div>

        {{-- Input Kategori --}}
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600;">Kategori</label>
            <select name="category" required style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; background: white;">
                <option value="Umum">Umum</option>
                <option value="Tips Budidaya">Tips Budidaya</option>
                <option value="Industri">Industri</option>
                <option value="Penyakit Sawit">Penyakit Sawit</option>
            </select>
        </div>

        {{-- Input Gambar --}}
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600;">Gambar Header (Thumbnail)</label>
            <input type="file" name="image" accept="image/*"
                   style="width: 100%; padding: 10px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f8fafc;">
            <small style="color: #888;">Format: JPG, PNG. Maksimal 2MB. (Opsional)</small>
        </div>

        {{-- Checkbox Populer --}}
        <div style="margin-bottom: 25px; display: flex; align-items: center; gap: 10px;">
            <input type="checkbox" name="is_popular" id="is_popular" value="1" style="width: 18px; height: 18px;">
            <label for="is_popular" style="font-weight: 600; cursor: pointer;">Jadikan Berita Populer (Tampil di Slider Atas)</label>
        </div>

        <div style="display: flex; gap: 10px;">
            <button type="submit" class="btn-primary">💾 Simpan</button>
            <a href="{{ route('admin.kabar-sawit.index') }}" class="btn-secondary">← Kembali</a>
        </div>
    </form>
</div>

<style>
    .btn-primary, .btn-secondary {
        padding: 12px 24px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        border: none;
        cursor: pointer;
    }
    .btn-primary {
        background: var(--admin-secondary);
        color: white;
    }
    .btn-secondary {
        background: #e2e8f0;
        color: var(--admin-dark);
    }
</style>
@endsection
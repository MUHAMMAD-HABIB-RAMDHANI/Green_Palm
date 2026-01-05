{{-- EDIT: resources/views/admin/kabar-sawit/edit.blade.php --}}
@extends('admin.layouts.app')
@section('title', 'Edit Kabar Sawit')
@section('header-title', 'Edit Kabar Sawit')

@section('content')
<div class="card" style="max-width: 800px; margin: 0 auto;">
    <h3>✏️ Edit Berita</h3>
    
    <form action="{{ route('admin.kabar-sawit.update', $kabar->id) }}" method="POST" enctype="multipart/form-data" style="margin-top: 20px;">
        @csrf
        @method('PUT')
        
        {{-- Judul --}}
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600;">Judul Berita</label>
            <input type="text" name="title" value="{{ $kabar->title }}" required 
                   style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px;">
        </div>

        {{-- URL --}}
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600;">Link Berita (URL)</label>
            <input type="url" name="url" value="{{ $kabar->url }}" required 
                   style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px;">
        </div>

        {{-- Kategori --}}
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600;">Kategori</label>
            <select name="category" required style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; background: white;">
                <option value="Umum" {{ $kabar->category == 'Umum' ? 'selected' : '' }}>Umum</option>
                <option value="Tips Budidaya" {{ $kabar->category == 'Tips Budidaya' ? 'selected' : '' }}>Tips Budidaya</option>
                <option value="Industri" {{ $kabar->category == 'Industri' ? 'selected' : '' }}>Industri</option>
                <option value="Penyakit Sawit" {{ $kabar->category == 'Penyakit Sawit' ? 'selected' : '' }}>Penyakit Sawit</option>
            </select>
        </div>

        {{-- Gambar --}}
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600;">Gambar Header</label>
            
            @if($kabar->image)
                <div style="margin-bottom: 10px;">
                    <img src="{{ asset('storage/' . $kabar->image) }}" alt="Current Image" style="height: 100px; border-radius: 8px; object-fit: cover;">
                    <p style="font-size: 12px; color: #666; margin-top: 5px;">Gambar saat ini</p>
                </div>
            @endif

            <input type="file" name="image" accept="image/*"
                   style="width: 100%; padding: 10px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f8fafc;">
            <small style="color: #888;">Biarkan kosong jika tidak ingin mengganti gambar.</small>
        </div>

        {{-- Checkbox Populer --}}
        <div style="margin-bottom: 25px; display: flex; align-items: center; gap: 10px;">
            <input type="checkbox" name="is_popular" id="is_popular" value="1" 
                   style="width: 18px; height: 18px;" 
                   {{ $kabar->is_popular ? 'checked' : '' }}>
            <label for="is_popular" style="font-weight: 600; cursor: pointer;">Jadikan Berita Populer</label>
        </div>

        <div style="display: flex; gap: 10px;">
            <button type="submit" class="btn-primary">💾 Update</button>
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
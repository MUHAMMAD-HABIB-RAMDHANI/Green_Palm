{{-- EDIT: resources/views/admin/edukasi/edit.blade.php --}}
@extends('admin.layouts.app')
@section('title', 'Edit Video Edukasi')
@section('header-title', 'Edit Video Edukasi')

@section('content')
<div class="card" style="max-width: 800px; margin: 0 auto; border-left: 5px solid #2b7a0b;">
    <h3 style="color: #1E4620; font-weight: 700;">✏️ Edit Video Edukasi</h3>
    
    <form action="{{ route('admin.edukasi.update', $video->id) }}" method="POST" style="margin-top: 20px;">
        @csrf
        @method('PUT')
        
        {{-- Preview Video Saat Ini (Optional UI Enhancement) --}}
        <div style="margin-bottom: 25px; padding: 15px; background: #f8fafc; border-radius: 10px; border: 1px dashed #cbd5e1; display: flex; gap: 15px; align-items: center;">
            <div style="width: 120px; height: 70px; border-radius: 8px; overflow: hidden; flex-shrink: 0;">
                {{-- Asumsi Model Video memiliki aksesors thumbnail --}}
                <img src="{{ $video->thumbnail }}" alt="Thumbnail Saat Ini" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
            <div>
                <label style="font-size: 12px; color: #64748b; font-weight: 600;">Video Saat Ini:</label>
                <div style="font-size: 14px; font-weight: 600; color: #333;">{{ $video->title }}</div>
                <a href="{{ $video->url }}" target="_blank" style="font-size: 12px; color: #2b7a0b; text-decoration: none;">
                    🔗 Tes Link Video
                </a>
            </div>
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #1E4620;">Judul Video</label>
            <input type="text" name="title" value="{{ $video->title }}" required 
                   class="custom-input">
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #1E4620;">URL YouTube</label>
            <input type="url" name="url" value="{{ $video->url }}" required 
                   class="custom-input">
            <small style="color: #64748b; font-size: 12px; margin-top: 5px; display: block;">
                *Pastikan link yang dimasukkan adalah link lengkap YouTube
            </small>
        </div>

        <div style="display: flex; gap: 10px; margin-top: 30px;">
            <button type="submit" class="btn-primary">💾 Update Video</button>
            {{-- Tambahkan class 'confirm-exit' jika menggunakan Global Modal --}}
            <a href="{{ route('admin.edukasi.index') }}" class="btn-secondary confirm-exit">← Kembali</a>
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
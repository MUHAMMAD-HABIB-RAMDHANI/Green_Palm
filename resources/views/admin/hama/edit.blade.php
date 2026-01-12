{{-- EDIT: resources/views/admin/hama/edit.blade.php --}}
@extends('admin.layouts.app')
@section('title', 'Edit Hama Sawit')
@section('header-title', 'Edit Hama Sawit')

@section('content')
<div class="card" style="max-width: 800px; margin: 0 auto; border-left: 5px solid #2b7a0b;">
    <h3 style="color: #1E4620; font-weight: 700;">✏️ Edit Hama Sawit</h3>
    
    <form action="{{ route('admin.hama.update', $hama->id) }}" method="POST" enctype="multipart/form-data" style="margin-top: 20px;">
        @csrf
        @method('PUT')
        
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #1E4620;">Nama Hama</label>
            <input type="text" name="name" value="{{ $hama->name }}" required 
                   class="custom-input">
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #1E4620;">Nama Latin</label>
            <input type="text" name="latin_name" value="{{ $hama->latin_name }}" 
                   class="custom-input">
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #1E4620;">Foto Hama</label>
            
            @if($hama->image)
                <div style="margin-bottom: 10px; padding: 10px; border: 1px dashed #cbd5e1; border-radius: 8px; display: inline-block;">
                    <p style="margin: 0 0 5px 0; font-size: 12px; color: #64748b;">Foto Saat Ini:</p>
                    <img src="{{ $hama->image_url }}" alt="Current" style="width: 200px; height: 150px; object-fit: cover; border-radius: 8px;">
                </div>
            @endif

            <input type="file" name="image" accept="image/*" 
                   class="custom-input" style="padding: 9px;">
            <small style="color: #64748b; font-size: 12px; display: block; margin-top: 5px;">
                *Biarkan kosong jika tidak ingin mengubah foto (Maks. 2MB)
            </small>
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #1E4620;">Deskripsi</label>
            <textarea name="description" rows="4" 
                      class="custom-input">{{ $hama->description }}</textarea>
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #1E4620;">Solusi/Pengendalian</label>
            <textarea name="solution" rows="4" 
                      class="custom-input">{{ $hama->solution }}</textarea>
        </div>

        <div style="display: flex; gap: 10px; margin-top: 30px;">
            <button type="submit" class="btn-primary">💾 Update Data</button>
            {{-- Tambahkan class 'confirm-exit' jika menggunakan modal global --}}
            <a href="{{ route('admin.hama.index') }}" class="btn-secondary confirm-exit">← Kembali</a>
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
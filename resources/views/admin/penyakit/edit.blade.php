{{-- EDIT: resources/views/admin/penyakit/edit.blade.php --}}
@extends('admin.layouts.app')
@section('title', 'Edit Penyakit Sawit')
@section('header-title', 'Edit Penyakit Sawit')

@section('content')
<div class="card" style="max-width: 800px; margin: 0 auto;">
    <h3>✏️ Edit Penyakit Sawit</h3>
    
    <form action="{{ route('admin.penyakit.update', $penyakit->id) }}" method="POST" enctype="multipart/form-data" style="margin-top: 20px;">
        @csrf
        @method('PUT')
        
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600;">Nama Penyakit</label>
            <input type="text" name="name" value="{{ $penyakit->name }}" required 
                   style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px;">
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600;">Nama Latin</label>
            <input type="text" name="latin_name" value="{{ $penyakit->latin_name }}" 
                   style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px;">
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600;">Foto Penyakit</label>
            @if($penyakit->image)
                <img src="{{ $penyakit->image_url }}" alt="Current" style="width: 200px; height: 150px; object-fit: cover; border-radius: 8px; margin-bottom: 10px;">
            @endif
            <input type="file" name="image" accept="image/*" 
                   style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px;">
            <small style="color: #888;">Biarkan kosong jika tidak ingin mengubah foto</small>
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600;">Deskripsi</label>
            <textarea name="description" rows="4" 
                      style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px;">{{ $penyakit->description }}</textarea>
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600;">Solusi/Penanganan</label>
            <textarea name="solution" rows="4" 
                      style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px;">{{ $penyakit->solution }}</textarea>
        </div>

        <div style="display: flex; gap: 10px;">
            <button type="submit" class="btn-primary">💾 Update</button>
            <a href="{{ route('admin.penyakit.index') }}" class="btn-secondary">← Kembali</a>
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
    .btn-primary { background: var(--admin-secondary); color: white; }
    .btn-secondary { background: #e2e8f0; color: var(--admin-dark); }
</style>
@endsection
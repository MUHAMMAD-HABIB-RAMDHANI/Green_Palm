{{-- EDIT: resources/views/admin/hama/edit.blade.php --}}
@extends('admin.layouts.app')
@section('title', 'Edit Hama Sawit')
@section('header-title', 'Edit Hama Sawit')

@section('content')
<div class="card" style="max-width: 800px; margin: 0 auto;">
    <h3>✏️ Edit Hama Sawit</h3>
    
    <form action="{{ route('admin.hama.update', $hama->id) }}" method="POST" enctype="multipart/form-data" style="margin-top: 20px;">
        @csrf
        @method('PUT')
        
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600;">Nama Hama</label>
            <input type="text" name="name" value="{{ $hama->name }}" required 
                   style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px;">
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600;">Nama Latin</label>
            <input type="text" name="latin_name" value="{{ $hama->latin_name }}" 
                   style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px;">
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600;">Foto Hama</label>
            @if($hama->image)
                <img src="{{ $hama->image_url }}" alt="Current" style="width: 200px; height: 150px; object-fit: cover; border-radius: 8px; margin-bottom: 10px;">
            @endif
            <input type="file" name="image" accept="image/*" 
                   style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px;">
            <small style="color: #888;">Biarkan kosong jika tidak ingin mengubah foto</small>
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600;">Deskripsi</label>
            <textarea name="description" rows="4" 
                      style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px;">{{ $hama->description }}</textarea>
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600;">Solusi/Pengendalian</label>
            <textarea name="solution" rows="4" 
                      style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px;">{{ $hama->solution }}</textarea>
        </div>

        <div style="display: flex; gap: 10px;">
            <button type="submit" class="btn-primary">💾 Update</button>
            <a href="{{ route('admin.hama.index') }}" class="btn-secondary">← Kembali</a>
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
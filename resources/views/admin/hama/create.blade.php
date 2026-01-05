{{-- CREATE: resources/views/admin/hama/create.blade.php --}}
@extends('admin.layouts.app')
@section('title', 'Tambah Hama Sawit')
@section('header-title', 'Tambah Hama Sawit')

@section('content')
<div class="card" style="max-width: 800px; margin: 0 auto;">
    <h3>🐛 Tambah Hama Sawit Baru</h3>
    
    <form action="{{ route('admin.hama.store') }}" method="POST" enctype="multipart/form-data" style="margin-top: 20px;">
        @csrf
        
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600;">Nama Hama</label>
            <input type="text" name="name" required 
                   style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px;" 
                   placeholder="Contoh: Ulat Api">
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600;">Nama Latin</label>
            <input type="text" name="latin_name" 
                   style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px;" 
                   placeholder="Contoh: Setothosea asigna">
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600;">Foto Hama</label>
            <input type="file" name="image" accept="image/*" 
                   style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px;">
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600;">Deskripsi</label>
            <textarea name="description" rows="4" 
                      style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px;" 
                      placeholder="Jelaskan ciri-ciri dan dampak hama..."></textarea>
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600;">Solusi/Pengendalian</label>
            <textarea name="solution" rows="4" 
                      style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px;" 
                      placeholder="Cara pengendalian dan pencegahan..."></textarea>
        </div>

        <div style="display: flex; gap: 10px;">
            <button type="submit" class="btn-primary">💾 Simpan</button>
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
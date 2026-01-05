{{-- CREATE: resources/views/admin/harga-sawit/create.blade.php --}}
@extends('admin.layouts.app')
@section('title', 'Tambah Harga Sawit')
@section('header-title', 'Tambah Harga Sawit')

@section('content')
<div class="card" style="max-width: 800px; margin: 0 auto;">
    <h3>💰 Tambah Harga Sawit Baru</h3>
    
    <form action="{{ route('admin.harga-sawit.store') }}" method="POST" style="margin-top: 20px;">
        @csrf
        
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600;">Daerah</label>
            <input type="text" name="region" required 
                   style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px;" 
                   placeholder="Contoh: Riau, Sumatera Utara">
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600;">Harga per Kg (Rp)</label>
            <input type="number" name="price" required step="0.01" min="0"
                   style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px;" 
                   placeholder="Contoh: 3500">
        </div>

        <div style="display: flex; gap: 10px;">
            <button type="submit" class="btn-primary">💾 Simpan</button>
            <a href="{{ route('admin.harga-sawit.index') }}" class="btn-secondary">← Kembali</a>
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
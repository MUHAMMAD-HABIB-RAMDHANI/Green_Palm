{{-- EDIT: resources/views/admin/harga-sawit/edit.blade.php --}}
@extends('admin.layouts.app')
@section('title', 'Edit Harga Sawit')
@section('header-title', 'Edit Harga Sawit')

@section('content')
<div class="card" style="max-width: 800px; margin: 0 auto;">
    <h3>✏️ Edit Harga Sawit</h3>
    
    <form action="{{ route('admin.harga-sawit.update', $price->id) }}" method="POST" style="margin-top: 20px;">
        @csrf
        @method('PUT')
        
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600;">Daerah</label>
            <input type="text" name="region" value="{{ $price->region }}" required 
                   style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px;">
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600;">Harga per Kg (Rp)</label>
            <input type="number" name="price" value="{{ $price->price }}" required step="0.01" min="0"
                   style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px;">
        </div>

        <div style="display: flex; gap: 10px;">
            <button type="submit" class="btn-primary">💾 Update</button>
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
{{-- INDEX: resources/views/admin/hama/index.blade.php --}}
@extends('admin.layouts.app')
@section('title', 'Kelola Hama Sawit')
@section('header-title', 'Kelola Hama Sawit')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3>🐛 Daftar Hama Sawit</h3>
        <a href="{{ route('admin.hama.create') }}" class="btn-primary">+ Tambah Hama</a>
    </div>

    @if($hamas->isEmpty())
        <p style="text-align: center; padding: 40px; color: #888;">Belum ada data hama sawit.</p>
    @else
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
            @foreach($hamas as $hama)
            <div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                <img src="{{ $hama->image_url }}" alt="{{ $hama->name }}" 
                     style="width: 100%; height: 200px; object-fit: cover;">
                
                <div style="padding: 16px;">
                    <h4 style="margin: 0 0 8px; font-size: 18px; color: var(--admin-dark);">{{ $hama->name }}</h4>
                    <p style="margin: 0 0 12px; font-size: 13px; color: #888; font-style: italic;">{{ $hama->latin_name }}</p>
                    <p style="margin: 0 0 16px; font-size: 14px; color: #666; line-height: 1.5;">
                        {{ Str::limit($hama->description, 100) }}
                    </p>
                    
                    <div style="display: flex; gap: 10px;">
                        <a href="{{ route('admin.hama.edit', $hama->id) }}" 
                           style="flex: 1; padding: 8px; background: #3b82f6; color: white; text-align: center; border-radius: 6px; text-decoration: none; font-size: 14px;">
                            ✏️ Edit
                        </a>
                        <form action="{{ route('admin.hama.destroy', $hama->id) }}" method="POST" style="flex: 1;" onsubmit="return confirm('Hapus data ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    style="width: 100%; padding: 8px; background: #ef4444; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 14px;">
                                🗑️ Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div style="margin-top: 20px;">
            {{ $hamas->links() }}
        </div>
    @endif
</div>

<style>
    .btn-primary {
        padding: 10px 20px;
        background: var(--admin-secondary);
        color: white;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
    }
</style>
@endsection
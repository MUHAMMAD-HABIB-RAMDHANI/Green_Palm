{{-- INDEX: resources/views/admin/kabar-sawit/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Kelola Kabar Sawit')
@section('header-title', 'Kelola Kabar Sawit')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3>📰 Daftar Berita</h3>
        <a href="{{ route('admin.kabar-sawit.create') }}" class="btn-primary">+ Tambah Berita</a>
    </div>

    @if($kabarSawits->isEmpty())
        <div style="text-align: center; padding: 50px; color: #888;">
            <p style="font-size: 50px; margin-bottom: 10px;">📭</p>
            <p>Belum ada berita yang ditambahkan.</p>
        </div>
    @else
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                    <th style="padding: 12px; text-align: center; width: 80px;">Gambar</th>
                    <th style="padding: 12px; text-align: left;">Judul & URL</th>
                    <th style="padding: 12px; text-align: left;">Kategori</th>
                    <th style="padding: 12px; text-align: center;">Status</th>
                    <th style="padding: 12px; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kabarSawits as $item)
                <tr style="border-bottom: 1px solid #e2e8f0;">
                    {{-- Kolom Gambar --}}
                    <td style="padding: 12px; text-align: center;">
                        @if($item->image)
                            <img src="{{ asset('storage/' . $item->image) }}" 
                                 alt="Thumb" 
                                 style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px; border: 1px solid #ddd;">
                        @else
                            <div style="width: 60px; height: 60px; background: #eee; border-radius: 8px; display: flex; align-items: center; justify-content: center; margin: 0 auto; color: #aaa;">
                                No IMG
                            </div>
                        @endif
                    </td>

                    {{-- Kolom Judul & URL --}}
                    <td style="padding: 12px;">
                        <div style="font-weight: 600; color: #2d3748; margin-bottom: 4px;">
                            {{ $item->title }}
                        </div>
                        <a href="{{ $item->url }}" target="_blank" style="font-size: 12px; color: #3b82f6; text-decoration: none;">
                            🔗 {{ Str::limit($item->url, 40) }} ↗
                        </a>
                        <div style="font-size: 11px; color: #888; margin-top: 4px;">
                            📅 {{ \Carbon\Carbon::parse($item->published_at)->format('d M Y') }}
                        </div>
                    </td>

                    {{-- Kolom Kategori --}}
                    <td style="padding: 12px;">
                        <span style="background: #e6f1e3; color: #1E4620; padding: 4px 10px; border-radius: 15px; font-size: 12px; font-weight: 500;">
                            {{ $item->category }}
                        </span>
                    </td>

                    {{-- Kolom Status Populer --}}
                    <td style="padding: 12px; text-align: center;">
                        @if($item->is_popular)
                            <span style="background: #fff7ed; color: #c2410c; border: 1px solid #ffedd5; padding: 4px 8px; border-radius: 6px; font-size: 11px; font-weight: 600;">
                                ★ Populer
                            </span>
                        @else
                            <span style="color: #94a3b8; font-size: 11px;">Regular</span>
                        @endif
                    </td>

                    {{-- Kolom Aksi --}}
                    <td style="padding: 12px; text-align: center;">
                        <div style="display: flex; justify-content: center; gap: 10px;">
                            <a href="{{ route('admin.kabar-sawit.edit', $item->id) }}" title="Edit" style="text-decoration: none; font-size: 18px;">
                                ✏️
                            </a>
                            
                            <form action="{{ route('admin.kabar-sawit.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus berita ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" title="Hapus" style="background: none; border: none; cursor: pointer; font-size: 18px;">
                                    🗑️
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Pagination --}}
        <div style="margin-top: 20px;">
            {{ $kabarSawits->links() }}
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
        transition: background 0.3s;
    }
    .btn-primary:hover {
        opacity: 0.9;
    }
</style>
@endsection
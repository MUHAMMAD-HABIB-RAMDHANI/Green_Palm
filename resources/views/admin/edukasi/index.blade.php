{{-- INDEX: resources/views/admin/edukasi/index.blade.php --}}
@extends('admin.layouts.app')
@section('title', 'Kelola Video Edukasi')
@section('header-title', 'Kelola Video Edukasi')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3>📚 Daftar Video Edukasi</h3>
        <a href="{{ route('admin.edukasi.create') }}" class="btn-primary">+ Tambah Video</a>
    </div>

    @if($videos->isEmpty())
        <p style="text-align: center; padding: 40px; color: #888;">Belum ada video edukasi.</p>
    @else
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                    <th style="padding: 12px; text-align: left;">Thumbnail</th>
                    <th style="padding: 12px; text-align: left;">Judul</th>
                    <th style="padding: 12px; text-align: left;">URL</th>
                    <th style="padding: 12px; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($videos as $video)
                <tr style="border-bottom: 1px solid #e2e8f0;">
                    <td style="padding: 12px;">
                        <img src="{{ $video->thumbnail }}" alt="Thumbnail" style="width: 120px; height: 70px; object-fit: cover; border-radius: 8px;">
                    </td>
                    <td style="padding: 12px;">{{ $video->title }}</td>
                    <td style="padding: 12px;">
                        <a href="{{ $video->url }}" target="_blank" style="color: #3b82f6; text-decoration: none;">Lihat Video</a>
                    </td>
                    <td style="padding: 12px; text-align: center;">
                        <a href="{{ route('admin.edukasi.edit', $video->id) }}" style="color: #3b82f6; margin-right: 10px;">✏️ Edit</a>
                        <form action="{{ route('admin.edukasi.destroy', $video->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Hapus video ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background: none; border: none; color: #ef4444; cursor: pointer;">🗑️ Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div style="margin-top: 20px;">
            {{ $videos->links() }}
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
        transition: all 0.3s ease;
    }
    .btn-primary:hover {
        background: var(--admin-primary);
    }
</style>
@endsection
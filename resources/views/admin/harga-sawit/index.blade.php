{{-- INDEX: resources/views/admin/harga-sawit/index.blade.php --}}
@extends('admin.layouts.app')
@section('title', 'Kelola Harga Sawit')
@section('header-title', 'Kelola Harga Sawit')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3>💰 Daftar Harga Sawit</h3>
        <a href="{{ route('admin.harga-sawit.create') }}" class="btn-primary">+ Tambah Harga</a>
    </div>

    @if($prices->isEmpty())
        <p style="text-align: center; padding: 40px; color: #888;">Belum ada data harga sawit.</p>
    @else
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                    <th style="padding: 12px; text-align: left;">Daerah</th>
                    <th style="padding: 12px; text-align: left;">Harga (Rp/Kg)</th>
                    <th style="padding: 12px; text-align: left;">Terakhir Update</th>
                    <th style="padding: 12px; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($prices as $price)
                <tr style="border-bottom: 1px solid #e2e8f0;">
                    <td style="padding: 12px;">{{ $price->region }}</td>
                    <td style="padding: 12px; font-weight: 600; color: #2b7a0b;">Rp {{ number_format($price->price, 0, ',', '.') }}</td>
                    <td style="padding: 12px;">{{ $price->updated_at->format('d M Y, H:i') }}</td>
                    <td style="padding: 12px; text-align: center;">
                        <a href="{{ route('admin.harga-sawit.edit', $price->id) }}" style="color: #3b82f6; margin-right: 10px;">✏️ Edit</a>
                        <form action="{{ route('admin.harga-sawit.destroy', $price->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Hapus data ini?')">
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
            {{ $prices->links() }}
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
{{-- INDEX: resources/views/admin/hama/index.blade.php --}}
@extends('admin.layouts.app')
@section('title', 'Kelola Hama Sawit')
@section('header-title', 'Kelola Hama Sawit')

@section('content')
<div class="card" style="border: none; background: transparent; box-shadow: none; padding: 0;">
    
    {{-- Tombol Kembali ke Menu Update --}}
    <div style="margin-bottom: 20px;">
        <a href="{{ route('admin.update-data') }}" class="btn-back">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 12H5M12 19l-7-7 7-7"/>
            </svg>
            <span>Kembali ke Menu Update</span>
        </a>
    </div>

    {{-- Header Section --}}
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; background: white; padding: 20px; border-radius: 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
        <div>
            <h3 style="margin: 0; color: #1E4620; font-weight: 700; font-size: 20px;">🐛 Daftar Hama Sawit</h3>
            <p style="margin: 5px 0 0; color: #64748b; font-size: 14px;">Kelola data hama dan cara pengendaliannya</p>
        </div>
        <a href="{{ route('admin.hama.create') }}" class="btn-add">
            + Tambah Hama
        </a>
    </div>

    @if($hamas->isEmpty())
        <div style="text-align: center; padding: 60px; background: white; border-radius: 16px; border: 2px dashed #cbd5e1;">
            <div style="font-size: 48px; margin-bottom: 15px; opacity: 0.5;">🦗</div>
            <h4 style="color: #1E4620; margin-bottom: 8px;">Belum ada data hama</h4>
            <p style="color: #64748b;">Silakan tambahkan data hama sawit baru.</p>
        </div>
    @else
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 25px;">
            @foreach($hamas as $hama)
            <div class="hama-card">
                {{-- Image Container --}}
                <div style="position: relative;">
                    <img src="{{ $hama->image_url }}" alt="{{ $hama->name }}" 
                         style="width: 100%; height: 200px; object-fit: cover;">
                    {{-- Latin Name Badge --}}
                    <div style="position: absolute; top: 10px; right: 10px; background: rgba(0,0,0,0.6); color: white; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-style: italic;">
                        {{ $hama->latin_name }}
                    </div>
                </div>
                
                <div style="padding: 20px;">
                    <h4 style="margin: 0 0 10px; font-size: 18px; color: #1E4620; font-weight: 700;">
                        {{ $hama->name }}
                    </h4>
                    
                    <p style="margin: 0 0 20px; font-size: 14px; color: #64748b; line-height: 1.6; min-height: 45px;">
                        {{ Str::limit($hama->description, 90) }}
                    </p>
                    
                    <div style="display: flex; gap: 10px; border-top: 1px solid #f1f5f9; padding-top: 15px;">
                        {{-- Edit Button --}}
                        <a href="{{ route('admin.hama.edit', $hama->id) }}" class="btn-action btn-edit">
                            ✏️ Edit
                        </a>

                        {{-- Delete Button (Trigger Global Modal) --}}
                        <button type="button" 
                                class="btn-action btn-delete confirm-delete"
                                data-action="{{ route('admin.hama.destroy', $hama->id) }}"
                                data-name="{{ $hama->name }}">
                            🗑️ Hapus
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div style="margin-top: 30px;">
            {{ $hamas->links() }}
        </div>
    @endif
</div>

<style>
    /* Tombol Kembali Styling (Green Palm Theme) */
    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        background-color: #f0fdf4; /* Hijau Sangat Muda */
        color: #166534; /* Hijau Tua */
        border: 1px solid #bbf7d0;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0,0,0,0.03);
    }

    .btn-back:hover {
        background-color: #dcfce7;
        border-color: #2b7a0b;
        color: #14532d;
        transform: translateX(-4px);
        box-shadow: 0 4px 10px rgba(43, 122, 11, 0.15);
    }

    .btn-back svg {
        transition: transform 0.3s ease;
    }

    .btn-back:hover svg {
        transform: translateX(-3px);
    }

    /* Card Styling */
    .hama-card {
        background: #fff;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: 1px solid #f1f5f9;
        display: flex;
        flex-direction: column;
    }

    .hama-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(43, 122, 11, 0.15);
        border-color: #bbf7d0;
    }

    /* Add Button */
    .btn-add {
        padding: 12px 24px;
        background: linear-gradient(135deg, #2b7a0b 0%, #1E4620 100%);
        color: white;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        box-shadow: 0 4px 10px rgba(43, 122, 11, 0.3);
        transition: all 0.3s ease;
    }
    
    .btn-add:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(43, 122, 11, 0.4);
    }

    /* Action Buttons */
    .btn-action {
        flex: 1;
        padding: 10px;
        border-radius: 8px;
        text-align: center;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        border: none;
        transition: all 0.2s;
    }

    .btn-edit {
        background: #f0fdf4;
        color: #15803d;
        border: 1px solid #dcfce7;
    }
    
    .btn-edit:hover {
        background: #2b7a0b;
        color: white;
        border-color: #2b7a0b;
    }

    .btn-delete {
        background: #fef2f2;
        color: #b91c1c;
        border: 1px solid #fee2e2;
    }
    
    .btn-delete:hover {
        background: #ef4444;
        color: white;
        border-color: #ef4444;
    }
</style>
@endsection
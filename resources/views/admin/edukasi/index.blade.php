{{-- INDEX: resources/views/admin/edukasi/index.blade.php --}}
@extends('admin.layouts.app')
@section('title', 'Kelola Video Edukasi')
@section('header-title', 'Kelola Video Edukasi')

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
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; background: white; padding: 20px; border-radius: 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); flex-wrap: wrap; gap: 15px;">
        <div>
            <h3 style="margin: 0; color: #1E4620; font-weight: 700; font-size: 20px;">📚 Daftar Video Edukasi</h3>
            <p style="margin: 5px 0 0; color: #64748b; font-size: 14px;">Kelola materi edukasi untuk petani</p>
        </div>
        <a href="{{ route('admin.edukasi.create') }}" class="btn-add">
            + Tambah Video
        </a>
    </div>

    @if($videos->isEmpty())
        <div style="text-align: center; padding: 60px; background: white; border-radius: 16px; border: 2px dashed #cbd5e1;">
            <div style="font-size: 48px; margin-bottom: 15px; opacity: 0.5;">🎥</div>
            <h4 style="color: #1E4620; margin-bottom: 8px;">Belum ada video edukasi</h4>
            <p style="color: #64748b;">Silakan tambahkan video baru untuk edukasi petani.</p>
        </div>
    @else
        <div class="card" style="padding: 0; overflow: hidden; border-radius: 16px; border: 1px solid #f1f5f9;">
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; min-width: 800px;">
                    <thead>
                        <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                            <th style="padding: 18px 24px; text-align: left; color: #1E4620; font-weight: 700; width: 180px;">Thumbnail</th>
                            <th style="padding: 18px 24px; text-align: left; color: #1E4620; font-weight: 700;">Judul & Info</th>
                            <th style="padding: 18px 24px; text-align: center; color: #1E4620; font-weight: 700; width: 150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($videos as $video)
                        <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s ease;" 
                            onmouseover="this.style.background='#f0fdf4'" 
                            onmouseout="this.style.background='white'">
                            
                            {{-- Thumbnail Column --}}
                            <td style="padding: 20px 24px;">
                                <div style="position: relative; width: 140px; height: 80px; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                                    <img src="{{ $video->thumbnail }}" alt="Thumbnail" style="width: 100%; height: 100%; object-fit: cover;">
                                    <div style="position: absolute; inset: 0; background: rgba(0,0,0,0.2); display: flex; align-items: center; justify-content: center;">
                                        <span style="font-size: 24px; color: white; opacity: 0.8;">▶️</span>
                                    </div>
                                </div>
                            </td>
                            
                            {{-- Info Column --}}
                            <td style="padding: 20px 24px;">
                                <h4 style="font-size: 16px; font-weight: 600; color: #1e293b; margin: 0 0 8px 0; line-height: 1.4;">
                                    {{ Str::limit($video->title, 60) }}
                                </h4>
                                
                                <div style="display: flex; align-items: center; gap: 15px; flex-wrap: wrap;">
                                    <span style="display: flex; align-items: center; gap: 5px; font-size: 13px; color: #64748b; background: #f1f5f9; padding: 4px 10px; border-radius: 20px;">
                                        🗓️ {{ $video->created_at->format('d M Y') }}
                                    </span>
                                    
                                    <a href="{{ $video->url }}" target="_blank" class="link-external">
                                        🔗 Buka Link <span style="font-size: 10px; margin-left: 2px;">↗</span>
                                    </a>
                                </div>
                            </td>
                            
                            {{-- Action Column --}}
                            <td style="padding: 20px 24px;">
                                <div class="action-group">
                                    <a href="{{ route('admin.edukasi.edit', $video->id) }}" class="btn-action edit" title="Edit Data">
                                        ✏️
                                    </a>
                                    
                                    {{-- Global Modal Trigger --}}
                                    <button type="button" 
                                            class="btn-action delete confirm-delete"
                                            data-action="{{ route('admin.edukasi.destroy', $video->id) }}"
                                            data-name="{{ $video->title }}"
                                            title="Hapus Data">
                                        🗑️
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div style="margin-top: 25px;">
            {{ $videos->links() }}
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

    /* Button Add */
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
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-add:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(43, 122, 11, 0.4);
    }

    /* Link External Styling */
    .link-external {
        font-size: 13px; 
        color: #0369a1; 
        text-decoration: none; 
        font-weight: 500;
        transition: 0.2s;
        display: inline-flex;
        align-items: center;
    }
    .link-external:hover {
        color: #0ea5e9;
        text-decoration: underline;
    }

    /* Action Buttons Grouping */
    .action-group {
        display: flex;
        justify-content: center;
        gap: 8px;
        background: #f8fafc;
        padding: 6px;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        width: fit-content;
        margin: 0 auto;
    }

    .btn-action {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        text-decoration: none;
        font-size: 16px;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    /* Edit Button Style */
    .btn-action.edit {
        background: white;
        color: #ca8a04;
        border: 1px solid #e2e8f0;
    }
    .btn-action.edit:hover {
        background: #fef08a;
        border-color: #fde047;
        transform: translateY(-2px);
    }

    /* Delete Button Style */
    .btn-action.delete {
        background: white;
        color: #dc2626;
        border: 1px solid #e2e8f0;
    }
    .btn-action.delete:hover {
        background: #fee2e2;
        border-color: #fca5a5;
        transform: translateY(-2px);
    }

    /* Responsive Adjustments */
    @media (max-width: 640px) {
        .btn-add {
            width: 100%;
            justify-content: center;
        }
        
        .action-group {
            flex-direction: row;
        }
    }
</style>
@endsection
@extends('admin.layouts.app')

@section('title', 'Update Data')
@section('header-title', 'Update Data')

@section('content')
<style>
    .update-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 25px;
        margin-top: 20px;
    }

    .update-card {
        background: linear-gradient(135deg, #fff 0%, #f8fafc 100%);
        border-radius: 16px;
        padding: 30px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        border-left: 5px solid var(--admin-secondary);
        transition: all 0.3s ease;
        text-decoration: none;
        display: block;
    }

    .update-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 28px rgba(0, 0, 0, 0.15);
        border-left-color: var(--admin-accent);
    }

    .update-icon {
        width: 70px;
        height: 70px;
        background: linear-gradient(135deg, var(--admin-secondary), var(--admin-primary));
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 36px;
        margin-bottom: 20px;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .update-title {
        font-size: 20px;
        font-weight: 700;
        color: var(--admin-dark);
        margin-bottom: 10px;
    }

    .update-desc {
        font-size: 14px;
        color: var(--text-light);
        line-height: 1.6;
    }

    @media (max-width: 768px) {
        .update-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="card">
    <h3>📝 Pilih Data yang Ingin Dikelola</h3>
    <p>Pilih salah satu menu di bawah untuk mengelola data edukasi, berita, harga, penyakit, atau hama.</p>

    <div class="update-grid">
        
        {{-- MENU BARU: Kabar Sawit --}}
        <a href="{{ route('admin.kabar-sawit.index') }}" class="update-card">
            <div class="update-icon">📰</div>
            <div class="update-title">Kabar Sawit</div>
            <div class="update-desc">Kelola link berita, artikel populer, dan informasi terbaru</div>
        </a>

        <a href="{{ route('admin.edukasi.index') }}" class="update-card">
            <div class="update-icon">📚</div>
            <div class="update-title">Video Edukasi</div>
            <div class="update-desc">Kelola video edukasi pembudidayaan kelapa sawit untuk petani</div>
        </a>

        <a href="{{ route('admin.harga-sawit.index') }}" class="update-card">
            <div class="update-icon">💰</div>
            <div class="update-title">Harga Sawit</div>
            <div class="update-desc">Update harga sawit per daerah untuk referensi petani</div>
        </a>

        <a href="{{ route('admin.penyakit.index') }}" class="update-card">
            <div class="update-icon">🦠</div>
            <div class="update-title">Penyakit Sawit</div>
            <div class="update-desc">Kelola database penyakit kelapa sawit beserta solusinya</div>
        </a>

        <a href="{{ route('admin.hama.index') }}" class="update-card">
            <div class="update-icon">🐛</div>
            <div class="update-title">Hama Sawit</div>
            <div class="update-desc">Kelola database hama kelapa sawit beserta cara penanganannya</div>
        </a>
    </div>
</div>
@endsection
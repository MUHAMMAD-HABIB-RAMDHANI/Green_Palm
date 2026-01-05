@extends('layouts.app')

@section('title', 'Upgrade Premium')

@section('content')

<style>
    :root {
        --primary-green: #2b7a0b;
        --dark-green: #1e5607;
        --gold: #ffd700;
    }

    .premium-wrapper {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        min-height: 100vh;
        padding: 20px 30px 40px;
    }

    .premium-container {
        max-width: 1000px;
        margin: 0 auto;
    }

    /* Header */
    .premium-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .premium-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: linear-gradient(135deg, var(--gold) 0%, #ffed4e 100%);
        color: #222;
        padding: 8px 20px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 14px;
        margin-bottom: 15px;
        box-shadow: 0 4px 15px rgba(255, 215, 0, 0.3);
    }

    .premium-title {
        font-size: 32px;
        font-weight: 800;
        color: var(--primary-green);
        margin-bottom: 10px;
    }

    .premium-subtitle {
        font-size: 16px;
        color: #666;
    }

    /* --- PERUBAHAN CSS DI SINI --- */

    /* Grid Layout untuk Gambar Paket (DIUBAH MENJADI VERTIKAL) */
    .pricing-grid {
        display: grid;
        /* Menggunakan 1 kolom penuh agar item tersusun atas-bawah */
        grid-template-columns: 1fr; 
        gap: 50px; /* Jarak antar paket diperbesar */
        margin-bottom: 50px;
        justify-items: center; /* Pusatkan gambar secara horizontal */
    }

    /* Wrapper Gambar Paket (DIUBAH UKURANNYA) */
    .package-item {
        position: relative;
        cursor: pointer;
        border-radius: 25px; /* Radius sudut diperbesar sedikit */
        overflow: hidden;
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        /* Max-width diperbesar signifikan agar gambar besar (misal 900px) */
        max-width: 900px; 
        width: 100%;
    }

    /* --- AKHIR PERUBAHAN CSS --- */

    .package-item:hover {
        transform: translateY(-10px);
        box-shadow: 0 25px 50px rgba(43, 122, 11, 0.25);
    }

    .package-img {
        width: 100%;
        height: auto;
        display: block;
    }

    /* Overlay Loading saat diklik */
    .loading-overlay {
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(43, 122, 11, 0.85); /* Hijau transparan */
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        color: white;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
        z-index: 10;
    }

    .package-item.processing .loading-overlay {
        opacity: 1;
        visibility: visible;
    }

    /* Spinner sederhana */
    .spinner {
        width: 50px; height: 50px; /* Spinner diperbesar sedikit */
        border: 5px solid rgba(255,255,255,0.3);
        border-radius: 50%;
        border-top-color: #fff;
        animation: spin 1s ease-in-out infinite;
        margin-bottom: 15px;
    }

    @keyframes spin { to { transform: rotate(360deg); } }

    /* Status Disabled jika sudah premium */
    .package-item.disabled {
        filter: grayscale(100%);
        cursor: not-allowed;
        opacity: 0.7;
    }
    .package-item.disabled:hover {
        transform: none;
        box-shadow: none;
    }

    /* Current Premium Status Box */
    .current-premium {
        background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        border: 2px solid #28a745;
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 30px;
        text-align: center;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .premium-wrapper { padding: 15px; }
        .premium-title { font-size: 26px; }
        /* Di mobile, gap diperkecil */
        .pricing-grid { gap: 30px; }
    }
</style>

<div class="premium-wrapper">
    <div class="premium-container">
        
        {{-- Header --}}
        <div class="premium-header">
            <div class="premium-badge">
                <span>👑</span>
                <span>UPGRADE PREMIUM</span>
            </div>
            <h1 class="premium-title">Pilih Paket Premium</h1>
            <p class="premium-subtitle">Investasi terbaik untuk produktivitas kebun Anda</p>
        </div>

        {{-- Status Jika Sudah Premium --}}
        @if($user->isPremium())
        <div class="current-premium">
            <h3>✨ Anda Sudah Menjadi Member Premium</h3>
            <p style="margin: 0; color: #155724;">
                Aktif hingga: <strong>{{ $user->premium_until->format('d F Y') }}</strong>
                <br>
                <small>({{ $user->getRemainingPremiumDays() }} hari lagi)</small>
            </p>
        </div>
        @endif

        {{-- Grid Gambar Paket (Vertikal) --}}
        <div class="pricing-grid">
            
            <div class="package-item {{ $user->isPremium() ? 'disabled' : '' }}" 
                 onclick="{{ $user->isPremium() ? '' : "subscribe('monthly', this)" }}">
                
                {{-- Pastikan file ini ada: public/images/paket 1.png --}}
                <img src="{{ asset('images/paket 1.png') }}" alt="Paket Bulanan" class="package-img">
                
                {{-- Overlay Loading --}}
                <div class="loading-overlay">
                    <div class="spinner"></div>
                    <strong style="font-size: 18px;">Memproses Paket Bulanan...</strong>
                </div>
            </div>

            <div class="package-item {{ $user->isPremium() ? 'disabled' : '' }}" 
                 onclick="{{ $user->isPremium() ? '' : "subscribe('yearly', this)" }}">
                
                {{-- Pastikan file ini ada: public/images/paket 2.png --}}
                <img src="{{ asset('images/paket 2.png') }}" alt="Paket Tahunan" class="package-img">
                
                {{-- Overlay Loading --}}
                <div class="loading-overlay">
                    <div class="spinner"></div>
                    <strong style="font-size: 18px;">Memproses Paket Tahunan...</strong>
                </div>
            </div>

        </div>

        {{-- Back Button --}}
        <div style="text-align: center; margin-top: 30px; padding-bottom: 20px;">
            <a href="{{ route('dashboard.beranda') }}" style="color: #666; text-decoration: none; font-weight: 600; padding: 10px 20px; border-radius: 30px; background: rgba(0,0,0,0.05); transition: background 0.3s;">
                ← Kembali ke Beranda
            </a>
        </div>

    </div>
</div>

{{-- Midtrans Snap Script --}}
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
<script>
    function subscribe(plan, element) {
        if (element.classList.contains('disabled') || element.classList.contains('processing')) {
            return;
        }

        element.classList.add('processing');

        fetch('{{ route("payment.create") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ plan: plan })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                snap.pay(data.snap_token, {
                    onSuccess: function(result) {
                        window.location.href = '{{ route("payment.finish") }}?order_id=' + data.order_id;
                    },
                    onPending: function(result) {
                        alert('Menunggu pembayaran Anda');
                        window.location.href = '{{ route("payment.history") }}';
                    },
                    onError: function(result) {
                        alert('Pembayaran gagal, silakan coba lagi');
                        element.classList.remove('processing');
                    },
                    onClose: function() {
                        element.classList.remove('processing');
                    }
                });
            } else {
                alert('Gagal membuat transaksi: ' + data.message);
                element.classList.remove('processing');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan, silakan coba lagi');
            element.classList.remove('processing');
        });
    }
</script>

@endsection
@extends('layouts.app')

@section('title', 'Status Pembayaran')

@section('content')

<style>
    .finish-wrapper {
        background: #f8f9fa;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .finish-container {
        background: white;
        border-radius: 20px;
        padding: 50px;
        max-width: 600px;
        text-align: center;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
    }

    .status-icon {
        font-size: 80px;
        margin-bottom: 20px;
        animation: bounce 1s ease-in-out;
    }

    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-20px); }
    }

    .status-title {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 15px;
    }

    .status-title.success { color: #28a745; }
    .status-title.pending { color: #ffc107; }
    .status-title.failed { color: #dc3545; }

    .status-message {
        color: #666;
        font-size: 16px;
        margin-bottom: 30px;
    }

    .transaction-info {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 30px;
        text-align: left;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid #e0e0e0;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-label {
        color: #666;
        font-weight: 500;
    }

    .info-value {
        color: #222;
        font-weight: 600;
    }

    .btn-action {
        padding: 15px 40px;
        background: linear-gradient(135deg, #2b7a0b 0%, #1e5607 100%);
        color: white;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        font-size: 16px;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s ease;
        margin: 5px;
    }

    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(43, 122, 11, 0.4);
        color: white;
    }

    .btn-secondary {
        background: #6c757d;
    }
</style>

<div class="finish-wrapper">
    <div class="finish-container">
        
        @if($transaction->isSuccess())
            <div class="status-icon">🎉</div>
            <h1 class="status-title success">Pembayaran Berhasil!</h1>
            <p class="status-message">
                Selamat! Akun Anda sekarang telah upgrade ke Premium.<br>
                Nikmati semua fitur eksklusif yang tersedia.
            </p>
        @elseif($transaction->isPending())
            <div class="status-icon">⏳</div>
            <h1 class="status-title pending">Menunggu Pembayaran</h1>
            <p class="status-message">
                Transaksi Anda sedang diproses.<br>
                Silakan selesaikan pembayaran Anda.
            </p>
        @else
            <div class="status-icon">❌</div>
            <h1 class="status-title failed">Pembayaran Gagal</h1>
            <p class="status-message">
                Maaf, pembayaran Anda tidak dapat diproses.<br>
                Silakan coba lagi.
            </p>
        @endif

        <div class="transaction-info">
            <div class="info-row">
                <span class="info-label">Order ID</span>
                <span class="info-value">{{ $transaction->order_id }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Paket</span>
                <span class="info-value">{{ ucfirst(str_replace('premium_', '', $transaction->payment_type)) }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Total</span>
                <span class="info-value">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Status</span>
                <span class="info-value">{{ ucfirst($transaction->status) }}</span>
            </div>
            @if($transaction->paid_at)
            <div class="info-row">
                <span class="info-label">Tanggal Bayar</span>
                <span class="info-value">{{ $transaction->paid_at->format('d M Y H:i') }}</span>
            </div>
            @endif
        </div>

        <div>
            <a href="{{ route('dashboard.beranda') }}" class="btn-action">
                Kembali ke Beranda
            </a>
            @if($transaction->isSuccess())
            <a href="{{ route('dashboard.laporan') }}" class="btn-action">
                Lihat Laporan Premium
            </a>
            @endif
        </div>

    </div>
</div>

@endsection
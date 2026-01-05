<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Transaction;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction as MidtransTransaction; // TAMBAHAN PENTING
use Carbon\Carbon;

class PremiumApiController extends Controller
{
    public function __construct()
    {
        // Set Midtrans configuration
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    // ... (method getStatus & getPackages & createTransaction BIARKAN SAMA SEPERTI SEBELUMNYA) ...
    // Agar kode tidak kepanjangan, saya hanya menulis method getStatus, getPackages, createTransaction, getTransactions
    // dalam bentuk ringkas. KODE LAMA ANDA DI BAGIAN INI SUDAH BAGUS.
    // FOKUS PERUBAHAN ADA DI BAWAH (method checkStatus).

    public function getStatus()
    {
        $user = Auth::user();
        return response()->json([
            'status' => 'success',
            'data' => [
                'is_premium' => $user->isPremium(), // Pastikan logic isPremium() di User Model mengecek kolom premium_until
                'premium_until' => $user->premium_until ? Carbon::parse($user->premium_until)->format('Y-m-d H:i:s') : null,
                'remaining_days' => $user->isPremium() ? ceil(now()->diffInDays(Carbon::parse($user->premium_until), false)) : 0,
            ]
        ]);
    }

    public function getPackages()
    {
        // ... (Biarkan kode lama Anda di sini) ...
        // Copy paste isi method getPackages dari file lama Anda
        $packages = [
            [
                'id' => 'monthly',
                'name' => 'Premium Bulanan',
                'price' => 30000,
                'original_price' => 50000,
                'discount' => 'Hemat 40%',
                'duration' => '30 hari',
                'duration_days' => 30,
                'features' => ['Akses laporan keuangan lengkap', 'Grafik pendapatan', 'Export data', 'Notifikasi perawatan', 'Analisa produktivitas', 'Support prioritas']
            ],
            [
                'id' => 'yearly',
                'name' => 'Premium Tahunan',
                'price' => 300000,
                'original_price' => 600000,
                'discount' => 'Hemat 50% + Gratis 2 Bulan',
                'duration' => '365 hari',
                'duration_days' => 365,
                'is_recommended' => true,
                'features' => ['Semua fitur Premium Bulanan', 'Konsultasi gratis', 'Akses webinar', 'Prioritas fitur baru', 'Backup data otomatis', 'Support 24/7']
            ],
        ];

        return response()->json(['status' => 'success', 'data' => $packages]);
    }

    public function createTransaction(Request $request)
    {
        // ... (Biarkan kode lama Anda di sini) ...
        // Copy paste isi method createTransaction dari file lama Anda
        $request->validate(['plan' => 'required|in:monthly,yearly']);
        $user = Auth::user();
        $plan = $request->plan;
        $amount = $plan === 'yearly' ? 300000 : 30000;
        $orderId = 'PREMIUM-' . $user->id . '-' . time();

        try {
            $transaction = Transaction::create([
                'user_id' => $user->id,
                'order_id' => $orderId,
                'payment_type' => 'premium_' . $plan,
                'amount' => $amount,
                'status' => 'pending',
                'expired_at' => Carbon::now()->addHours(24),
            ]);

            $params = [
                'transaction_details' => ['order_id' => $orderId, 'gross_amount' => $amount],
                'customer_details' => ['first_name' => $user->username, 'email' => $user->email, 'phone' => $user->phone ?? ''],
                'item_details' => [['id' => $plan, 'price' => $amount, 'quantity' => 1, 'name' => $plan === 'yearly' ? 'Premium Tahunan' : 'Premium Bulanan']],
            ];

            $snapTransaction = Snap::createTransaction($params);
            $snapToken = $snapTransaction->token;
            $paymentUrl = $snapTransaction->redirect_url;

            $transaction->update(['payment_data' => json_encode(['snap_token' => $snapToken]), 'payment_url' => $paymentUrl]);

            return response()->json([
                'status' => 'success',
                'message' => 'Transaksi berhasil dibuat',
                'data' => [
                    'order_id' => $orderId,
                    'snap_token' => $snapToken,
                    'payment_url' => $paymentUrl,
                    'amount' => $amount,
                    'expired_at' => $transaction->expired_at->format('Y-m-d H:i:s'),
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Payment Error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Gagal membuat transaksi: ' . $e->getMessage()], 500);
        }
    }

    public function getTransactions()
    {
        // ... (Biarkan kode lama Anda di sini) ...
        $user = Auth::user();
        $transactions = Transaction::where('user_id', $user->id)->orderBy('created_at', 'desc')->get()->map(function ($transaction) {
            return [
                'id' => $transaction->id,
                'order_id' => $transaction->order_id,
                'payment_type' => $transaction->payment_type,
                'amount' => $transaction->amount,
                'status' => $transaction->status,
                'payment_method' => $transaction->payment_method,
                'payment_url' => $transaction->payment_url,
                'paid_at' => $transaction->paid_at ? Carbon::parse($transaction->paid_at)->format('Y-m-d H:i:s') : null,
                'created_at' => $transaction->created_at->format('Y-m-d H:i:s'),
            ];
        });
        return response()->json(['status' => 'success', 'data' => $transactions]);
    }

    /**
     * Check Transaction Status (UPDATED)
     * GET /api/premium/check-status/{orderId}
     */
    // GANTI method checkStatus yang lama dengan yang ini:
    public function checkStatus($orderId)
    {
        // 1. Ambil data transaksi lokal
        $transaction = Transaction::where('order_id', $orderId)
            ->where('user_id', Auth::id())
            ->first();

        if (!$transaction) {
            return response()->json(['status' => 'error', 'message' => 'Transaksi tidak ditemukan'], 404);
        }

        // 2. LOGIKA KEBAL ERROR 404 (FIX)
        // Hanya cek ke Midtrans jika status database masih pending
        if ($transaction->status == 'pending') {
            try {
                // Panggil API Midtrans
                $midtransStatus = MidtransTransaction::status($orderId);
                $transactionStatus = $midtransStatus->transaction_status;
                $paymentType = $midtransStatus->payment_type ?? 'unknown';

                $newStatus = null;
                // Mapping status dari Midtrans ke Database kita
                if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
                    $newStatus = 'success';
                } else if ($transactionStatus == 'deny' || $transactionStatus == 'expire' || $transactionStatus == 'cancel') {
                    $newStatus = 'failed';
                }

                // Update Database jika status berubah jadi Sukses
                if ($newStatus == 'success') {
                    $transaction->status = 'success';
                    $transaction->payment_method = $paymentType;
                    $transaction->paid_at = Carbon::now();
                    $transaction->save();

                    // --- AKTIFKAN PREMIUM USER ---
                    $user = $transaction->user;
                    $daysToAdd = ($transaction->amount >= 300000) ? 365 : 30; // 300rb = Tahunan, 30rb = Bulanan

                    if ($user->premium_until && Carbon::parse($user->premium_until)->isFuture()) {
                        // Tambah hari dari tanggal expired yang sudah ada
                        $user->premium_until = Carbon::parse($user->premium_until)->addDays($daysToAdd);
                    } else {
                        // Mulai baru dari sekarang
                        $user->premium_until = Carbon::now()->addDays($daysToAdd);
                    }
                    $user->save();
                    // -----------------------------
                } 
                else if ($newStatus == 'failed') {
                    $transaction->status = 'failed';
                    $transaction->save();
                }

            } catch (\Exception $e) {
                // === KUNCI PERBAIKAN DI SINI ===
                // Jika error mengandung "404", artinya User belum bayar / Data belum sinkron di Midtrans.
                // JANGAN return error 500. Biarkan saja return data 'pending' yang ada di DB lokal.
                if (strpos($e->getMessage(), '404') !== false) {
                    // Log info saja, jangan error
                    // Log::info("Transaksi $orderId belum update di Midtrans (404), menunggu user...");
                } else {
                    // Jika error lain (koneksi putus dll), baru catat sebagai error
                    Log::error("Midtrans Check Error: " . $e->getMessage());
                }
            }
        }

        // 3. Return Data ke Android (Status tetap 'pending' jika 404, jadi Android tidak crash)
        return response()->json([
            'status' => 'success', // HTTP 200 OK
            'data' => [
                'order_id' => $transaction->order_id,
                'status' => $transaction->status, // pending / success / failed
                'amount' => $transaction->amount,
                'payment_method' => $transaction->payment_method,
                'paid_at' => $transaction->paid_at ? Carbon::parse($transaction->paid_at)->format('Y-m-d H:i:s') : null,
            ]
        ]);
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Transaction;
use Midtrans\Config;
use Midtrans\Snap;
use Carbon\Carbon;

class PaymentController extends Controller
{
    public function __construct()
    {
        // Set Midtrans configuration
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    /**
     * Halaman upgrade premium
     */
    public function upgrade()
    {
        $user = Auth::user();

        // Harga paket premium
        $packages = [
            'monthly' => [
                'name' => 'Premium Bulanan',
                'price' => 30000, // PROMO HARGA SPESIAL
                'original_price' => 50000,
                'discount' => 'Hemat 40%',
                'duration' => '30 hari',
                'features' => [
                    'Akses laporan keuangan lengkap',
                    'Grafik pendapatan & pengeluaran',
                    'Export data ke Excel/PDF',
                    'Notifikasi jadwal perawatan',
                    'Analisa produktivitas kebun',
                    'Support prioritas',
                ]
            ],
            'yearly' => [
                'name' => 'Premium Tahunan',
                'price' => 300000, // 30k x 10 bulan (gratis 2 bulan)
                'original_price' => 600000,
                'discount' => 'Hemat 50% + Gratis 2 Bulan',
                'duration' => '365 hari',
                'features' => [
                    'Semua fitur Premium Bulanan',
                    'Konsultasi gratis dengan ahli',
                    'Akses webinar eksklusif',
                    'Prioritas fitur baru',
                    'Backup data otomatis',
                    'Support 24/7',
                ]
            ],
        ];

        return view('payment.upgrade', compact('user', 'packages'));
    }

    /**
     * Proses pembuatan transaksi dan payment link
     */
    public function createTransaction(Request $request)
    {
        $request->validate([
            'plan' => 'required|in:monthly,yearly',
        ]);

        $user = Auth::user();
        $plan = $request->plan;

        // Set harga berdasarkan paket (PROMO PRICE)
        $amount = $plan === 'yearly' ? 300000 : 30000;

        // Generate unique order ID
        $orderId = 'PREMIUM-' . $user->id . '-' . time();

        try {
            // Buat transaksi di database
            $transaction = Transaction::create([
                'user_id' => $user->id,
                'order_id' => $orderId,
                'payment_type' => 'premium_' . $plan,
                'amount' => $amount,
                'status' => 'pending',
                'expired_at' => Carbon::now()->addHours(24),
            ]);

            // Siapkan parameter untuk Midtrans
            $params = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => $amount,
                ],
                'customer_details' => [
                    'first_name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone ?? '', // Opsional: kirim no hp jika ada
                ],
                'item_details' => [
                    [
                        'id' => $plan,
                        'price' => $amount,
                        'quantity' => 1,
                        'name' => $plan === 'yearly' ? 'Premium Tahunan' : 'Premium Bulanan',
                    ]
                ],
                'callbacks' => [
                    'finish' => route('payment.finish'),
                ]
            ];

            // ✅ PERBAIKAN 1: Gunakan createTransaction agar dapat Redirect URL
            // Snap::createTransaction mengembalikan object (token & redirect_url)
            $snapTransaction = Snap::createTransaction($params);
            
            $snapToken = $snapTransaction->token;
            $paymentUrl = $snapTransaction->redirect_url; // Ini URL pembayaran penuh

            // Update transaction dengan token DAN payment URL
            $transaction->update([
                'payment_data' => json_encode(['snap_token' => $snapToken]),
                'payment_url' => $paymentUrl, // Simpan URL agar tidak null
            ]);

            return response()->json([
                'success' => true,
                'snap_token' => $snapToken,
                'payment_url' => $paymentUrl,
                'order_id' => $orderId,
            ]);

        } catch (\Exception $e) {
            Log::error('Payment Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat transaksi: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Callback dari Midtrans setelah pembayaran
     */
    public function callback(Request $request)
    {
        $serverKey = env('MIDTRANS_SERVER_KEY');
        
        // ✅ Log data incoming request untuk debugging
        Log::info('Midtrans Callback Hit:', $request->all());

        // Validasi Signature Key
        $hashed = hash('sha512', $request->order_id . $request->status_code . $request->gross_amount . $serverKey);
        if ($hashed !== $request->signature_key) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $transaction = Transaction::where('order_id', $request->order_id)->first();
        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        // ✅ PERBAIKAN 2: Deteksi Payment Method
        $paymentType = $request->payment_type;
        $paymentMethod = $paymentType; // Default value

        // Logic untuk memperjelas nama metode pembayaran
        if ($paymentType == 'bank_transfer') {
            if (isset($request->va_numbers[0]['bank'])) {
                $paymentMethod = 'Bank Transfer - ' . strtoupper($request->va_numbers[0]['bank']);
            } elseif (isset($request->permata_va_number)) {
                $paymentMethod = 'Bank Transfer - PERMATA';
            }
        } elseif ($paymentType == 'echannel') {
            $paymentMethod = 'Mandiri Bill';
        } elseif ($paymentType == 'qris') {
            $paymentMethod = 'QRIS / GoPay';
        } elseif ($paymentType == 'cstore') {
            $store = $request->store ?? 'Alfamart/Indomaret';
            $paymentMethod = 'Gerai - ' . strtoupper($store);
        }

        // Update status berdasarkan response dari Midtrans
        $transactionStatus = $request->transaction_status;
        $fraudStatus = $request->fraud_status ?? 'accept';

        // Variable data untuk update
        $updateData = [
            'payment_method' => $paymentMethod, // Simpan metode pembayaran
        ];

        if ($transactionStatus == 'capture') {
            if ($fraudStatus == 'accept') {
                $transaction->update($updateData);
                $this->activatePremium($transaction);
            }
        } elseif ($transactionStatus == 'settlement') {
            $transaction->update($updateData);
            $this->activatePremium($transaction);
        } elseif ($transactionStatus == 'pending') {
            $transaction->update(['status' => 'pending']);
        } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
            $transaction->update(['status' => 'failed']);
        }

        return response()->json(['message' => 'OK']);
    }

    /**
     * Halaman setelah pembayaran selesai
     */
    public function finish(Request $request)
    {
        $orderId = $request->order_id;
        $transaction = Transaction::where('order_id', $orderId)->first();

        if (!$transaction) {
            return redirect()->route('dashboard.beranda')
                ->with('error', 'Transaksi tidak ditemukan.');
        }

        return view('payment.finish', compact('transaction'));
    }

    /**
     * Riwayat transaksi user
     */
    public function history()
    {
        $user = Auth::user();
        $transactions = Transaction::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('payment.history', compact('user', 'transactions'));
    }

    /**
     * Activate premium untuk user
     */
    private function activatePremium($transaction)
    {
        // Pastikan status sukses dan tanggal bayar terisi
        $transaction->update([
            'status' => 'success',
            'paid_at' => Carbon::now(),
        ]);

        $plan = str_replace('premium_', '', $transaction->payment_type);
        
        // Panggil method di Model User untuk set premium
        if ($transaction->user) {
            $transaction->user->activatePremium($plan);
            Log::info('Premium activated for user: ' . $transaction->user_id . ' Plan: ' . $plan);
        }
    }
}
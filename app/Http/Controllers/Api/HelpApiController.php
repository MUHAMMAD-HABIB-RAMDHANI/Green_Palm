<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HelpRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HelpApiController extends Controller
{
    /**
     * Store help request (Proses Simpan Pesan dari Android)
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $validator = Validator::make($request->all(), [
            'message' => 'required|string|min:10|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = $request->user();

            // 2. Simpan ke Database
            // Admin akan melihat ini karena app.blade.php mengecek tabel 'help_requests'
            $helpRequest = HelpRequest::create([
                'user_id' => $user->id,
                'username' => $user->username,
                'message' => $request->message,
                'status' => 'pending', // Status awal 'pending' memicu badge merah di Admin
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Pesan bantuan berhasil dikirim! Mohon tunggu balasan admin di menu riwayat.',
                'data' => $helpRequest
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengirim pesan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get User's Help History (Untuk ditampilkan di Android)
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $history = HelpRequest::where('user_id', $user->id)
            ->latest()
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'message' => $item->message,
                    'status' => $item->status,
                    'status_label' => $item->status_label,
                    'admin_reply' => $item->admin_reply,
                    'created_at' => $item->created_at->format('d M Y, H:i'),
                    'replied_at' => $item->replied_at ? $item->replied_at->format('d M Y, H:i') : null,
                ];
            });

        return response()->json([
            'status' => 'success',
            'message' => 'Data riwayat bantuan berhasil diambil',
            'data' => $history
        ]);
    }

    /**
     * Get Single Help Request Detail
     */
    public function show(Request $request, $id)
    {
        $user = $request->user();
        
        // Cari data milik user yang sedang login
        $help = HelpRequest::where('user_id', $user->id)->find($id);

        if (!$help) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan atau bukan milik Anda'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $help->id,
                'message' => $help->message,
                'status' => $help->status,
                'status_label' => $help->status_label, // Pastikan accessor ini ada di Model
                'admin_reply' => $help->admin_reply,
                // Format tanggal dipercantik untuk Android
                'created_at' => $help->created_at->translatedFormat('l, d F Y • H:i'),
                'replied_at' => $help->replied_at ? $help->replied_at->translatedFormat('l, d F Y • H:i') : null,
            ]
        ]);
    }
}
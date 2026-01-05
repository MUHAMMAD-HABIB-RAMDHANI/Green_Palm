<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HelpRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Notification;
use Illuminate\Support\Facades\Validator;

class AdminHelpController extends Controller
{
    /**
     * Display all help requests (Halaman Kelola Bantuan)
     */
    public function index(Request $request)
    {
        // Query builder untuk help requests
        $query = HelpRequest::with('user')->latest();

        // Filter berdasarkan status jika ada
        if ($request->has('status') && in_array($request->status, ['pending', 'in_progress', 'resolved'])) {
            $query->where('status', $request->status);
        }

        // Ambil data dengan pagination
        $helpRequests = $query->paginate(20);

        // Hitung statistik
        $stats = [
            'total' => HelpRequest::count(),
            'pending' => HelpRequest::where('status', 'pending')->count(),
            'in_progress' => HelpRequest::where('status', 'in_progress')->count(),
            'resolved' => HelpRequest::where('status', 'resolved')->count(),
        ];

        return view('admin.bantuan', [
            'helpRequests' => $helpRequests,
            'stats' => $stats,
        ]);
    }

    /**
     * Show detail help request
     */
    public function show($id)
    {
        $helpRequest = HelpRequest::with('user')->findOrFail($id);

        return view('admin.bantuan-detail', [
            'helpRequest' => $helpRequest,
        ]);
    }

    /**
     * Update status help request
     */
    public function updateStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,in_progress,resolved',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        try {
            $helpRequest = HelpRequest::findOrFail($id);
            $helpRequest->update([
                'status' => $request->status,
            ]);

            return back()->with('success', '✓ Status berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->with('error', '✗ Gagal memperbarui status: ' . $e->getMessage());
        }
    }

    /**
     * Reply to help request
     */
    public function reply(Request $request, $id)
    {
        // 1. Validasi Input
        $validator = Validator::make($request->all(), [
            'admin_reply' => 'required|string|min:10|max:1000',
        ], [
            'admin_reply.required' => 'Balasan wajib diisi',
            'admin_reply.min' => 'Balasan minimal 10 karakter',
            'admin_reply.max' => 'Balasan maksimal 1000 karakter',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        try {
            $helpRequest = HelpRequest::findOrFail($id);
            
            // 2. Update Data Tiket Bantuan
            $helpRequest->update([
                'admin_reply' => $request->admin_reply,
                'replied_at' => now(),
                'status' => 'resolved',
            ]);

            // 3. ✅ KIRIM NOTIFIKASI (KONSEP SAMA DENGAN PENYAKIT)
            // Bedanya: Penyakit (Broadcast/user_id=null), Ini (Personal/user_id=target)
            
            Notification::create([
                'user_id' => $helpRequest->user_id, // Target spesifik user
                'type'    => 'help_reply',          // Tipe khusus untuk Android membedakan icon/intent
                'title'   => '💬 Balasan Admin',    // Judul Notifikasi
                
                // Pesan: Ambil cuplikan balasan admin (maks 60 karakter) agar user penasaran
                'message' => 'Admin: "' . Str::limit($request->admin_reply, 60) . '"', 
                
                'icon'    => '💬',
                'link'    => 'help_center',         // KUNCI NAVIGASI ANDROID
                'data'    => json_encode([
                    'help_id' => $helpRequest->id   // ID untuk membuka detail langsung
                ]),
                'read_at' => null,                  // WAJIB NULL agar terdeteksi Worker sebagai "Baru"
            ]);

            return back()->with('success', '✓ Balasan terkirim dan notifikasi masuk antrian aplikasi!');
        } catch (\Exception $e) {
            return back()->with('error', '✗ Gagal mengirim balasan: ' . $e->getMessage());
        }
    }

    /**
     * Delete help request
     */
    public function destroy($id)
    {
        try {
            $helpRequest = HelpRequest::findOrFail($id);
            $helpRequest->delete();

            return back()->with('success', '✓ Pesan bantuan berhasil dihapus!');
        } catch (\Exception $e) {
            return back()->with('error', '✗ Gagal menghapus pesan: ' . $e->getMessage());
        }
    }

    /**
     * Get unread help requests count (untuk notifikasi badge)
     */
    public function getUnreadCount()
    {
        return HelpRequest::where('status', 'pending')->count();
    }
    
}
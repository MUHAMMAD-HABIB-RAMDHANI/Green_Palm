<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

class NotificationApiController extends Controller
{
    /**
     * Mengambil daftar notifikasi untuk user yang sedang login
     * Termasuk notifikasi personal (user_id = Auth::id()) dan broadcast (user_id = null atau 0)
     */
    public function index(Request $request)
    {
        try {
            $userId = Auth::id();
            
            // PERBAIKAN: Gunakan query manual agar Broadcast (NULL) ikut terambil
            $notifications = Notification::where(function($query) use ($userId) {
                    $query->where('user_id', $userId)
                          ->orWhereNull('user_id'); // Menangkap notifikasi broadcast
                })
                ->latest()
                ->paginate(20);

            // PERBAIKAN: Hitung unread untuk Personal + Broadcast
            // Catatan: Unread broadcast dihitung server, tapi status 'read' 
            // broadcast hanya tersimpan di HP User (karena database read_at milik bersama)
            $unreadCount = Notification::where(function($query) use ($userId) {
                    $query->where('user_id', $userId)
                          ->orWhereNull('user_id');
                })
                ->whereNull('read_at')
                ->count();

            return response()->json([
                'status' => 'success',
                'message' => 'Notifikasi berhasil diambil',
                'data' => [
                    'notifications' => $notifications->items(),
                    'unread_count' => $unreadCount,
                    'pagination' => [
                        'current_page' => $notifications->currentPage(),
                        'last_page' => $notifications->lastPage(),
                        'per_page' => $notifications->perPage(),
                        'total' => $notifications->total()
                    ]
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil notifikasi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mengambil jumlah notifikasi yang belum dibaca
     */
    public function unreadCount()
    {
        try {
            $userId = Auth::id();
            
            // PERBAIKAN: Query disamakan dengan index
            $unreadCount = Notification::where(function($query) use ($userId) {
                    $query->where('user_id', $userId)
                          ->orWhereNull('user_id');
                })
                ->whereNull('read_at')
                ->count();

            return response()->json([
                'status' => 'success',
                'data' => [
                    'unread_count' => $unreadCount
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil jumlah notifikasi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Tandai notifikasi sebagai sudah dibaca
     */
    public function markAsRead($id)
    {
        try {
            $userId = Auth::id();
            
            // PERBAIKAN: Gunakan query pencarian yang menyertakan NULL
            // Jika pakai forUser() biasa, notifikasi broadcast akan 'Not Found'
            $notification = Notification::where(function($query) use ($userId) {
                    $query->where('user_id', $userId)
                          ->orWhereNull('user_id');
                })->findOrFail($id);
            
            // Logika ini sudah benar: 
            // Hanya update database jika personal. Broadcast statusnya diatur di Android (Lokal).
            if ($notification->user_id !== null) { 
                $notification->update(['read_at' => now()]);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Notifikasi berhasil ditandai sebagai dibaca',
                'data' => $notification
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Notifikasi tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menandai notifikasi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Tandai semua notifikasi sebagai sudah dibaca
     */
    public function markAllAsRead()
    {
        try {
            $userId = Auth::id();
            // Hanya tandai notifikasi personal
            Notification::where('user_id', $userId)
                ->whereNull('read_at')
                ->update(['read_at' => now()]);

            return response()->json([
                'status' => 'success',
                'message' => 'Semua notifikasi personal berhasil ditandai sebagai dibaca'
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Hapus notifikasi personal
     * Notifikasi broadcast tidak bisa dihapus oleh user
     */
    public function destroy($id)
    {
        try {
            $userId = Auth::id();
            // Hanya user yang bisa hapus notifikasi personal
            $notification = Notification::where('user_id', $userId)->findOrFail($id);
            $notification->delete();

            return response()->json(['status' => 'success', 'message' => 'Notifikasi berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menghapus: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Hapus semua notifikasi personal yang sudah dibaca
     */
    public function clearRead()
    {
        try {
            $userId = Auth::id();
            
            // Hapus notifikasi personal yang sudah dibaca
            Notification::where('user_id', $userId)
                ->whereNotNull('read_at')
                ->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Notifikasi yang sudah dibaca berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus notifikasi: ' . $e->getMessage()
            ], 500);
        }
    }
}
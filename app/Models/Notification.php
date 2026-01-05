<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';

    protected $fillable = [
        'user_id', // NULL = broadcast ke semua user, filled = notifikasi personal
        'type',    // 'reminder' (jadwal) atau 'info' (admin) atau 'announcement' (pengumuman)
        'title',
        'message',
        'icon',
        'link',    // Link tujuan ketika diklik
        'data',
        'read_at',
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
    ];

    public function isRead(): bool
    {
        return !is_null($this->read_at);
    }

    public function markAsRead()
    {
        if (!$this->isRead()) {
            $this->update(['read_at' => now()]);
        }
    }

    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Scope untuk notifikasi yang belum dibaca
     */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    /**
     * Scope untuk mengurutkan dari yang terbaru
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * ✅ BARU: Scope untuk mengambil notifikasi yang relevan untuk user tertentu
     * - Notifikasi personal (user_id = $userId)
     * - Notifikasi broadcast (user_id = NULL)
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where(function ($q) use ($userId) {
            $q->where('user_id', $userId)      // Notifikasi personal
              ->orWhereNull('user_id')         // Broadcast (Format Baru)
              ->orWhere('user_id', 0);         // Broadcast (Format Lama di DB Anda)
        });
    }

    /**
     * Cek apakah notifikasi ini adalah broadcast
     */
    public function isBroadcast(): bool
    {
        // Dianggap broadcast jika NULL atau 0
        return is_null($this->user_id) || $this->user_id === 0 || $this->user_id === '0';
    }

    /**
     * Cek apakah notifikasi ini adalah personal
     */
    public function isPersonal(): bool
    {
        // Kebalikan dari isBroadcast
        return !$this->isBroadcast();
    }
}
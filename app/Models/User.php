<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Carbon\Carbon;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username', 
        'email', 
        'password',
        'phone', 
        'gender', 
        'birth_date', 
        'profile_picture',
        'is_premium',
        'premium_until',
        'premium_plan',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'premium_until' => 'datetime',
            'is_premium' => 'boolean',
            'birth_date' => 'date',
        ];
    }

    // ============================================================
    // RELASI KE TABEL LAIN
    // ============================================================

    /**
     * Relasi dengan data kebun
     */
    public function dataKebuns()
    {
        return $this->hasMany(DataKebun::class);
    }

    /**
     * Relasi dengan transaksi
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * ✅ Relasi dengan notifikasi
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * ✅ Relasi dengan pemupukan
     */
    public function pemupukan()
    {
        return $this->hasMany(Pemupukan::class);
    }

    /**
     * ✅ Relasi dengan penunasan
     */
    public function penunasan()
    {
        return $this->hasMany(Penunasan::class);
    }

    /**
     * ✅ Relasi dengan penyemprotan
     */
    public function penyemprotan()
    {
        return $this->hasMany(Penyemprotan::class);
    }

    /**
     * ✅ Relasi dengan sanitasi
     */
    public function sanitasi()
    {
        return $this->hasMany(Sanitasi::class);
    }

    /**
     * ✅ Relasi dengan kastrasi
     */
    public function kastrasi()
    {
        return $this->hasMany(Kastrasi::class);
    }

    /**
     * ✅ Relasi dengan panen
     */
    public function panen()
    {
        return $this->hasMany(Panen::class);
    }

    // ============================================================
    // HELPER METHODS
    // ============================================================

    /**
     * ✅ Check if user is admin
     */
    public function isAdmin()
    {
        return $this->email === 'admin@gmail.com';
    }

    /**
     * Check if user is premium and not expired
     */
    public function isPremium()
    {
        if (!$this->is_premium) {
            return false;
        }

        if ($this->premium_until && $this->premium_until->isPast()) {
            // Auto-expire premium jika sudah lewat
            $this->update([
                'is_premium' => false,
                'premium_until' => null,
                'premium_plan' => null,
            ]);
            return false;
        }

        return true;
    }

    /**
     * Activate premium for user
     */
    public function activatePremium($plan = 'monthly')
    {
        $duration = $plan === 'yearly' ? 365 : 30;

        $this->update([
            'is_premium' => true,
            'premium_until' => Carbon::now()->addDays($duration),
            'premium_plan' => $plan,
        ]);
    }

    /**
     * Get remaining premium days
     */
    public function getRemainingPremiumDays()
    {
        if (!$this->isPremium()) {
            return 0;
        }

        return Carbon::now()->diffInDays($this->premium_until, false);
    }

    /**
     * ✅ Get unread notifications count
     */
    public function getUnreadNotificationsCount()
    {
        return $this->notifications()->whereNull('read_at')->count();
    }

    /**
     * ✅ Get latest activity date (untuk cek jadwal perawatan)
     */
    public function getLastMaintenanceDate($type)
    {
        switch ($type) {
            case 'pemupukan':
                return $this->pemupukan()->latest('tanggal_pemupukan')->first()?->tanggal_pemupukan;
            case 'penunasan':
                return $this->penunasan()->latest('tanggal_penunasan')->first()?->tanggal_penunasan;
            case 'penyemprotan':
                return $this->penyemprotan()->latest('tanggal_penyemprotan')->first()?->tanggal_penyemprotan;
            case 'sanitasi':
                return $this->sanitasi()->latest('tanggal_sanitasi')->first()?->tanggal_sanitasi;
            case 'kastrasi':
                return $this->kastrasi()->latest('tanggal_kastrasi')->first()?->tanggal_kastrasi;
            default:
                return null;
        }
    }

    /**
     * ✅ Check if user has any maintenance records
     */
    public function hasMaintenanceRecords()
    {
        return $this->pemupukan()->exists() 
            || $this->penunasan()->exists() 
            || $this->penyemprotan()->exists() 
            || $this->sanitasi()->exists() 
            || $this->kastrasi()->exists();
    }

    /**
     * ✅ Relasi dengan rating
     */
    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    /**
     * ✅ Check apakah user sudah pernah memberikan rating
     */
    public function hasRated()
    {
        return $this->ratings()->exists();
    }

    /**
     * ✅ Get rating terakhir user
     */
    public function getLatestRating()
    {
        return $this->ratings()->latest()->first();
    }

    /**
    * ✅ Relasi dengan help requests (bantuan)
    */
    public function helpRequests()
    {
        return $this->hasMany(HelpRequest::class);
    }

    /**
     * ✅ Get pending help requests count
     */
    public function getPendingHelpRequestsCount()
    {
        return $this->helpRequests()->where('status', 'pending')->count();
    }

    /**
     * ✅ Get latest help request
     */
    public function getLatestHelpRequest()
    {
        return $this->helpRequests()->latest()->first();
    }
}
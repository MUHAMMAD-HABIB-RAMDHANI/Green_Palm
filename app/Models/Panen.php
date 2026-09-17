<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Panen extends Model
{
    use HasFactory;

    protected $table = 'panens';

    protected $fillable = [
        'user_id',
        'kebun_id',
        'tanggal_panen',
        'berat_total_tbs',
        'jumlah_tbs',
        'berat_brondolan',
        'tanggal_panen_berikutnya',
        'total_upah_panen',
        'biaya_lainnya',
        'pendapatan', // <--- Pastikan kolom ini ada
        'foto_panen',
    ];

    protected $casts = [
        'biaya_lainnya' => 'array', // Cast JSON ke array
        // 'foto_panen' => 'array',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    // Relasi ke Kebun
    public function kebun()
    {
        return $this->belongsTo(DataKebun::class);
    }
}
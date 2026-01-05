<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemupukan extends Model
{
    use HasFactory;

    protected $table = 'pemupukans';

    protected $fillable = [
        'user_id',
        'kebun_id',
        'tanggal_pemupukan',
        'jenis_pupuk',
        'total_pupuk',
        'pupuk_per_pokok',
        'total_upah',
        'rincian_upah', // [BARU]
        'biaya_pembelian',
    ];

    // [BARU] Cast JSON ke Array agar bisa di-looping di Blade
    protected $casts = [
        'rincian_upah' => 'array',
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
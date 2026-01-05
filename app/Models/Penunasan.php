<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penunasan extends Model
{
    use HasFactory;

    protected $table = 'penunasans';

    protected $fillable = [
        'user_id',
        'kebun_id',
        'tanggal_penunasan',
        'jumlah_pokok_ditunas',
        'total_upah',
        'ada_biaya_lain',
        'biaya_lain',
        'rincian_upah',
    ];

    // [BARU] Cast JSON ke Array
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
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penyemprotan extends Model
{
    use HasFactory;

    protected $table = 'penyemprotans';

    protected $fillable = [
        'user_id',
        'kebun_id',
        'tanggal_penyemprotan',
        'jenis_pestisida_racun',
        'penggunaan_pestisida',
        'luas_lahan_disemprot',
        'total_upah',
        'ada_biaya_lain',
        'biaya_lain',
        'rincian_upah', // [BARU]
    ];

    // [BARU] Cast JSON ke Array
    protected $casts = [
        'rincian_upah' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function kebun()
    {
        return $this->belongsTo(DataKebun::class);
    }
}
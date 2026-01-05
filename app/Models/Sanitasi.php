<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sanitasi extends Model
{
    use HasFactory;

    protected $table = 'sanitasis';

    protected $fillable = [
        'user_id',
        'kebun_id',
        'tanggal_sanitasi',
        'jumlah_pokok_disanitasi',
        'total_upah',
        'ada_biaya_lain',
        'biaya_lain',
        'rincian_upah', // [BARU] Tambahkan ini
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
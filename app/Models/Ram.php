<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ram extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama_ram',
        'nomor_wa',
        'lokasi_ram',
        'latitude',
        'longitude',
        'harga_beli_tbs',
        'layanan_jemput_buah',
        'timbangan_digital',
        'menerima_berondolan',
        'tidak_ada_pengembalian',
        'foto_tampak_depan',
    ];

    protected $casts = [
        'harga_beli_tbs' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'layanan_jemput_buah' => 'boolean',
        'timbangan_digital' => 'boolean',
        'menerima_berondolan' => 'boolean',
        'tidak_ada_pengembalian' => 'boolean',
    ];

    /**
     * Relasi dengan User (Toke)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Format harga dengan Rupiah
     */
    public function getFormattedHargaAttribute()
    {
        return 'Rp ' . number_format($this->harga_beli_tbs, 0, ',', '.');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataKebun extends Model
{
    use HasFactory;

    protected $table = 'data_kebun';

    protected $fillable = [
        'user_id',
        'nama_kebun',          // ✅ Sesuaikan
        'lokasi_kebun',        // ✅ Sesuaikan
        'luas_lahan',
        'jumlah_hektar',
        'tahun_tanam',
        'tahu_jenis_bibit', 
        'jenis_bibit_nama',   // ✅ Sesuaikan
        'jenis_tanah',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pemupukans()
    {
        return $this->hasMany(Pemupukan::class);
    }

    public function panens()
    {
        // Parameter kedua ('data_kebun_id') adalah nama kolom foreign key di tabel panens.
        // Sesuaikan jika nama kolom di database Anda berbeda (misal: 'kebun_id').
        return $this->hasMany(Panen::class, 'kebun_id'); 
    }
}
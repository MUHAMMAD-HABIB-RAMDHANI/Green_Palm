<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hama extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'latin_name', 'image', 'description', 'solution'];

    // Accessor untuk URL Gambar
    public function getImageUrlAttribute()
    {
        if ($this->image && file_exists(public_path('storage/' . $this->image))) {
            return asset('storage/' . $this->image);
        }
        // Placeholder jika gambar kosong
        return 'https://placehold.co/300x300/e0e0e0/333?text=No+Image';
    }
}

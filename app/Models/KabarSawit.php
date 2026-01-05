<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KabarSawit extends Model
{
    use HasFactory;

    // Hapus 'content', Tambahkan 'url'
    protected $fillable = ['title', 'url', 'image', 'category', 'is_popular', 'published_at'];

    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        // Placeholder jika admin tidak upload gambar
        return 'https://placehold.co/600x400/e6f1e3/1E4620?text=Kabar+Sawit';
    }
}
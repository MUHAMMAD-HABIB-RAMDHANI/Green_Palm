<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducationVideo extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'url'];

    // --- ACCESSOR: Membuat atribut 'thumbnail' secara virtual ---
    // Ini akan otomatis dipanggil saat kita menulis $video->thumbnail di Blade
    public function getThumbnailAttribute()
    {
        $url = $this->url;
        $videoId = null;

        // Pola Regex untuk mengambil ID Youtube dari berbagai format link
        // Support: youtube.com/watch?v=ID, youtu.be/ID, youtube.com/embed/ID
        $pattern = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/';
        
        if (preg_match($pattern, $url, $matches)) {
            $videoId = $matches[1];
        }

        // Jika ID ketemu, return URL gambar dari server YouTube
        if ($videoId) {
            return "https://img.youtube.com/vi/{$videoId}/hqdefault.jpg";
        }

        // Fallback jika link rusak/bukan youtube
        return 'https://placehold.co/640x360/e6f1e3/1E4620?text=Video+Tidak+Tersedia';
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\EducationVideo;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function storeVideo(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'url'   => 'required|url' // Validasi harus format URL valid
        ]);

        EducationVideo::create([
            'title' => $request->title,
            'url'   => $request->url
        ]);

        return redirect()->back()->with('success', 'Video berhasil ditambahkan!');
    }

    public function storePrice(Request $request)
    {
        $request->validate([
            'region' => 'required|string|max:255',
            'price'  => 'required|numeric',
            'date'   => 'required|date',
        ]);

        \App\Models\PalmPrice::create([
            'region' => $request->region,
            'price'  => $request->price,
            'updated_at_date' => $request->date,
        ]);

        return back()->with('success', 'Data harga sawit berhasil ditambahkan!');
    }
}

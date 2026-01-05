<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KabarSawit;
use App\Models\Notification; // Pastikan ini ada
use Illuminate\Support\Facades\Storage;

class AdminKabarSawitController extends Controller
{
    public function index()
    {
        $kabarSawits = KabarSawit::latest()->paginate(10);
        return view('admin.kabar-sawit.index', compact('kabarSawits'));
    }

    public function create()
    {
        return view('admin.kabar-sawit.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'url'   => 'required|url',
            'category' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['title', 'url', 'category']);
        $data['is_popular'] = $request->has('is_popular') ? true : false;
        $data['published_at'] = now();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('kabar-sawit', 'public');
            $data['image'] = $path;
        }

        // 1. Simpan Berita
        $kabar = KabarSawit::create($data);

        // 2. BROADCAST NOTIFIKASI (CREATE)
        try {
            Notification::create([
                'user_id' => null, // Broadcast
                'type'    => 'kabar_sawit',
                'title'   => 'Kabar Sawit Terbaru!',
                'message' => $request->title,
                'icon'    => 'ic_news',
                'link'    => $request->url, // Link ke berita eksternal
                'data' => [
                    'kabar_id' => $kabar->id,
                    'category' => $request->category
                ],
                'read_at' => null
            ]);
        } catch (\Exception $e) {
            \Log::error("Gagal notif create: " . $e->getMessage());
        }

        return redirect()->route('admin.kabar-sawit.index')
            ->with('success', 'Berita berhasil ditambahkan dan notifikasi dikirim!');
    }

    public function edit($id)
    {
        $kabar = KabarSawit::findOrFail($id);
        return view('admin.kabar-sawit.edit', compact('kabar'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'url'   => 'required|url',
            'category' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $kabar = KabarSawit::findOrFail($id);
        
        $data = $request->only(['title', 'url', 'category']);
        $data['is_popular'] = $request->has('is_popular') ? true : false;

        if ($request->hasFile('image')) {
            if ($kabar->image && Storage::disk('public')->exists($kabar->image)) {
                Storage::disk('public')->delete($kabar->image);
            }
            $path = $request->file('image')->store('kabar-sawit', 'public');
            $data['image'] = $path;
        }

        $kabar->update($data);

        // ==========================================================
        // ✅ TAMBAHAN: BROADCAST NOTIFIKASI (UPDATE)
        // ==========================================================
        try {
            Notification::create([
                'user_id' => null, // Broadcast
                'type'    => 'kabar_sawit', // Tetap gunakan tipe ini agar Android bisa handle link-nya
                'title'   => 'Berita Diperbarui 📝',
                'message' => 'Update info: "' . $request->title . '". Baca selengkapnya disini.',
                'icon'    => 'ic_news',
                'link'    => $request->url,
                'data' => [
                    'kabar_id' => $kabar->id,
                    'category' => $request->category
                ],
                'read_at' => null
            ]);
        } catch (\Exception $e) {
            \Log::error("Gagal notif update: " . $e->getMessage());
        }

        return redirect()->route('admin.kabar-sawit.index')
            ->with('success', 'Berita berhasil diperbarui dan notifikasi dikirim!');
    }

    public function destroy($id)
    {
        $kabar = KabarSawit::findOrFail($id);
        $title = $kabar->title; // Simpan judul untuk pesan notifikasi sebelum dihapus
        
        if ($kabar->image && Storage::disk('public')->exists($kabar->image)) {
            Storage::disk('public')->delete($kabar->image);
        }
        
        $kabar->delete();

        // ==========================================================
        // ✅ TAMBAHAN: BROADCAST NOTIFIKASI (DELETE)
        // ==========================================================
        // Opsional: Memberitahu user bahwa berita telah ditarik/dihapus
        try {
            Notification::create([
                'user_id' => null,
                'type'    => 'info', // Gunakan tipe 'info' biasa karena datanya sudah hilang
                'title'   => 'Berita Dihapus 🗑️',
                'message' => 'Berita "' . $title . '" telah dihapus atau ditarik kembali.',
                'icon'    => 'ic_info',
                'link'    => null, // Tidak ada link karena berita sudah dihapus
                'read_at' => null
            ]);
        } catch (\Exception $e) {
            \Log::error("Gagal notif delete: " . $e->getMessage());
        }

        return redirect()->route('admin.kabar-sawit.index')
            ->with('success', 'Berita berhasil dihapus!');
    }
}
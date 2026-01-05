<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EducationVideo;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;

class AdminEdukasiController extends Controller
{
    public function index()
    {
        $videos = EducationVideo::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.edukasi.index', compact('videos'));
    }

    public function create()
    {
        return view('admin.edukasi.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|url'
        ]);

        $video = EducationVideo::create($request->only(['title', 'url']));

        // ✅ BROADCAST NOTIFICATION: Kirim 1 notifikasi yang tampil ke SEMUA user
        Notification::create([
            'user_id' => null,  // NULL = broadcast ke semua user
            'type'    => 'info',
            'title'   => 'Video Edukasi Baru 🎥',
            'message' => 'Admin baru saja menambahkan video: ' . $request->title,
            'icon'    => '🎓',
            'link'    => route('dashboard.edukasi'),
            'read_at' => null,
        ]);

        return redirect()->route('admin.edukasi.index')
            ->with('success', 'Video ditambahkan & Notifikasi broadcast dikirim ke semua user!');
    }

    public function edit($id)
    {
        $video = EducationVideo::findOrFail($id);
        return view('admin.edukasi.edit', compact('video'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|url'
        ]);

        $video = EducationVideo::findOrFail($id);
        $oldTitle = $video->title;
        $video->update($request->only(['title', 'url']));

        // ✅ BROADCAST NOTIFICATION: Update video edukasi
        Notification::create([
            'user_id' => null,  // NULL = broadcast
            'type'    => 'info',
            'title'   => 'Video Edukasi Diperbarui 📝',
            'message' => 'Admin memperbarui video: "' . $oldTitle . '" menjadi "' . $request->title . '"',
            'icon'    => '🎓',
            'link'    => route('dashboard.edukasi'),
            'read_at' => null,
        ]);

        return redirect()->route('admin.edukasi.index')
            ->with('success', 'Video edukasi berhasil diperbarui & notifikasi dikirim!');
    }

    public function destroy($id)
    {
        $video = EducationVideo::findOrFail($id);
        $title = $video->title;
        $video->delete();

        // ✅ BROADCAST NOTIFICATION: Hapus video edukasi (opsional)
        Notification::create([
            'user_id' => null,  // NULL = broadcast
            'type'    => 'info',
            'title'   => 'Video Edukasi Dihapus 🗑️',
            'message' => 'Admin menghapus video: "' . $title . '"',
            'icon'    => 'ℹ️',
            'link'    => route('dashboard.edukasi'),
            'read_at' => null,
        ]);

        return redirect()->route('admin.edukasi.index')
            ->with('success', 'Video edukasi berhasil dihapus & notifikasi dikirim!');
    }
}
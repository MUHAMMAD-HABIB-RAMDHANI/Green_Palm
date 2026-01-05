<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hama;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminHamaController extends Controller
{
    public function index()
    {
        $hamas = Hama::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.hama.index', compact('hamas'));
    }

    public function create()
    {
        return view('admin.hama.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'latin_name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'solution' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = $request->only(['name', 'latin_name', 'description', 'solution']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('hama', 'public');
        }

        Hama::create($data);

        // ✅ BROADCAST NOTIFICATION: Data hama baru
        Notification::create([
            'user_id' => null,  // NULL = broadcast ke semua user
            'type'    => 'info',
            'title'   => 'Info Hama Baru 🐛',
            'message' => 'Admin menambahkan info hama: "' . $request->name . '". Pelajari cara mengatasinya!',
            'icon'    => '🐛',
            'link'    => route('dashboard.daftar-hama'),
            'read_at' => null,

            'data'    => [
                'hama_id' => $hama->id, // Kirim ID agar Android bisa buka Detail
                'jenis'   => 'hama'
            ],
        ]);

        return redirect()->route('admin.hama.index')
            ->with('success', 'Hama berhasil ditambahkan & notifikasi broadcast dikirim!');
    }

    public function edit($id)
    {
        $hama = Hama::findOrFail($id);
        return view('admin.hama.edit', compact('hama'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'latin_name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'solution' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $hama = Hama::findOrFail($id);
        $oldName = $hama->name;
        $data = $request->only(['name', 'latin_name', 'description', 'solution']);

        if ($request->hasFile('image')) {
            // Hapus gambar lama
            if ($hama->image) {
                Storage::disk('public')->delete($hama->image);
            }
            $data['image'] = $request->file('image')->store('hama', 'public');
        }

        $hama->update($data);

        // ✅ BROADCAST NOTIFICATION: Update data hama
        Notification::create([
            'user_id' => null,  // NULL = broadcast
            'type'    => 'info',
            'title'   => 'Info Hama Diperbarui 📝',
            'message' => 'Admin memperbarui info hama: "' . $request->name . '". Cek solusi terbaru!',
            'icon'    => '🐛',
            'link'    => route('dashboard.daftar-hama'),
            'read_at' => null,

            'data'    => [
                'hama_id' => $hama->id, // Kirim ID agar Android bisa buka Detail
                'jenis'   => 'hama'
            ],
        ]);

        return redirect()->route('admin.hama.index')
            ->with('success', 'Hama berhasil diperbarui & notifikasi dikirim!');
    }

    public function destroy($id)
    {
        $hama = Hama::findOrFail($id);
        $name = $hama->name;
        
        if ($hama->image) {
            Storage::disk('public')->delete($hama->image);
        }
        
        $hama->delete();

        // ✅ BROADCAST NOTIFICATION: Hapus data hama (opsional)
        Notification::create([
            'user_id' => null,  // NULL = broadcast
            'type'    => 'info',
            'title'   => 'Info Hama Dihapus 🗑️',
            'message' => 'Data hama "' . $name . '" telah dihapus dari sistem',
            'icon'    => 'ℹ️',
            'link'    => route('dashboard.daftar-hama'),
            'read_at' => null,
        ]);

        return redirect()->route('admin.hama.index')
            ->with('success', 'Hama berhasil dihapus & notifikasi dikirim!');
    }
}
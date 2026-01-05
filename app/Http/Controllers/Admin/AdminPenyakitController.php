<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penyakit;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminPenyakitController extends Controller
{
    public function index()
    {
        $penyakits = Penyakit::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.penyakit.index', compact('penyakits'));
    }

    public function create()
    {
        return view('admin.penyakit.create');
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
            $data['image'] = $request->file('image')->store('penyakit', 'public');
        }

        Penyakit::create($data);

        // ✅ BROADCAST NOTIFICATION: Data penyakit baru
        Notification::create([
            'user_id' => null,  // NULL = broadcast ke semua user
            'type'    => 'info',
            'title'   => 'Info Penyakit Baru 🦠',
            'message' => 'Admin menambahkan info penyakit: "' . $request->name . '". Kenali dan cegah sekarang!',
            'icon'    => '🦠',
            'link'    => route('dashboard.daftar-penyakit'),
            'read_at' => null,

            'data'    => [
                'penyakit_id' => $penyakit->id, 
                'category'    => 'penyakit'
            ],
        ]);

        return redirect()->route('admin.penyakit.index')
            ->with('success', 'Penyakit berhasil ditambahkan & notifikasi broadcast dikirim!');
    }

    public function edit($id)
    {
        $penyakit = Penyakit::findOrFail($id);
        return view('admin.penyakit.edit', compact('penyakit'));
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

        $penyakit = Penyakit::findOrFail($id);
        $oldName = $penyakit->name;
        $data = $request->only(['name', 'latin_name', 'description', 'solution']);

        if ($request->hasFile('image')) {
            // Hapus gambar lama
            if ($penyakit->image) {
                Storage::disk('public')->delete($penyakit->image);
            }
            $data['image'] = $request->file('image')->store('penyakit', 'public');
        }

        $penyakit->update($data);

        // ✅ BROADCAST NOTIFICATION: Update data penyakit
        Notification::create([
            'user_id' => null,  // NULL = broadcast
            'type'    => 'info',
            'title'   => 'Info Penyakit Diperbarui 📝',
            'message' => 'Admin memperbarui info penyakit: "' . $request->name . '". Cek pencegahan terbaru!',
            'icon'    => '🦠',
            'link'    => route('dashboard.daftar-penyakit'),
            'read_at' => null,

            'data'    => [
                'penyakit_id' => $penyakit->id, 
                'category'    => 'penyakit'
            ],
        ]);

        return redirect()->route('admin.penyakit.index')
            ->with('success', 'Penyakit berhasil diperbarui & notifikasi dikirim!');
    }

    public function destroy($id)
    {
        $penyakit = Penyakit::findOrFail($id);
        $name = $penyakit->name;
        
        if ($penyakit->image) {
            Storage::disk('public')->delete($penyakit->image);
        }
        
        $penyakit->delete();

        // ✅ BROADCAST NOTIFICATION: Hapus data penyakit (opsional)
        Notification::create([
            'user_id' => null,  // NULL = broadcast
            'type'    => 'info',
            'title'   => 'Info Penyakit Dihapus 🗑️',
            'message' => 'Data penyakit "' . $name . '" telah dihapus dari sistem',
            'icon'    => 'ℹ️',
            'link'    => route('dashboard.daftar-penyakit'),
            'read_at' => null,
        ]);

        return redirect()->route('admin.penyakit.index')
            ->with('success', 'Penyakit berhasil dihapus & notifikasi dikirim!');
    }
}
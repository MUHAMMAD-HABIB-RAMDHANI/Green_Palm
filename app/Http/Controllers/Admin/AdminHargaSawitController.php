<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PalmPrice;
use App\Models\Notification;
use Illuminate\Http\Request;

class AdminHargaSawitController extends Controller
{
    public function index()
    {
        $prices = PalmPrice::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.harga-sawit.index', compact('prices'));
    }

    public function create()
    {
        return view('admin.harga-sawit.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'region' => 'required|string|max:255',
            'price' => 'required|numeric|min:0'
        ]);

        PalmPrice::create($request->only(['region', 'price']));

        // ✅ BROADCAST NOTIFICATION: Harga sawit baru
        Notification::create([
            'user_id' => null,  // NULL = broadcast ke semua user
            'type'    => 'info',
            'title'   => 'Harga Sawit Baru 💰',
            'message' => 'Harga sawit ' . $request->region . ': Rp ' . number_format($request->price, 0, ',', '.') . '/kg',
            'icon'    => '💰',
            'link'    => route('dashboard.harga-sawit'),
            'read_at' => null,
        ]);

        return redirect()->route('admin.harga-sawit.index')
            ->with('success', 'Harga sawit berhasil ditambahkan & notifikasi broadcast dikirim!');
    }

    public function edit($id)
    {
        $price = PalmPrice::findOrFail($id);
        return view('admin.harga-sawit.edit', compact('price'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'region' => 'required|string|max:255',
            'price' => 'required|numeric|min:0'
        ]);

        $price = PalmPrice::findOrFail($id);
        $oldPrice = $price->price;
        $price->update($request->only(['region', 'price']));

        // ✅ BROADCAST NOTIFICATION: Update harga sawit
        $priceChange = $request->price - $oldPrice;
        $changeText = $priceChange > 0 
            ? 'naik Rp ' . number_format(abs($priceChange), 0, ',', '.') . ' 📈' 
            : ($priceChange < 0 
                ? 'turun Rp ' . number_format(abs($priceChange), 0, ',', '.') . ' 📉'
                : 'tetap stabil');

        Notification::create([
            'user_id' => null,  // NULL = broadcast
            'type'    => 'info',
            'title'   => 'Update Harga Sawit 💰',
            'message' => 'Harga sawit ' . $request->region . ' ' . $changeText . '. Harga baru: Rp ' . number_format($request->price, 0, ',', '.') . '/kg',
            'icon'    => '💰',
            'link'    => route('dashboard.harga-sawit'),
            'read_at' => null,
        ]);

        return redirect()->route('admin.harga-sawit.index')
            ->with('success', 'Harga sawit berhasil diperbarui & notifikasi dikirim!');
    }

    public function destroy($id)
    {
        $price = PalmPrice::findOrFail($id);
        $region = $price->region;
        $price->delete();

        // ✅ BROADCAST NOTIFICATION: Hapus harga sawit (opsional)
        Notification::create([
            'user_id' => null,  // NULL = broadcast
            'type'    => 'info',
            'title'   => 'Harga Sawit Dihapus 🗑️',
            'message' => 'Data harga sawit untuk region "' . $region . '" telah dihapus',
            'icon'    => 'ℹ️',
            'link'    => route('dashboard.harga-sawit'),
            'read_at' => null,
        ]);

        return redirect()->route('admin.harga-sawit.index')
            ->with('success', 'Harga sawit berhasil dihapus & notifikasi dikirim!');
    }
}
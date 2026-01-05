<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\DataKebun;
use App\Models\Panen;
use Illuminate\Http\Request;
use App\Models\Notification;

class AdminDashboardController extends Controller
{
    /**
     * Dashboard Admin - Beranda
     */
    public function beranda()
    {
        $totalUsers = User::count();
        $totalKebun = DataKebun::count();
        $totalPanen = Panen::count();

        return view('admin.beranda', compact(
            'totalUsers',
            'totalKebun', 
            'totalPanen'
        ));
    }

    /**
     * Kelola Users
     */
    public function users()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(20);
        
        return view('admin.users.index', compact('users'));
    }

    /**
     * Detail User
     */
    public function userDetail($id)
    {
        $user = User::with(['kebuns', 'panens'])->findOrFail($id);
        
        return view('admin.users.detail', compact('user'));
    }

    /**
     * Hapus User
     */
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->email === 'admin@gmail.com') {
            return back()->with('error', 'Tidak dapat menghapus akun admin!');
        }
        
        $user->delete();
        
        return redirect()->route('admin.users')
            ->with('success', 'User berhasil dihapus!');
    }

    /**
     * Data Kebun
     */
    public function kebun()
    {
        $kebuns = DataKebun::with('user')->orderBy('created_at', 'desc')->paginate(20);
        
        return view('admin.kebun.index', compact('kebuns'));
    }

    /**
     * Detail Kebun
     */
    public function kebunDetail($id)
    {
        $kebun = DataKebun::with(['user', 'panens'])->findOrFail($id);
        
        return view('admin.kebun.detail', compact('kebun'));
    }

    /**
     * Laporan
     */
    public function laporan()
    {
        $stats = [
            'total_users' => User::count(),
            'total_kebun' => DataKebun::count(),
            'total_luas' => DataKebun::sum('luas_lahan'),
            'total_panen' => Panen::count(),
            'total_produksi' => Panen::sum('berat_total_tbs'),
        ];

        $panenPerBulan = Panen::selectRaw('MONTH(tanggal_panen) as bulan, COUNT(*) as total')
            ->whereYear('tanggal_panen', date('Y'))
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        return view('admin.laporan', compact('stats', 'panenPerBulan'));
    }

    /**
     * Menu Update Data
     */
    public function updateData()
    {
        return view('admin.update-data.index');
    }
}
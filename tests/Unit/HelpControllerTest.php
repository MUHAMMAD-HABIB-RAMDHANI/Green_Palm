<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Http\Controllers\HelpController;

class HelpControllerTest extends TestCase
{
    /**
     * TC-01: Deteksi masalah teknis
     */
    public function test_TC01_klasifikasi_teknis()
    {
        $controller = new HelpController();
        $hasil = $controller->klasifikasiPesanBantuan("Tolong, aplikasi saya ERROR saat pencatatan kebun"); 
        // Walaupun ERROR huruf besar, sistem harusnya tetap bisa mendeteksi
        $this->assertEquals("Tinggi: Teknis", $hasil);
    }

    /**
     * TC-02: Deteksi pertanyaan panduan
     */
    public function test_TC02_klasifikasi_panduan()
    {
        $controller = new HelpController();
        $hasil = $controller->klasifikasiPesanBantuan("bagaimana cara ubah profil?");
        $this->assertEquals("Rendah: Panduan", $hasil);
    }

    /**
     * TC-03: Deteksi masalah keuangan
     */
    public function test_TC03_klasifikasi_keuangan()
    {
        $controller = new HelpController();
        $hasil = $controller->klasifikasiPesanBantuan("Toke bilang harga sawit turun, kenapa uang saya kurang?");
        $this->assertEquals("Sedang: Keuangan", $hasil);
    }

    /**
     * TC-04: Deteksi obrolan umum
     */
    public function test_TC04_klasifikasi_umum()
    {
        $controller = new HelpController();
        $hasil = $controller->klasifikasiPesanBantuan("Halo admin, semoga sehat selalu");
        $this->assertEquals("Normal: Umum", $hasil);
    }

    /**
     * TC-05: Validasi pesan kosong
     */
    public function test_TC05_pesan_kosong()
    {
        $controller = new HelpController();
        $hasil = $controller->klasifikasiPesanBantuan("   "); // Cuma spasi
        $this->assertEquals("Error: Pesan kosong", $hasil);
    }
}
<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Http\Controllers\AdminEdukasiController;

class AdminEdukasiControllerTest extends TestCase
{
    /**
     * Skenario 1: Admin mengisi semuanya dengan benar.
     */
    public function test_admin_input_lengkap()
    {
        $controller = new AdminEdukasiController();
        $hasil = $controller->validasiFormVideo("Cara Pruning Sawit", "https://youtube.com/watch?v=123");
        $this->assertEquals("Valid: Video siap dipublikasikan", $hasil);
    }

    /**
     * Skenario 2 (Kasus Anda): Admin isi judul, tapi lupa copy-paste URL.
     */
    public function test_admin_lupa_input_url()
    {
        $controller = new AdminEdukasiController();
        // Parameter pertama (judul) diisi, parameter kedua (URL) dikosongkan
        $hasil = $controller->validasiFormVideo("Cara Memupuk Sawit", ""); 
        $this->assertEquals("Error: URL video wajib diisi", $hasil);
    }

    /**
     * Skenario 3: Admin lupa judul, tapi terlanjur masukin URL.
     */
    public function test_admin_lupa_input_judul()
    {
        $controller = new AdminEdukasiController();
        $hasil = $controller->validasiFormVideo("", "https://youtube.com/watch?v=123");
        $this->assertEquals("Error: Judul video wajib diisi", $hasil);
    }

    /**
     * Skenario 4: Admin salah ketik URL (tidak pakai http/https).
     */
    public function test_admin_salah_format_url()
    {
        $controller = new AdminEdukasiController();
        $hasil = $controller->validasiFormVideo("Cara Panen", "youtube.com/watch?v=123"); 
        $this->assertEquals("Error: Format URL tidak valid (harus berupa link)", $hasil);
    }
}
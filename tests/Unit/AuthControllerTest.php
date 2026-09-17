<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Http\Controllers\Auth\AuthController; // Sesuaikan jika tidak di dalam folder Auth

class AuthControllerTest extends TestCase
{
    /**
     * TC-01: Password paling aman (Besar, Kecil, Angka, Simbol)
     */
    public function test_TC01_password_kuat()
    {
        $controller = new AuthController();
        $hasil = $controller->cekKekuatanPassword("T0k3_M4ntap!");
        $this->assertEquals("Kuat", $hasil);
    }

    /**
     * TC-02: Password menengah (Besar, Kecil, Angka, TANPA Simbol)
     */
    public function test_TC02_password_sedang()
    {
        $controller = new AuthController();
        $hasil = $controller->cekKekuatanPassword("SawitRiau2026");
        $this->assertEquals("Sedang", $hasil);
    }

    /**
     * TC-03: Password biasa (Kecil semua & Angka)
     */
    public function test_TC03_password_lemah()
    {
        $controller = new AuthController();
        $hasil = $controller->cekKekuatanPassword("petani1234");
        $this->assertEquals("Lemah", $hasil);
    }

    /**
     * TC-04: Password terlalu pendek
     */
    public function test_TC04_password_pendek()
    {
        $controller = new AuthController();
        $hasil = $controller->cekKekuatanPassword("sawit");
        $this->assertEquals("Error: Terlalu Pendek", $hasil);
    }

    /**
     * TC-05: Input kosong
     */
    public function test_TC05_password_kosong()
    {
        $controller = new AuthController();
        $hasil = $controller->cekKekuatanPassword("");
        $this->assertEquals("Error: Password kosong", $hasil);
    }
}
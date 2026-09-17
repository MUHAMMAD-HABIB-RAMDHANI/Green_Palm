<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase; // Pastikan menggunakan bawaan PHPUnit untuk test murni
use App\Http\Controllers\PanenController;

class PanenControllerTest extends TestCase
{
    /**
     * TC-01: Test input berat dan tanggal panen yang diisi dengan benar.
     */
    public function test_TC01_input_berat_dan_tanggal_valid()
    {
        $controller = new PanenController();
        $hasil = $controller->validasiInputPanen(500, "2026-04-02");
        $this->assertEquals("Valid", $hasil);
    }

    /**
     * TC-02: Test input berat dengan angka minus.
     */
    public function test_TC02_input_berat_minus_error()
    {
        $controller = new PanenController();
        $hasil = $controller->validasiInputPanen(-10, "2026-04-02");
        $this->assertEquals("Error: Berat tidak valid", $hasil);
    }

    /**
     * TC-03: Test input berat dengan angka nol.
     */
    public function test_TC03_input_berat_nol_error()
    {
        $controller = new PanenController();
        $hasil = $controller->validasiInputPanen(0, "2026-04-02");
        $this->assertEquals("Error: Berat tidak valid", $hasil);
    }

    /**
     * TC-04: Test input berat yang dikosongkan.
     */
    public function test_TC04_input_berat_kosong_error()
    {
        $controller = new PanenController();
        $hasil = $controller->validasiInputPanen("", "2026-04-02");
        $this->assertEquals("Error: Berat kosong", $hasil);
    }

    /**
     * TC-05: Test input berat diisi, tapi tanggal dikosongkan.
     */
    public function test_TC05_input_tanggal_kosong_error()
    {
        $controller = new PanenController();
        $hasil = $controller->validasiInputPanen(500, "");
        $this->assertEquals("Error: Tanggal kosong", $hasil);
    }
}
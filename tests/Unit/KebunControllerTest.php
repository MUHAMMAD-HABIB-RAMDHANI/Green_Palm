<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Http\Controllers\KebunController;

class KebunControllerTest extends TestCase
{
    /**
     * TC-01: Pohon usia < 3 tahun (Belum Menghasilkan)
     */
    public function test_TC01_estimasi_belum_menghasilkan()
    {
        $controller = new KebunController();
        $hasil = $controller->estimasiPotensiPanen(2, 2); 
        $this->assertEquals("Potensi: 0 Ton (Belum Menghasilkan)", $hasil);
    }

    /**
     * TC-02: Pohon usia 3-8 tahun (TM Muda)
     */
    public function test_TC02_estimasi_tm_muda()
    {
        $controller = new KebunController();
        $hasil = $controller->estimasiPotensiPanen(2, 5); // 2 ha * 1.5 ton
        $this->assertEquals("Potensi: 3 Ton", $hasil);
    }

    /**
     * TC-03: Pohon usia 9-15 tahun (TM Puncak)
     */
    public function test_TC03_estimasi_tm_puncak()
    {
        $controller = new KebunController();
        $hasil = $controller->estimasiPotensiPanen(4, 10); // 4 ha * 2.5 ton
        $this->assertEquals("Potensi: 10 Ton", $hasil);
    }

    /**
     * TC-04: Pohon usia > 15 tahun (Tanaman Tua)
     */
    public function test_TC04_estimasi_tanaman_tua()
    {
        $controller = new KebunController();
        $hasil = $controller->estimasiPotensiPanen(3, 20); // 3 ha * 1.8 ton
        $this->assertEquals("Potensi: 5.4 Ton", $hasil);
    }

    /**
     * TC-05 & TC-06: Validasi data minus atau nol
     */
    public function test_TC05_TC06_validasi_input_error()
    {
        $controller = new KebunController();
        
        // Test luas hektar minus
        $hasil1 = $controller->estimasiPotensiPanen(-1, 5);
        $this->assertEquals("Error: Data tidak valid", $hasil1);

        // Test usia nol
        $hasil2 = $controller->estimasiPotensiPanen(2, 0);
        $this->assertEquals("Error: Data tidak valid", $hasil2);
    }
}
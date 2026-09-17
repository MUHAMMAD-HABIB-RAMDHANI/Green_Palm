<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Http\Controllers\PerawatanController;

class PerawatanControllerTest extends TestCase
{
    /**
     * TC-01: Pohon 6 bulan
     */
    public function test_TC01_fase_pembibitan()
    {
        $controller = new PerawatanController();
        $hasil = $controller->rekomendasiPupuk(6, "mineral");
        $this->assertEquals("Fokus Urea & ZA", $hasil);
    }

    /**
     * TC-02: Pohon 24 bulan (2 tahun)
     */
    public function test_TC02_fase_tbm()
    {
        $controller = new PerawatanController();
        $hasil = $controller->rekomendasiPupuk(24, "gambut"); // Gambut atau mineral sama saja di fase ini
        $this->assertEquals("NPK Rutin", $hasil);
    }

    /**
     * TC-03: Pohon 60 bulan (5 tahun) di tanah Mineral
     */
    public function test_TC03_fase_tm_mineral()
    {
        $controller = new PerawatanController();
        $hasil = $controller->rekomendasiPupuk(60, "mineral");
        $this->assertEquals("NPK + KCl", $hasil);
    }

    /**
     * TC-04: Pohon 60 bulan (5 tahun) di tanah Gambut
     */
    public function test_TC04_fase_tm_gambut()
    {
        $controller = new PerawatanController();
        // Coba pakai huruf besar untuk memastikan validasi (strtolower) berjalan
        $hasil = $controller->rekomendasiPupuk(60, "GAMBUT"); 
        $this->assertEquals("NPK + Ekstra Cu & Zn", $hasil);
    }

    /**
     * TC-05: Input usia minus
     */
    public function test_TC05_usia_error()
    {
        $controller = new PerawatanController();
        $hasil = $controller->rekomendasiPupuk(-5, "mineral");
        $this->assertEquals("Error: Usia tidak valid", $hasil);
    }

    /**
     * TC-06: Input tanah yang tidak dikenal
     */
    public function test_TC06_tanah_error()
    {
        $controller = new PerawatanController();
        $hasil = $controller->rekomendasiPupuk(40, "pasir");
        $this->assertEquals("Error: Tanah tidak terdaftar", $hasil);
    }
}
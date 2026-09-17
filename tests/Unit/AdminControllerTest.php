<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Http\Controllers\AdminController; 

class AdminControllerTest extends TestCase
{
    /**
     * TC-01: Data lengkap dan usia cukup
     */
    public function test_TC01_akun_disetujui()
    {
        $controller = new AdminController();
        // Parameter: Usia 25, KTP True, Lahan True
        $hasil = $controller->verifikasiPendaftaran(25, true, true); 
        $this->assertEquals("Akun Disetujui", $hasil);
    }

    /**
     * TC-02: Usia masih di bawah 18 tahun
     */
    public function test_TC02_usia_tidak_cukup()
    {
        $controller = new AdminController();
        $hasil = $controller->verifikasiPendaftaran(16, true, true);
        $this->assertEquals("Ditolak: Usia di bawah ketentuan", $hasil);
    }

    /**
     * TC-03: Tidak melampirkan KTP
     */
    public function test_TC03_tanpa_ktp_ditolak()
    {
        $controller = new AdminController();
        $hasil = $controller->verifikasiPendaftaran(30, false, true);
        $this->assertEquals("Ditolak: KTP wajib dilampirkan", $hasil);
    }

    /**
     * TC-04: KTP ada, tapi bukti lahan belum ada (Pending)
     */
    public function test_TC04_tanpa_lahan_ditangguhkan()
    {
        $controller = new AdminController();
        $hasil = $controller->verifikasiPendaftaran(40, true, false);
        $this->assertEquals("Ditangguhkan: Menunggu bukti lahan", $hasil);
    }

    /**
     * TC-05: Input usia error/tidak masuk akal
     */
    public function test_TC05_usia_error()
    {
        $controller = new AdminController();
        $hasil = $controller->verifikasiPendaftaran(0, false, false);
        $this->assertEquals("Error: Data tidak valid", $hasil);
    }
}
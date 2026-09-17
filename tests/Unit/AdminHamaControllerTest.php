<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Http\Controllers\Admin\AdminHamaController; // Sesuaikan dengan namespace Anda

class AdminHamaControllerTest extends TestCase
{
    /**
     * TC-01 (CREATE): Admin menambah data baru dengan lengkap (Nama dan Foto ada)
     */
    public function test_create_data_lengkap_valid()
    {
        $controller = new AdminHamaController();
        $hasil = $controller->validasiFormHama("create", "Ulat Api", "ulat.jpg");
        $this->assertEquals("Valid: Data baru siap disimpan", $hasil);
    }

    /**
     * TC-02 (CREATE): Admin menambah data baru, tapi LUPA upload foto
     * Harusnya ditolak, karena data baru butuh foto awal.
     */
    public function test_create_lupa_foto_error()
    {
        $controller = new AdminHamaController();
        $hasil = $controller->validasiFormHama("create", "Kumbang Tanduk", "");
        $this->assertEquals("Error: Foto wajib diunggah untuk data baru", $hasil);
    }

    /**
     * TC-03 (EDIT): Admin mengedit nama hama, tapi TIDAK upload foto baru
     * Harusnya valid, karena sistem akan menggunakan foto yang lama di database.
     */
    public function test_edit_tanpa_ubah_foto_valid()
    {
        $controller = new AdminHamaController();
        $hasil = $controller->validasiFormHama("edit", "Ulat Api Ganas", "");
        $this->assertEquals("Valid: Update teks saja (tanpa ubah foto)", $hasil);
    }

    /**
     * TC-04 (EDIT): Admin mengedit nama hama DAN mengupload foto baru
     */
    public function test_edit_ganti_foto_baru_valid()
    {
        $controller = new AdminHamaController();
        $hasil = $controller->validasiFormHama("edit", "Tikus Kebun", "tikus_terbaru.png");
        $this->assertEquals("Valid: Update teks dan ganti foto baru", $hasil);
    }

    /**
     * TC-05 (CREATE/EDIT): Admin lupa mengisi nama hama
     */
    public function test_lupa_isi_nama_error()
    {
        $controller = new AdminHamaController();
        
        // Dites pada mode create
        $hasil = $controller->validasiFormHama("create", "", "gambar.jpg");
        $this->assertEquals("Error: Nama hama wajib diisi", $hasil);
    }

    /**
     * TC-06 (CREATE/EDIT): Admin salah upload jenis file (misal PDF)
     */
    public function test_format_foto_salah_error()
    {
        $controller = new AdminHamaController();
        
        // Dites pada mode edit
        $hasil = $controller->validasiFormHama("edit", "Babi Hutan", "laporan.pdf");
        $this->assertEquals("Error: Format foto tidak valid (hanya jpg, jpeg, png)", $hasil);
    }
}
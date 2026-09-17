<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\DataKebun; // Sesuaikan dengan nama model kebun Anda

class PencatatanKebunTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Setup user yang sudah login untuk setiap test
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    /**
     * Test Case 1: Input Data panen (tanggal, jumlah, lokasi).
     * Expected: Data tersimpan di sistem.
     */
    public function test_user_can_input_data_panen_correctly()
    {
        // Sesuaikan parameter array dengan field di database Anda
        $response = $this->post('/panen', [
            'tanggal_panen' => '2026-04-02',
            'jumlah_tandan' => 150,
            'berat_kg' => 2000,
            'lokasi' => 'Blok A'
        ]);

        // Validasi: Data masuk ke database panens
        $response->assertStatus(302); // Biasanya redirect setelah sukses submit
        $this->assertDatabaseHas('panens', [
            'jumlah_tandan' => 150,
            'lokasi' => 'Blok A'
        ]);
    }

    /**
     * Test Case 2: Input Data kosong.
     * Expected: Sistem menolak input.
     */
    public function test_system_rejects_empty_panen_input()
    {
        $response = $this->post('/panen', [
            'tanggal_panen' => '',
            'jumlah_tandan' => '',
            'berat_kg' => '',
            'lokasi' => ''
        ]);

        // Validasi: Ada error session untuk field yang required
        $response->assertSessionHasErrors(['tanggal_panen', 'jumlah_tandan', 'berat_kg', 'lokasi']);
    }
}
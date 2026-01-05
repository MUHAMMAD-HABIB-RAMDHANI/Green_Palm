<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penunasans', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke User & Kebun
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('kebun_id')->nullable()->constrained('data_kebun')->onDelete('set null');

            // Field sesuai gambar
            $table->date('tanggal_penunasan');
            $table->integer('jumlah_pokok_ditunas');        // Jumlah Pokok yang Ditunas
            $table->decimal('total_upah', 15, 2);           // Total Upah Penunasan (Rp)
            $table->enum('ada_biaya_lain', ['Ya', 'Tidak'])->default('Tidak'); // Apakah Ada Biaya Lainnya Selain Upah?
            $table->decimal('biaya_lain', 15, 2)->nullable(); // Biaya Lain (jika ada)

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penunasans');
    }
};
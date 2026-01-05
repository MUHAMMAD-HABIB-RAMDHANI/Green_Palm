<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penyemprotans', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('kebun_id')->nullable()->constrained('data_kebun')->onDelete('set null');

            $table->date('tanggal_penyemprotan');
            $table->string('jenis_pestisida_racun');           // Jenis Pestisida/Racun (Jika Ada)
            $table->decimal('penggunaan_pestisida', 10, 2);    // Penggunaan Pestisida/Racun (liter)
            $table->decimal('luas_lahan_disemprot', 10, 2);    // Luas Lahan yang Disemprot (ha)
            $table->decimal('total_upah', 15, 2);              // Total Upah Penyemprotan (Rp)
            $table->enum('ada_biaya_lain', ['Ya', 'Tidak'])->default('Tidak');
            $table->decimal('biaya_lain', 15, 2)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penyemprotans');
    }
};
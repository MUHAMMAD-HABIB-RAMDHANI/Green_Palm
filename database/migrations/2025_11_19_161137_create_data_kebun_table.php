<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_kebun', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nama_kebun');        // ✅ Ubah dari 'nama'
            $table->string('lokasi_kebun');      // ✅ Ubah dari 'lokasi'
            $table->decimal('luas_lahan', 10, 2);
            $table->integer('jumlah_hektar');
            $table->integer('tahun_tanam');
            $table->boolean('tahu_jenis_bibit')->default(0); // ✅ Ubah dari enum 'jenis_bibit'
            $table->string('jenis_tanah')->nullable();
            $table->timestamps();
            
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_kebun');
    }
};
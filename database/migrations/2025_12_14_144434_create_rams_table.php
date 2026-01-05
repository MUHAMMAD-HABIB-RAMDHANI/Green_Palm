<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nama_ram');
            $table->string('lokasi_ram');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->decimal('harga_beli_tbs', 10, 2); // Harga per kg
            $table->boolean('layanan_jemput_buah')->default(false);
            $table->boolean('timbangan_digital')->default(false);
            $table->boolean('menerima_berondolan')->default(false);
            $table->boolean('tidak_ada_pengembalian')->default(false);
            $table->string('foto_tampak_depan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rams');
    }
};
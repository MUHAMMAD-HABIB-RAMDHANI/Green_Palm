<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('panens', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke User & Kebun
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('kebun_id')->constrained('data_kebun')->onDelete('cascade');

            // Hasil Panen
            $table->date('tanggal_panen');
            $table->decimal('berat_total_tbs', 10, 2);          // Berat Total TBS (kg)
            $table->integer('jumlah_tbs')->nullable();           // Jumlah TBS (Jika Ada)
            $table->decimal('berat_brondolan', 10, 2)->nullable(); // Berat Brondolan (kg) (Jika Ada)
            $table->date('tanggal_panen_berikutnya')->nullable(); // Tanggal Panen Berikutnya

            // Upah Panen & Biaya Lainnya
            $table->decimal('total_upah_panen', 15, 2)->default(0);
            $table->text('biaya_lainnya')->nullable(); // JSON untuk multiple upah panen

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('panens');
    }
};
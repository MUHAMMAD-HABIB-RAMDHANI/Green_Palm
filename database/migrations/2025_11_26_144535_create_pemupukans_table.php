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
        Schema::create('pemupukans', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke User
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Jika nanti ingin dikaitkan dengan kebun tertentu
            $table->foreignId('kebun_id')->nullable()->constrained('data_kebun')->onDelete('set null');

            // Field sesuai input form
            $table->date('tanggal_pemupukan');
            $table->string('jenis_pupuk');
            $table->decimal('total_pupuk', 10, 2);      // Kg
            $table->decimal('pupuk_per_pokok', 8, 2);   // Kg/Pokok
            $table->decimal('total_upah', 15, 2);       // Rp
            $table->decimal('biaya_pembelian', 15, 2)->nullable(); // Rp (Jika Ada)

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemupukans');
    }
};
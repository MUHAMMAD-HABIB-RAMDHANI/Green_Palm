<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sanitasis', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('kebun_id')->nullable()->constrained('data_kebun')->onDelete('set null');

            $table->date('tanggal_sanitasi');
            $table->integer('jumlah_pokok_disanitasi');      // Jumlah Pokok yang Disanitasi
            $table->decimal('total_upah', 15, 2);            // Total Upah Sanitasi (Rp)
            $table->enum('ada_biaya_lain', ['Ya', 'Tidak'])->default('Tidak');
            $table->decimal('biaya_lain', 15, 2)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sanitasis');
    }
};
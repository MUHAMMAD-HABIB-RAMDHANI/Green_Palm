<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penyakits', function (Blueprint $table) {
            // Mengubah tipe kolom 'solution' menjadi TEXT
            $table->text('solution')->change();
        });
    }

    public function down(): void
    {
        Schema::table('penyakits', function (Blueprint $table) {
            // Kembalikan ke string (VARCHAR 255) jika di-rollback
            // Pastikan data tidak panjang saat rollback agar tidak error
            $table->string('solution', 255)->change();
        });
    }
};

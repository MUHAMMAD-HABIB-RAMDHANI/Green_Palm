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
        // Kosongkan data lama dulu karena struktur berubah total (opsional, biar aman)
        \DB::table('kabar_sawits')->truncate(); 

        Schema::table('kabar_sawits', function (Blueprint $table) {
            // 1. Hapus kolom 'content' karena tidak dipakai lagi
            $table->dropColumn('content');

            // 2. Tambah kolom 'url' untuk link berita
            $table->string('url')->after('title'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kabar_sawits', function (Blueprint $table) {
            // Kembalikan seperti semula jika rollback
            $table->dropColumn('url');
            $table->text('content')->nullable();
        });
    }
};
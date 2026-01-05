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
        // Cek dulu apakah kolom 'user_id' SUDAH ADA di tabel 'notifications'
        if (!Schema::hasColumn('notifications', 'user_id')) {
            Schema::table('notifications', function (Blueprint $table) {
                $table->foreignId('user_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('users')
                    ->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            // 1. Hapus Foreign Key Constraint dulu (Wajib urutan ini)
            // Format default nama constraint laravel: nama_tabel_nama_kolom_foreign
            $table->dropForeign(['user_id']);

            // 2. Baru hapus kolomnya
            $table->dropColumn('user_id');
        });
    }
};
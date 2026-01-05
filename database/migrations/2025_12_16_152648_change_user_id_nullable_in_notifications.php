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
        Schema::table('notifications', function (Blueprint $table) {
            // Kita gunakan method ->change() untuk mengubah kolom yang SUDAH ADA
            // agar menerima nilai NULL
            $table->unsignedBigInteger('user_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            // Kembalikan ke tidak boleh NULL (hati-hati jika sudah ada data NULL)
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
        });
    }
};
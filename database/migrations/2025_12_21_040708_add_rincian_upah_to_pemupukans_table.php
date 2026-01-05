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
        Schema::table('pemupukans', function (Blueprint $table) {
            // Menambahkan kolom rincian_upah (tipe text untuk menyimpan JSON)
            // Ini mirip dengan 'biaya_lainnya' di tabel panen
            $table->text('rincian_upah')->nullable()->after('total_upah');
            
            // Kita pastikan total_upah defaultnya 0 jika belum
            // (Opsional, tapi praktik bagus)
            $table->decimal('total_upah', 15, 2)->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemupukans', function (Blueprint $table) {
            $table->dropColumn('rincian_upah');
        });
    }
};
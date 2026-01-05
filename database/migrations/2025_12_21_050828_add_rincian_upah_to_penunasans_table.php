<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penunasans', function (Blueprint $table) {
            // Kolom untuk menyimpan JSON rincian
            $table->text('rincian_upah')->nullable()->after('total_upah');
            
            // Ubah total_upah agar default 0 (jika belum)
            $table->decimal('total_upah', 15, 2)->default(0)->change();
        });
    }

    public function down(): void
    {
        Schema::table('penunasans', function (Blueprint $table) {
            $table->dropColumn('rincian_upah');
        });
    }
};
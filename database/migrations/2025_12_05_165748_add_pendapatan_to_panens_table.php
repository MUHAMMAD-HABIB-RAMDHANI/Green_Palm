<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('panens', function (Blueprint $table) {
            // Menambahkan kolom pendapatan dengan tipe decimal (15 digit, 2 desimal)
            // 'after' digunakan untuk meletakkan kolom setelah kolom tertentu (opsional)
            $table->decimal('pendapatan', 15, 2)->default(0)->after('biaya_lainnya');
        });
    }

    public function down(): void
    {
        Schema::table('panens', function (Blueprint $table) {
            // Menghapus kolom jika migration di-rollback
            $table->dropColumn('pendapatan');
        });
    }
};
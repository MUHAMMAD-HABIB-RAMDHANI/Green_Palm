<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kastrasis', function (Blueprint $table) {
            $table->text('rincian_upah')->nullable()->after('total_upah');
            // Pastikan total_upah memiliki default 0
            $table->decimal('total_upah', 15, 2)->default(0)->change();
        });
    }

    public function down(): void
    {
        Schema::table('kastrasis', function (Blueprint $table) {
            $table->dropColumn('rincian_upah');
        });
    }
};
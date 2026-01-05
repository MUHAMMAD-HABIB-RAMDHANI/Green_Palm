<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('palm_prices', function (Blueprint $table) {
            $table->dropColumn('updated_at_date');
        });
    }

    public function down(): void
    {
        Schema::table('palm_prices', function (Blueprint $table) {
            $table->date('updated_at_date')->nullable();
        });
    }
};
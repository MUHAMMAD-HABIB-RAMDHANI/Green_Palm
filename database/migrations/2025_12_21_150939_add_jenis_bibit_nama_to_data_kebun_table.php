<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('data_kebun', function (Blueprint $table) {
            $table->string('jenis_bibit_nama')->nullable()->after('tahu_jenis_bibit');
        });
    }

    public function down()
    {
        Schema::table('data_kebun', function (Blueprint $table) {
            $table->dropColumn('jenis_bibit_nama');
        });
    }
};
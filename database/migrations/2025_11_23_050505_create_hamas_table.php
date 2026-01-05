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
        Schema::create('hamas', function (Blueprint $table) {
            $table->id();
            $table->string('name');        // Nama Hama (misal: Ulat Api)
            $table->string('latin_name')->nullable(); // Nama Latin (misal: Sethotosa asigna)
            $table->string('image')->nullable(); // Path gambar
            $table->text('description')->nullable(); // Penjelasan detail (untuk fitur detail nanti)
            $table->string('solution')->nullable();  // Cara penanganan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hamas');
    }
};

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
        Schema::create('kabar_sawits', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('url'); // <--- URL Link ke berita asli
            $table->string('image')->nullable(); // Gambar Header (Thumbnail)
            $table->string('category')->default('Umum');
            $table->boolean('is_popular')->default(false);
            $table->date('published_at')->default(now());
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kabar_sawits');
    }
};

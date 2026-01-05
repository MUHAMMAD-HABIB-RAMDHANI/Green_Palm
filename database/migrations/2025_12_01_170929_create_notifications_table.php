<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // 'video_edukasi', 'harga_sawit', 'penyakit', 'hama'
            $table->string('title');
            $table->text('message');
            $table->string('icon')->default('🔔'); // Emoji icon
            $table->string('link')->nullable(); // Link tujuan
            $table->json('data')->nullable(); // Data tambahan (video_id, dll)
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            
            $table->index('created_at');
            $table->index('read_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
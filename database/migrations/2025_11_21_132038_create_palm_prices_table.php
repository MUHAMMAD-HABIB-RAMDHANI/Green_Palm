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
        Schema::create('palm_prices', function (Blueprint $table) {
            $table->id();
            $table->string('region');        // Nama Daerah (misal: Situbondo, Jawa Timur)
            $table->decimal('price', 10, 2); // Harga (misal: 3568.00)
            $table->date('updated_at_date'); // Tanggal data diperbarui (tanggal manual dari admin)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('palm_prices');
    }
};

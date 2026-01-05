<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Tambahkan kolom-kolom profil, semua nullable
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone', 20)->nullable();
            }
            if (!Schema::hasColumn('users', 'gender')) {
                $table->string('gender', 20)->nullable(); // validasi nilainya di controller
            }
            if (!Schema::hasColumn('users', 'birth_date')) {
                $table->date('birth_date')->nullable();
            }
            if (!Schema::hasColumn('users', 'profile_picture')) {
                $table->string('profile_picture', 255)->nullable();
            }
        });

        // (Opsional tapi disarankan) pastikan username unik di level DB
        try {
            Schema::table('users', function (Blueprint $table) {
                $table->unique('username', 'users_username_unique');
            });
        } catch (\Throwable $e) {
            // abaikan jika index sudah ada
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // hapus unique index jika ada
            try { $table->dropUnique('users_username_unique'); } catch (\Throwable $e) {}

            // hapus kolom tambahan
            if (Schema::hasColumn('users','profile_picture')) $table->dropColumn('profile_picture');
            if (Schema::hasColumn('users','birth_date'))      $table->dropColumn('birth_date');
            if (Schema::hasColumn('users','gender'))          $table->dropColumn('gender');
            if (Schema::hasColumn('users','phone'))           $table->dropColumn('phone');
        });
    }
};

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
        Schema::table('users', function (Blueprint $table) {
            // Tambah kolom untuk foto profil
            if (!Schema::hasColumn('users', 'profile_picture')) {
                $table->string('profile_picture')->nullable()->after('email');
            }
            
            // Tambah kolom untuk no handphone
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone', 20)->nullable()->after('email');
            }
            
            // Tambah kolom untuk jenis kelamin
            if (!Schema::hasColumn('users', 'gender')) {
                $table->enum('gender', ['Laki-laki', 'Perempuan'])->nullable()->after('email');
            }
            
            // Tambah kolom untuk tanggal lahir
            if (!Schema::hasColumn('users', 'birth_date')) {
                $table->date('birth_date')->nullable()->after('email');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['profile_picture', 'phone', 'gender', 'birth_date']);
        });
    }
};

/**
 * Cara Generate Migration:
 * 
 * php artisan make:migration add_profile_fields_to_users_table
 * 
 * Lalu copy paste kode di atas ke file migration yang dibuat
 * 
 * Jalankan:
 * php artisan migrate
 * 
 * Struktur users setelah migration:
 * - id
 * - username (nama pengguna)
 * - email
 * - password
 * - profile_picture (foto profil)
 * - phone (no handphone)
 * - gender (jenis kelamin: Laki-laki/Perempuan)
 * - birth_date (tanggal lahir)
 * - email_verified_at
 * - created_at
 * - updated_at
 */
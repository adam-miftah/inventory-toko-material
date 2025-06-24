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
        Schema::table('items', function (Blueprint $table) {
            // Periksa apakah kolom 'unit' ada sebelum mencoba mengubahnya
            if (Schema::hasColumn('items', 'unit')) {
                // Ubah kolom 'unit' menjadi boleh kosong (nullable)
                $table->string('unit', 50)->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            if (Schema::hasColumn('items', 'unit')) {
                // Mengembalikan kolom 'unit' menjadi TIDAK boleh kosong (not nullable)
                // PENTING: Jika Anda sudah punya data NULL di kolom ini, perintah ini akan gagal.
                // Anda mungkin perlu menyediakan nilai default atau membersihkan data NULL terlebih dahulu.
                $table->string('unit', 50)->nullable(false)->change();
            }
        });
    }
};

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
            // Pastikan kolom ini tidak ada sebelum menambahkannya
            if (!Schema::hasColumn('items', 'description')) {
                $table->text('description')->nullable()->after('stock'); // Atau posisi lain yang Anda inginkan
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            if (Schema::hasColumn('items', 'description')) {
                $table->dropColumn('description');
            }
        });
    }
};

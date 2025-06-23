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
        Schema::table('retur_pembelians', function (Blueprint $table) {
            // Tambahkan kolom string untuk nomor retur, bisa null, letakkan setelah kolom 'id'
            $table->string('return_number')->nullable()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('retur_pembelians', function (Blueprint $table) {
            $table->dropColumn('return_number');
        });
    }
};
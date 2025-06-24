<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('keramiks', function (Blueprint $table) {
            // Untuk mereset ID, kita akan menggunakan cara manual saat insert
            // dan tidak bergantung pada auto-increment bawaan database
            $table->unsignedBigInteger('id'); // ID manual
            $table->string('name');
            $table->string('size'); // Ukuran keramik
            $table->decimal('purchase_price', 10, 2); // Harga Modal
            $table->decimal('selling_price', 10, 2); // Harga Jual
            $table->string('unit'); // Satuan (contoh: dus, meter persegi)
            $table->integer('stock');
            $table->timestamps();

            // Menentukan ID sebagai primary key setelah didefinisikan
            $table->primary('id');
        });

        // Mengatur auto increment dimulai dari 1 setelah setiap truncate atau jika tabel kosong
        // Ini adalah implementasi untuk "mereset ID ke 1"
        DB::statement('ALTER TABLE keramiks AUTO_INCREMENT = 1;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keramiks');
    }
};
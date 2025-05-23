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
        Schema::create('cats', function (Blueprint $table) {
            // Untuk mereset ID, kita akan menggunakan cara manual saat insert
            // dan tidak bergantung pada auto-increment bawaan database
            $table->unsignedBigInteger('id'); // ID manual
            $table->string('name');
            $table->string('type_of_paint'); // Jenis Cat (contoh: tembok, kayu, besi)
            $table->string('color'); // Warna
            $table->string('code')->nullable(); // Kode warna
            $table->decimal('weight', 8, 2); // Berat (contoh: kg, liter)
            $table->decimal('price', 10, 2); // Harga
            $table->integer('stock');
            $table->timestamps();

            // Menentukan ID sebagai primary key setelah didefinisikan
            $table->primary('id');
        });

        // Mengatur auto increment dimulai dari 1 setelah setiap truncate atau jika tabel kosong
        // Ini adalah implementasi untuk "mereset ID ke 1"
        DB::statement('ALTER TABLE cats AUTO_INCREMENT = 1;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cats');
    }
};
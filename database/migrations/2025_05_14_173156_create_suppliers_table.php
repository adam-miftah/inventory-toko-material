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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id(); // Ini akan membuat kolom 'id' sebagai primary key auto-incrementing bigint unsigned
            $table->string('name'); // Nama supplier (misalnya, "PT Maju Jaya")
            $table->string('contact_person')->nullable(); // Nama kontak person di supplier (opsional)
            $table->string('email')->nullable(); // Alamat email supplier (opsional)
            $table->string('phone')->nullable(); // Nomor telepon supplier (opsional)
            $table->text('address')->nullable(); // Alamat lengkap supplier (opsional, bisa lebih panjang dari string biasa)
            $table->timestamps(); // Membuat kolom 'created_at' dan 'updated_at' untuk mencatat waktu pembuatan dan pembaruan data
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers'); // Menghapus tabel 'suppliers' jika migrasi di-rollback
    }
};
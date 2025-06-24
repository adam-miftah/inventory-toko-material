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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            // PERBAIKAN: Menambahkan kolom purchase_price (Harga Modal)
            $table->decimal('purchase_price', 10, 2)->nullable()->default(0); // Harga Modal
            $table->decimal('price', 10, 2); // Harga Jual
            $table->string('unit')->nullable(); // Satuan dibuat nullable
            $table->integer('stock');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->text('description')->nullable();

            // Kolom spesifik (dibuat nullable agar fleksibel)
            $table->string('color_name', 100)->nullable();
            $table->string('color_code', 50)->nullable();
            $table->string('paint_type', 100)->nullable();
            $table->string('volume', 50)->nullable();
            $table->string('size', 100)->nullable();
            $table->string('texture', 100)->nullable();
            $table->string('motif', 100)->nullable();
            $table->string('grade', 50)->nullable();
            $table->string('finish_type', 100)->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
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
        Schema::create('items', function (Blueprint $table) {
            $table->id(); // Ini akan menjadi ID yang otomatis naik
            $table->string('name');
            $table->decimal('price', 10, 2);
            $table->string('unit'); // Satuan (contoh: pcs, kg, meter)
            $table->integer('stock');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade'); // Foreign key ke categories
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
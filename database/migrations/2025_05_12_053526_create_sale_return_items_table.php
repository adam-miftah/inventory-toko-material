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
        Schema::create('sale_return_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_return_id')->constrained('sale_returns')->onDelete('cascade');
            $table->foreignId('item_id')->constrained('items')->onDelete('cascade'); // Barang yang diretur
            $table->integer('quantity'); // Kuantitas yang diretur
            $table->decimal('price_per_unit', 15, 2); // Harga per unit saat penjualan
            $table->decimal('subtotal', 15, 2); // Subtotal untuk item ini
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_return_items');
    }
};
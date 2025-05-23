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
        Schema::create('retur_pembelian_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('retur_pembelian_id')->constrained()->onDelete('cascade');
            $table->foreignId('pembelian_item_id')->nullable()->constrained()->onDelete('set null'); // Referensi ke item pembelian yang diretur
            $table->foreignId('item_id')->constrained()->onDelete('cascade');
            $table->string('item_name');
            $table->integer('quantity')->unsigned();
            $table->decimal('unit_price', 15, 2);
            $table->decimal('subtotal_returned', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('retur_pembelian_items');
    }
};
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
        Schema::create('sale_returns', function (Blueprint $table) {
            $table->id();
            $table->string('return_number')->unique(); // Nomor retur, bisa auto-generated atau manual
            $table->foreignId('sale_id')->nullable()->constrained('sales')->onDelete('set null'); // Relasi ke penjualan asli
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // User yang melakukan retur (kasir)
            $table->timestamp('return_date')->useCurrent(); // Tanggal retur
            $table->decimal('total_returned_amount', 15, 2); // Total nilai barang yang diretur
            $table->decimal('refund_amount', 15, 2)->nullable(); // Jumlah uang yang dikembalikan ke pelanggan
            $table->text('reason')->nullable(); // Alasan retur
            $table->text('notes')->nullable(); // Catatan tambahan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_returns');
    }
};
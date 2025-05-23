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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->date('sale_date');
            $table->string('customer_name')->nullable(); // Bisa dikembangkan dengan customer_id
            $table->decimal('total_amount', 10, 2); // Total item sebelum diskon/pajak
            $table->decimal('discount_amount', 10, 2)->default(0.00);
            $table->decimal('tax_amount', 10, 2)->default(0.00);
            $table->decimal('grand_total', 10, 2); // Total akhir
            $table->string('payment_method'); // cash, card, transfer, etc.
            $table->decimal('paid_amount', 10, 2); // Jumlah uang yang dibayarkan oleh pelanggan
            $table->decimal('change_amount', 10, 2); // Jumlah kembalian
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Kasir/User yang melakukan transaksi
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
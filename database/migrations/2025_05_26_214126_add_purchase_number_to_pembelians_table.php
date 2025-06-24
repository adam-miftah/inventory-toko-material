
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
        Schema::table('pembelians', function (Blueprint $table) {
            // Tambahkan kolom purchase_number setelah kolom 'id' atau 'purchase_date'
            // Pastikan unik dan tidak boleh null
            $table->string('purchase_number')->unique()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembelians', function (Blueprint $table) {
            $table->dropColumn('purchase_number');
        });
    }
};
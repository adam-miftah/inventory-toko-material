<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSpecificItemFieldsToItemsTable extends Migration
{
    /**
     * Jalankan migrasi.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            // For Keramik
            $table->string('size', 100)->nullable()->after('stock'); // e.g., 60x60, 30x30
            $table->string('texture', 100)->nullable()->after('size'); // e.g., Glossy, Matte, Rustic
            $table->string('motif', 100)->nullable()->after('texture'); // e.g., Marble, Wood, Plain
            $table->string('grade', 50)->nullable()->after('motif'); // e.g., KW1, KW2, KW3
            $table->string('finish_type', 50)->nullable()->after('grade'); // e.g., Glazed, Unglazed

            // For Cat
            $table->string('color_name', 100)->nullable()->after('finish_type'); // e.g., White, Maroon
            $table->string('color_code', 50)->nullable()->after('color_name'); // e.g., #FFFFFF, RAL 3003
            $table->string('paint_type', 100)->nullable()->after('color_code'); // e.g., Wall Paint, Wood & Iron Paint
            $table->string('volume', 50)->nullable()->after('paint_type'); // e.g., 1 Liter, 5 Kg, 2.5 Liter

            // For Keramik (Harga Modal)
            $table->decimal('purchase_price', 10, 2)->nullable()->after('price'); // Harga Modal
        });
    }

    /**
     * Balikkan migrasi.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn([
                'size',
                'texture',
                'motif',
                'grade',
                'finish_type',
                'color_name',
                'color_code',
                'paint_type',
                'volume',
                'purchase_price',
            ]);
        });
    }
}
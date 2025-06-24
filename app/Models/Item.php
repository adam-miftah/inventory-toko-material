<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    /**
     * PERBAIKAN: Menambahkan 'purchase_price' ke fillable.
     * Semua kolom spesifik juga dimasukkan agar bisa diisi.
     */
    protected $fillable = [
        'category_id',
        'name',
        'price',            // Ini akan jadi Harga Jual
        'purchase_price',   // PERBAIKAN: Ini Harga Modal untuk semua barang
        'unit',
        'stock',
        'description',
        // Bidang spesifik untuk Cat
        'color_name',
        'color_code',
        'paint_type',
        'volume',
        // Bidang spesifik untuk Keramik
        'size',
        'texture',
        'motif',
        'grade',
        'finish_type',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Tambahkan accessor ini jika Anda ingin memastikan nilainya selalu numerik
    public function getPurchasePriceAttribute($value)
    {
        return $value ?? 0;
    }
}
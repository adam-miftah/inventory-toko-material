<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'price',          // Ini akan jadi Harga Jual
        'purchase_price', // Ini Harga Modal
        'unit',           // Harus diizinkan NULL di database
        'stock',
        'description',
        // Bidang spesifik untuk Cat
        'color_name',
        'color_code',
        'paint_type',     // Untuk Jenis Cat
        'volume',         // Untuk Berat/Volume Cat

        // Bidang spesifik untuk Keramik
        'size',
        'texture',
        'motif',
        'grade',
        'finish_type',    // Opsional, jika ingin ada finish type untuk keramik
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
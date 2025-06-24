<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{
    use HasFactory;
    protected $table = 'sale_items';

    protected $fillable = [
        'sale_id',
        'item_id',
        'item_name',
        'quantity',
        'unit_price',
        'subtotal',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class); // Asumsi relasi ke model Item utama
    }
}
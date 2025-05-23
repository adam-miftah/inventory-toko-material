<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleReturnItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_return_id',
        'item_id',
        'quantity',
        'price_per_unit',
        'subtotal',
    ];

    protected $casts = [
        'price_per_unit' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function saleReturn()
    {
        return $this->belongsTo(SaleReturn::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class); // Pastikan Anda memiliki model Item
    }
}
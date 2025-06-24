<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PembelianItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'pembelian_id',
        'item_id',
        'item_name',
        'quantity',
        'unit_price',
        'subtotal',
    ];

    public function pembelian(): BelongsTo
    {
        return $this->belongsTo(Pembelian::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function getSubtotalFormattedAttribute()
    {
        return 'Rp ' . number_format($this->subtotal, 0, ',', '.');
    }

    public function getUnitPriceFormattedAttribute()
    {
        return 'Rp ' . number_format($this->unit_price, 0, ',', '.');
    }
}
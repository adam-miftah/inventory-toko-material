<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReturPembelianItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'retur_pembelian_id',
        'pembelian_item_id', // Untuk referensi ke item pembelian yang diretur
        'item_id',
        'item_name',
        'quantity',
        'unit_price',
        'subtotal_returned',
    ];

    public function returPembelian(): BelongsTo
    {
        return $this->belongsTo(ReturPembelian::class);
    }

    public function pembelianItem(): BelongsTo
    {
        return $this->belongsTo(PembelianItem::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function getSubtotalReturnedFormattedAttribute()
    {
        return 'Rp ' . number_format($this->subtotal_returned, 0, ',', '.');
    }

    public function getUnitPriceFormattedAttribute()
    {
        return 'Rp ' . number_format($this->unit_price, 0, ',', '.');
    }
}
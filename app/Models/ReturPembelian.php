<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReturPembelian extends Model
{
    use HasFactory;

    protected $fillable = [
        'pembelian_id',
        'return_number',
        'retur_date',
        'notes',
        'total_returned_amount',
        'user_id',
    ];

    protected $casts = [
        'retur_date' => 'date',
    ];

    public function pembelian(): BelongsTo
    {
        return $this->belongsTo(Pembelian::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(ReturPembelianItem::class);
    }

    public function getTotalReturnedAmountFormattedAttribute()
    {
        return 'Rp ' . number_format($this->total_returned_amount, 0, ',', '.');
    }
}
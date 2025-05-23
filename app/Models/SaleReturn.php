<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleReturn extends Model
{
    use HasFactory;

    protected $fillable = [
        'return_number',
        'sale_id',
        'user_id',
        'return_date',
        'total_returned_amount',
        'refund_amount',
        'reason',
        'notes',
    ];

    protected $casts = [
        'return_date' => 'datetime',
        'total_returned_amount' => 'decimal:2',
        'refund_amount' => 'decimal:2',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(SaleReturnItem::class);
    }
}
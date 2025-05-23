<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;
    protected $table = 'sales';
    protected $with = ['items'];
    protected $fillable = [
        'invoice_number',
        'sale_date',
        'customer_name',
        'total_amount',
        'discount_amount',
        'tax_amount',
        'grand_total',
        'payment_method',
        'paid_amount',
        'change_amount',
        'user_id',
        'notes',
    ];

    protected $casts = [
        'sale_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;
    protected $table = 'sales';
    
    // Catatan: Anda mungkin ingin memeriksa kembali penggunaan $with ini.
    // Eager loading 'items' setiap saat bisa jadi tidak efisien jika Anda tidak selalu butuh detail item.
    // Namun, ini tidak terkait dengan masalah waktu Anda.
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

    /**
     * The attributes that should be cast.
     * Mengubah 'date' menjadi 'datetime' adalah kuncinya.
     * @var array
     */
    protected $casts = [
        'sale_date' => 'datetime', // <-- PERUBAHAN DI SINI
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        // Pastikan nama relasi ini sudah benar. Di controller Anda mungkin menggunakan nama lain.
        return $this->hasMany(SaleItem::class); 
    }
}
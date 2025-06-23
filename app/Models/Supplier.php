<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'contact_person',
        'email',
        'phone',
        'address',
    ];

    public function pembelians(): HasMany
    {
        return $this->hasMany(Pembelian::class);
    }
    public function purchases()
    {
        // Ganti 'Pembelian::class' jika nama model pembelian Anda berbeda.
        // Laravel akan secara otomatis mencari foreign key 'supplier_id' di tabel pembelian.
        return $this->hasMany(Pembelian::class); 
    }
}
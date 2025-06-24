<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Keramik extends Model
{
    use HasFactory;

    // Karena ID direset manual, nonaktifkan auto-increment bawaan Laravel
    public $incrementing = false;
    protected $keyType = 'int'; // Atau 'string' jika Anda ingin ID non-integer

    protected $fillable = [
        'id', // Sertakan ID di fillable karena kita akan mengaturnya secara manual
        'name',
        'size',
        'purchase_price',
        'selling_price',
        'unit',
        'stock',
    ];

    // Method untuk mendapatkan ID berikutnya yang "disimulasikan"
    public static function getNextId()
    {
        $lastId = self::max('id');
        return $lastId ? $lastId + 1 : 1;
    }

    // Override method save() untuk mengatur ID secara manual
    public function save(array $options = [])
    {
        if (is_null($this->id)) {
            $this->id = self::getNextId();
        }
        return parent::save($options);
    }
}
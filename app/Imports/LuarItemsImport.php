<?php

namespace App\Imports;

use App\Models\Item;
use App\Models\Category;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class LuarItemsImport implements ToModel, WithHeadingRow, WithValidation
{
    use SkipsFailures;

    private $category;

    public function __construct()
    {
        $this->category = Category::where('type', 'luar')->first();
         if (!$this->category) {
            throw new \Exception("Kategori dengan tipe 'luar' tidak ditemukan. Harap buat kategori terlebih dahulu.");
        }
    }

    public function model(array $row)
    {
        return new Item([
            'category_id'    => $this->category->id,
            'name'           => $row['nama'],
            // PERBAIKAN: Menambahkan harga_modal dan menyesuaikan harga_jual
            'purchase_price' => $row['harga_modal'],
            'price'          => $row['harga_jual'],
            'stock'          => $row['stok'],
            'unit'           => $row['satuan'],
            'description'    => $row['deskripsi'] ?? null,
            // Pastikan field lain yang tidak relevan di-set null
            'size'           => null,
            'paint_type'     => null,
            'color_name'     => null,
            'color_code'     => null,
            'volume'         => null,
        ]);
    }

    public function rules(): array
    {
        return [
            // PERBAIKAN: Menyesuaikan aturan validasi dengan nama kolom di Excel
            'nama'         => 'required|string|max:255',
            'harga_modal'  => 'required|numeric|min:0',
            'harga_jual'   => 'required|numeric|min:0',
            'stok'         => 'required|integer|min:0',
            'satuan'       => 'required|string|max:50',
            'deskripsi'    => 'nullable|string',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'nama.required'         => 'Kolom nama barang wajib diisi',
            'harga_modal.required'  => 'Kolom harga_modal wajib diisi',
            'harga_jual.required'   => 'Kolom harga_jual wajib diisi',
            'stok.required'         => 'Kolom stok wajib diisi',
            'satuan.required'       => 'Kolom satuan wajib diisi',
        ];
    }
}
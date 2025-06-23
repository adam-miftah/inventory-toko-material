<?php

namespace App\Imports;

use App\Models\Item;
use App\Models\Category;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class GeneralItemsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    private $category;

    public function __construct()
    {
        // Cari dan simpan kategori 'general' sekali saja untuk efisiensi
        $this->category = Category::where('type', 'general')->first();

        if (!$this->category) {
            throw new \Exception("Kategori dengan tipe 'general' tidak ditemukan. Harap buat kategori terlebih dahulu.");
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
            'nama.required'          => 'Kolom nama barang wajib diisi',
            'harga_modal.required'   => 'Kolom harga_modal wajib diisi',
            'harga_modal.numeric'    => 'Kolom harga_modal harus berupa angka',
            'harga_jual.required'    => 'Kolom harga_jual wajib diisi',
            'harga_jual.numeric'     => 'Kolom harga_jual harus berupa angka',
            'stok.required'          => 'Kolom stok wajib diisi',
            'stok.integer'           => 'Kolom stok harus berupa bilangan bulat',
            'satuan.required'        => 'Kolom satuan wajib diisi',
        ];
    }
}
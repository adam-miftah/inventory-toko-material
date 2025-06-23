<?php

namespace App\Imports;

use App\Models\Item;
use App\Models\Category;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class CatItemsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    private $category;

    public function __construct()
    {
        $this->category = Category::where('type', 'cat')->first();
        if (!$this->category) {
            throw new \Exception("Kategori dengan tipe 'cat' tidak ditemukan. Harap buat kategori terlebih dahulu.");
        }
    }

    public function model(array $row)
    {
        $description = ($row['deskripsi'] === '-' || is_null($row['deskripsi'])) ? null : $row['deskripsi'];

        return new Item([
            'category_id'    => $this->category->id,
            'name'           => $row['nama'],
            'paint_type'     => $row['jenis'],
            'color_name'     => $row['warna'],
            'color_code'     => $row['kode_warna'],
            'volume'         => $row['volume'],
            // PERBAIKAN: Menambahkan harga_modal dan menyesuaikan harga_jual
            'purchase_price' => $row['harga_modal'],
            'price'          => $row['harga_jual'],
            'stock'          => $row['stok'],
            'description'    => $description,
            // Pastikan field lain yang tidak relevan di-set null
            'size'           => null,
            'unit'           => null, // Cat tidak memiliki 'unit' standar seperti Pcs/Box
        ]);
    }

    public function rules(): array
    {
        return [
            // PERBAIKAN: Menyesuaikan aturan validasi dengan nama kolom di Excel
            'nama'         => 'required|string|max:255',
            'jenis'        => 'required|string|max:100',
            'warna'        => 'required|string|max:100',
            'kode_warna'   => 'nullable|string|max:50',
            'volume'       => 'required|string|max:50',
            'harga_modal'  => 'required|numeric|min:0',
            'harga_jual'   => 'required|numeric|min:0',
            'stok'         => 'required|integer|min:0',
            'deskripsi'    => 'nullable|string',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'nama.required'         => 'Kolom nama cat wajib diisi',
            'jenis.required'        => 'Kolom jenis cat wajib diisi',
            'warna.required'        => 'Kolom warna cat wajib diisi',
            'volume.required'       => 'Kolom volume cat wajib diisi',
            'harga_modal.required'  => 'Kolom harga_modal wajib diisi',
            'harga_jual.required'   => 'Kolom harga_jual wajib diisi',
            'stok.required'         => 'Kolom stok wajib diisi',
        ];
    }
}
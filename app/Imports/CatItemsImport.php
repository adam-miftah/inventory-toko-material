<?php

namespace App\Imports;

use App\Models\Item;
use App\Models\Category;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithPreparedData;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class CatItemsImport implements ToModel, WithHeadingRow, WithValidation
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

    /**
     * Prepare the data for validation.
     * This method runs for each row BEFORE the validation rules are applied.
     */
    public function prepareForValidation($data, $index)
    {
        // Check if 'kode_warna' exists in the row data.
        if (isset($data['kode_warna'])) {
            // Force the value to be a string if it's numeric.
            // This permanently solves the 'must be a string' validation error.
            if (is_numeric($data['kode_warna'])) {
                $data['kode_warna'] = (string) $data['kode_warna'];
            }
        }

        return $data;
    }

    /**
     * Create a new model instance for each row.
     */
    public function model(array $row)
    {
        $description = ($row['deskripsi'] === '-' || is_null($row['deskripsi'])) ? null : $row['deskripsi'];

        return new Item([
            'category_id'    => $this->category->id,
            'name'           => $row['nama'],
            'paint_type'     => $row['jenis'],
            'color_name'     => $row['warna'] ?? null,
            'color_code'     => $row['kode_warna'] ?? null,
            'volume'         => $row['volume'] ?? null,
            'purchase_price' => $row['harga_modal'],
            'price'          => $row['harga_jual'],
            'stock'          => $row['stok'],
            'description'    => $description,
            // Ensure irrelevant fields are set to null for this category.
            'size'           => null,
            'unit'           => null,
        ]);
    }

    /**
     * Define the validation rules for each row.
     */
    public function rules(): array
    {
        return [
            'nama'        => 'required|string|max:255',
            'jenis'       => 'required|string|max:100',
            'warna'       => 'required|string|max:100',
            'kode_warna'  => 'nullable|string|max:50', 
            'volume'      => 'required|string|max:50',
            'harga_modal' => 'required|numeric|min:0',
            'harga_jual'  => 'required|numeric|min:0',
            'stok'        => 'required|integer|min:0',
            'deskripsi'   => 'nullable|string',
        ];
    }

    /**
     * Define custom validation messages.
     */
    public function customValidationMessages()
    {
        return [
            'nama.required'        => 'Kolom nama cat wajib diisi',
            'jenis.required'       => 'Kolom jenis cat wajib diisi',
            'warna.required'       => 'Kolom warna cat wajib diisi',
            'volume.required'      => 'Kolom volume cat wajib diisi',
            'harga_modal.required' => 'Kolom harga_modal wajib diisi',
            'harga_jual.required'  => 'Kolom harga_jual wajib diisi',
            'stok.required'        => 'Kolom stok wajib diisi',
        ];
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Imports\GeneralItemsImport;
use App\Imports\CatItemsImport;
use App\Imports\KeramikItemsImport;
use App\Imports\LuarItemsImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Validators\ValidationException;

class ItemController extends Controller
{
    /**
     * Menampilkan daftar semua item, dipisahkan berdasarkan kategori.
     */
    public function index()
    {
        $items = Item::with('category')->orderBy('name', 'asc')->get();

        $generalItems = $items->filter(function ($item) {
            return !in_array(strtolower($item->category->type ?? 'general'), ['cat', 'keramik', 'luar']);
        });
        $catItems = $items->filter(fn($item) => strtolower($item->category->type ?? '') === 'cat');
        $keramikItems = $items->filter(fn($item) => strtolower($item->category->type ?? '') === 'keramik');
        $luarItems = $items->filter(fn($item) => strtolower($item->category->type ?? '') === 'luar');

        return view('inventory.items.index', compact(
            'generalItems',
            'catItems',
            'keramikItems',
            'luarItems'
        ));
    }

    /**
     * Menampilkan form untuk membuat item baru.
     */
    public function create()
    {
        $categories = Category::all();
        return view('inventory.items.create', compact('categories'));
    }
    
    /**
     * Menyimpan item baru ke dalam database.
     */
    public function store(Request $request)
    {
        $category = Category::find($request->category_id);
        $categoryType = $category ? strtolower($category->type) : 'general';

        $rules = $this->getValidationRules($categoryType);
        $validatedData = $request->validate($rules);
        
        Item::create($validatedData);

        return redirect()->route('inventory.items.index')
                         ->with('success', 'Barang berhasil ditambahkan!')
                         ->with('active_tab', $categoryType);
    }
    
    /**
     * Menampilkan detail dari satu item.
     */
    public function show(Item $item)
    {
        $item->load('category');
        return view('inventory.items.show', compact('item'));
    }

    /**
     * Menampilkan form untuk mengedit item.
     */
    public function edit(Item $item)
    {
        $categories = Category::all();
        return view('inventory.items.edit', compact('item', 'categories'));
    }

    /**
     * Memperbarui data item di database.
     */
    public function update(Request $request, Item $item)
    {
        $category = Category::find($request->category_id);
        $categoryType = $category ? strtolower($category->type) : 'general';
        
        $rules = $this->getValidationRules($categoryType, $item->id);
        $validatedData = $request->validate($rules);

        // Bersihkan data lama yang mungkin tidak relevan lagi
        $item->fill($this->clearIrrelevantData($validatedData, $categoryType));
        $item->save();

        return redirect()->route('inventory.items.index')
                         ->with('success', 'Barang berhasil diperbarui!')
                         ->with('active_tab', $categoryType);
    }
    
    /**
     * Menghapus item dari database.
     */
    public function destroy(Request $request, Item $item)
    {
        if ($item->saleItems()->exists() || $item->purchaseItems()->exists()) {
             $errorMessage = 'Barang "' . $item->name . '" tidak dapat dihapus karena sudah memiliki riwayat transaksi.';
            if ($request->ajax()) {
                return response()->json(['error' => $errorMessage], 422);
            }
            return redirect()->route('inventory.items.index')->with('error', $errorMessage);
        }

        $categoryType = $item->category ? strtolower($item->category->type) : 'general';
        $itemName = $item->name;
        $item->delete();

        if ($request->ajax()) {
            return response()->json(['success' => "Barang '$itemName' berhasil dihapus."]);
        }

        return redirect()->route('inventory.items.index')
                         ->with('success', "Barang '$itemName' berhasil dihapus!")
                         ->with('active_tab', $categoryType);
    }

    /**
     * Mengimpor data item dari file Excel.
     */
    public function import(Request $request)
    {
        $request->validate([
            'category_type' => 'required|in:general,cat,keramik,luar',
            'file' => 'required|file|mimes:xlsx,xls'
        ]);

        $categoryType = $request->category_type;
        $importer = null;

        switch ($categoryType) {
            case 'cat': $importer = new CatItemsImport; break;
            case 'keramik': $importer = new KeramikItemsImport; break;
            case 'luar': $importer = new LuarItemsImport; break;
            case 'general': $importer = new GeneralItemsImport; break;
        }

        try {
            if ($importer) {
                Excel::import($importer, $request->file('file'));
                return redirect()->route('inventory.items.index')
                                ->with('success', 'Data barang berhasil diimpor!')
                                ->with('active_tab', $categoryType);
            }
        } catch (ValidationException $e) {
            $failures = $e->failures();
            $errorMessages = [];
            foreach ($failures as $failure) {
                $errorMessages[] = 'Baris ' . $failure->row() . ': ' . implode(', ', $failure->errors());
            }
            return redirect()->back()->with('error', 'Gagal mengimpor data. Error: ' . implode('; ', $errorMessages));
        } catch (\Exception $e) {
            Log::error('Import Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memproses file: ' . $e->getMessage());
        }
    }

    /**
     * Helper untuk mendapatkan aturan validasi berdasarkan tipe kategori.
     */
    private function getValidationRules(string $type, int $itemId = null): array
    {
        // PERBAIKAN: 'purchase_price' dipindahkan ke aturan dasar (base rules)
        $rules = [
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'purchase_price' => ['required', 'numeric', 'min:0'], // Harga Modal
            'price' => ['required', 'numeric', 'min:0'], // Harga Jual
            'stock' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
        ];

        switch ($type) {
            case 'cat':
                return array_merge($rules, [
                    'paint_type' => ['required', 'string', 'max:100'],
                    'color_name' => ['required', 'string', 'max:100'],
                    'color_code' => ['nullable', 'string', 'max:50'],
                    'volume' => ['required', 'string', 'max:50'],
                ]);
            case 'keramik':
                return array_merge($rules, [
                    'size' => ['required', 'string', 'max:100'],
                    'unit' => ['required', 'string', 'max:50'],
                ]);
            default: // 'general', 'luar', etc.
                return array_merge($rules, [
                    'unit' => ['required', 'string', 'max:50'],
                ]);
        }
    }

    /**
     * Helper untuk membersihkan data yang tidak relevan saat update.
     */
    private function clearIrrelevantData(array $data, string $type): array
    {
        // PERBAIKAN: 'purchase_price' sekarang relevan untuk semua, jadi dihapus dari daftar 'allFields' yang mungkin di-null-kan.
        $allFields = ['size', 'paint_type', 'color_name', 'color_code', 'volume', 'unit'];
        $relevantFields = [];

        switch ($type) {
            case 'cat':
                $relevantFields = ['paint_type', 'color_name', 'color_code', 'volume'];
                break;
            case 'keramik':
                $relevantFields = ['size', 'unit'];
                break;
            default: // 'general', 'luar'
                $relevantFields = ['unit'];
                break;
        }

        foreach ($allFields as $field) {
            if (!in_array($field, $relevantFields)) {
                $data[$field] = null;
            }
        }
        return $data;
    }
}
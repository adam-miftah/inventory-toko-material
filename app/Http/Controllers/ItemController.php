<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $categoryId = $request->input('category_id'); // Ambil category_id dari request

        $items = Item::with('category')->get(); // Ambil semua item terlebih dahulu

        // Pisahkan item berdasarkan kategori
        $generalItems = collect();
        $catItems = collect();
        $keramikItems = collect();

        // Ambil ID kategori "Cat", "Keramik", dan "Umum" (jika ada kategori umum)
        // Pastikan nama kategori di database konsisten (misal: "Cat", "Keramik", "Umum" atau "Lain-lain")
        $catCategory = Category::where('name', 'LIKE', '%cat%')->first();
        $keramikCategory = Category::where('name', 'LIKE', '%keramik%')->first();
        $generalCategory = Category::where('name', 'LIKE', '%umum%') // Sesuaikan jika nama kategori umum Anda berbeda
                                    ->orWhere('name', 'LIKE', '%lain-lain%')
                                    ->first();

        foreach ($items as $item) {
            if ($item->category) {
                if ($catCategory && stripos($item->category->name, 'cat') !== false) {
                    $catItems->push($item);
                } elseif ($keramikCategory && stripos($item->category->name, 'keramik') !== false) {
                    $keramikItems->push($item);
                } else {
                    // Jika kategori tidak cocok dengan "Cat" atau "Keramik", masukkan ke Umum
                    $generalItems->push($item);
                }
            } else {
                // Jika item tidak memiliki kategori (jarang terjadi jika relasi di set not nullable)
                $generalItems->push($item);
            }
        }

        $categories = Category::all(); // Ambil semua kategori untuk dropdown filter
        return view('inventory.items.index', compact('generalItems', 'catItems', 'keramikItems', 'categories', 'categoryId'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        // Ambil ID kategori "Cat" dan "Keramik"
        // Pastikan nama kategori di database konsisten (misal: "Cat" atau "Keramik")
        $catCategory = Category::where('name', 'LIKE', '%cat%')->first();
        $keramikCategory = Category::where('name', 'LIKE', '%keramik%')->first();

        // Teruskan ID kategori ini ke view untuk JavaScript
        $catCategoryId = $catCategory ? $catCategory->id : null;
        $keramikCategoryId = $keramikCategory ? $keramikCategory->id : null;

        return view('inventory.items.create', compact('categories', 'catCategoryId', 'keramikCategoryId'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'stock' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
        ];

        $category = Category::find($request->category_id);
        $validatedData = [];

        if ($category) {
            if (stripos($category->name, 'cat') !== false) {
                // Aturan untuk Kategori Cat
                $rules = array_merge($rules, [
                    'paint_type' => ['required', 'string', 'max:100'], // Jenis Cat
                    'color_name' => ['required', 'string', 'max:100'],
                    'color_code' => ['nullable', 'string', 'max:50'],
                    'volume' => ['required', 'string', 'max:50'], // Berat/Volume Cat
                    'price' => ['required', 'numeric', 'min:0'], // Harga Jual Cat
                ]);
                $validatedData = $request->validate($rules);
                // Set bidang lain yang tidak relevan menjadi null
                $validatedData['size'] = null;
                $validatedData['texture'] = null;
                $validatedData['motif'] = null;
                $validatedData['grade'] = null;
                $validatedData['purchase_price'] = null;
                $validatedData['unit'] = null; // <-- Set null di sini untuk kategori Cat
                $validatedData['finish_type'] = null;

            } elseif (stripos($category->name, 'keramik') !== false) {
                // Aturan untuk Kategori Keramik
                $rules = array_merge($rules, [
                    'size' => ['required', 'string', 'max:100'],
                    'purchase_price' => ['required', 'numeric', 'min:0'], // Harga Modal Keramik
                    'price' => ['required', 'numeric', 'min:0'], // Harga Jual Keramik
                    'unit' => ['required', 'string', 'max:50'], // Satuan Keramik (misal: Box, Meter)
                    // Anda bisa tambahkan validasi untuk texture, motif, grade jika Anda ingin mereka wajib
                    // 'texture' => ['nullable', 'string', 'max:100'],
                    // 'motif' => ['nullable', 'string', 'max:100'],
                    // 'grade' => ['nullable', 'string', 'max:50'],
                    // 'finish_type' => ['nullable', 'string', 'max:50'],
                ]);
                $validatedData = $request->validate($rules);
                // Set bidang lain yang tidak relevan menjadi null
                $validatedData['color_name'] = null;
                $validatedData['color_code'] = null;
                $validatedData['paint_type'] = null;
                $validatedData['volume'] = null;

            } else {
                // Aturan untuk Kategori Umum (selain Cat dan Keramik)
                $rules = array_merge($rules, [
                    'price' => ['required', 'numeric', 'min:0'], // Harga
                    'unit' => ['required', 'string', 'max:50'],
                ]);
                $validatedData = $request->validate($rules);
                // Set semua bidang spesifik menjadi null
                $validatedData['size'] = null;
                $validatedData['texture'] = null;
                $validatedData['motif'] = null;
                $validatedData['grade'] = null;
                $validatedData['color_name'] = null;
                $validatedData['color_code'] = null;
                $validatedData['paint_type'] = null;
                $validatedData['volume'] = null;
                $validatedData['purchase_price'] = null;
                $validatedData['finish_type'] = null;
            }
        } else {
             // Jika kategori tidak ditemukan (harusnya tidak terjadi karena validasi exists)
            return redirect()->back()->withErrors(['category_id' => 'Kategori tidak valid.']);
        }

        Item::create($validatedData);

        // Menentukan kategori yang dipilih untuk dikirim kembali ke view index
        $selectedCategoryFilter = 'general'; // Default
        if (stripos($category->name, 'cat') !== false) {
            $selectedCategoryFilter = 'cat';
        } elseif (stripos($category->name, 'keramik') !== false) {
            $selectedCategoryFilter = 'keramik';
        }

        return redirect()->route('inventory.items')->with('success', 'Barang berhasil ditambahkan!')
                         ->with('category_id_filter', $selectedCategoryFilter);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function show(Item $item)
    {
        return view('inventory.items.show', compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function edit(Item $item)
    {
        $categories = Category::all();
        $catCategory = Category::where('name', 'LIKE', '%cat%')->first();
        $keramikCategory = Category::where('name', 'LIKE', '%keramik%')->first();

        $catCategoryId = $catCategory ? $catCategory->id : null;
        $keramikCategoryId = $keramikCategory ? $keramikCategory->id : null;

        return view('inventory.items.edit', compact('item', 'categories', 'catCategoryId', 'keramikCategoryId'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Item $item)
    {
        $rules = [
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'stock' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
        ];

        $category = Category::find($request->category_id);

        // Kosongkan semua bidang spesifik yang mungkin tidak relevan untuk kategori baru
        // Ini penting agar data lama dari kategori lain tidak ikut tersimpan
        // Kita akan mengisi ulang nilai dari request setelah validasi
        $item->fill([
            'size' => null, 'texture' => null, 'motif' => null, 'grade' => null,
            'color_name' => null, 'color_code' => null, 'paint_type' => null, 'finish_type' => null, 'volume' => null,
            'purchase_price' => null, 'unit' => null, 'price' => null,
        ]);

        if ($category) {
            if (stripos($category->name, 'cat') !== false) {
                $rules = array_merge($rules, [
                    'paint_type' => ['required', 'string', 'max:100'],
                    'color_name' => ['required', 'string', 'max:100'],
                    'color_code' => ['nullable', 'string', 'max:50'],
                    'volume' => ['required', 'string', 'max:50'],
                    'price' => ['required', 'numeric', 'min:0'],
                ]);
            } elseif (stripos($category->name, 'keramik') !== false) {
                $rules = array_merge($rules, [
                    'size' => ['required', 'string', 'max:100'],
                    'purchase_price' => ['required', 'numeric', 'min:0'],
                    'price' => ['required', 'numeric', 'min:0'],
                    'unit' => ['required', 'string', 'max:50'],
                    // 'texture' => ['nullable', 'string', 'max:100'],
                    // 'motif' => ['nullable', 'string', 'max:100'],
                    // 'grade' => ['nullable', 'string', 'max:50'],
                    // 'finish_type' => ['nullable', 'string', 'max:50'],
                ]);
            } else {
                $rules = array_merge($rules, [
                    'price' => ['required', 'numeric', 'min:0'],
                    'unit' => ['required', 'string', 'max:50'],
                ]);
            }
        } else {
            return redirect()->back()->withErrors(['category_id' => 'Kategori tidak valid.']);
        }

        $validatedData = $request->validate($rules);
        $item->update($validatedData);

        // Menentukan kategori yang dipilih untuk dikirim kembali ke view index
        $selectedCategoryFilter = 'general'; // Default
        if (stripos($category->name, 'cat') !== false) {
            $selectedCategoryFilter = 'cat';
        } elseif (stripos($category->name, 'keramik') !== false) {
            $selectedCategoryFilter = 'keramik';
        }

        return redirect()->route('inventory.items')->with('success', 'Barang berhasil diperbarui!')
->with('category_id_filter', $selectedCategoryFilter);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Item $item)
    {
        $categoryName = $item->category->name ?? ''; // Ambil nama kategori sebelum dihapus

        $item->delete();

        // Menentukan kategori yang dipilih untuk dikirim kembali ke view index
        $selectedCategoryFilter = 'general'; // Default
        if (stripos($categoryName, 'cat') !== false) {
            $selectedCategoryFilter = 'cat';
        } elseif (stripos($categoryName, 'keramik') !== false) {
            $selectedCategoryFilter = 'keramik';
        }

        return redirect()->route('inventory.items')->with('success', 'Barang berhasil dihapus!')
                         ->with('category_id_filter', $selectedCategoryFilter);
    }
}
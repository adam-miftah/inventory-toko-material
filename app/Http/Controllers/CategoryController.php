<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('name', 'asc')->get(); 
        return view('inventory.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('inventory.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'type' => 'required|in:general,keramik,cat,luar',
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'type' => $request->type,
        ]);

        return redirect()->route('inventory.categories.index')->with('success', 'Jenis barang berhasil ditambahkan!');
    }

    public function edit(Category $category)
    {
        return view('inventory.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'type' => 'required|in:general,keramik,cat,luar', 
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'type' => $request->type,
        ]);

        return redirect()->route('inventory.categories.index')->with('success', 'Jenis barang berhasil diperbarui!');
    }

    public function destroy(Request $request, Category $category) // Tambahkan Request
    {
        // Cek relasi sebelum menghapus
        if ($category->items()->exists()) {
            $errorMessage = 'Kategori "' . $category->name . '" tidak dapat dihapus karena masih memiliki barang terkait.';
            if ($request->ajax()) {
                return response()->json(['error' => $errorMessage], 422);
            }
            return redirect()->route('inventory.categories.index')->with('error', $errorMessage);
        }

        $categoryName = $category->name;
        $category->delete();
        
        $successMessage = 'Jenis barang "' . $categoryName . '" berhasil dihapus.';
        if ($request->ajax()) {
            return response()->json(['success' => $successMessage]);
        }

        return redirect()->route('inventory.categories.index')->with('success', $successMessage);
    }
}

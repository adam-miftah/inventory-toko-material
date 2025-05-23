<?php

namespace App\Http\Controllers;

use App\Models\Cat;
use Illuminate\Http\Request;

class CatController extends Controller
{
    public function index()
    {
        $cats = Cat::orderBy('id', 'asc')->get(); // Urutkan berdasarkan ID
        return view('inventory.cats.index', compact('cats'));
    }

    public function create()
    {
        return view('inventory.cats.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type_of_paint' => 'required|string|max:100',
            'color' => 'required|string|max:100',
            'code' => 'nullable|string|max:50',
            'weight' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        // Mengatur ID secara manual
        $request->merge(['id' => Cat::getNextId()]);

        Cat::create($request->all());

        return redirect()->route('inventory.cats')->with('success', 'Data cat berhasil ditambahkan!');
    }

    public function show(Cat $cat)
    {
        return view('inventory.cats.show', compact('cat'));
    }

    public function edit(Cat $cat)
    {
        return view('inventory.cats.edit', compact('cat'));
    }

    public function update(Request $request, Cat $cat)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type_of_paint' => 'required|string|max:100',
            'color' => 'required|string|max:100',
            'code' => 'nullable|string|max:50',
            'weight' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        $cat->update($request->all());

        return redirect()->route('inventory.cats')->with('success', 'Data cat berhasil diperbarui!');
    }

    public function destroy(Cat $cat)
    {
        $cat->delete();
        return redirect()->route('inventory.cats')->with('success', 'Data cat berhasil dihapus!');
    }
}
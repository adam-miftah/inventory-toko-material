<?php

namespace App\Http\Controllers;

use App\Models\Keramik;
use Illuminate\Http\Request;

class KeramikController extends Controller
{
    public function index()
    {
        $keramiks = Keramik::orderBy('id', 'asc')->get(); // Urutkan berdasarkan ID
        return view('inventory.keramiks.index', compact('keramiks'));
    }

    public function create()
    {
        return view('inventory.keramiks.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'size' => 'required|string|max:100',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'unit' => 'required|string|max:50',
            'stock' => 'required|integer|min:0',
        ]);

        // Mengatur ID secara manual
        $request->merge(['id' => Keramik::getNextId()]);

        Keramik::create($request->all());

        return redirect()->route('inventory.keramiks')->with('success', 'Data keramik berhasil ditambahkan!');
    }

    public function show(Keramik $keramik)
    {
        return view('inventory.keramiks.show', compact('keramik'));
    }

    public function edit(Keramik $keramik)
    {
        return view('inventory.keramiks.edit', compact('keramik'));
    }

    public function update(Request $request, Keramik $keramik)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'size' => 'required|string|max:100',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'unit' => 'required|string|max:50',
            'stock' => 'required|integer|min:0',
        ]);

        $keramik->update($request->all());

        return redirect()->route('inventory.keramiks')->with('success', 'Data keramik berhasil diperbarui!');
    }

    public function destroy(Keramik $keramik)
    {
        $keramik->delete();
        return redirect()->route('inventory.keramiks')->with('success', 'Data keramik berhasil dihapus!');
    }
}
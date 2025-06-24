<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SupplierController extends Controller
{
    /**
     * Menampilkan daftar semua supplier, dioptimalkan untuk DataTables.
     */
    public function index()
    {
        // Gunakan get() untuk mengirim semua data ke DataTables
        $suppliers = Supplier::withCount('purchases')->latest()->get();
        return view('suppliers.index', compact('suppliers'));
    }

    /**
     * Menampilkan form untuk membuat supplier baru.
     */
    public function create()
    {
        return view('suppliers.create');
    }

    /**
     * Menyimpan supplier baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:suppliers,name',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255|unique:suppliers,email',
            'address' => 'nullable|string',
        ]);

        Supplier::create($request->all());

        return redirect()->route('suppliers.index')->with('success', 'Supplier berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail dari satu supplier.
     */
    public function show(Supplier $supplier)
    {
        // Muat relasi pembelian dan item-itemnya untuk ditampilkan di halaman detail
        $supplier->load('purchases.items.item');
        return view('suppliers.show', compact('supplier'));
    }

    /**
     * Menampilkan form untuk mengedit supplier.
     */
    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    /**
     * Memperbarui data supplier di database.
     */
    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:suppliers,name,' . $supplier->id,
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255|unique:suppliers,email,' . $supplier->id,
            'address' => 'nullable|string',
        ]);

        $supplier->update($request->all());

        return redirect()->route('suppliers.index')->with('success', 'Supplier berhasil diperbarui.');
    }

    /**
     * Menghapus supplier dari database dengan pengecekan relasi.
     */
    public function destroy(Request $request, Supplier $supplier)
    {
        if ($supplier->purchases()->exists()) {
            $errorMessage = 'Supplier "' . $supplier->name . '" tidak dapat dihapus karena sudah memiliki riwayat pembelian.';
            if ($request->ajax()) {
                return response()->json(['error' => $errorMessage], 422); // Unprocessable Entity
            }
            return redirect()->route('suppliers.index')->with('error', $errorMessage);
        }

        $supplierName = $supplier->name;
        $supplier->delete();

        $successMessage = 'Supplier "' . $supplierName . '" berhasil dihapus.';
        if ($request->ajax()) {
            return response()->json(['success' => $successMessage]);
        }
        
        return redirect()->route('suppliers.index')->with('success', $successMessage);
    }
}

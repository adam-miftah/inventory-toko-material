<?php
namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\PembelianItem;
use App\Models\ReturPembelian;
use App\Models\ReturPembelianItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReturPembelianController extends Controller
{
    public function index()
    {
        $returPembelians = ReturPembelian::latest()->with('pembelian', 'items')->get();
    return view('retur-pembelian.index', compact('returPembelians'));
    }

    public function create()
    {
        $pembelians = Pembelian::all();
        return view('retur-pembelian.create', compact('pembelians'));
    }

    // app/Http/Controllers/ReturPembelianController.php

public function store(Request $request)
    {
        $request->validate([
            'pembelian_id' => 'required|exists:pembelians,id',
            'retur_date' => 'required|date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.pembelian_item_id' => 'required|exists:pembelian_items,id',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // ======================================================
            // PERBAIKAN 1: Buat Retur Pembelian terlebih dahulu tanpa items
            // untuk mendapatkan ID-nya.
            // ======================================================
            $returPembelian = ReturPembelian::create([
                'pembelian_id' => $request->pembelian_id,
                'retur_date' => $request->retur_date,
                'notes' => $request->notes,
                'return_number' => 'TEMP', // Isi sementara
                'total_returned_amount' => 0,
                'user_id' => Auth::id(),
            ]);

            // ======================================================
            // PERBAIKAN 2: Buat nomor retur unik menggunakan ID
            // ======================================================
            $returnNumber = 'RTR-' . now()->format('Ymd') . '-' . $returPembelian->id;

            $totalReturnedAmount = 0;
            foreach ($request->input('items') as $itemData) {
                $subtotalReturned = $itemData['quantity'] * $itemData['unit_price'];
                $totalReturnedAmount += $subtotalReturned;

                $pembelianItem = PembelianItem::findOrFail($itemData['pembelian_item_id']);

                ReturPembelianItem::create([
                    'retur_pembelian_id' => $returPembelian->id,
                    'pembelian_item_id' => $itemData['pembelian_item_id'],
                    'item_id' => $itemData['item_id'],
                    'item_name' => $pembelianItem->item_name,
                    'quantity' => $itemData['quantity'],
                    'unit_price' => $itemData['unit_price'],
                    'subtotal_returned' => $subtotalReturned,
                ]);

                // Kurangi stok barang
                $pembelianItem->item->decrement('stock', $itemData['quantity']);
            }

            // ======================================================
            // PERBAIKAN 3: Update data dengan nomor retur dan total yang benar
            // ======================================================
            $returPembelian->update([
                'return_number' => $returnNumber,
                'total_returned_amount' => $totalReturnedAmount
            ]);

            DB::commit();
            return redirect()->route('retur-pembelian.index')->with('success', 'Retur pembelian berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();
            // Tambahkan pesan error yang lebih detail untuk debugging
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])->withInput();
        }
    }

    public function show(ReturPembelian $returPembelian)
    {
        $returPembelian->load('pembelian', 'user', 'items');
        return view('retur-pembelian.show', compact('returPembelian'));
    }

    public function destroy(ReturPembelian $returPembelian)
    {
        DB::beginTransaction();
        try {
            // Kembalikan stok barang yang diretur (tambah stok)
            foreach ($returPembelian->items as $returItem) {
                $pembelianItem = PembelianItem::find($returItem->pembelian_item_id);
                if ($pembelianItem) {
                    $item = $pembelianItem->item;
                    $item->increment('stock', $returItem->quantity);
                }
            }

            $returPembelian->items()->delete();
            $returPembelian->delete();

            DB::commit();
            return redirect()->route('retur-pembelian.index')->with('success', 'Retur pembelian berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan saat menghapus retur pembelian.']);
        }
    }
    public function getPembelianItems(Pembelian $pembelian)
{
    $items = $pembelian->items;
    return response()->json($items);
}
}
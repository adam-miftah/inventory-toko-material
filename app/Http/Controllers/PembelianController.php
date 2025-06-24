<?php
namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Pembelian;
use App\Models\PembelianItem;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PembelianController extends Controller
{
    public function index()
    {
         $pembelians = Pembelian::latest()->with('supplier', 'items')->get();
    return view('pembelian.index', compact('pembelians'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        $items = Item::all();
        return view('pembelian.create', compact('suppliers', 'items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'purchase_date' => 'required|date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // --- Generate Nomor Pembelian Unik ---
            $currentDate = Carbon::parse($request->purchase_date);
            $prefix = 'PO-' . $currentDate->format('Ymd'); // Contoh: PO-20240527

            // Cari nomor pembelian terakhir untuk tanggal yang sama
            $lastPembelian = Pembelian::where('purchase_number', 'like', $prefix . '%')
                                        ->orderBy('purchase_number', 'desc')
                                        ->first();

            $sequence = 1;
            if ($lastPembelian) {
                // Ambil nomor urut dari nomor pembelian terakhir
                // Contoh: PO-20240527-0012 -> ambil 0012
                $lastNumber = (int) substr($lastPembelian->purchase_number, -4);
                $sequence = $lastNumber + 1;
            }

            // Format nomor urut menjadi 4 digit dengan leading zeros (0001, 0002, dst)
            $purchaseNumber = $prefix . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
            // --- Akhir Generate Nomor Pembelian Unik ---

            $pembelian = Pembelian::create([
                'supplier_id' => $request->supplier_id,
                'purchase_date' => $request->purchase_date,
                'notes' => $request->notes,
                'total_amount' => 0, // Akan diupdate nanti
                'user_id' => Auth::id(),
                'purchase_number' => $purchaseNumber, // Simpan nomor pembelian
            ]);

            $totalAmount = 0;
            foreach ($request->input('items') as $itemData) {
                $item = Item::findOrFail($itemData['item_id']);
                $subtotal = $itemData['quantity'] * $itemData['price'];
                $totalAmount += $subtotal;

                PembelianItem::create([
                    'pembelian_id' => $pembelian->id,
                    'item_id' => $item->id,
                    'item_name' => $item->name,
                    'quantity' => $itemData['quantity'],
                    'unit_price' => $itemData['price'],
                    'subtotal' => $subtotal,
                ]);

                // Pastikan `stock` di model Item sudah ada dan benar tipenya (integer)
                $item->increment('stock', $itemData['quantity']);
            }

            $pembelian->update(['total_amount' => $totalAmount]);

            DB::commit();
            return redirect()->route('pembelian.index')->with('success', 'Pembelian berhasil disimpan dengan nomor ' . $purchaseNumber . '.');

        } catch (\Exception $e) {
            DB::rollBack();
            // Anda bisa log $e->getMessage() untuk debugging lebih lanjut
            return redirect()->back()->withErrors(['error_simpan' => 'Terjadi kesalahan saat menyimpan pembelian: ' . $e->getMessage()])->withInput();
        }
    }

    public function show(Pembelian $pembelian)
    {
        $pembelian->load('supplier', 'user', 'items.item');
        return view('pembelian.show', compact('pembelian'));
    }

    public function edit(Pembelian $pembelian)
    {
        $suppliers = Supplier::all();
        $items = Item::all();
        $pembelian->load('items');
        return view('pembelian.edit', compact('pembelian', 'suppliers', 'items'));
    }

    public function update(Request $request, Pembelian $pembelian)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'purchase_date' => 'required|date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $pembelian->update([
                'supplier_id' => $request->supplier_id,
                'purchase_date' => $request->purchase_date,
                'notes' => $request->notes,
            ]);

            $totalAmount = 0;
            $pembelian->items()->delete(); // Remove old items

            foreach ($request->input('items') as $itemData) {
                $item = Item::findOrFail($itemData['item_id']);
                $subtotal = $itemData['quantity'] * $itemData['price'];
                $totalAmount += $subtotal;

                PembelianItem::create([
                    'pembelian_id' => $pembelian->id,
                    'item_id' => $item->id,
                    'item_name' => $item->name,
                    'quantity' => $itemData['quantity'],
                    'unit_price' => $itemData['price'],
                    'subtotal' => $subtotal,
                ]);

                // Need to handle stock update carefully based on changes
                // For simplicity, let's assume we adjust based on the new quantities
                // You might need more sophisticated logic here.
                $oldItem = $pembelian->items()->where('item_id', $item->id)->first();
                $stockChange = $itemData['quantity'] - ($oldItem ? $oldItem->quantity : 0);
                $item->increment('stock', $stockChange);
            }

            $pembelian->update(['total_amount' => $totalAmount]);

            DB::commit();
            return redirect()->route('pembelian.index')->with('success', 'Pembelian berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan saat memperbarui pembelian.'])->withInput();
        }
    }

    public function destroy(Pembelian $pembelian)
    {
        DB::beginTransaction();
        try {
            foreach ($pembelian->items as $itemPembelian) {
                $item = Item::find($itemPembelian->item_id);
                if ($item) {
                    $item->decrement('stock', $itemPembelian->quantity);
                }
            }

            $pembelian->items()->delete();
            $pembelian->delete();

            DB::commit();
            return redirect()->route('pembelian.index')->with('success', 'Pembelian berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan saat menghapus pembelian.']);
        }
    }
}
<?php
namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Pembelian;
use App\Models\PembelianItem;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PembelianController extends Controller
{
    public function index()
    {
         $pembelians = Pembelian::latest()->with('supplier', 'items')->paginate(10);
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
            $pembelian = Pembelian::create([
                'supplier_id' => $request->supplier_id,
                'purchase_date' => $request->purchase_date,
                'notes' => $request->notes,
                'total_amount' => 0, // Will be updated later
                'user_id' => Auth::id(),
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

                $item->increment('stock', $itemData['quantity']);
            }

            $pembelian->update(['total_amount' => $totalAmount]);

            DB::commit();
            return redirect()->route('pembelian.index')->with('success', 'Pembelian berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan pembelian.'])->withInput();
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
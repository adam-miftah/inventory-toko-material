<?php

namespace App\Http\Controllers;

use App\Models\SaleReturn;
use App\Models\Sale;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class SaleReturnController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $saleReturns = SaleReturn::with(['sale', 'user'])->latest()->get();
        return view('penjualan.retur.index', compact('saleReturns'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sales = Sale::with('items.item')->get(); // Ambil semua penjualan dengan item-itemnya
        // Anda bisa filter penjualan yang sudah selesai atau dalam periode tertentu
        return view('penjualan.retur.create', compact('sales'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'sale_id' => 'nullable|exists:sales,id',
            'return_date' => 'required|date',
            'reason' => 'required|string|max:255',
            'notes' => 'nullable|string|max:500',
            'returned_items' => 'required|array',
            'returned_items.*.item_id' => 'required|exists:items,id',
            'returned_items.*.quantity' => 'required|integer|min:1',
            'refund_amount' => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $totalReturnedAmount = 0;
            $returnedItemDetails = [];
            $sale = null;

            if ($request->sale_id) {
                $sale = Sale::with('items')->find($request->sale_id);
                if (!$sale) {
                    throw ValidationException::withMessages(['sale_id' => 'Transaksi penjualan tidak ditemukan.']);
                }
            }

            foreach ($request->returned_items as $itemData) {
                $item = Item::find($itemData['item_id']);
                if (!$item) {
                    throw ValidationException::withMessages(['returned_items' => 'Salah satu barang tidak ditemukan.']);
                }

                // Jika retur terkait dengan penjualan asli, validasi kuantitas dan ambil harga
                if ($sale) {
                    $saleItem = $sale->items->where('item_id', $item->id)->first();
                    if (!$saleItem || $itemData['quantity'] > $saleItem->quantity) {
                        throw ValidationException::withMessages(['returned_items' => "Kuantitas retur untuk " . $item->item->name . " melebihi kuantitas yang dijual atau barang tidak ada dalam transaksi asli."]);
                    }
                    $pricePerUnit = $saleItem->unit_price; // Menggunakan unit_price dari sale_items
                } else {
                    // Jika tidak terkait penjualan asli, ambil harga jual saat ini
                    $pricePerUnit = $item->price; // Menggunakan price dari items (pastikan ini adalah harga jual)
                }

                $subtotal = $itemData['quantity'] * $pricePerUnit;
                $totalReturnedAmount += $subtotal;

                $returnedItemDetails[] = [
                    'item_id' => $item->id,
                    'quantity' => $itemData['quantity'],
                    'price_per_unit' => $pricePerUnit,
                    'subtotal' => $subtotal,
                ];

                // Kembalikan stok barang
                $item->increment('stock', $itemData['quantity']);
            }

            // Generate nomor retur
            $lastReturn = SaleReturn::latest()->first();
            $returnNumber = 'R' . date('Ymd') . str_pad(($lastReturn ? (int)substr($lastReturn->return_number, -4) : 0) + 1, 4, '0', STR_PAD_LEFT);

            $saleReturn = SaleReturn::create([
                'return_number' => $returnNumber,
                'sale_id' => $request->sale_id,
                'user_id' => Auth::id(), // User yang sedang login
                'return_date' => $request->return_date,
                'total_returned_amount' => $totalReturnedAmount,
                'refund_amount' => $request->refund_amount,
                'reason' => $request->reason,
                'notes' => $request->notes,
            ]);

            foreach ($returnedItemDetails as $detail) {
                $saleReturn->items()->create($detail);
            }

            DB::commit();
            return redirect()->route('penjualan.retur.index')->with('success', 'Retur penjualan berhasil diproses!');

        } catch (ValidationException $e) {
            DB::rollBack();
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(SaleReturn $retur)
    {
        $retur->load(['sale.items.item', 'user', 'items.item']);
        return view('penjualan.retur.show', compact('retur'));
    }
    public function destroy(SaleReturn $retur)
    {
        DB::beginTransaction();
        try {
            // Kembalikan stok barang sebelum menghapus retur
            foreach ($retur->items as $returnItem) {
                $item = Item::find($returnItem->item_id);
                if ($item) {
                    $item->decrement('stock', $returnItem->quantity);
                }
            }
            $retur->delete();
            DB::commit();
            return redirect()->route('penjualan.retur.index')->with('success', 'Retur penjualan berhasil dihapus dan stok dikembalikan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus retur: ' . $e->getMessage());
        }
    }
}
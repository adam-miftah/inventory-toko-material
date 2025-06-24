<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Item; // Pastikan model Item Anda diimpor
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon; // Pastikan Carbon diimpor

class SaleController extends Controller
{
    /**
     * Menampilkan daftar semua transaksi penjualan.
     */
    public function index(Request $request)
    {
        $query = Sale::with('user', 'items')
            ->when($request->start_date, function ($query, $startDate) {
                return $query->whereDate('sale_date', '>=', $startDate);
            })
            ->when($request->end_date, function ($query, $endDate) {
                return $query->whereDate('sale_date', '<=', $endDate);
            })
            ->when($request->payment_method, function ($query, $paymentMethod) {
                return $query->where('payment_method', $paymentMethod);
            })
            ->latest();

        // PERUBAHAN DI SINI: Ganti paginate(10) menjadi get()
        // Ini akan mengirim SEMUA data ke view agar bisa dikelola oleh DataTables
        $sales = $query->get();
        
        return view('penjualan.transaksi.index', compact('sales'));
    }

    /**
     * Menampilkan form untuk membuat transaksi penjualan baru (halaman POS).
     */
    public function create()
    {
        $items = Item::where('stock', '>', 0)
                     ->orderBy('name')
                     ->get();
        return view('penjualan.transaksi.create', compact('items'));
    }

    /**
     * Menyimpan transaksi penjualan baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'nullable|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price_per_unit' => 'required|numeric|min:0',
            'payment_method' => 'required|string|in:cash,transfer',
            'paid_amount' => 'required|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $totalAmount = 0;
            $saleItemsData = [];
            $itemsToUpdateStock = [];

            foreach ($validated['items'] as $itemId => $itemData) {
                $item = Item::find($itemData['item_id']);
                if (!$item) {
                    throw ValidationException::withMessages([ 'items.' . $itemId => 'Item tidak ditemukan.']);
                }

                if ($item->stock < $itemData['quantity']) {
                    throw ValidationException::withMessages(['items.' . $itemId . '.quantity' => 'Stok ' . $item->name . ' tidak mencukupi. Tersedia: ' . $item->stock]);
                }

                $subtotal = $itemData['price_per_unit'] * $itemData['quantity'];
                $totalAmount += $subtotal;

                $saleItemsData[] = [
                    'item_id' => $item->id,
                    'item_name' => $item->name,
                    'quantity' => $itemData['quantity'],
                    'unit_price' => $itemData['price_per_unit'],
                    'subtotal' => $subtotal,
                ];

                $itemsToUpdateStock[$item->id] = $item->stock - $itemData['quantity'];
            }

            $discountAmount = $validated['discount_amount'] ?? 0;
            $grandTotal = $totalAmount - $discountAmount;

            if ($validated['paid_amount'] < $grandTotal) {
                throw ValidationException::withMessages(['paid_amount' => 'Jumlah pembayaran tidak mencukupi.']);
            }

            $sale = Sale::create([
                'invoice_number' => $this->generateInvoiceNumber(),
                'sale_date' => now(),
                'customer_name' => $validated['customer_name'] ?? 'Umum',
                'total_amount' => $totalAmount,
                'discount_amount' => $discountAmount,
                'grand_total' => $grandTotal,
                'payment_method' => $validated['payment_method'],
                'paid_amount' => $validated['paid_amount'],
                'change_amount' => $validated['paid_amount'] - $grandTotal,
                'user_id' => Auth::id(),
                'notes' => $request->notes,
            ]);

            $sale->items()->createMany($saleItemsData);

            foreach ($itemsToUpdateStock as $itemId => $newStock) {
                Item::where('id', $itemId)->update(['stock' => $newStock]);
            }

            DB::commit(); 

            return redirect()->route('penjualan.transaksi.show', $sale)->with('success', 'Transaksi penjualan berhasil dibuat!');
        } catch (ValidationException $e) {
            DB::rollBack();
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memproses transaksi: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Menampilkan detail transaksi penjualan.
     */
    public function show(Sale $transaksi)
    {
        $transaksi->load('user', 'items.item');
        return view('penjualan.transaksi.show', compact('transaksi'));
    }

    /**
     * Helper function to generate a unique invoice number.
     */
    private function generateInvoiceNumber()
    {
        $latestSale = Sale::latest('id')->first();
        $today = now()->format('Ymd');
        $nextNumber = 1;

        if ($latestSale) {
            $lastInvoiceNumber = $latestSale->invoice_number;
            $lastDate = substr($lastInvoiceNumber, 4, 8);

            if ($lastDate === $today) {
                $lastNum = (int) substr($lastInvoiceNumber, -4);
                $nextNumber = $lastNum + 1;
            }
        }
        return 'INV-' . $today . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
    
    public function printReceipt(Sale $transaksi)
    {
        $transaksi->load('items', 'user');
        
        $storeSettings = [
            'store_name' => 'TB SOGOL ANUGRAH MANDIRI',
            'store_address' => 'Jl. Contoh No.123, Kota Anda',
            'store_phone' => '(021) 12345678',
            'store_email' => 'info@perusahaan.com'
        ];
        
        return view('penjualan.struk', [
            'sale' => $transaksi,
            'settings' => (object)$storeSettings,
            'is_print_view' => true
        ]);
    }
}

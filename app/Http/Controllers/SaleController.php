<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Item; // Pastikan model Item Anda diimpor
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class SaleController extends Controller
{
    /**
     * Menampilkan daftar semua transaksi penjualan.
     */
    public function index(Request $request)
{
    $sales = Sale::with('user', 'items')
        ->when($request->start_date, function ($query, $startDate) {
            return $query->whereDate('sale_date', '>=', $startDate);
        })
        ->when($request->end_date, function ($query, $endDate) {
            return $query->whereDate('sale_date', '<=', $endDate);
        })
        ->when($request->payment_method, function ($query, $paymentMethod) {
            return $query->where('payment_method', $paymentMethod);
        })
        ->latest()
        ->paginate(10);
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

    // Debugging: Tampilkan data yang diambil
    // dd($items->toArray()); // Gunakan toArray() agar lebih mudah dibaca jika banyak item
    // dd($items->isEmpty()); // Debugging: cek apakah koleksi kosong

    return view('penjualan.transaksi.create', compact('items'));
    }

    /**
     * Menyimpan transaksi penjualan baru ke database.
     */
    public function store(Request $request)
{
    $validated = $request->validate([
        'customer_name' => 'nullable|string|max:255',
        'items' => 'required|array|min:1', // Minimal harus ada 1 item
        'items.*.quantity' => 'required|integer|min:1',
        'payment_method' => 'required|string|in:cash,card,transfer,other', // Sesuaikan metode pembayaran Anda
        'paid_amount' => 'required|numeric|min:0',
        'discount_amount' => 'nullable|numeric|min:0',
        'tax_amount' => 'nullable|numeric|min:0',
        'notes' => 'nullable|string',
    ]);

    DB::beginTransaction(); // Mulai transaksi database
    try {
        $totalAmount = 0;
        $saleItemsData = [];
        $itemsToUpdateStock = [];

        foreach ($validated['items'] as $itemId => $itemData) { // Ubah $itemData menjadi $itemId => $itemData
            $item = Item::find($itemId); // Gunakan $itemId sebagai ID
            if (!$item) {
                throw ValidationException::withMessages([ 'items.' . $itemId => 'Item dengan ID ' . $itemId . ' tidak ditemukan.']);
            }

            // Cek stok yang tersedia
            if ($item->stock < $itemData['quantity']) {
                throw ValidationException::withMessages(['items.' . $itemId . '.quantity' => 'Stok ' . $item->name . ' tidak mencukupi. Tersedia: ' . $item->stock . ', Diminta: ' . $itemData['quantity']]);}

            $subtotal = $item->price * $itemData['quantity'];
            $totalAmount += $subtotal;

            $saleItemsData[] = [
                'item_id' => $item->id,
                'item_name' => $item->name, // Simpan nama item untuk riwayat transaksi
                'quantity' => $itemData['quantity'],
                'unit_price' => $item->price,
                'subtotal' => $subtotal,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Siapkan untuk update stok setelah transaksi berhasil
            $itemsToUpdateStock[$item->id] = $item->stock - $itemData['quantity'];
        }

        $discountAmount = $validated['discount_amount'] ?? 0;
        $taxAmount = $validated['tax_amount'] ?? 0;
        $grandTotal = $totalAmount - $discountAmount + $taxAmount;

        // Validasi jumlah pembayaran
        if ($validated['paid_amount'] < $grandTotal) {
            throw ValidationException::withMessages(['paid_amount' => 'Jumlah pembayaran tidak mencukupi. Dibutuhkan: Rp ' . number_format($grandTotal, 0, ',', '.') . '.',]);
        }

        $sale = Sale::create([
            'invoice_number' => $this->generateInvoiceNumber(), // Panggil fungsi generator nomor faktur
            'sale_date' => now()->toDateString(),
            'customer_name' => $validated['customer_name'] ?? null,
            'total_amount' => $totalAmount,
            'discount_amount' => $discountAmount,
            'tax_amount' => $taxAmount,
            'grand_total' => $grandTotal,
            'payment_method' => $validated['payment_method'],
            'paid_amount' => $validated['paid_amount'],
            'change_amount' => $validated['paid_amount'] - $grandTotal,
            'user_id' => Auth::id(),
            'notes' => $validated['notes'] ?? null,
        ]);

        // Simpan detail item penjualan
        $sale->items()->createMany($saleItemsData);

        // Perbarui stok barang di inventaris
        foreach ($itemsToUpdateStock as $itemId => $newStock) {
            Item::where('id', $itemId)->update(['stock' => $newStock]);
        }

        DB::commit(); // Komit transaksi jika semua berhasil

        return redirect()->route('penjualan.transaksi.print_receipt', $sale)->with('success', 'Transaksi penjualan berhasil dibuat!');

    } catch (ValidationException $e) {
        DB::rollBack(); // Rollback transaksi jika validasi gagal
        return redirect()->back()->withErrors($e->errors())->withInput();
    } catch (\Exception $e) {
        DB::rollBack(); // Rollback transaksi jika ada error lain
        return redirect()->back()->with('error', 'Terjadi kesalahan saat memproses transaksi: ' . $e->getMessage())->withInput();
    }
}

    /**
     * Menampilkan detail transaksi penjualan.
     */
    public function show(Sale $transaksi)
    {
        // dd($transaksi->toArray()); 
        $transaksi->load('user', 'items.item'); // Memuat relasi user dan item-item penjualan
        return view('penjualan.transaksi.show', compact('transaksi'));
    }

    /**
     * Helper function to generate a unique invoice number.
     * Format: INV-YYYYMMDD-NNNN
     */
    private function generateInvoiceNumber()
    {
        $latestSale = Sale::latest()->first();
        $today = now()->format('Ymd');
        $nextNumber = 1;

        if ($latestSale) {
            $lastInvoiceNumber = $latestSale->invoice_number;
            $lastDate = substr($lastInvoiceNumber, 4, 8); // Extract YYYYMMDD part

            if ($lastDate === $today) {
                $lastNum = (int) substr($lastInvoiceNumber, -4); // Extract NNNN part
                $nextNumber = $lastNum + 1;
            }
        }
        return 'INV-' . $today . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
    // Di SaleController.php
public function printReceipt(Sale $sale)
{
    $sale->load('items'); // Memuat detail item penjualan
    return view('penjualan.struk', compact('sale')); // Mengarahkan ke view cetak struk
}
}
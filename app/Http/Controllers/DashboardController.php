<?php

namespace App\Http\Controllers;

use App\Models\SparePart;
use App\Models\Inventory;
use App\Models\StockTransaction;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Statistik Utama
        $inventoryValue = SparePart::join('inventories', 'spare_parts.id', '=', 'inventories.spare_part_id')
            ->sum(DB::raw('inventories.stock * spare_parts.unit_price'));
        
        $totalSKU = SparePart::count();
        $lowStockItems = Inventory::lowStock()->with('sparePart')->get();
        $criticalCount = $lowStockItems->count();

        // 2. Data untuk Modal
        $skuList = SparePart::with('inventory')->latest()->take(50)->get();
        $pendingOrderList = StockTransaction::where('type', 'masuk')
            ->where('status', 'Pending')
            ->with(['sparePart', 'supplier'])
            ->latest()
            ->get();

        $pendingOrders = $pendingOrderList->count();

        // 3. Data Komposisi Kategori (Pie Chart)
        $categoryStats = SparePart::select('category', DB::raw('count(*) as total'))
            ->groupBy('category')
            ->get()
            ->map(function($item) use ($totalSKU) {
                return [
                    'name' => $item->category,
                    'percentage' => $totalSKU > 0 ? round(($item->total / $totalSKU) * 100) : 0
                ];
            });

        // 4. Data Pergerakan Stok 30 Hari (Line Chart)
        // Kita buat array 30 hari terakhir agar grafik tidak kosong jika ada hari tanpa transaksi
        $movementData = collect();
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i)->format('Y-m-d');
            $val = StockTransaction::where('type', 'masuk')
                ->whereDate('created_at', $date)
                ->sum('quantity');
            $movementData->put($date, $val);
        }

        // Tambahkan logika untuk mencari supplier terakhir secara dinamis
        foreach ($lowStockItems as $item) {
            $lastTransaction = \App\Models\StockTransaction::where('spare_part_id', $item->spare_part_id)
                ->where('type', 'masuk')
                ->with('supplier')
                ->latest()
                ->first();
                
                // Tempelkan nama supplier ke objek secara sementara untuk ditampilkan di Blade
                $item->last_supplier_name = $lastTransaction->supplier->name ?? 'Belum Ada Inbound';
                $item->last_supplier_id = $lastTransaction->supplier_id ?? null;
        }

        return view('manajer-pembelian-dashboard', compact(
            'inventoryValue', 'totalSKU', 'criticalCount', 'pendingOrders', 'pendingOrderList',
            'lowStockItems', 'skuList', 'pendingOrderList', 'categoryStats', 'movementData'
        ));
    }

    public function massReorder()
    {
        $lowStockItems = Inventory::lowStock()->get();

        DB::transaction(function () use ($lowStockItems) {
            foreach ($lowStockItems as $item) {
                $lastSupplier = \App\Models\StockTransaction::where('spare_part_id', $item->spare_part_id)
                    ->where('type', 'masuk')
                    ->latest()
                    ->first();

                \App\Models\StockTransaction::create([
                    'spare_part_id' => $item->spare_part_id,
                    'user_id'       => Auth::id(),
                    'supplier_id'   => $lastSupplier->supplier_id ?? null, // Ambil dari transaksi terakhir
                    'type'          => 'masuk',
                    'quantity'      => $item->min_stock * 2,
                    'reference'     => 'MASS-REORDER-' . date('YmdHis'),
                    'status'        => 'Pending',
                    'notes'         => 'Otomatis dari supplier terakhir'
                ]);
            }
        });

        return redirect()->back()->with('success', 'Pesanan massal diproses berdasarkan riwayat supplier.');
    }

    public function singleReorder($inventory_id)
    {
        // 1. Cari data inventory yang dimaksud
        $inventory = Inventory::with('sparePart')->findOrFail($inventory_id);

        // 2. Cari siapa supplier terakhir yang mengirim barang ini
        $lastTransaction = StockTransaction::where('spare_part_id', $inventory->spare_part_id)
            ->where('type', 'masuk')
            ->latest()
            ->first();

        try {
            DB::transaction(function () use ($inventory, $lastTransaction) {
                // 3. Buat draf transaksi Pending
                StockTransaction::create([
                    'spare_part_id' => $inventory->spare_part_id,
                    'user_id'       => Auth::id(),
                    'supplier_id'   => $lastTransaction->supplier_id ?? null,
                    'type'          => 'masuk',
                    'quantity'      => $inventory->min_stock * 2, // Default pesanan: 2x min_stock
                    'reference'     => 'REORDER-' . $inventory->sparePart->part_number . '-' . date('Ymd'),
                    'status'        => 'Pending',
                    'notes'         => 'Pemesanan manual dari tabel Low Stock'
                ]);
            });

            return redirect()->back()->with('success', 'Draf pesanan untuk ' . $inventory->sparePart->name . ' berhasil dibuat.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memproses reorder: ' . $e->getMessage());
        }
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\SparePart;
use App\Models\StockTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class OutbondController extends Controller
{
    public function index()
    {
        // 1. Kalkulasi Stat Cards
        $inventoryValue = Inventory::join('spare_parts', 'inventories.spare_part_id', '=', 'spare_parts.id')
            ->sum(DB::raw('inventories.stock * spare_parts.unit_price'));

        $totalSKU = SparePart::count();
        $newSKUCount = SparePart::where('created_at', '>=', Carbon::now()->subDays(7))->count();

        $lowStockItems = Inventory::lowStock()->with('sparePart')->get();
        $criticalCount = $lowStockItems->count();

        // Pending Outbound (Transaksi Keluar yang belum dikirim/selesai)
        $pendingOutbound = StockTransaction::where('type', 'keluar')
            ->where('status', 'Pending')
            ->count();

        // 2. Data Pie Chart (Komposisi Kategori)
        $categoryStats = SparePart::select('category', DB::raw('count(*) as total'))
            ->groupBy('category')
            ->get()
            ->map(function ($item) use ($totalSKU) {
                return [
                    'name' => $item->category,
                    'percentage' => round(($item->total / $totalSKU) * 100)
                ];
            });

        // 3. Data Line Chart (Aktivitas Keluar 30 Hari Terakhir)
        $movementData = collect();
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $count = StockTransaction::where('type', 'keluar')
                ->whereDate('created_at', $date)
                ->sum('quantity');
            $movementData->push($count);
        }

        // 4. Riwayat Supplier Terakhir untuk Tabel Low Stock
        foreach ($lowStockItems as $item) {
            $lastInbound = StockTransaction::where('spare_part_id', $item->spare_part_id)
                ->where('type', 'masuk')
                ->with('supplier')
                ->latest()
                ->first();
            
            $item->last_supplier_name = $lastInbound->supplier->name ?? 'N/A';
        }

        $transactions = StockTransaction::where('type', 'keluar')
            ->with(['sparePart'])
            ->latest()
            ->take(10) // Ambil 10 transaksi terakhir
            ->get();

        $pendingTransactions = StockTransaction::where('type', 'keluar')
            ->where('status', 'Pending')
            ->with('sparePart')
            ->latest()
            ->get();

        $pendingOutbound = $pendingTransactions->count();

        $totalSKU = SparePart::count();
        $newSKUCount = SparePart::where('created_at', '>=', Carbon::now()->subDays(7))->count();

        // Data Outbond Berdasarkan Status
        $completedTransactions = StockTransaction::where('type', 'keluar')->where('status', 'Selesai')->with('sparePart')->latest()->get();
        $completedCount = $completedTransactions->count();

        $pendingTransactions = StockTransaction::where('type', 'keluar')->where('status', 'Pending')->with('sparePart')->latest()->get();
        $pendingOutbound = $pendingTransactions->count();

        $cancelledTransactions = StockTransaction::where('type', 'keluar')->where('status', 'Batal')->with('sparePart')->latest()->get();
        $cancelledCount = $cancelledTransactions->count();

        // Data lainnya untuk chart dan tabel peringatan
        $categoryStats = SparePart::select('category', DB::raw('count(*) as total'))->groupBy('category')->get()->map(function($item) use ($totalSKU){
            return ['name' => $item->category, 'percentage' => $totalSKU > 0 ? round(($item->total / $totalSKU) * 100) : 0];
        });

        $lowStockItems = Inventory::lowStock()->with('sparePart')->get();
        $transactions = StockTransaction::where('type', 'keluar')->with('sparePart')->latest()->take(10)->get();

        return view('admin-gudang-outbondstock', compact(
            'totalSKU', 'newSKUCount', 'completedCount', 'completedTransactions',
            'pendingOutbound', 'pendingTransactions', 'cancelledCount', 'cancelledTransactions',
            'categoryStats', 'movementData', 'lowStockItems', 'transactions'
        ));
        }

    // Fungsi tambahan untuk mencatat pengeluaran barang baru (Outbound Action)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'spare_part_id' => 'required',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string'
        ]);

        DB::transaction(function () use ($validated) {
            // Update stok fisik di tabel inventory
            $inventory = Inventory::where('spare_part_id', $validated['spare_part_id'])->first();
            
            if ($inventory->stock < $validated['quantity']) {
                throw new \Exception('Stok tidak mencukupi untuk pengeluaran ini.');
            }

            $inventory->decrement('stock', $validated['quantity']);

            // Catat transaksi keluar
            StockTransaction::create([
                'spare_part_id' => $validated['spare_part_id'],
                'user_id' => Auth::id(),
                'type' => 'keluar',
                'quantity' => $validated['quantity'],
                'reference' => 'OUT-' . date('YmdHis'),
                'status' => 'Selesai',
                'notes' => $validated['notes']
            ]);
        });

        return redirect()->back()->with('success', 'Barang berhasil dikeluarkan dari gudang.');
    }
}
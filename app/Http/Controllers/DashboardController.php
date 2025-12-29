<?php

namespace App\Http\Controllers;

use App\Models\SparePart;
use App\Models\Inventory;
use App\Models\StockTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // UC-05: Ringkasan Nilai Total Inventori (Sum unit_price * stock)
        $inventoryValue = SparePart::join('inventories', 'spare_parts.id', '=', 'inventories.spare_part_id')
            ->sum(DB::raw('inventories.stock * spare_parts.unit_price'));

        $totalSKU = SparePart::count();
        $pendingOrders = StockTransaction::where('status', 'Pending')->count();

        // UC-07: Menggunakan scope LowStock dari model Inventory
        $lowStockItems = Inventory::lowStock()->with('sparePart')->get();
        $criticalCount = $lowStockItems->count();

        return view('manajer-pembelian-dashboard', compact(
            'inventoryValue', 
            'totalSKU', 
            'criticalCount', 
            'pendingOrders', 
            'lowStockItems'
        ));
    }
}

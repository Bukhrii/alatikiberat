<?php

namespace App\Http\Controllers;

use App\Models\SparePart;
use App\Models\StockTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class InventoryReportController extends Controller
{
    public function index()
    {
        // Menghasilkan ringkasan laporan per kategori (seperti di view)
        $categoryValuation = SparePart::join('inventories', 'spare_parts.id', '=', 'inventories.spare_part_id')
            ->select(
                'category',
                DB::raw('count(spare_parts.id) as item_count'),
                DB::raw('sum(inventories.stock) as total_stock'),
                DB::raw('sum(inventories.stock * spare_parts.unit_price) as total_value')
            )
            ->groupBy('category')
            ->get();

        return view('manajer-pembelian-inventoryreports', compact('categoryValuation'));
    }

    public function fastMoving()
    {
        // UC-08: Menganalisis item Fast-Moving berdasarkan transaksi keluar terbanyak
        $fastMovingItems = StockTransaction::where('type', 'keluar')
            ->select('spare_part_id', DB::raw('sum(quantity) as total_sold'))
            ->groupBy('spare_part_id')
            ->orderBy('total_sold', 'desc')
            ->with('sparePart')
            ->take(10)
            ->get();

        return view('manajer-pembelian-inventoryreports-fast', compact('fastMovingItems'));
    }
}

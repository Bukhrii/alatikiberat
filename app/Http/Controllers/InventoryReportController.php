<?php

namespace App\Http\Controllers;

use App\Models\SparePart;
use App\Exports\InventoryReportExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class InventoryReportController extends Controller
{
    public function index(Request $request)
    {
        // Menentukan nilai default jika input kosong: Defaultnya adalah Hari Ini
        $startDate = $request->input('start_date', date('Y-m-d'));
        $endDate = $request->input('end_date', date('Y-m-d'));
        
        // Ambil daftar kategori untuk dropdown dinamis
        $categories = SparePart::distinct()->pluck('category');

        $query = SparePart::join('inventories', 'spare_parts.id', '=', 'inventories.spare_part_id')
            ->select(
                'category',
                DB::raw('count(spare_parts.id) as item_count'),
                DB::raw('sum(inventories.stock) as total_stock'),
                DB::raw('sum(inventories.stock * spare_parts.unit_price) as total_value')
            );

        // Filter Kategori
        if ($request->filled('category') && $request->category != 'Semua Kategori') {
            $query->where('category', $request->category);
        }

        // Filter Tanggal: Membatasi data berdasarkan kapan stok tersebut terakhir diupdate/masuk
        // Gunakan whereBetween pada kolom updated_at atau created_at
        $query->whereBetween('inventories.updated_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);

        $categoryValuation = $query->groupBy('category')->get();

        return view('manajer-pembelian-inventoryreports', compact(
            'categoryValuation', 
            'categories', 
            'startDate', 
            'endDate'
        ));
    }

    public function exportExcel(Request $request)
    {
        $startDate = $request->input('start_date', date('Y-m-d'));
        $endDate = $request->input('end_date', date('Y-m-d'));
        
        $query = SparePart::join('inventories', 'spare_parts.id', '=', 'inventories.spare_part_id')
            ->select(
                'category',
                DB::raw('count(spare_parts.id) as item_count'),
                DB::raw('sum(inventories.stock) as total_stock'),
                DB::raw('sum(inventories.stock * spare_parts.unit_price) as total_value')
            )
            ->whereBetween('inventories.updated_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);

        if ($request->filled('category') && $request->category != 'Semua Kategori') {
            $query->where('category', $request->category);
        }

        $data = $query->groupBy('category')->get();

        // Unduh file dengan nama yang dinamis berdasarkan tanggal
        return Excel::download(
            new InventoryReportExport($data), 
            'Laporan_Inventaris_' . $startDate . '_ke_' . $endDate . '.xlsx'
        );
    }

    public function exportPdf(Request $request)
    {
        $startDate = $request->input('start_date', date('Y-m-d'));
        $endDate = $request->input('end_date', date('Y-m-d'));
        
        $query = SparePart::join('inventories', 'spare_parts.id', '=', 'inventories.spare_part_id')
            ->select(
                'category',
                DB::raw('count(spare_parts.id) as item_count'),
                DB::raw('sum(inventories.stock) as total_stock'),
                DB::raw('sum(inventories.stock * spare_parts.unit_price) as total_value')
            )
            ->whereBetween('inventories.updated_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);

        if ($request->filled('category') && $request->category != 'Semua Kategori') {
            $query->where('category', $request->category);
        }

        $categoryValuation = $query->groupBy('category')->get();

        // Load view khusus untuk PDF
        $pdf = Pdf::loadView('inventory-pdf', compact('categoryValuation', 'startDate', 'endDate'))
                  ->setPaper('a4', 'portrait');

        return $pdf->download('Laporan_Inventaris_' . $startDate . '.pdf');
    }
}

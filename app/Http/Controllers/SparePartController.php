<?php

namespace App\Http\Controllers;

use App\Models\SparePart;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SparePartController extends Controller
{
    public function index()
    {
        $parts = SparePart::with('inventory')->get();
        return view('admin-gudang-management', compact('parts'));
    }

    // UC-01: Create - Menambah Suku Cadang Baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'part_number' => 'required|unique:spare_parts',
            'name' => 'required',
            'category' => 'required',
            'brand' => 'required',
            'unit_price' => 'required|numeric',
            'location_rack' => 'required',
            'min_stock' => 'required|integer',
        ]);

        DB::transaction(function () use ($validated) {
            $part = SparePart::create([
                'part_number' => $validated['part_number'],
                'name' => $validated['name'],
                'category' => $validated['category'],
                'brand' => $validated['brand'],
                'unit_price' => $validated['unit_price'],
                // supplier_id dibiarkan null di sini
            ]);
        
            Inventory::create([
                'spare_part_id' => $part->id,
                'stock' => 0,
                'min_stock' => $validated['min_stock'],
                'location_rack' => $validated['location_rack'],
            ]);
        });

        return redirect()->back()->with('success', 'Barang berhasil didaftarkan.');
    }

// app/Http/Controllers/SparePartController.php

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'category' => 'required',
            'brand' => 'required',
            'unit_price' => 'required|numeric',
            'location_rack' => 'required',
            'min_stock' => 'required|integer',
        ]);

        DB::transaction(function () use ($request, $id) {
            $part = SparePart::findOrFail($id);
            // Update data utama (Termasuk Brand & Kategori)
            $part->update($request->only(['name', 'category', 'brand', 'unit_price']));

            // Update data inventory
            $part->inventory->update([
                'location_rack' => $request->location_rack,
                'min_stock' => $request->min_stock,
            ]);
        });

        return redirect()->back()->with('success', 'Data Suku Cadang berhasil diperbarui.');
    }

    public function destroy($id)
    {
        SparePart::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Item berhasil dihapus.');
    }

    // UC-04: Menampilkan Daftar Lokasi Rak
    // 1. Menampilkan Daftar Lokasi & Kalkulasi Kapasitas
    public function rackLocations(Request $request)
    {
        $search = $request->input('search');

        // Mengambil data inventory
        $inventories = Inventory::with('sparePart')
            ->when($search, function ($query, $search) {
                return $query->whereHas('sparePart', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                    ->orWhere('part_number', 'like', "%{$search}%");
                });
            })->get();

        // Tentukan kapasitas maksimal rak (Misal: 100 item per rak)
        $maxCapacity = 100; 
    
        // Hitung jumlah item riil di tiap rak
        $counts = [
            'A' => Inventory::where('location_rack', 'LIKE', 'A%')->count(),
            'B' => Inventory::where('location_rack', 'LIKE', 'B%')->count(),
            'C' => Inventory::where('location_rack', 'LIKE', 'C%')->count(),
        ];

        // Hitung persentase untuk progress bar
        $capacity = [
            'A' => ($counts['A'] / $maxCapacity) * 100,
            'B' => ($counts['B'] / $maxCapacity) * 100,
            'C' => ($counts['C'] / $maxCapacity) * 100,
        ];

        return view('admin-gudang-stocklocation', compact('inventories', 'capacity', 'counts', 'maxCapacity'));
    }

    // 3. Fungsi Pindahkan (Update Lokasi Rak)
    public function moveItem(Request $request, $id)
    {
        $request->validate([
            'new_rack' => 'required|string|max:10'
        ]);

        $inventory = Inventory::findOrFail($id);
        $inventory->update([
            'location_rack' => $request->new_rack
        ]);

        return redirect()->back()->with('success', 'Posisi barang berhasil dipindahkan ke Rak ' . $request->new_rack);
    }
}

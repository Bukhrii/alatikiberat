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
            $part = SparePart::create($validated);
            
            // Otomatis buat entry di tabel inventory saat sparepart baru ditambah
            Inventory::create([
                'spare_part_id' => $part->id,
                'stock' => 0,
                'min_stock' => $validated['min_stock'],
                'location_rack' => $validated['location_rack'],
            ]);
        });

        return redirect()->back()->with('success', 'Data Suku Cadang berhasil ditambahkan.');
    }

    // UC-04: Menampilkan Daftar Lokasi Rak
    public function rackLocations()
    {
        $inventories = Inventory::with('sparePart')->get();
        return view('admin-gudang-stocklocation', compact('inventories'));
    }
}

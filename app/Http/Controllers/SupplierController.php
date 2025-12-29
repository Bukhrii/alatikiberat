<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        // Fitur Pencarian berdasarkan Nama atau Contact Person
        $suppliers = Supplier::when($search, function ($query, $search) {
            return $query->where('name', 'like', "%{$search}%")
                         ->orWhere('contact_person', 'like', "%{$search}%");
        })->paginate(10);

        return view('manajer-pembelian-suppliermanagement', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'address' => 'required|string',
            'rating' => 'required|in:A,B,C,D',
        ]);

        Supplier::create($validated);
        return redirect()->back()->with('success', 'Supplier berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'address' => 'required|string',
            'rating' => 'required|in:A,B,C,D',
        ]);

        $supplier->update($validated);
        return redirect()->back()->with('success', 'Data supplier berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);
        
        // Cek relasi agar tidak error jika supplier sudah punya transaksi
        if ($supplier->transactions()->count() > 0) {
            return redirect()->back()->with('error', 'Supplier tidak bisa dihapus karena memiliki riwayat transaksi.');
        }

        $supplier->delete();
        return redirect()->back()->with('success', 'Supplier berhasil dihapus.');
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::all();
        return view('manajer-pembelian-suppliermanagement', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'contact_person' => 'required',
            'phone_number' => 'required',
            'address' => 'required',
            'rating' => 'required|in:A,B,C,D',
        ]);

        Supplier::create($validated);
        return redirect()->back()->with('success', 'Supplier berhasil ditambahkan.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\StockTransaction;
use App\Models\Inventory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class StockTransactionController extends Controller
{
    public function inboundIndex()
    {
        $transactions = StockTransaction::where('type', 'masuk')->with(['sparePart', 'supplier'])->latest()->get();
        return view('admin-gudang-inboundstock', compact('transactions'));
    }

    // UC-02: Simpan Stok Masuk
    public function storeInbound(Request $request)
    {
        $request->validate([
            'spare_part_id' => 'required',
            'supplier_id' => 'required',
            'quantity' => 'required|integer|min:1',
            'reference' => 'required',
        ]);

        DB::transaction(function () use ($request) {
            StockTransaction::create([
                'spare_part_id' => $request->spare_part_id,
                'user_id' => Auth::id(),
                'supplier_id' => $request->supplier_id,
                'type' => 'masuk',
                'quantity' => $request->quantity,
                'reference' => $request->reference,
                'status' => 'Selesai',
                'notes' => $request->notes
            ]);

            Inventory::where('spare_part_id', $request->spare_part_id)->increment('stock', $request->quantity);
        });

        return redirect()->back()->with('success', 'Stok Masuk berhasil dicatat.');
    }

    // UC-03: Simpan Stok Keluar
    public function storeOutbound(Request $request)
    {
        $request->validate([
            'spare_part_id' => 'required',
            'quantity' => 'required|integer|min:1',
            'reference' => 'required',
        ]);

        $inventory = Inventory::where('spare_part_id', $request->spare_part_id)->first();
        if ($inventory->stock < $request->quantity) {
            return redirect()->back()->with('error', 'Stok tidak cukup!');
        }

        DB::transaction(function () use ($request) {
            StockTransaction::create([
                'spare_part_id' => $request->spare_part_id,
                'user_id' => Auth::id(),
                'type' => 'keluar',
                'quantity' => $request->quantity,
                'reference' => $request->reference,
                'status' => 'Selesai',
                'notes' => $request->notes
            ]);

            Inventory::where('spare_part_id', $request->spare_part_id)->decrement('stock', $request->quantity);
        });

        return redirect()->back()->with('success', 'Stok Keluar berhasil dicatat.');
    }
}

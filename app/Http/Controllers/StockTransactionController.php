<?php

namespace App\Http\Controllers;

use App\Models\StockTransaction;
use App\Models\Inventory;
use App\Models\SparePart;
use App\Models\Supplier;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class StockTransactionController extends Controller
{
    public function inboundIndex()
    {
        // Mengambil riwayat transaksi masuk
        $transactions = StockTransaction::where('type', 'masuk')
            ->with(['sparePart', 'supplier'])
            ->latest()
            ->get();

        // Mengambil data untuk pilihan di dropdown form
        $parts = SparePart::all();
        $suppliers = Supplier::all();

        $inventoryValue = \App\Models\Inventory::join('spare_parts', 'inventories.spare_part_id', '=', 'spare_parts.id')
            ->sum(\Illuminate\Support\Facades\DB::raw('inventories.stock * spare_parts.unit_price'));

        return view('admin-gudang-inboundstock', compact('transactions', 'parts', 'suppliers', 'inventoryValue'));
    }

    // UC-02: Simpan Stok Masuk
    public function storeInbound(Request $request)
    {
        $request->validate([
            'spare_part_id' => 'required',
            'supplier_id' => 'required',
            'quantity' => 'required|integer|min:1',
            'reference' => 'required',
            'status' => 'required|in:Selesai,Pending,Batal', // Validasi status
        ]);

        DB::transaction(function () use ($request) {
            StockTransaction::create([
                'spare_part_id' => $request->spare_part_id,
                'user_id' => Auth::id(),
                'supplier_id' => $request->supplier_id,
                'type' => 'masuk',
                'quantity' => $request->quantity,
                'reference' => $request->reference,
                'status' => $request->status, // Mengambil dari input
                'notes' => $request->notes
            ]);

            // Stok hanya bertambah jika status langsung 'Selesai'
            if ($request->status === 'Selesai') {
                Inventory::where('spare_part_id', $request->spare_part_id)->increment('stock', $request->quantity);
            }
        });

        return redirect()->back()->with('success', 'Transaksi masuk berhasil dicatat.');
    }

    public function updateStatus(Request $request, $id)
    {
        $transaction = StockTransaction::findOrFail($id);
        $oldStatus = $transaction->status;
        $newStatus = $request->status;

        DB::transaction(function () use ($transaction, $oldStatus, $newStatus) {
            $inventory = Inventory::where('spare_part_id', $transaction->spare_part_id)->first();

            if ($transaction->type === 'masuk') {
                // Logika Inbound (Stok Bertambah jika Selesai)
                if ($oldStatus !== 'Selesai' && $newStatus === 'Selesai') {
                    $inventory->increment('stock', $transaction->quantity);
                } elseif ($oldStatus === 'Selesai' && $newStatus !== 'Selesai') {
                    $inventory->decrement('stock', $transaction->quantity);
                }
            } else {
                // Logika Outbound (Stok Berkurang jika Selesai)
                if ($oldStatus !== 'Selesai' && $newStatus === 'Selesai') {
                    // Pastikan stok cukup sebelum mengubah ke Selesai
                    if ($inventory->stock < $transaction->quantity) {
                        throw new \Exception('Stok tidak mencukupi untuk menyelesaikan transaksi ini.');
                    }
                    $inventory->decrement('stock', $transaction->quantity);
                } elseif ($oldStatus === 'Selesai' && $newStatus !== 'Selesai') {
                    // Kembalikan stok jika status berubah dari Selesai ke lainnya
                    $inventory->increment('stock', $transaction->quantity);
                }
            }

            $transaction->update(['status' => $newStatus]);
        });

        return redirect()->back()->with('success', 'Status transaksi berhasil diperbarui.');
    }

    // UC-03: Simpan Stok Keluar
    public function storeOutbound(Request $request)
    {
        // 1. Tambahkan validasi untuk input status
        $request->validate([
            'spare_part_id' => 'required',
            'quantity' => 'required|integer|min:1',
            'reference' => 'required',
            'status' => 'required|in:Selesai,Pending,Batal', // Validasi status dinamis
        ]);

        // 2. Cek ketersediaan barang di inventory
        $inventory = Inventory::where('spare_part_id', $request->spare_part_id)->first();
    
        if (!$inventory || $inventory->stock < $request->quantity) {
            return redirect()->back()->with('error', 'Stok tidak mencukupi untuk pengeluaran ini.');
        }

        DB::transaction(function () use ($request, $inventory) {
            // 3. Simpan transaksi dengan status dari input form
            StockTransaction::create([
                'spare_part_id' => $request->spare_part_id,
                'user_id' => Auth::id(),
                'type' => 'keluar',
                'quantity' => $request->quantity,
                'reference' => $request->reference,
                'status' => $request->status, // Mengambil status dinamis dari form
                'notes' => $request->notes
            ]);

            // 4. Logika Stok: Hanya kurangi stok jika statusnya 'Selesai'
            if ($request->status === 'Selesai') {
                $inventory->decrement('stock', $request->quantity);
            }
        });

        return redirect()->back()->with('success', 'Transaksi keluar (' . $request->status . ') berhasil dicatat.');
    }

    public function outboundIndex()
{
    // Mengambil data item yang stoknya menipis untuk peringatan di halaman outbound (UC-07)
    $lowStockItems = \App\Models\Inventory::lowStock()->with('sparePart')->get();
    
    // Mengambil riwayat transaksi keluar (UC-03)
    $transactions = StockTransaction::where('type', 'keluar')
        ->with(['sparePart'])
        ->latest()
        ->get();

    // Pastikan nama view sesuai dengan file yang Anda miliki
    return view('admin-gudang-outbondstock', compact('lowStockItems', 'transactions'));
}
}

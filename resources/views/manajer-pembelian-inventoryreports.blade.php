@extends('layouts.app')

@section('title', 'Laporan Inventaris')
@section('header-title', 'Laporan Inventaris')
@section('header-subtitle', 'Rekapitulasi data aset dan pergerakan stok.')

@section('content')
    <div class="bg-white dark:bg-[#1a202c] p-4 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm flex flex-wrap gap-4 items-end">
        <div>
            <label class="block text-xs font-bold text-gray-500 mb-1">Periode Awal</label>
            <input type="date" class="border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary">
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-500 mb-1">Periode Akhir</label>
            <input type="date" class="border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary">
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-500 mb-1">Kategori</label>
            <select class="border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary min-w-[150px]">
                <option>Semua Kategori</option>
                <option>Engine Parts</option>
                <option>Body Parts</option>
            </select>
        </div>
        <button class="bg-primary text-white px-5 py-2 rounded-lg text-sm font-bold h-10 hover:bg-blue-700">
            Tampilkan Laporan
        </button>
        <button class="bg-green-600 text-white px-5 py-2 rounded-lg text-sm font-bold h-10 hover:bg-green-700 flex items-center gap-2">
            <span class="material-symbols-outlined" style="font-size:18px">download</span> Export Excel
        </button>
    </div>

    <div class="bg-white dark:bg-[#1a202c] rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden min-h-[400px]">
        <div class="p-6 text-center border-b border-gray-100 dark:border-gray-700">
            <h2 class="text-xl font-bold">Laporan Valuasi Aset</h2>
            <p class="text-sm text-gray-500">Periode: 1 Nov 2023 - 30 Nov 2023</p>
        </div>
        
        <div class="p-6">
            <table class="w-full text-left border border-gray-200">
                <thead class="bg-gray-100 dark:bg-gray-800">
                    <tr>
                        <th class="p-3 border text-sm font-bold">Kategori</th>
                        <th class="p-3 border text-sm font-bold text-right">Jml Item</th>
                        <th class="p-3 border text-sm font-bold text-right">Total Stok</th>
                        <th class="p-3 border text-sm font-bold text-right">Nilai Aset (Rp)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categoryValuation as $report)
                    <tr>
                        <td class="p-3 border text-sm">{{ $report->category }}</td>
                        <td class="p-3 border text-sm text-right">{{ $report->item_count }}</td>
                        <td class="p-3 border text-sm text-right">{{ number_format($report->total_stock) }}</td>
                        <td class="p-3 border text-sm text-right">Rp {{ number_format($report->total_value, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
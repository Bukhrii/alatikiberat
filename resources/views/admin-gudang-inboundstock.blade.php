@extends('layouts.app')

@section('title', 'Inbound Stok')
@section('header-title', 'Manajemen Inbound Stok')
@section('header-subtitle', 'Pantau masuknya barang ke gudang.')

@section('content')
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="flex flex-col gap-2 rounded-xl p-5 bg-white dark:bg-[#1a202c] border border-[#dbe0e6] dark:border-gray-700 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-[#617289] dark:text-gray-400 text-sm font-bold uppercase tracking-wider">Total Inbound</p>
                <span class="material-symbols-outlined text-primary bg-primary/10 p-1.5 rounded-md" style="font-size: 20px;">input</span>
            </div>
            <div class="flex flex-col gap-1 mt-2">
                <p class="text-[#111418] dark:text-white text-3xl font-black">{{ number_format($transactions->sum('quantity')) }}</p>
                <div class="flex items-center gap-1">
                    <span class="material-symbols-outlined text-[#07883b] text-sm font-bold">trending_up</span>
                    <p class="text-[#07883b] text-sm font-bold leading-normal">+15% minggu ini</p>
                </div>
            </div>
        </div>
        
        </div>

    <div class="flex flex-col lg:flex-row gap-6">
        <div class="flex-1 flex flex-col gap-4 rounded-xl border border-[#dbe0e6] dark:border-gray-700 bg-white dark:bg-[#1a202c] p-6 shadow-sm">
             <h3 class="text-[#111418] dark:text-white text-lg font-bold">Sumber Inbound</h3>
             </div>

        <div class="flex-[1.5] flex flex-col gap-4 rounded-xl border border-[#dbe0e6] dark:border-gray-700 bg-white dark:bg-[#1a202c] p-6 shadow-sm">
             <h3 class="text-[#111418] dark:text-white text-lg font-bold">Aktivitas Masuk 30 Hari</h3>
             </div>
    </div>

    <div class="flex flex-col rounded-xl border border-[#dbe0e6] dark:border-gray-700 bg-white dark:bg-[#1a202c] shadow-sm overflow-hidden">
        <div class="flex items-center justify-between p-6 border-b border-[#dbe0e6] dark:border-gray-700">
             <h3 class="text-lg font-bold text-[#111418] dark:text-white">Riwayat Barang Masuk</h3>
             <button class="bg-primary text-white px-4 py-2 rounded-lg text-sm font-bold">Input Barang Masuk</button>
        </div>
        <div class="w-full overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#f0f2f4] dark:bg-gray-800">
                        <th class="p-4 text-xs font-bold uppercase text-[#617289]">Barang</th>
                        <th class="p-4 text-xs font-bold uppercase text-[#617289]">Jumlah</th>
                        <th class="p-4 text-xs font-bold uppercase text-[#617289]">Supplier</th>
                        <th class="p-4 text-xs font-bold uppercase text-[#617289] text-right">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#dbe0e6] dark:divide-gray-700">
                    @foreach($transactions as $trx)
                    <tr>
                        <td class="p-4 font-bold text-sm">{{ $trx->sparePart->name }}</td>
                        <td class="p-4 text-sm">{{ $trx->quantity }} Unit</td>
                        <td class="p-4 text-sm">{{ $trx->supplier->name }}</td>
                        <td class="p-4 text-right">
                            <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-bold">{{ $trx->status }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
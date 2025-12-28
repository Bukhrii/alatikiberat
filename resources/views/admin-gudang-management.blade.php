@extends('layouts.app')

@section('title', 'Manajemen Spare Part')
@section('header-title', 'Manajemen Spare Part Gudang')
@section('header-subtitle', 'Kelola inventaris sparepart dan stok di gudang.')

@section('content')
    <div class="flex flex-col rounded-xl border border-[#dbe0e6] dark:border-gray-700 bg-white dark:bg-[#1a202c] shadow-sm overflow-hidden">
        <div class="flex flex-wrap items-center justify-between gap-4 p-6 border-b border-[#dbe0e6] dark:border-gray-700">
            <h3 class="text-[#111418] dark:text-white text-lg font-bold">Master Data Sparepart</h3>
            <button class="flex items-center gap-2 bg-primary text-white px-4 py-2 rounded-lg font-bold hover:bg-blue-700 transition">
                <span class="material-symbols-outlined" style="font-size: 20px;">add</span>
                Tambah Item Baru
            </button>
        </div>
        
        <div class="w-full overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#f0f2f4] dark:bg-gray-800">
                        <th class="p-4 text-xs font-bold uppercase text-[#617289]">SKU & Nama</th>
                        <th class="p-4 text-xs font-bold uppercase text-[#617289]">Kategori</th>
                        <th class="p-4 text-xs font-bold uppercase text-[#617289]">Stok Fisik</th>
                        <th class="p-4 text-xs font-bold uppercase text-[#617289]">Harga Satuan</th>
                        <th class="p-4 text-xs font-bold uppercase text-[#617289] text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#dbe0e6] dark:divide-gray-700">
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                        <td class="p-4">
                            <div class="font-bold text-sm">Filter Oli</div>
                            <div class="text-xs text-gray-500">SKU: FLT-001</div>
                        </td>
                        <td class="p-4 text-sm">Engine Parts</td>
                        <td class="p-4 text-sm font-bold text-green-600">120 Pcs</td>
                        <td class="p-4 text-sm">Rp 45.000</td>
                        <td class="p-4 text-right">
                            <button class="text-gray-500 hover:text-primary"><span class="material-symbols-outlined">edit</span></button>
                            <button class="text-gray-500 hover:text-red-500 ml-2"><span class="material-symbols-outlined">delete</span></button>
                        </td>
                    </tr>
                    </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-200 dark:border-gray-700 text-center">
            <button class="text-sm font-bold text-primary">Muat Lebih Banyak...</button>
        </div>
    </div>
@endsection
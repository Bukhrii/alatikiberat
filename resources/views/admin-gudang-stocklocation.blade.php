@extends('layouts.app')

@section('title', 'Lokasi Stok')
@section('header-title', 'Manajemen Lokasi Stok')
@section('header-subtitle', 'Pantau posisi barang di gudang.')

@section('content')
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="p-5 bg-white dark:bg-[#1a202c] rounded-xl border border-[#dbe0e6] dark:border-gray-700 shadow-sm">
            <p class="text-sm font-bold text-gray-500 uppercase">Kapasitas Rak A</p>
            <p class="text-3xl font-black mt-2">85%</p>
            <div class="w-full bg-gray-200 h-2 rounded-full mt-2"><div class="bg-red-500 h-2 rounded-full w-[85%]"></div></div>
        </div>
        <div class="p-5 bg-white dark:bg-[#1a202c] rounded-xl border border-[#dbe0e6] dark:border-gray-700 shadow-sm">
            <p class="text-sm font-bold text-gray-500 uppercase">Kapasitas Rak B</p>
            <p class="text-3xl font-black mt-2">40%</p>
            <div class="w-full bg-gray-200 h-2 rounded-full mt-2"><div class="bg-blue-500 h-2 rounded-full w-[40%]"></div></div>
        </div>
        <div class="p-5 bg-white dark:bg-[#1a202c] rounded-xl border border-[#dbe0e6] dark:border-gray-700 shadow-sm">
            <p class="text-sm font-bold text-gray-500 uppercase">Kapasitas Rak C</p>
            <p class="text-3xl font-black mt-2">25%</p>
            <div class="w-full bg-gray-200 h-2 rounded-full mt-2"><div class="bg-green-500 h-2 rounded-full w-[25%]"></div></div>
        </div>
    </div>

    <div class="bg-white dark:bg-[#1a202c] rounded-xl border border-[#dbe0e6] dark:border-gray-700 shadow-sm p-6">
        <h3 class="text-lg font-bold mb-4">Daftar Lokasi Item</h3>
        <table class="w-full text-left">
            <thead>
                <tr class="border-b border-gray-200 dark:border-gray-700">
                    <th class="pb-3 text-sm font-bold text-gray-500">Zona</th>
                    <th class="pb-3 text-sm font-bold text-gray-500">Rak</th>
                    <th class="pb-3 text-sm font-bold text-gray-500">Item</th>
                    <th class="pb-3 text-sm font-bold text-gray-500 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @foreach($inventories as $inv)
                <tr class="group hover:bg-gray-50 dark:hover:bg-gray-800">
                    <td class="py-3 text-sm font-bold">Zona Default</td>
                    <td class="py-3 text-sm">{{ $inv->location_rack }}</td>
                    <td class="py-3 text-sm">{{ $inv->sparePart->name }}</td>
                    <td class="py-3 text-right">
                        <button class="text-primary font-bold text-sm">Pindahkan</button>
                    </td>
                </tr>
                 @endforeach
            </tbody>
        </table>
    </div>
@endsection
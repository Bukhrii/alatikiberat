@extends('layouts.app')

@section('title', 'Manajemen Supplier')
@section('header-title', 'Manajemen Supplier')
@section('header-subtitle', 'Kelola daftar dan kinerja supplier.')

@section('content')
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="p-5 bg-white dark:bg-[#1a202c] rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
            <p class="text-sm font-bold text-gray-500">Total Supplier Aktif</p>
            <p class="text-3xl font-black mt-2">12</p>
        </div>
        <div class="p-5 bg-white dark:bg-[#1a202c] rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
            <p class="text-sm font-bold text-gray-500">Purchase Order Bulan Ini</p>
            <p class="text-3xl font-black mt-2">8 PO</p>
        </div>
        <div class="p-5 bg-white dark:bg-[#1a202c] rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
            <p class="text-sm font-bold text-gray-500">Pending Delivery</p>
            <p class="text-3xl font-black mt-2 text-orange-500">3</p>
        </div>
    </div>

    <div class="flex flex-col rounded-xl border border-[#dbe0e6] dark:border-gray-700 bg-white dark:bg-[#1a202c] shadow-sm overflow-hidden mt-2">
        <div class="p-6 border-b border-[#dbe0e6] dark:border-gray-700 flex justify-between">
            <h3 class="text-lg font-bold">Daftar Supplier</h3>
            <button class="text-sm font-bold bg-primary text-white px-3 py-2 rounded">Tambah Supplier</button>
        </div>
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-800">
                    <th class="p-4 text-xs font-bold uppercase text-gray-500">Nama Supplier</th>
                    <th class="p-4 text-xs font-bold uppercase text-gray-500">Kontak</th>
                    <th class="p-4 text-xs font-bold uppercase text-gray-500">Rating Kinerja</th>
                    <th class="p-4 text-xs font-bold uppercase text-gray-500 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($suppliers as $s)
                <tr>
                    <td class="p-4">
                        <div class="font-bold text-sm">{{ $s->name }}</div>
                        <div class="text-xs text-gray-500">{{ $s->address }}</div>
                    </td>
                    <td class="p-4 text-sm">{{ $s->phone_number }}</td>
                    <td class="p-4 text-sm font-bold">{{ $s->rating }}</td>
                    <td class="p-4 text-right">
                        <button class="text-primary text-sm font-bold">Detail</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
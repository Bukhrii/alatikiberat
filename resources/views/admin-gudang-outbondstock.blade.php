@extends('layouts.app')

{{-- Bagian Title Browser --}}
@section('title', 'Outbound Stok')

{{-- Bagian Header Halaman --}}
@section('header-title', 'Manajemen Outbound Stok')
@section('header-subtitle', 'Pantau pengeluaran barang dari gudang.')

{{-- Bagian Konten Utama --}}
@section('content')
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Card 1: Nilai Inventaris --}}
        <div class="flex flex-col gap-2 rounded-xl p-5 bg-white dark:bg-[#1a202c] border border-[#dbe0e6] dark:border-gray-700 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-[#617289] dark:text-gray-400 text-sm font-bold uppercase tracking-wider">Nilai Inventaris</p>
                <span class="material-symbols-outlined text-primary bg-primary/10 p-1.5 rounded-md" style="font-size: 20px;">payments</span>
            </div>
            <div class="flex flex-col gap-1 mt-2">
                <p class="text-[#111418] dark:text-white text-3xl font-black leading-tight tracking-tight">Rp 1.2M</p>
                <div class="flex items-center gap-1">
                    <span class="material-symbols-outlined text-[#07883b] text-sm font-bold">trending_up</span>
                    <p class="text-[#07883b] text-sm font-bold leading-normal">+2.5% vs bulan lalu</p>
                </div>
            </div>
        </div>

        {{-- Card 2: Total SKU --}}
        <div class="flex flex-col gap-2 rounded-xl p-5 bg-white dark:bg-[#1a202c] border border-[#dbe0e6] dark:border-gray-700 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-[#617289] dark:text-gray-400 text-sm font-bold uppercase tracking-wider">Total SKU</p>
                <span class="material-symbols-outlined text-primary bg-primary/10 p-1.5 rounded-md" style="font-size: 20px;">category</span>
            </div>
            <div class="flex flex-col gap-1 mt-2">
                <p class="text-[#111418] dark:text-white text-3xl font-black leading-tight tracking-tight">3,450</p>
                <div class="flex items-center gap-1">
                    <span class="material-symbols-outlined text-[#07883b] text-sm font-bold">add</span>
                    <p class="text-[#07883b] text-sm font-bold leading-normal">12 SKU Baru</p>
                </div>
            </div>
        </div>

        {{-- Card 3: Stok Kritis --}}
        <div class="flex flex-col gap-2 rounded-xl p-5 bg-white dark:bg-[#1a202c] border border-orange-200 dark:border-orange-900 shadow-sm relative overflow-hidden">
            <div class="absolute right-0 top-0 h-full w-1 bg-orange-500"></div>
            <div class="flex items-center justify-between">
                <p class="text-[#617289] dark:text-gray-400 text-sm font-bold uppercase tracking-wider">Stok Kritis</p>
                <span class="material-symbols-outlined text-orange-600 bg-orange-100 p-1.5 rounded-md" style="font-size: 20px;">warning</span>
            </div>
            <div class="flex flex-col gap-1 mt-2">
                <p class="text-[#111418] dark:text-white text-3xl font-black leading-tight tracking-tight">12 Items</p>
                <div class="flex items-center gap-1">
                    <p class="text-orange-600 text-sm font-bold leading-normal">Perlu Restock Segera</p>
                </div>
            </div>
        </div>

        {{-- Card 4: Order Pending --}}
        <div class="flex flex-col gap-2 rounded-xl p-5 bg-white dark:bg-[#1a202c] border border-[#dbe0e6] dark:border-gray-700 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-[#617289] dark:text-gray-400 text-sm font-bold uppercase tracking-wider">Order Pending</p>
                <span class="material-symbols-outlined text-primary bg-primary/10 p-1.5 rounded-md" style="font-size: 20px;">local_shipping</span>
            </div>
            <div class="flex flex-col gap-1 mt-2">
                <p class="text-[#111418] dark:text-white text-3xl font-black leading-tight tracking-tight">5 Orders</p>
                <div class="flex items-center gap-1">
                    <p class="text-[#617289] text-sm font-medium leading-normal">Menunggu pengiriman</p>
                </div>
            </div>
        </div>
    </div>

    <div class="flex flex-col lg:flex-row gap-6">
        {{-- Chart 1: Komposisi Kategori --}}
        <div class="flex-1 flex flex-col gap-4 rounded-xl border border-[#dbe0e6] dark:border-gray-700 bg-white dark:bg-[#1a202c] p-6 shadow-sm">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-[#111418] dark:text-white text-lg font-bold leading-normal">Komposisi Kategori</h3>
                    <p class="text-[#617289] dark:text-gray-400 text-sm font-normal">Breakdown item berdasarkan kategori part.</p>
                </div>
                <button class="text-[#617289] hover:text-primary transition-colors">
                    <span class="material-symbols-outlined">more_horiz</span>
                </button>
            </div>
            <div class="flex items-center gap-8 py-4">
                <div class="relative size-40 rounded-full flex items-center justify-center bg-[conic-gradient(at_center,_var(--tw-gradient-stops))] from-primary via-blue-400 to-blue-200" style="background: conic-gradient(#136dec 0% 40%, #60a5fa 40% 70%, #93c5fd 70% 85%, #e2e8f0 85% 100%);">
                    <div class="size-24 bg-white dark:bg-[#1a202c] rounded-full flex flex-col items-center justify-center z-10">
                        <span class="text-xs text-[#617289] font-bold">Total</span>
                        <span class="text-lg font-black text-[#111418] dark:text-white">100%</span>
                    </div>
                </div>
                <div class="flex flex-col gap-3 flex-1">
                    <div class="flex items-center gap-2">
                        <div class="size-3 rounded-full bg-[#136dec]"></div>
                        <span class="text-sm font-medium text-[#111418] dark:text-white flex-1">Engine Parts</span>
                        <span class="text-sm font-bold text-[#617289]">40%</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="size-3 rounded-full bg-blue-400"></div>
                        <span class="text-sm font-medium text-[#111418] dark:text-white flex-1">Body Parts</span>
                        <span class="text-sm font-bold text-[#617289]">30%</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="size-3 rounded-full bg-blue-300"></div>
                        <span class="text-sm font-medium text-[#111418] dark:text-white flex-1">Electrical</span>
                        <span class="text-sm font-bold text-[#617289]">15%</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="size-3 rounded-full bg-slate-200"></div>
                        <span class="text-sm font-medium text-[#111418] dark:text-white flex-1">Others</span>
                        <span class="text-sm font-bold text-[#617289]">15%</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Chart 2: Pergerakan Stok 30 Hari --}}
        <div class="flex-[1.5] flex flex-col gap-4 rounded-xl border border-[#dbe0e6] dark:border-gray-700 bg-white dark:bg-[#1a202c] p-6 shadow-sm">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-[#111418] dark:text-white text-lg font-bold leading-normal">Pergerakan Stok 30 Hari</h3>
                    <div class="flex items-baseline gap-2 mt-1">
                        <span class="text-2xl font-black text-[#111418] dark:text-white">Rp 1.2M</span>
                        <span class="text-sm font-medium text-[#07883b] bg-green-100 px-2 py-0.5 rounded-full">+5.2%</span>
                    </div>
                </div>
                <div class="flex gap-2">
                    <select class="bg-[#f0f2f4] dark:bg-gray-800 border-none rounded-lg text-sm text-[#111418] dark:text-white font-medium py-1 px-3">
                        <option>Last 30 Days</option>
                        <option>This Year</option>
                    </select>
                </div>
            </div>
            <div class="flex-1 min-h-[180px] w-full mt-4 flex items-end justify-between gap-2 px-2 pb-2 border-b border-[#f0f2f4] dark:border-gray-700 relative">
                 <svg class="absolute inset-0 w-full h-full z-10" preserveAspectRatio="none" viewBox="0 0 100 100">
                    <defs>
                        <linearGradient id="chartGradient" x1="0%" y1="0%" x2="0%" y2="100%">
                            <stop offset="0%" style="stop-color:#136dec; stop-opacity:0.2"></stop>
                            <stop offset="100%" style="stop-color:#136dec; stop-opacity:0"></stop>
                        </linearGradient>
                    </defs>
                    <path d="M0,80 Q10,75 20,60 T40,50 T60,40 T80,30 T100,20 V100 H0 Z" fill="url(#chartGradient)"></path>
                    <path d="M0,80 Q10,75 20,60 T40,50 T60,40 T80,30 T100,20" fill="none" stroke="#136dec" stroke-linecap="round" stroke-width="2"></path>
                </svg>
            </div>
             <div class="flex justify-between text-[#617289] text-xs font-bold px-2">
                <span>Week 1</span>
                <span>Week 2</span>
                <span>Week 3</span>
                <span>Week 4</span>
            </div>
        </div>
    </div>

    <div class="flex flex-col rounded-xl border border-[#dbe0e6] dark:border-gray-700 bg-white dark:bg-[#1a202c] shadow-sm overflow-hidden">
        <div class="flex flex-wrap items-center justify-between gap-4 p-6 border-b border-[#dbe0e6] dark:border-gray-700">
            <div class="flex flex-col gap-1">
                <h3 class="text-[#111418] dark:text-white text-lg font-bold leading-normal">Low Stock Alerts</h3>
                <p class="text-[#617289] dark:text-gray-400 text-sm font-normal">Item dibawah level minimum stok yang membutuhkan reorder.</p>
            </div>
            <button class="flex items-center justify-center gap-2 rounded-lg bg-primary hover:bg-blue-700 text-white font-bold h-10 px-4 transition-colors">
                <span class="material-symbols-outlined" style="font-size: 20px;">add_shopping_cart</span>
                <span class="text-sm">Buat Pesanan Massal</span>
            </button>
        </div>
        
        <div class="w-full overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#f0f2f4] dark:bg-gray-800">
                        <th class="p-4 text-xs font-bold uppercase tracking-wide text-[#617289] w-[40%]">Nama Part & SKU</th>
                        <th class="p-4 text-xs font-bold uppercase tracking-wide text-[#617289] w-[20%]">Sisa Stok</th>
                        <th class="p-4 text-xs font-bold uppercase tracking-wide text-[#617289] w-[20%]">Supplier</th>
                        <th class="p-4 text-xs font-bold uppercase tracking-wide text-[#617289] w-[20%] text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#dbe0e6] dark:divide-gray-700">
                    {{-- Row 1 --}}
                    <tr class="group hover:bg-[#f8f9fa] dark:hover:bg-gray-800 transition-colors">
                        <td class="p-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-lg bg-gray-100 bg-cover bg-center border border-gray-200" style="background-image: url('https://placehold.co/100');"></div>
                                <div class="flex flex-col">
                                    <span class="text-[#111418] dark:text-white font-bold text-sm">Brake Pad Ceramic X200</span>
                                    <span class="text-[#617289] text-xs font-mono">SKU: BP-CER-200-F</span>
                                </div>
                            </div>
                        </td>
                        <td class="p-4">
                            <div class="flex flex-col gap-1.5 max-w-[140px]">
                                <div class="flex justify-between text-xs">
                                    <span class="font-bold text-red-600">5 Pcs</span>
                                    <span class="text-[#617289]">Min: 20</span>
                                </div>
                                <div class="h-1.5 w-full bg-gray-200 rounded-full overflow-hidden">
                                    <div class="h-full bg-red-500 w-[25%] rounded-full"></div>
                                </div>
                            </div>
                        </td>
                        <td class="p-4">
                            <span class="text-[#111418] dark:text-white text-sm font-medium">AutoParts Indo</span>
                        </td>
                        <td class="p-4 text-right">
                             <button class="text-primary hover:text-blue-700 font-bold text-sm bg-primary/10 hover:bg-primary/20 px-3 py-1.5 rounded-lg transition-colors">Reorder</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
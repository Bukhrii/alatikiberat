@extends('layouts.app')

@section('title', 'Dashboard Pembelian untuk Manajer')
@section('header-title', 'Dashboard Pembelian untuk Manajer')
@section('header-subtitle', 'Overview ketersediaan stok dan nilai inventaris.')

@section('content')
    {{-- 1. Baris Statistik Utama (Semua Bisa Diklik) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Nilai Inventaris --}}
        <div class="flex flex-col gap-2 rounded-xl p-5 bg-white border border-[#dbe0e6] shadow-sm">
            <p class="text-[#617289] text-sm font-bold uppercase tracking-wider">Nilai Inventaris</p>
            <p class="text-[#111418] text-3xl font-black">Rp {{ number_format($inventoryValue / 1000000, 1) }}M</p>
            <div class="flex items-center gap-1">
                <span class="material-symbols-outlined text-[#07883b] text-sm font-bold"></span>
                <p class="text-[#07883b] text-sm font-bold">Nilai Total Aset</p>
            </div>
        </div>
        
        {{-- Stok Kritis --}}
        <div onclick="toggleModal('modalCritical')" class="flex flex-col gap-2 rounded-xl p-5 bg-white border border-orange-200 shadow-sm cursor-pointer hover:bg-orange-50 transition relative overflow-hidden">
            <div class="absolute right-0 top-0 h-full w-1 bg-orange-500"></div>
            <p class="text-orange-600 text-sm font-bold uppercase tracking-wider">Stok Kritis</p>
            <p class="text-[#111418] text-3xl font-black">{{ $criticalCount }} Items</p>
            <p class="text-orange-600 text-xs font-bold italic">Klik untuk detail →</p>
        </div>

        {{-- Total SKU --}}
        <div onclick="toggleModal('modalSKU')" class="flex flex-col gap-2 rounded-xl p-5 bg-white border border-[#dbe0e6] shadow-sm cursor-pointer hover:bg-gray-50 transition">
            <p class="text-[#617289] text-sm font-bold uppercase tracking-wider">Total SKU</p>
            <p class="text-[#111418] text-3xl font-black">{{ $totalSKU }} Jenis</p>
            <p class="text-blue-600 text-xs font-bold italic">Daftar Barang →</p>
        </div>

        {{-- Pending Order --}}
        <div onclick="toggleModal('modalPending')" class="flex flex-col gap-2 rounded-xl p-5 bg-white border border-[#dbe0e6] shadow-sm cursor-pointer hover:bg-gray-50 transition">
            <p class="text-purple-600 text-sm font-bold uppercase tracking-wider">Pending Order</p>
            <p class="text-[#111418] text-3xl font-black">{{ $pendingOrders }} Trx</p>
            <p class="text-purple-600 text-xs font-bold italic">Klik detail →</p>
        </div>
    </div>

    {{-- 2. Baris Grafik Visualisasi --}}
    <div class="flex flex-col lg:flex-row gap-6 mt-6">
        {{-- Pie Chart Kategori --}}
        <div class="flex-1 bg-white p-6 rounded-xl border border-[#dbe0e6] shadow-sm">
            <h3 class="font-bold text-[#111418] mb-4">Komposisi Kategori</h3>
            <div class="flex items-center gap-6">
                @php $colors = ['#136dec', '#60a5fa', '#93c5fd', '#34d399', '#fbbf24']; $cumulative = 0; @endphp
                <div class="relative size-32 rounded-full" 
                     style="background: conic-gradient(@foreach($categoryStats as $idx => $c) {{ $colors[$idx % 5] }} {{ $cumulative }}% {{ $cumulative + $c['percentage'] }}%@if(!$loop->last),@endif @php $cumulative += $c['percentage']; @endphp @endforeach);">
                     <div class="absolute inset-4 bg-white rounded-full flex items-center justify-center text-[10px] font-bold text-gray-400">TOTAL</div>
                </div>
                <div class="flex flex-col gap-2 flex-1">
                    @foreach($categoryStats as $idx => $c)
                        <div class="flex justify-between text-xs font-bold">
                            <span class="flex items-center gap-1">
                                <span class="size-2 rounded-full" style="background-color: {{ $colors[$idx % 5] }}"></span>
                                <span class="text-gray-700">{{ $c['name'] }}</span>
                            </span>
                            <span class="text-gray-400">{{ $c['percentage'] }}%</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Line Chart Inbound 30 Hari --}}
        <div class="flex-[1.5] bg-white p-6 rounded-xl border border-[#dbe0e6] shadow-sm">
            <h3 class="font-bold text-[#111418] mb-4">Aktivitas Inbound 30 Hari Terakhir</h3>
            <div class="h-40 w-full relative">
                @php
                    $points = ""; $max = max($movementData->toArray()) ?: 1; $count = 0;
                    foreach($movementData as $val) {
                        $x = ($count / 29) * 100; $y = 100 - (($val / $max) * 80);
                        $points .= "$x,$y "; $count++;
                    }
                @endphp
                <svg class="w-full h-full" preserveAspectRatio="none" viewBox="0 0 100 100">
                    <defs>
                        <linearGradient id="chartGradient" x1="0%" x2="0%" y1="0%" y2="100%">
                            <stop offset="0%" style="stop-color:#136dec; stop-opacity:0.2"></stop>
                            <stop offset="100%" style="stop-color:#136dec; stop-opacity:0"></stop>
                        </linearGradient>
                    </defs>
                    <path d="M 0,100 {{ $points }} L 100,100 Z" fill="url(#chartGradient)" />
                    <polyline points="{{ $points }}" fill="none" stroke="#136dec" stroke-width="2" vector-effect="non-scaling-stroke"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- 3. Tabel Low Stock Alerts (DIKEMBALIKAN) --}}
    <div class="flex flex-col mt-6 rounded-xl border border-[#dbe0e6] bg-white shadow-sm overflow-hidden">
        <div class="flex flex-wrap items-center justify-between gap-4 p-6 border-b border-[#dbe0e6]">
            <div class="flex flex-col gap-1">
                <h3 class="text-[#111418] text-lg font-bold">Peringatan Stok Rendah</h3>
                <p class="text-[#617289] text-sm">Item dibawah level minimum stok yang membutuhkan reorder.</p>
            </div>
            <form action="{{ route('manager.mass-reorder') }}" method="POST">
                @csrf
                <button type="submit" class="flex items-center justify-center gap-2 rounded-lg bg-primary hover:bg-blue-700 text-white font-bold h-10 px-4 transition">
                    <span class="material-symbols-outlined" style="font-size: 20px;">add_shopping_cart</span>
                    <span class="text-sm">Buat Order Massal</span>
                </button>
            </form>
        </div>
        
        <div class="w-full overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#f8f9fa] text-[#617289] text-xs font-bold uppercase tracking-wider">
                        <th class="p-4 w-[40%]">Nama Part & SKU</th>
                        <th class="p-4 w-[20%]">Sisa Stok</th>
                        <th class="p-4 w-[20%]">Supplier</th>
                        <th class="p-4 w-[20%] text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#dbe0e6]">
                    @forelse($lowStockItems as $low)
                    <tr class="hover:bg-[#fcfcfc] transition">
                        <td class="p-4">
                            <div class="flex flex-col">
                                <span class="text-[#111418] font-bold text-sm">{{ $low->sparePart->name }}</span>
                                <span class="text-[#617289] text-xs font-mono">SKU: {{ $low->sparePart->part_number }}</span>
                            </div>
                        </td>
                        <td class="p-4">
                            <div class="flex flex-col gap-1.5 max-w-[140px]">
                                <div class="flex justify-between text-xs">
                                    <span class="font-bold text-red-600">{{ $low->stock }} Pcs</span>
                                    <span class="text-[#617289]">Min: {{ $low->min_stock }}</span>
                                </div>
                                <div class="h-1.5 w-full bg-gray-200 rounded-full overflow-hidden">
                                    <div class="h-full bg-red-500 rounded-full" 
                                         style="width: {{ $low->min_stock > 0 ? ($low->stock / $low->min_stock) * 100 : 0 }}%">
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="p-4 text-sm font-medium">
                            {{ $low->last_supplier_name }}
                        </td>
                        <td class="p-4 text-right">
                            <form action="{{ route('manager.reorder', $low->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="text-primary hover:text-blue-700 font-bold text-sm bg-primary/10 px-3 py-1.5 rounded-lg transition-all">
                                    Reorder
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="p-10 text-center text-gray-400 italic">Semua stok dalam kondisi aman.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- 4. SELURUH MODAL DETAIL --}}
    {{-- Modal SKU --}}
    <div id="modalSKU" class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl w-full max-w-2xl max-h-[80vh] overflow-y-auto p-6 shadow-2xl">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-lg">Daftar SKU Terdaftar</h3>
                <button onclick="toggleModal('modalSKU')" class="material-symbols-outlined text-gray-400">close</button>
            </div>
            <table class="w-full text-sm">
                <tr class="bg-gray-50 font-bold uppercase text-[10px] text-gray-500">
                    <th class="p-3">Nama</th><th class="p-3">SKU</th><th class="p-3 text-right">Stok Fisik</th>
                </tr>
                @foreach($skuList as $s)
                <tr class="border-b">
                    <td class="p-3">{{ $s->name }}</td><td class="p-3 text-gray-500">{{ $s->part_number }}</td>
                    <td class="p-3 text-right font-bold">{{ $s->inventory->stock ?? 0 }}</td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>

    {{-- Modal Pending Order --}}
    <div id="modalPending" class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl w-full max-w-2xl p-6 shadow-2xl">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-lg">Detail Transaksi Pending</h3>
                <button onclick="toggleModal('modalPending')" class="material-symbols-outlined text-gray-400">close</button>
            </div>
            <table class="w-full text-sm text-left">
                <tr class="bg-gray-50 font-bold text-gray-500 uppercase text-[10px]">
                    <th class="p-3">Barang</th><th class="p-3 text-center">Qty</th><th class="p-3">Supplier</th><th class="p-3">Status</th>
                </tr>
                @foreach($pendingOrderList as $p)
                <tr class="border-b">
                    <td class="p-3">{{ $p->sparePart->name }}</td>
                    <td class="p-3 text-center font-bold">{{ $p->quantity }}</td>
                    <td class="p-3">{{ $p->supplier->name ?? 'N/A' }}</td>
                    <td class="p-3 text-purple-600 font-bold uppercase text-[10px]">{{ $p->status }}</td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>

    {{-- Modal Critical --}}
    <div id="modalCritical" class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl w-full max-w-lg p-6 shadow-2xl">
            <div class="flex justify-between items-center mb-4 text-orange-600">
                <h3 class="font-bold text-lg">Peringatan Stok Kritis</h3>
                <button onclick="toggleModal('modalCritical')" class="material-symbols-outlined text-gray-400">close</button>
            </div>
            <div class="space-y-2 max-h-[60vh] overflow-y-auto pr-2">
                @foreach($lowStockItems as $l)
                <div class="flex justify-between items-center p-4 bg-orange-50 border border-orange-100 rounded-lg">
                    <div class="flex flex-col">
                        <span class="font-bold text-sm text-gray-800">{{ $l->sparePart->name }}</span>
                        <span class="text-xs text-gray-500">SKU: {{ $l->sparePart->part_number }}</span>
                    </div>
                    <span class="text-red-600 font-bold">{{ $l->stock }} / {{ $l->min_stock }} Pcs</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <script>
        function toggleModal(id) {
            const modal = document.getElementById(id);
            modal.classList.toggle('hidden');
        }
    </script>
@endsection
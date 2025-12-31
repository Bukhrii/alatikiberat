@extends('layouts.app')

@section('title', 'Lokasi Stok')
@section('header-title', 'Manajemen Lokasi Stok')
@section('header-subtitle', 'Pantau posisi barang di gudang.')

@section('content')
    {{-- Notifikasi Sukses --}}
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 shadow-sm">{{ session('success') }}</div>
    @endif

    {{-- 1. Kartu Kapasitas Dinamis --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        @foreach(['A' => 'red', 'B' => 'blue', 'C' => 'green'] as $rack => $color)
            <div class="p-5 bg-white dark:bg-[#1a202c] rounded-xl border border-[#dbe0e6] dark:border-gray-700 shadow-sm">
                <div class="flex justify-between items-start">
                    <p class="text-sm font-bold text-gray-500 uppercase">Kapasitas Rak {{ $rack }}</p>
                    {{-- Menampilkan angka riil vs kapasitas maksimal --}}
                    <span class="text-xs font-bold bg-gray-100 dark:bg-gray-800 px-2 py-1 rounded text-gray-600 dark:text-gray-400">
                        {{ $counts[$rack] }}/{{ $maxCapacity }}
                    </span>
                </div>
        
                <div class="flex items-baseline gap-2 mt-2">
                    <p class="text-3xl font-black">{{ round($capacity[$rack]) }}%</p>
                    <p class="text-xs text-gray-400 font-medium">Terisi</p>
                </div>

                <div class="w-full bg-gray-200 dark:bg-gray-700 h-2 rounded-full mt-3 overflow-hidden">
                    <div class="bg-{{ $color }}-500 h-2 rounded-full transition-all duration-700" 
                        style="width: {{ $capacity[$rack] }}%"></div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- 2. Daftar Lokasi Item --}}
    <div class="bg-white dark:bg-[#1a202c] rounded-xl border border-[#dbe0e6] dark:border-gray-700 shadow-sm p-6">
        <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
            <h3 class="text-lg font-bold">Daftar Lokasi Item</h3>
            {{-- Form Pencarian SKU/Nama --}}
            <form action="{{ url()->current() }}" method="GET" class="relative">
                <span class="material-symbols-outlined absolute left-3 top-2.5 text-gray-400" style="font-size: 20px;">search</span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari SKU atau nama part..." class="pl-10 pr-4 py-2 border border-[#dbe0e6] rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none dark:bg-gray-800 dark:border-gray-600">
            </form>
        </div>

        <div class="w-full overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-700">
                        <th class="pb-3 text-sm font-bold text-[#617289]">Zona</th>
                        <th class="pb-3 text-sm font-bold text-[#617289]">Rak</th>
                        <th class="pb-3 text-sm font-bold text-[#617289]">Item</th>
                        <th class="pb-3 text-sm font-bold text-[#617289] text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($inventories as $inv)
                    <tr class="group hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                        <td class="py-3 text-sm font-bold">
                            {{-- Logika Penentuan Zona berdasarkan huruf awal Rak --}}
                            Zona {{ strtoupper(substr($inv->location_rack, 0, 1)) }}
                        </td>
                        <td class="py-3 text-sm">
                            <span class="px-2 py-1 bg-gray-100 dark:bg-gray-700 rounded font-mono font-bold">{{ $inv->location_rack }}</span>
                        </td>
                        <td class="py-3 text-sm">
                            <div class="font-bold">{{ $inv->sparePart->name }}</div>
                            <div class="text-xs text-gray-400">{{ $inv->sparePart->part_number }}</div>
                        </td>
                        <td class="py-3 text-right">
                            <button onclick="openMoveModal({{ $inv->id }}, '{{ $inv->sparePart->name }}', '{{ $inv->location_rack }}')" 
                                    class="text-primary font-bold text-sm hover:underline">
                                Pindahkan
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="py-10 text-center text-gray-400 italic">Data lokasi tidak ditemukan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- 3. Modal Pindahkan Barang --}}
    <div id="modalMove" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-[#1a202c] rounded-xl shadow-xl w-full max-w-sm overflow-hidden animate-in fade-in duration-200">
            <div class="p-6 border-b flex justify-between items-center">
                <h3 class="text-lg font-bold dark:text-white">Pindahkan Barang</h3>
                <button onclick="toggleModal('modalMove')" class="text-gray-400 hover:text-red-500">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <form id="formMove" method="POST" class="p-6 space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Nama Barang</label>
                    <p id="move_item_name" class="font-bold text-sm dark:text-white"></p>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Lokasi Rak Baru</label>
                    <input type="text" name="new_rack" id="new_rack_input" 
                        class="w-full p-2 border rounded-lg dark:bg-gray-800 dark:border-gray-600 dark:text-white" 
                        placeholder="Contoh: A-01" 
                        pattern="^[A-C].*" 
                        title="Hanya diperbolehkan pindah ke Rak A, B, atau C" 
                        required>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="toggleModal('modalMove')" class="flex-1 px-4 py-2 border rounded-lg font-bold dark:text-white">Batal</button>
                    <button type="submit" class="flex-1 bg-primary text-white px-4 py-2 rounded-lg font-bold hover:bg-blue-700 transition">Update</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleModal(id) { document.getElementById(id).classList.toggle('hidden'); }

        function openMoveModal(id, name, currentRack) {
            document.getElementById('move_item_name').innerText = name;
            document.getElementById('new_rack_input').value = currentRack;
            // Set action URL secara dinamis
            document.getElementById('formMove').action = `/admin/locations/move/${id}`;
            toggleModal('modalMove');
        }
    </script>
@endsection
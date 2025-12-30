@extends('layouts.app')

@section('title', 'Inbound Stok')
@section('header-title', 'Manajemen Inbound Stok')
@section('header-subtitle', 'Pantau masuknya barang ke gudang.')

@section('content')
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-r-lg shadow-sm flex items-center gap-3">
            <span class="material-symbols-outlined">check_circle</span>
            <p class="font-bold">{{ session('success') }}</p>
        </div>
    @endif

    {{-- Card 1: Total Inbound (Existing) --}}
    <div class="flex flex-col gap-2 rounded-xl p-5 bg-white dark:bg-[#1a202c] border border-[#dbe0e6] dark:border-gray-700 shadow-sm">
        <div class="flex items-center justify-between">
            <p class="text-[#617289] dark:text-gray-400 text-sm font-bold uppercase tracking-wider">Total Inbound</p>
            <span class="material-symbols-outlined text-primary bg-primary/10 p-1.5 rounded-md" style="font-size: 20px;">input</span>
        </div>
        <div class="flex flex-col gap-1 mt-2">
            <p class="text-[#111418] dark:text-white text-3xl font-black leading-tight tracking-tight">
                {{ number_format($transactions->where('status', 'Selesai')->sum('quantity')) }}
            </p>
            <div class="flex items-center gap-1">
                <span class="material-symbols-outlined text-[#07883b] text-sm font-bold">check_circle</span>
                <p class="text-[#07883b] text-sm font-bold leading-normal">Total Jumlah Aset di Gudang</p>
            </div>
        </div>
    </div>

    {{-- Card 2: Nilai Inventaris (Baru) --}}
    <div class="flex flex-col gap-2 rounded-xl p-5 bg-white dark:bg-[#1a202c] border border-[#dbe0e6] dark:border-gray-700 shadow-sm">
        <div class="flex items-center justify-between">
            <p class="text-[#617289] dark:text-gray-400 text-sm font-bold uppercase tracking-wider">Nilai Inventaris</p>
            <span class="material-symbols-outlined text-primary bg-primary/10 p-1.5 rounded-md" style="font-size: 20px;">payments</span>
        </div>
        <div class="flex flex-col gap-1 mt-2">
            <p class="text-[#111418] dark:text-white text-3xl font-black leading-tight tracking-tight">
                Rp {{ number_format($inventoryValue / 1000000, 1) }}M
            </p>
            <div class="flex items-center gap-1">
                <span class="material-symbols-outlined text-primary text-sm font-bold">inventory_2</span>
                <p class="text-[#617289] dark:text-gray-400 text-sm font-medium leading-normal">Total Nilai Aset Gudang</p>
            </div>
        </div>
    </div>

    <div class="flex flex-col rounded-xl border border-[#dbe0e6] dark:border-gray-700 bg-white dark:bg-[#1a202c] p-6 shadow-sm mb-6">
        <h3 class="text-[#111418] dark:text-white text-lg font-bold mb-4">Input Barang Masuk Baru</h3>
        <form action="{{ route('admin.inbound.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <div>
                    <label class="block text-sm font-bold mb-1 text-[#111418] dark:text-white">Suku Cadang</label>
                    <select name="spare_part_id" class="w-full p-2 border border-[#dbe0e6] dark:border-gray-700 dark:bg-gray-800 dark:text-white rounded-lg outline-none focus:ring-2 focus:ring-primary h-[42px]" required>
                        <option value="">-- Pilih Barang --</option>
                        @foreach($parts as $p)
                            <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->part_number }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold mb-1 text-[#111418] dark:text-white">Supplier</label>
                    <select name="supplier_id" class="w-full p-2 border border-[#dbe0e6] dark:border-gray-700 dark:bg-gray-800 dark:text-white rounded-lg outline-none focus:ring-2 focus:ring-primary h-[42px]" required>
                        <option value="">-- Pilih Supplier --</option>
                        @foreach($suppliers as $s)
                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold mb-1 text-[#111418] dark:text-white">Jumlah (Qty)</label>
                    <input type="number" name="quantity" class="w-full p-2 border border-[#dbe0e6] dark:border-gray-700 dark:bg-gray-800 dark:text-white rounded-lg outline-none focus:ring-2 focus:ring-primary h-[42px]" min="1" placeholder="0" required>
                </div>

                <div>
                    <label class="block text-sm font-bold mb-1 text-[#111418] dark:text-white">Status Awal</label>
                    <select name="status" class="w-full p-2 border border-[#dbe0e6] dark:border-gray-700 dark:bg-gray-800 dark:text-white rounded-lg outline-none focus:ring-2 focus:ring-primary h-[42px]" required>
                        <option value="Selesai" class="text-green-600">Selesai (Tambah Stok)</option>
                        <option value="Pending" class="text-yellow-600">Pending</option>
                        <option value="Batal" class="text-red-600">Batal</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold mb-1 text-[#111418] dark:text-white">No. Surat Jalan</label>
                    <input type="text" name="reference" class="w-full p-2 border border-[#dbe0e6] dark:border-gray-700 dark:bg-gray-800 dark:text-white rounded-lg outline-none focus:ring-2 focus:ring-primary h-[42px]" placeholder="SJ-2025-XXX" required>
                </div>
            </div>

            <div class="mt-4 flex justify-end">
                <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg font-bold hover:bg-blue-700 transition flex items-center gap-2">
                    <span class="material-symbols-outlined">save</span>
                    Simpan Transaksi
                </button>
            </div>
        </form>
    </div>

    <div class="flex flex-col rounded-xl border border-[#dbe0e6] dark:border-gray-700 bg-white dark:bg-[#1a202c] shadow-sm overflow-hidden">
        <div class="p-6 border-b border-[#dbe0e6] dark:border-gray-700">
             <h3 class="text-lg font-bold text-[#111418] dark:text-white">Kelola Riwayat & Status Barang</h3>
        </div>
        <div class="w-full overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#f0f2f4] dark:bg-gray-800">
                        {{-- Tambahkan Kolom Tanggal --}}
                        <th class="p-4 text-xs font-bold uppercase text-[#617289]">Tanggal</th>
                        <th class="p-4 text-xs font-bold uppercase text-[#617289]">Barang & Ref</th>
                        <th class="p-4 text-xs font-bold uppercase text-[#617289]">Jumlah</th>
                        <th class="p-4 text-xs font-bold uppercase text-[#617289]">Status Saat Ini</th>
                        <th class="p-4 text-xs font-bold uppercase text-[#617289] text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#dbe0e6] dark:divide-gray-700">
                    @forelse($transactions as $trx)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                            {{-- Data Tanggal --}}
                            <td class="p-4">
                                <div class="text-sm font-bold text-[#111418] dark:text-white">
                                    {{ $trx->created_at->format('d M Y') }}
                                </div>
                                <div class="text-xs text-[#637588]">
                                    {{ $trx->created_at->format('H:i') }} WIB
                                </div>
                            </td>
                            <td class="p-4">
                                <div class="font-bold text-sm text-[#111418] dark:text-white">{{ $trx->sparePart->name }}</div>
                                <div class="text-xs text-[#637588]">Ref: {{ $trx->reference }}</div>
                            </td>
                            <td class="p-4 text-sm text-[#111418] dark:text-white font-medium">{{ number_format($trx->quantity) }} Unit</td>
                            <td class="p-4 text-sm">
                                @php
                                    $color = $trx->status == 'Selesai' ? 'green' : ($trx->status == 'Pending' ? 'yellow' : 'red');
                                @endphp
                                <span class="bg-{{ $color }}-100 text-{{ $color }}-700 px-3 py-1 rounded-full text-xs font-bold uppercase">
                                    {{ $trx->status }}
                                </span>
                            </td>
                            <td class="p-4 text-right">
                                <form action="{{ route('admin.inbound.updateStatus', $trx->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" onchange="this.form.submit()" class="text-xs p-1 border rounded bg-gray-50 dark:bg-gray-700 dark:text-white cursor-pointer focus:ring-2 focus:ring-primary">
                                        <option value="Selesai" {{ $trx->status == 'Selesai' ? 'selected' : '' }}>Set Selesai</option>
                                        <option value="Pending" {{ $trx->status == 'Pending' ? 'selected' : '' }}>Set Pending</option>
                                        <option value="Batal" {{ $trx->status == 'Batal' ? 'selected' : '' }}>Set Batal</option>
                                    </select>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-10 text-center text-gray-500 italic">Belum ada riwayat stok masuk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
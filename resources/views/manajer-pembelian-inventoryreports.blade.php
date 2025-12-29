@extends('layouts.app')

@section('title', 'Laporan Inventaris')
@section('header-title', 'Laporan Inventaris')
@section('header-subtitle', 'Rekapitulasi data aset dan pergerakan stok.')

@section('content')
    {{-- Form Filter --}}
    <form action="{{ route('manager.reports') }}" method="GET" class="bg-white dark:bg-[#1a202c] p-4 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm flex flex-wrap gap-4 items-end">
        <div>
            <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Periode Awal</label>
            {{-- Tambahkan id="start_date" untuk JavaScript --}}
            <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}" 
                max="{{ request('end_date') }}"
                class="border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary">
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Periode Akhir</label>
            {{-- Tambahkan id="end_date" untuk JavaScript --}}
            <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}" 
                min="{{ request('start_date') }}"
                class="border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary">
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Kategori</label>
            <select name="category" class="border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary min-w-[150px]">
                <option value="Semua Kategori">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>
                        {{ $cat }}
                    </option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="bg-primary text-white px-5 py-2 rounded-lg text-sm font-bold h-10 hover:bg-blue-700 transition-colors">
            Tampilkan Laporan
        </button>
        <a href="{{ route('manager.reports.export', request()->query()) }}" 
        class="bg-green-600 text-white px-5 py-2 rounded-lg text-sm font-bold h-10 hover:bg-green-700 flex items-center gap-2 transition-colors">
            <span class="material-symbols-outlined" style="font-size:18px">download</span> 
            Export Excel
        </a>
    </form>

    {{-- Kartu Laporan --}}
    <div class="bg-white dark:bg-[#1a202c] rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden min-h-[400px] mt-6">
        <div class="p-10 text-center border-b border-gray-100 dark:border-gray-700">
            <h2 class="text-2xl font-bold text-[#111418] dark:text-white">Laporan Valuasi Aset</h2>
            <p class="text-sm text-gray-500 mt-1">
                Periode: {{ request('start_date') ? \Carbon\Carbon::parse(request('start_date'))->format('d M Y') : \Carbon\Carbon::parse(request('start_date'))->format('d M Y') }} 
                - 
                {{ request('end_date') ? \Carbon\Carbon::parse(request('end_date'))->format('d M Y') : \Carbon\Carbon::parse(request('start_date'))->format('d M Y') }}
            </p>
        </div>
        
        <div class="p-6">
            <div class="w-full overflow-x-auto">
                <table class="w-full text-left border-collapse border border-gray-200 dark:border-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th class="p-4 border-b border-gray-200 dark:border-gray-700 text-sm font-bold uppercase text-[#617289]">Kategori</th>
                            <th class="p-4 border-b border-gray-200 dark:border-gray-700 text-sm font-bold uppercase text-[#617289] text-center">Jumlah Item</th>
                            <th class="p-4 border-b border-gray-200 dark:border-gray-700 text-sm font-bold uppercase text-[#617289] text-center">Total Stok</th>
                            <th class="p-4 border-b border-gray-200 dark:border-gray-700 text-sm font-bold uppercase text-[#617289] text-right">Nilai Aset (Rp)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($categoryValuation as $report)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                            <td class="p-4 text-sm text-[#111418] dark:text-white font-medium">{{ $report->category }}</td>
                            <td class="p-4 text-sm text-center">{{ $report->item_count }}</td>
                            <td class="p-4 text-sm text-center font-bold">{{ number_format($report->total_stock) }}</td>
                            <td class="p-4 text-sm text-right font-bold text-primary">
                                Rp {{ number_format($report->total_value, 0, ',', '.') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="p-10 text-center text-gray-500 italic">
                                Tidak ada data aset ditemukan untuk kriteria ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    @if($categoryValuation->count() > 0)
                    <tfoot class="bg-gray-50 dark:bg-gray-800 font-bold">
                        <tr>
                            <td class="p-4 text-sm">TOTAL KESELURUHAN</td>
                            <td class="p-4 text-sm text-center">{{ $categoryValuation->sum('item_count') }}</td>
                            <td class="p-4 text-sm text-center">{{ number_format($categoryValuation->sum('total_stock')) }}</td>
                            <td class="p-4 text-sm text-right text-primary">
                                Rp {{ number_format($categoryValuation->sum('total_value'), 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>

    {{-- Script untuk validasi dinamis --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const startInput = document.getElementById('start_date');
            const endInput = document.getElementById('end_date');

            // Ketika Periode Awal diubah, set batas MINIMAL Periode Akhir
            startInput.addEventListener('change', function() {
                endInput.min = this.value;
            });

            // Ketika Periode Akhir diubah, set batas MAKSIMAL Periode Awal
            endInput.addEventListener('change', function() {
                startInput.max = this.value;
            });
        });
    </script>
@endsection
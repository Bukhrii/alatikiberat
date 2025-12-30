@extends('layouts.app')

@section('title', 'Outbound Stok')
@section('header-title', 'Manajemen Outbound Stok')
@section('header-subtitle', 'Pantau pengeluaran barang dari gudang.')

@section('content')
    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 shadow-sm">{{ session('success') }}</div>
    @endif

    {{-- 1. Baris Statistik Utama (Stat Card Baru) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Card 1: Outbound Selesai (Ganti Nilai Inventaris) --}}
        <div onclick="toggleModal('modalCompletedOutbound')" class="flex flex-col gap-2 rounded-xl p-5 bg-white dark:bg-[#1a202c] border border-[#dbe0e6] dark:border-gray-700 shadow-sm cursor-pointer hover:bg-gray-50 transition">
            <div class="flex items-center justify-between">
                <p class="text-[#617289] dark:text-gray-400 text-sm font-bold uppercase tracking-wider">Outbond Selesai</p>
                <span class="material-symbols-outlined text-green-600 bg-green-100 p-1.5 rounded-md" style="font-size: 20px;">check_circle</span>
            </div>
            <div class="flex flex-col gap-1 mt-2">
                <p class="text-[#111418] dark:text-white text-3xl font-black">{{ number_format($completedCount ?? 0) }}</p>
                <p class="text-[#07883b] text-xs font-bold leading-normal">Berhasil Keluar →</p>
            </div>
        </div>

        {{-- Card 2: Total SKU (Bisa Diklik) --}}
        <div onclick="toggleModal('modalSKUList')" class="flex flex-col gap-2 rounded-xl p-5 bg-white dark:bg-[#1a202c] border border-[#dbe0e6] dark:border-gray-700 shadow-sm cursor-pointer hover:bg-gray-50 transition">
            <div class="flex items-center justify-between">
                <p class="text-[#617289] dark:text-gray-400 text-sm font-bold uppercase tracking-wider">Total SKU</p>
                <span class="material-symbols-outlined text-primary bg-primary/10 p-1.5 rounded-md" style="font-size: 20px;">category</span>
            </div>
            <div class="flex flex-col gap-1 mt-2">
                <p class="text-[#111418] dark:text-white text-3xl font-black">{{ number_format($totalSKU ?? 0) }}</p>
                <p class="text-primary text-xs font-bold leading-normal">+{{ $newSKUCount ?? 0 }} SKU Baru →</p>
            </div>
        </div>

        {{-- Card 3: Outbound Batal (Ganti Stok Kritis) --}}
        <div onclick="toggleModal('modalCancelledOutbound')" class="flex flex-col gap-2 rounded-xl p-5 bg-white dark:bg-[#1a202c] border border-red-200 dark:border-red-900 shadow-sm relative overflow-hidden cursor-pointer hover:bg-red-50 transition">
            <div class="absolute right-0 top-0 h-full w-1 bg-red-500"></div>
            <div class="flex items-center justify-between">
                <p class="text-[#617289] dark:text-gray-400 text-sm font-bold uppercase tracking-wider">Outbond Batal</p>
                <span class="material-symbols-outlined text-red-600 bg-red-100 p-1.5 rounded-md" style="font-size: 20px;">cancel</span>
            </div>
            <div class="flex flex-col gap-1 mt-2">
                <p class="text-[#111418] dark:text-white text-3xl font-black">{{ number_format($cancelledCount ?? 0) }}</p>
                <p class="text-red-600 text-xs font-bold italic">Lihat Transaksi Batal →</p>
            </div>
        </div>

        {{-- Card 4: Outbound Pending (Tetap) --}}
        <div onclick="toggleModal('modalPendingOutbound')" class="flex flex-col gap-2 rounded-xl p-5 bg-white dark:bg-[#1a202c] border border-[#dbe0e6] dark:border-gray-700 shadow-sm cursor-pointer hover:bg-gray-50 transition">
            <div class="flex items-center justify-between">
                <p class="text-[#617289] dark:text-gray-400 text-sm font-bold uppercase tracking-wider">Outbound Pending</p>
                <span class="material-symbols-outlined text-orange-500 bg-orange-100 p-1.5 rounded-md" style="font-size: 20px;">local_shipping</span>
            </div>
            <div class="flex flex-col gap-1 mt-2">
                <p class="text-[#111418] dark:text-white text-3xl font-black">{{ number_format($pendingOutbound ?? 0) }}</p>
                <p class="text-orange-500 text-xs font-bold italic">Cek Pengiriman →</p>
            </div>
        </div>
    </div>

    {{-- 2. Grafik Visualisasi (DIPERBAIKI UKURANNYA) --}}
    <div class="flex flex-col lg:flex-row gap-6 mt-6">
        {{-- Donut Chart Komposisi Kategori (flex-1 agar seimbang) --}}
        <div class="flex-1 flex flex-col gap-4 rounded-xl border border-[#dbe0e6] dark:border-gray-700 bg-white dark:bg-[#1a202c] p-6 shadow-sm h-full justify-between">
            <h3 class="text-[#111418] dark:text-white text-lg font-bold">Komposisi Kategori</h3>
            {{-- ... (Kode internal donut chart sama seperti sebelumnya) ... --}}
            <div class="flex items-center gap-8 py-4">
                @php 
                    $colors = ['#136dec', '#60a5fa', '#93c5fd', '#cbd5e1', '#f8fafc'];
                    $cumulative = 0; $gradientParts = [];
                    foreach(($categoryStats ?? []) as $idx => $cat) {
                        $start = $cumulative; $end = $cumulative + $cat['percentage'];
                        $color = $colors[$idx % count($colors)];
                        $gradientParts[] = "$color $start% $end%";
                        $cumulative += $cat['percentage'];
                    }
                    $gradient = implode(", ", $gradientParts);
                @endphp
                <div class="relative size-40 rounded-full flex items-center justify-center" style="background: conic-gradient({{ $gradient ?: '#f0f2f4 0% 100%' }});">
                    <div class="size-24 bg-white dark:bg-[#1a202c] rounded-full flex flex-col items-center justify-center z-10">
                        <span class="text-xs text-[#617289] font-bold">Total</span>
                        <span class="text-lg font-black text-[#111418] dark:text-white">100%</span>
                    </div>
                </div>
                <div class="flex flex-col gap-3 flex-1">
                    @foreach(($categoryStats ?? []) as $idx => $cat)
                    <div class="flex items-center gap-2">
                        <div class="size-3 rounded-full" style="background-color: {{ $colors[$idx % count($colors)] }}"></div>
                        <span class="text-sm font-medium text-[#111418] dark:text-white flex-1">{{ $cat['name'] }}</span>
                        <span class="text-sm font-bold text-[#617289]">{{ $cat['percentage'] }}%</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Line Chart Volume Outbound (DIPERBAIKI: Ukuran Sedang & Tetap) --}}
        {{-- Menggunakan flex-1 agar lebarnya seimbang dengan donut chart --}}
        <div class="flex-1 flex flex-col gap-4 rounded-xl border border-[#dbe0e6] dark:border-gray-700 bg-white dark:bg-[#1a202c] p-6 shadow-sm h-full justify-between">
            <div class="flex justify-between items-start">
                <h3 class="text-[#111418] dark:text-white text-lg font-bold">Volume Outbound (30 Hari Terakhir)</h3>
                <span class="text-[10px] font-black bg-primary/10 text-primary px-2 py-1 rounded animate-pulse">LIVE</span>
            </div>
            
            {{-- Kontainer Grafik dengan TINGGI TETAP (h-72 = ukurang sedang) --}}
            <div class="w-full h-72 mt-4 relative">
                @php
                    $dataArray = array_values(($movementData ?? collect())->toArray());
                    $dataCount = count($dataArray);
                    $realMax = ($dataCount > 0) ? max($dataArray) : 0;
                    $maxValue = ($realMax > 0) ? $realMax * 1.2 : 10; 

                    $points = "";
                    $pathData = "M 0,100 ";
                    if ($dataCount > 0) {
                        foreach($dataArray as $index => $val) {
                            $x = ($dataCount > 1) ? ($index / ($dataCount - 1)) * 100 : 0;
                            $y = 90 - (($val / $maxValue) * 80); 
                            $points .= "{$x},{$y} ";
                            $pathData .= "L {$x},{$y} ";
                        }
                        $pathData .= "L 100,100 Z";
                    }
                @endphp

                @if($realMax > 0)
                    {{-- SVG mengisi penuh kontainer h-72 --}}
                    <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none" style="overflow: visible;">
                        <defs>
                            <linearGradient id="gradOut" x1="0%" y1="0%" x2="0%" y2="100%">
                                <stop offset="0%" style="stop-color:#136dec; stop-opacity:0.2" />
                                <stop offset="100%" style="stop-color:#136dec; stop-opacity:0" />
                            </linearGradient>
                        </defs>
                        <path d="{{ $pathData }}" fill="url(#gradOut)" />
                        <polyline points="{{ $points }}" fill="none" stroke="#136dec" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" vector-effect="non-scaling-stroke"/>
                         {{-- Titik Poin Terakhir --}}
                        @php 
                            $lastVal = end($dataArray); 
                            $lastY = 90 - (($lastVal / $maxValue) * 80);
                        @endphp
                        <circle cx="100" cy="{{ $lastY }}" r="3" fill="#136dec" stroke="white" stroke-width="1" />
                    </svg>
                @else
                    <div class="absolute inset-0 flex flex-col items-center justify-center bg-gray-50/50 dark:bg-gray-800/10 rounded-lg border-2 border-dashed border-gray-200 dark:border-gray-700">
                        <p class="text-gray-400 text-xs font-bold uppercase tracking-widest">Belum Ada Data Transaksi</p>
                    </div>
                @endif
            </div>
            <div class="flex justify-between text-[#617289] text-[10px] font-bold px-1 border-t pt-4 dark:border-gray-700 mt-auto">
                <span>30 Hari Lalu</span>
                <span>15 Hari Lalu</span>
                <span class="text-primary">Hari Ini</span>
            </div>
        </div>
    </div>

    {{-- 3. Tabel Riwayat Outbound (Tetap di bawah, ukuran penuh) --}}
    <div class="flex flex-col mt-6 rounded-xl border border-[#dbe0e6] dark:border-gray-700 bg-white dark:bg-[#1a202c] shadow-sm overflow-hidden">
        <div class="flex flex-wrap items-center justify-between gap-4 p-6 border-b dark:border-gray-700 bg-gray-50/50">
            <h3 class="text-[#111418] dark:text-white text-lg font-bold">Riwayat Transaksi Keluar</h3>
            <button onclick="toggleModal('modalOutbound')" class="bg-primary hover:bg-blue-700 text-white font-bold h-10 px-4 rounded-lg text-sm transition flex items-center gap-2">
                <span class="material-symbols-outlined">add</span> Catat Pengeluaran
            </button>
        </div>
        <div class="w-full overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-[#f0f2f4] dark:bg-gray-800 text-[#617289] text-xs font-bold uppercase tracking-wider">
                        <th class="p-4">Tanggal</th>
                        <th class="p-4">Barang & SKU</th>
                        <th class="p-4 text-center">Qty</th>
                        <th class="p-4">No. Referensi</th>
                        <th class="p-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#dbe0e6] dark:divide-gray-700">
                    @forelse(($transactions ?? []) as $trx)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition text-sm">
                        <td class="p-4 text-gray-500">{{ $trx->created_at->format('d/m/Y H:i') }}</td>
                        <td class="p-4 font-bold dark:text-white">
                            {{ $trx->sparePart->name }} <br>
                            <span class="text-xs font-mono text-gray-400">SKU: {{ $trx->sparePart->part_number }}</span>
                        </td>
                        <td class="p-4 text-center font-bold text-blue-600">{{ $trx->quantity }}</td>
                        <td class="p-4 font-mono">{{ $trx->reference }}</td>
                        <td class="p-4">
                            <form action="{{ route('admin.transactions.updateStatus', $trx->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <select name="status" onchange="this.form.submit()" 
                                    class="text-[10px] font-bold uppercase rounded-full px-2 py-1 border-none focus:ring-0 cursor-pointer
                                    {{ $trx->status == 'Selesai' ? 'bg-green-100 text-green-700' : ($trx->status == 'Pending' ? 'bg-orange-100 text-orange-700' : 'bg-red-100 text-red-700') }}">
                                    <option value="Selesai" {{ $trx->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                    <option value="Pending" {{ $trx->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="Batal" {{ $trx->status == 'Batal' ? 'selected' : '' }}>Batal</option>
                                </select>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="p-10 text-center text-gray-400 italic">Belum ada riwayat pengeluaran.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- MODAL DETAIL --}}
    
    {{-- Modal SKU --}}
    <div id="modalSKUList" class="fixed inset-0 z-[70] hidden bg-black/50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-[#1a202c] rounded-xl w-full max-w-2xl p-6 shadow-2xl overflow-y-auto max-h-[80vh]">
            <div class="flex justify-between items-center mb-4 border-b pb-2">
                <h3 class="font-bold text-lg dark:text-white">Daftar Semua SKU</h3>
                <button onclick="toggleModal('modalSKUList')" class="material-symbols-outlined text-gray-400">close</button>
            </div>
            <table class="w-full text-sm">
                @foreach(\App\Models\SparePart::with('inventory')->get() as $s)
                <tr class="border-b dark:border-gray-700">
                    <td class="p-2 dark:text-white">{{ $s->name }}</td>
                    <td class="p-2 font-mono text-gray-500">{{ $s->part_number }}</td>
                    <td class="p-2 text-right font-bold">{{ $s->inventory->stock ?? 0 }}</td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>

    {{-- Modal Selesai --}}
    <div id="modalCompletedOutbound" class="fixed inset-0 z-[70] hidden bg-black/50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-[#1a202c] rounded-xl w-full max-w-2xl p-6 shadow-2xl overflow-y-auto max-h-[80vh]">
            <div class="flex justify-between items-center mb-4 border-b pb-2 text-green-600 font-bold uppercase">
                <h3>Transaksi Berhasil</h3>
                <button onclick="toggleModal('modalCompletedOutbound')" class="material-symbols-outlined">close</button>
            </div>
            <table class="w-full text-sm">
                @forelse(($completedTransactions ?? []) as $ct)
                <tr class="border-b dark:border-gray-700">
                    <td class="p-2 dark:text-white">{{ $ct->sparePart->name }}</td>
                    <td class="p-2 text-center font-bold">{{ $ct->quantity }}</td>
                    <td class="p-2 font-mono text-xs">{{ $ct->reference }}</td>
                </tr>
                @empty
                <tr><td class="p-4 text-center text-gray-400 italic">Tidak ada data.</td></tr>
                @endforelse
            </table>
        </div>
    </div>

    {{-- Modal Batal --}}
    <div id="modalCancelledOutbound" class="fixed inset-0 z-[70] hidden bg-black/50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-[#1a202c] rounded-xl w-full max-w-2xl p-6 shadow-2xl overflow-y-auto max-h-[80vh]">
            <div class="flex justify-between items-center mb-4 border-b pb-2 text-red-600 font-bold uppercase">
                <h3>Transaksi Dibatalkan</h3>
                <button onclick="toggleModal('modalCancelledOutbound')" class="material-symbols-outlined">close</button>
            </div>
            <table class="w-full text-sm">
                @forelse(($cancelledTransactions ?? []) as $cnt)
                <tr class="border-b dark:border-gray-700">
                    <td class="p-2 dark:text-white">{{ $cnt->sparePart->name }}</td>
                    <td class="p-2 text-center font-bold">{{ $cnt->quantity }}</td>
                    <td class="p-2 font-mono text-xs">{{ $cnt->reference }}</td>
                </tr>
                @empty
                <tr><td class="p-4 text-center text-gray-400 italic">Tidak ada data.</td></tr>
                @endforelse
            </table>
        </div>
    </div>

    {{-- Modal Pending --}}
    <div id="modalPendingOutbound" class="fixed inset-0 z-[70] hidden bg-black/50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-[#1a202c] rounded-xl w-full max-w-2xl p-6 shadow-2xl overflow-y-auto max-h-[80vh]">
            <div class="flex justify-between items-center mb-4 border-b pb-2 text-orange-600 font-bold uppercase">
                <h3>Transaksi Menunggu (Pending)</h3>
                <button onclick="toggleModal('modalPendingOutbound')" class="material-symbols-outlined">close</button>
            </div>
            <table class="w-full text-sm">
                @forelse(($pendingTransactions ?? []) as $pt)
                <tr class="border-b dark:border-gray-700">
                    <td class="p-2 dark:text-white">{{ $pt->sparePart->name }}</td>
                    <td class="p-2 text-center font-bold">{{ $pt->quantity }}</td>
                    <td class="p-2 font-mono text-xs">{{ $pt->reference }}</td>
                </tr>
                @empty
                <tr><td class="p-4 text-center text-gray-400 italic">Tidak ada data.</td></tr>
                @endforelse
            </table>
        </div>
    </div>

    {{-- Modal Form Catat Outbound (DIPERBAIKI) --}}
    <div id="modalOutbound" class="fixed inset-0 z-[60] hidden bg-black/50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-[#1a202c] p-6 rounded-xl w-full max-w-md shadow-2xl">
            <div class="flex justify-between items-center mb-4 border-b pb-2">
                <h3 class="font-bold text-lg dark:text-white text-[#111418]">Catat Barang Keluar</h3>
                <button onclick="toggleModal('modalOutbound')" class="material-symbols-outlined text-gray-400">close</button>
            </div>
            <form action="{{ route('admin.outbound.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nama Barang</label>
                    <select name="spare_part_id" class="w-full border rounded-lg p-2 text-sm dark:bg-gray-800 dark:border-gray-700 dark:text-white" required>
                        @foreach(\App\Models\SparePart::all() as $p)
                            <option value="{{ $p->id }}">{{ $p->name }} (Stok: {{ $p->inventory->stock ?? 0 }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Jumlah</label>
                    <input type="number" name="quantity" class="w-full border rounded-lg p-2 text-sm dark:bg-gray-800 dark:border-gray-700 dark:text-white" required>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Referensi</label>
                    <input type="text" name="reference" class="w-full border rounded-lg p-2 text-sm dark:bg-gray-800 dark:border-gray-700 dark:text-white" required>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Status</label>
                    <select name="status" class="w-full border rounded-lg p-2 text-sm dark:bg-gray-800 dark:border-gray-700 dark:text-white" required>
                        <option value="Selesai">Selesai</option>
                        <option value="Pending">Pending</option>
                        <option value="Batal">Batal</option>
                    </select>
                </div>
                <div class="flex gap-2 pt-4">
                    <button type="button" onclick="toggleModal('modalOutbound')" class="flex-1 bg-gray-100 py-2 rounded-lg text-sm">Batal</button>
                    <button type="submit" class="flex-1 bg-primary text-white py-2 rounded-lg text-sm font-bold">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleModal(id) {
            const modal = document.getElementById(id);
            if (modal) {
                modal.classList.toggle('hidden');
            }
        }
    </script>
@endsection
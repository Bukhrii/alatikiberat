@extends('layouts.app')

@section('title', 'Manajemen Spare Part')
@section('header-title', 'Manajemen Spare Part Gudang')
@section('header-subtitle', 'Kelola inventaris sparepart dan stok di gudang.')

@section('content')
    <div class="flex flex-col rounded-xl border border-[#dbe0e6] dark:border-gray-700 bg-white dark:bg-[#1a202c] shadow-sm overflow-hidden">
        <div class="flex flex-wrap items-center justify-between gap-4 p-6 border-b border-[#dbe0e6] dark:border-gray-700">
            <h3 class="text-[#111418] dark:text-white text-lg font-bold">Master Data Sparepart</h3>
            <button onclick="toggleModal('modalTambah')" class="flex items-center gap-2 bg-primary text-white px-4 py-2 rounded-lg font-bold hover:bg-blue-700 transition">
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
                    @foreach($parts as $item)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                        <td class="p-4">
                            <div class="font-bold text-sm text-[#111418]">{{ $item->name }}</div>
                            <div class="text-xs text-[#637588]">SKU: {{ $item->part_number }}</div>
                        </td>
                        <td class="p-4 text-sm text-[#111418]">{{ $item->category }}</td>
                        <td class="p-4 text-sm font-bold {{ $item->inventory->stock <= $item->inventory->min_stock ? 'text-red-600' : 'text-green-600' }}">
                            {{ $item->inventory->stock }} Pcs
                        </td>
                        <td class="p-4 text-sm text-[#111418]">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                        
                        <td class="p-4 text-right flex justify-end gap-2">
                            <button onclick="openEditModal({{ $item }})" class="text-blue-600 hover:text-blue-800">
                                <span class="material-symbols-outlined">edit</span>
                            </button>
                            <button type="button" onclick="openDeleteModal({{ $item->id }}, '{{ $item->name }}')" class="text-red-600 hover:text-red-800">
                                    <span class="material-symbols-outlined">delete</span>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{-- <div class="p-4 border-t border-gray-200 dark:border-gray-700 text-center">
            <button class="text-sm font-bold text-primary">Muat Lebih Banyak...</button>
        </div> --}}
    </div>
    <div id="modalTambah" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-[500px] shadow-lg rounded-xl bg-white">
            <div class="flex justify-between items-center pb-3 border-b">
                <h3 class="text-xl font-bold text-[#111418]">Tambah Suku Cadang Baru</h3>
                <button onclick="toggleModal('modalTambah')" class="text-black close-modal">&times;</button>
            </div>

            <form action="{{ route('spare-parts.store') }}" method="POST" class="mt-4 space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold mb-1">Part Number (SKU)</label>
                        <input type="text" name="part_number" class="w-full p-2 border rounded-lg" placeholder="Contoh: FLT-001" required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-1">Nama Barang</label>
                        <input type="text" name="name" class="w-full p-2 border rounded-lg" required>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold mb-1">Kategori</label>
                        <select name="category" class="w-full p-2 border rounded-lg" required>
                            <option value="Engine Parts">Engine Parts</option>
                            <option value="Brake System">Brake System</option>
                            <option value="Filters">Filters</option>
                            <option value="Electrical">Electrical</option>
                        </select>
                    </div>
                <div>
                        <label class="block text-sm font-bold mb-1">Brand</label>
                        <input type="text" name="brand" class="w-full p-2 border rounded-lg" required>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold mb-1">Harga Satuan (Rp)</label>
                    <input type="number" name="unit_price" class="w-full p-2 border rounded-lg" required>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold mb-1">Lokasi Rak</label>
                        <input type="text" name="location_rack" 
                            class="w-full p-2 border rounded-lg" 
                            placeholder="Contoh: A-12" 
                            pattern="^[A-C].*" 
                            title="Lokasi rak harus diawali dengan huruf A, B, atau C (Contoh: A-01)" 
                            required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-1">Stok Minimum</label>
                        <input type="number" name="min_stock" class="w-full p-2 border rounded-lg" required>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <button type="button" onclick="toggleModal('modalTambah')" class="px-4 py-2 bg-gray-200 rounded-lg font-bold">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-[#136dec] text-white rounded-lg font-bold">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
    <div id="modalEdit" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-[500px] shadow-lg rounded-xl bg-white">
            <div class="flex justify-between items-center pb-3 border-b">
                <h3 class="text-xl font-bold text-[#111418]">Edit Suku Cadang</h3>
                <button onclick="toggleModal('modalEdit')" class="text-black">&times;</button>
            </div>

            <form id="formEdit" method="POST" class="mt-4 space-y-4">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold mb-1">Nama Barang</label>
                        <input type="text" id="edit_name" name="name" class="w-full p-2 border rounded-lg" required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-1">Brand</label>
                        <input type="text" id="edit_brand" name="brand" class="w-full p-2 border rounded-lg" required>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold mb-1">Kategori</label>
                        <select id="edit_category" name="category" class="w-full p-2 border rounded-lg" required>
                            <option value="Engine Parts">Engine Parts</option>
                            <option value="Brake System">Brake System</option>
                            <option value="Filters">Filters</option>
                            <option value="Electrical">Electrical</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-1">Harga Satuan</label>
                        <input type="number" id="edit_price" name="unit_price" class="w-full p-2 border rounded-lg" required>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold mb-1">Lokasi Rak</label>
                        <input type="text" id="edit_rack" name="location_rack" 
                            class="w-full p-2 border rounded-lg" 
                            pattern="^[A-C].*" 
                            title="Lokasi rak harus diawali dengan huruf A, B, atau C (Contoh: B-05)" 
                            required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-1">Stok Minimum</label>
                        <input type="number" id="edit_min" name="min_stock" class="w-full p-2 border rounded-lg" required>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <button type="button" onclick="toggleModal('modalEdit')" class="px-4 py-2 bg-gray-200 rounded-lg">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-[#136dec] text-white rounded-lg font-bold">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
    <div id="modalDelete" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-[60]">
        <div class="relative top-40 mx-auto p-5 border w-[400px] shadow-lg rounded-xl bg-white text-center">
            <div class="mt-3">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                    <span class="material-symbols-outlined text-red-600">delete_forever</span>
                </div>
                <h3 class="text-lg leading-6 font-bold text-gray-900">Konfirmasi Hapus</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">
                        Apakah Anda yakin ingin menghapus <span id="delete_item_name" class="font-bold text-gray-800"></span>? Tindakan ini tidak dapat dibatalkan.
                    </p>
                </div>
                <div class="flex justify-center gap-3 mt-4">
                    <button onclick="toggleModal('modalDelete')" class="px-4 py-2 bg-gray-200 text-gray-800 text-sm font-bold rounded-lg">
                        Batal
                    </button>
                    <form id="formDelete" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white text-sm font-bold rounded-lg hover:bg-red-700">
                            Hapus Permanen
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        // 1. Satu fungsi toggle untuk semua modal
        function toggleModal(id) {
            const modal = document.getElementById(id);
            if (modal) {
                modal.classList.toggle('hidden');
            }
        }

        // 2. Logika menutup modal jika klik di luar box (berlaku untuk semua)
        window.onclick = function(event) {
            const modals = ['modalTambah', 'modalEdit', 'modalDelete'];
            modals.forEach(id => {
                const modal = document.getElementById(id);
                if (event.target == modal) {
                    modal.classList.add('hidden');
                }
            });
        }

        // 3. Fungsi membuka Modal Delete
        function openDeleteModal(id, name) {
            document.getElementById('delete_item_name').innerText = name;
            // Pastikan URL action sesuai dengan rute destroy Anda
            document.getElementById('formDelete').action = `/admin/spare-parts/${id}`;
            toggleModal('modalDelete');
        }

        // 4. Fungsi membuka Modal Edit
        function openEditModal(item) {
            document.getElementById('edit_name').value = item.name;
            document.getElementById('edit_brand').value = item.brand;
            document.getElementById('edit_price').value = item.unit_price;
            document.getElementById('edit_category').value = item.category;
            
            // Data dari relasi inventory
            if (item.inventory) {
                document.getElementById('edit_rack').value = item.inventory.location_rack;
                document.getElementById('edit_min').value = item.inventory.min_stock;
            }
            
            document.getElementById('formEdit').action = `/admin/spare-parts/${item.id}`;
            toggleModal('modalEdit');
        }
    </script>

@endsection
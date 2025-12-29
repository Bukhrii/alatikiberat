@extends('layouts.app')

@section('title', 'Manajemen Supplier')
@section('header-title', 'Daftar Mitra Supplier')
@section('header-subtitle', 'Kelola data supplier untuk pengadaan barang gudang.')

@section('content')
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 shadow-sm">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 shadow-sm">{{ session('error') }}</div>
    @endif

    <div class="flex flex-col rounded-xl border border-[#dbe0e6] dark:border-gray-700 bg-white dark:bg-[#1a202c] shadow-sm overflow-hidden">
        {{-- Header Card --}}
        <div class="flex flex-wrap items-center justify-between gap-4 p-6 border-b border-[#dbe0e6] dark:border-gray-700">
            <div>
                <h3 class="text-[#111418] dark:text-white text-lg font-bold">Data Supplier</h3>
                <form action="{{ url()->current() }}" method="GET" class="mt-2">
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-2.5 text-gray-400" style="font-size: 20px;">search</span>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama supplier..." class="pl-10 pr-4 py-2 border border-[#dbe0e6] rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none w-64 dark:bg-gray-800 dark:border-gray-600">
                    </div>
                </form>
            </div>
            <button onclick="toggleModal('modalTambah')" class="flex items-center gap-2 bg-primary text-white px-4 py-2 rounded-lg font-bold hover:bg-blue-700 transition">
                <span class="material-symbols-outlined" style="font-size: 20px;">add</span> Tambah Supplier
            </button>
        </div>
        
        <div class="w-full overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#f0f2f4] dark:bg-gray-800">
                        <th class="p-4 text-xs font-bold uppercase text-[#617289]">Nama & Rating</th>
                        <th class="p-4 text-xs font-bold uppercase text-[#617289]">Contact Person</th>
                        <th class="p-4 text-xs font-bold uppercase text-[#617289]">Telepon</th>
                        <th class="p-4 text-xs font-bold uppercase text-[#617289]">Alamat</th>
                        <th class="p-4 text-xs font-bold uppercase text-[#617289] text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#dbe0e6] dark:divide-gray-700">
                    @forelse($suppliers as $s)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                        <td class="p-4">
                            <div class="font-bold text-sm text-[#111418] dark:text-white">{{ $s->name }}</div>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $s->rating == 'A' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">Rating {{ $s->rating }}</span>
                        </td>
                        <td class="p-4 text-sm dark:text-gray-300">{{ $s->contact_person }}</td>
                        <td class="p-4 text-sm dark:text-gray-300">{{ $s->phone_number }}</td>
                        <td class="p-4 text-sm text-gray-500 truncate max-w-xs">{{ $s->address }}</td>
                        <td class="p-4 text-right">
                            <div class="flex justify-end gap-3">
                                <button onclick="openEditModal({{ json_encode($s) }})" class="text-gray-400 hover:text-primary transition"><span class="material-symbols-outlined">edit</span></button>
                                <button onclick="openDeleteModal({{ $s->id }})" class="text-gray-400 hover:text-red-600 transition"><span class="material-symbols-outlined">delete</span></button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="p-10 text-center text-gray-400 italic">Belum ada data supplier.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-200 dark:border-gray-700">
            {{ $suppliers->links() }}
        </div>
    </div>

    {{-- MODAL TAMBAH --}}
    <div id="modalTambah" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-[#1a202c] rounded-xl shadow-xl w-full max-w-lg overflow-hidden animate-in fade-in zoom-in duration-200">
            <div class="p-6 border-b flex justify-between items-center">
                <h3 class="text-lg font-bold dark:text-white">Tambah Supplier Baru</h3>
                <button onclick="toggleModal('modalTambah')" class="text-gray-400 hover:text-red-500"><span class="material-symbols-outlined">close</span></button>
            </div>
            <form action="{{ route('suppliers.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nama Perusahaan</label><input type="text" name="name" class="w-full p-2 border rounded dark:bg-gray-800 dark:border-gray-600 dark:text-white" required></div>
                    <div><label class="block text-xs font-bold text-gray-500 uppercase mb-1">Rating</label>
                        <select name="rating" class="w-full p-2 border rounded dark:bg-gray-800 dark:border-gray-600 dark:text-white" required>
                            <option value="A">A (Sangat Bagus)</option>
                            <option value="B">B (Bagus)</option>
                            <option value="C">C (Cukup)</option>
                            <option value="D">D (Kurang)</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="block text-xs font-bold text-gray-500 uppercase mb-1">Contact Person</label><input type="text" name="contact_person" class="w-full p-2 border rounded dark:bg-gray-800 dark:border-gray-600 dark:text-white" required></div>
                    <div><label class="block text-xs font-bold text-gray-500 uppercase mb-1">No. Telepon</label><input type="text" name="phone_number" class="w-full p-2 border rounded dark:bg-gray-800 dark:border-gray-600 dark:text-white" required></div>
                </div>
                <div><label class="block text-xs font-bold text-gray-500 uppercase mb-1">Alamat Lengkap</label><textarea name="address" class="w-full p-2 border rounded dark:bg-gray-800 dark:border-gray-600 dark:text-white" rows="3" required></textarea></div>
                <div class="flex gap-3 pt-4"><button type="button" onclick="toggleModal('modalTambah')" class="flex-1 px-4 py-2 border rounded-lg font-bold dark:text-white">Batal</button><button type="submit" class="flex-1 bg-primary text-white px-4 py-2 rounded-lg font-bold hover:bg-blue-700 transition">Simpan</button></div>
            </form>
        </div>
    </div>

    {{-- MODAL EDIT --}}
    <div id="modalEdit" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-[#1a202c] rounded-xl shadow-xl w-full max-w-lg overflow-hidden">
            <div class="p-6 border-b flex justify-between items-center">
                <h3 class="text-lg font-bold dark:text-white">Edit Data Supplier</h3>
                <button onclick="toggleModal('modalEdit')" class="text-gray-400 hover:text-red-500"><span class="material-symbols-outlined">close</span></button>
            </div>
            <form id="formEdit" method="POST" class="p-6 space-y-4">
                @csrf @method('PUT')
                <div><label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nama Perusahaan</label><input type="text" id="edit_name" name="name" class="w-full p-2 border rounded dark:bg-gray-800 dark:border-gray-600 dark:text-white" required></div>
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="block text-xs font-bold text-gray-500 uppercase mb-1">Contact Person</label><input type="text" id="edit_contact" name="contact_person" class="w-full p-2 border rounded dark:bg-gray-800 dark:border-gray-600 dark:text-white" required></div>
                    <div><label class="block text-xs font-bold text-gray-500 uppercase mb-1">No. Telepon</label><input type="text" id="edit_phone" name="phone_number" class="w-full p-2 border rounded dark:bg-gray-800 dark:border-gray-600 dark:text-white" required></div>
                </div>
                <div><label class="block text-xs font-bold text-gray-500 uppercase mb-1">Rating</label>
                    <select id="edit_rating" name="rating" class="w-full p-2 border rounded dark:bg-gray-800 dark:border-gray-600 dark:text-white" required>
                        <option value="A">A</option><option value="B">B</option><option value="C">C</option><option value="D">D</option>
                    </select>
                </div>
                <div><label class="block text-xs font-bold text-gray-500 uppercase mb-1">Alamat</label><textarea id="edit_address" name="address" class="w-full p-2 border rounded dark:bg-gray-800 dark:border-gray-600 dark:text-white" rows="3" required></textarea></div>
                <div class="flex gap-3 pt-4"><button type="button" onclick="toggleModal('modalEdit')" class="flex-1 px-4 py-2 border rounded-lg font-bold dark:text-white">Batal</button><button type="submit" class="flex-1 bg-primary text-white px-4 py-2 rounded-lg font-bold hover:bg-blue-700 transition">Update</button></div>
            </form>
        </div>
    </div>

    {{-- MODAL HAPUS --}}
    <div id="modalDelete" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-[#1a202c] rounded-xl shadow-xl w-full max-w-sm p-6 text-center">
            <span class="material-symbols-outlined text-red-500 text-6xl mb-4">warning</span>
            <h3 class="text-xl font-bold dark:text-white mb-2">Konfirmasi Hapus</h3>
            <p class="text-gray-500 mb-6">Yakin ingin menghapus supplier ini?</p>
            <div class="flex gap-3">
                <button onclick="toggleModal('modalDelete')" class="flex-1 px-4 py-2 border rounded-lg font-bold dark:text-white">Batal</button>
                <form id="formDelete" method="POST" class="flex-1">@csrf @method('DELETE')<button type="submit" class="w-full bg-red-600 text-white px-4 py-2 rounded-lg font-bold hover:bg-red-700">Ya, Hapus</button></form>
            </div>
        </div>
    </div>

    <script>
        function toggleModal(id) { document.getElementById(id).classList.toggle('hidden'); }
        function openEditModal(s) {
            document.getElementById('edit_name').value = s.name;
            document.getElementById('edit_contact').value = s.contact_person;
            document.getElementById('edit_phone').value = s.phone_number;
            document.getElementById('edit_rating').value = s.rating;
            document.getElementById('edit_address').value = s.address;
            document.getElementById('formEdit').action = `/manager/suppliers/update/${s.id}`;
            toggleModal('modalEdit');
        }
        function openDeleteModal(id) {
            document.getElementById('formDelete').action = `/manager/suppliers/delete/${id}`;
            toggleModal('modalDelete');
        }
    </script>
@endsection
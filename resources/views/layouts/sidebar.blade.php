<aside class="flex flex-col w-64 bg-white border-r border-[#dbe0e6] min-h-screen p-4">
    <div class="mb-8 px-3">
        <h2 class="text-xl font-extrabold text-[#136dec]">Sistem Inventaris Gudang PT. Borneo</h2>
    </div>

    <nav class="flex-1 space-y-1">
        
        {{-- MENU KHUSUS ADMIN GUDANG --}}
        @if(auth()->user()->role === 'warehouse_admin')
            <a href="/admin/management" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-100 text-sm">
                <span class="material-symbols-outlined">inventory_2</span> Manajemen Spare Part
            </a>
            <a href="/admin/inbound" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-100 text-sm">
                <span class="material-symbols-outlined">download</span> Inbound Stock
            </a>
            <a href="/admin/outbound" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-100 text-sm">
                <span class="material-symbols-outlined">upload</span> Outbound Stock
            </a>
            <a href="/admin/locations" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-100 text-sm">
                <span class="material-symbols-outlined">location_on</span> Lokasi Stok
            </a>
        @endif

        {{-- MENU KHUSUS MANAJER --}}
        @if(auth()->user()->role === 'procurement_manager')
            <a href="/manager/dashboard" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-100 text-sm">
                <span class="material-symbols-outlined">dashboard</span> Dashboard Manajer
            </a>
            <a href="/manager/reports" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-100 text-sm">
                <span class="material-symbols-outlined">analytics</span> Laporan Inventaris
            </a>
            <a href="/manager/supplier-management" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-100 text-sm">
                <span class="material-symbols-outlined">group</span> Manajemen Supplier
            </a>
        @endif

    </nav>

    {{-- TOMBOL LOGOUT --}}
    <div class="mt-auto pt-4 border-t border-[#dbe0e6]">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="flex w-full items-center gap-3 px-3 py-2 text-red-600 hover:bg-red-50 rounded-lg transition">
                <span class="material-symbols-outlined">logout</span>
                <span class="text-sm font-bold">Keluar</span>
            </button>
        </form>
    </div>
</aside>
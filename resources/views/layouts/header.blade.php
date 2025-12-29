<header class="flex flex-col md:flex-row md:items-center justify-between gap-4 px-6 py-5 bg-white dark:bg-[#1a202c] border-b border-[#dbe0e6] dark:border-gray-700 sticky top-0 z-10">
    <div class="flex flex-col gap-1">
        <h2 class="text-[#111418] dark:text-white text-2xl font-black leading-tight tracking-tight">
            @yield('header-title', 'Dashboard')
        </h2>
        <p class="text-[#617289] dark:text-gray-400 text-sm font-medium">
            @yield('header-subtitle', 'Overview sistem inventory.')
        </p>
    </div>
    <div class="flex items-center gap-4">
        {{-- Hanya tombol menu mobile yang dipertahankan untuk navigasi di layar kecil --}}
        <button class="lg:hidden bg-[#f0f2f4] dark:bg-gray-800 text-[#111418] dark:text-white rounded-full p-2">
            <span class="material-symbols-outlined" style="font-size: 24px;">menu</span>
        </button>
    </div>
</header>
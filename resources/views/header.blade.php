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
        <label class="flex flex-col min-w-[280px] h-10 hidden sm:block">
            <div class="flex w-full flex-1 items-stretch rounded-lg h-full bg-[#f0f2f4] dark:bg-gray-800 border border-transparent focus-within:border-primary/50 transition-colors">
                <div class="text-[#617289] flex items-center justify-center pl-3">
                    <span class="material-symbols-outlined" style="font-size: 20px;">search</span>
                </div>
                <input class="flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg bg-transparent text-[#111418] dark:text-white focus:outline-0 focus:ring-0 border-none h-full placeholder:text-[#617289] px-2 text-sm font-normal leading-normal" placeholder="Cari SKU atau nama part..."/>
            </div>
        </label>
        <button class="bg-[#f0f2f4] dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-[#111418] dark:text-white rounded-full p-2 relative transition-colors">
            <span class="material-symbols-outlined" style="font-size: 24px;">notifications</span>
            <span class="absolute top-2 right-2 size-2 bg-red-500 rounded-full border border-white"></span>
        </button>
        <button class="lg:hidden bg-[#f0f2f4] dark:bg-gray-800 text-[#111418] dark:text-white rounded-full p-2">
            <span class="material-symbols-outlined" style="font-size: 24px;">menu</span>
        </button>
    </div>
</header>
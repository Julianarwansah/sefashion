<aside id="sidebar" class="w-72 bg-gradient-to-b from-purple-900 via-purple-800 to-purple-900 shadow-2xl relative transition-all duration-300 flex-shrink-0">
    <!-- Decorative Elements -->
    <div class="absolute top-0 right-0 w-32 h-32 bg-pink-500 rounded-full filter blur-3xl opacity-20"></div>
    <div class="absolute bottom-0 left-0 w-40 h-40 bg-yellow-400 rounded-full filter blur-3xl opacity-10"></div>
    
    <div class="relative z-10 h-full flex flex-col">
        <!-- Header -->
        <div class="flex items-center justify-between p-6 border-b border-white/10">
            <div class="flex items-center gap-3" id="logo-section">
                <div class="w-10 h-10 bg-gradient-to-br from-yellow-400 to-pink-500 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-white">Konveksi</h1>
                    <p class="text-xs text-pink-300">Admin Dashboard</p>
                </div>
            </div>
            <button onclick="toggleSidebar()" class="p-2 rounded-lg bg-white/10 hover:bg-white/20 transition-all hover:scale-110">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Navigation -->
        <nav class="mt-6 px-3 flex-1">
            @php
                $currentRoute = request()->route()->getName();
            @endphp

            <!-- Dashboard -->
<a href="{{ route('admin.dashboard') }}"
   class="nav-item w-full flex items-center gap-4 px-4 py-4 mb-2 rounded-xl transition-all
   {{ request()->routeIs('admin.dashboard') ? 'active-menu' : 'inactive-menu' }}">

    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
    </svg>

    <span class="font-semibold nav-text">Dashboard</span>

    @if (request()->routeIs('admin.dashboard'))
    <svg class="w-5 h-5 ml-auto text-white nav-text" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
    </svg>
    @endif
</a>


            <!-- Admin -->
            <a href="{{ route('admin.adminn.index') }}" 
               class="nav-item w-full flex items-center gap-4 px-4 py-4 mb-2 rounded-xl transition-all {{ str_starts_with($currentRoute, 'admin.adminn') ? 'active-menu' : 'inactive-menu' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <span class="font-semibold nav-text">Admin</span>
                @if(str_starts_with($currentRoute, 'admin.adminn'))
                <svg class="w-5 h-5 ml-auto text-white nav-text" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                @endif
            </a>

            <!-- Customer -->
            <a href="{{ route('admin.customer.index') }}" 
               class="nav-item w-full flex items-center gap-4 px-4 py-4 mb-2 rounded-xl transition-all {{ str_starts_with($currentRoute, 'admin.customer') ? 'active-menu' : 'inactive-menu' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <span class="font-semibold nav-text">Customer</span>
                @if(str_starts_with($currentRoute, 'admin.customer'))
                <svg class="w-5 h-5 ml-auto text-white nav-text" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                @endif
            </a>

            <!-- Produk -->
            <a href="{{ route('admin.produk.index') }}" 
               class="nav-item w-full flex items-center gap-4 px-4 py-4 mb-2 rounded-xl transition-all {{ str_starts_with($currentRoute, 'admin.produk') ? 'active-menu' : 'inactive-menu' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                <span class="font-semibold nav-text">Produk</span>
                @if(str_starts_with($currentRoute, 'admin.produk'))
                <svg class="w-5 h-5 ml-auto text-white nav-text" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                @endif
            </a>

            <!-- Pesanan -->
            <a href="{{ route('admin.pesanan.index') }}" 
               class="nav-item w-full flex items-center gap-4 px-4 py-4 mb-2 rounded-xl transition-all {{ str_starts_with($currentRoute, 'admin.pesanan') ? 'active-menu' : 'inactive-menu' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <span class="font-semibold nav-text">Pesanan</span>
                @if(str_starts_with($currentRoute, 'admin.pesanan'))
                <svg class="w-5 h-5 ml-auto text-white nav-text" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                @endif
            </a>

            <!-- Pembayaran -->
            <a href="{{ route('admin.pembayaran.index') }}" 
               class="nav-item w-full flex items-center gap-4 px-4 py-4 mb-2 rounded-xl transition-all {{ str_starts_with($currentRoute, 'admin.pesanan') ? 'active-menu' : 'inactive-menu' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <span class="font-semibold nav-text">Pembayaran</span>
                @if(str_starts_with($currentRoute, 'admin.pembayaran'))
                <svg class="w-5 h-5 ml-auto text-white nav-text" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                @endif
            </a>

            <!-- Pengiriman -->
            <a href="{{ route('admin.pengiriman.index') }}" 
               class="nav-item w-full flex items-center gap-4 px-4 py-4 mb-2 rounded-xl transition-all {{ str_starts_with($currentRoute, 'admin.pengiriman') ? 'active-menu' : 'inactive-menu' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                </svg>
                <span class="font-semibold nav-text">Pengiriman</span>
                @if(str_starts_with($currentRoute, 'admin.pengiriman'))
                <svg class="w-5 h-5 ml-auto text-white nav-text" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                @endif
            </a>

            <!-- Menu lainnya dengan logika yang sama -->
        </nav>
    </div>
</aside>
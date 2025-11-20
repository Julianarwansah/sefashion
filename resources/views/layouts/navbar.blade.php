<header class="bg-white/80 glass shadow-lg border-b-4 border-gradient-to-r from-purple-500 via-pink-500 to-yellow-400">
    <div class="flex flex-col lg:flex-row lg:items-center justify-between px-4 lg:px-8 py-4 gap-4">
        <div class="flex items-center gap-4 flex-1">
            <button onclick="toggleSidebar()" class="lg:hidden p-2 rounded-lg bg-purple-100 hover:bg-purple-200 transition-all">
                <svg class="w-6 h-6 text-purple-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <div class="relative flex-1 max-w-xl">
                <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 text-purple-400 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" placeholder="Search anything..." class="w-full pl-12 pr-4 py-3 bg-purple-50 border-2 border-purple-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-pink-200 focus:border-pink-400 transition-all">
            </div>
        </div>

        <div class="flex items-center gap-4">
            <div class="flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-purple-100 to-pink-100 rounded-xl">
                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span class="text-sm font-semibold text-purple-900">{{ now()->format('M d, Y') }}</span>
            </div>
            
            <button class="relative p-3 rounded-xl bg-gradient-to-br from-purple-500 to-pink-500 hover:shadow-lg transition-all hover:scale-110">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                <span class="absolute -top-1 -right-1 w-5 h-5 bg-yellow-400 rounded-full flex items-center justify-center text-xs font-bold text-purple-900">5</span>
            </button>
            
            <div class="flex items-center gap-3 pl-4 border-l-2 border-purple-200">
                <div class="text-right hidden sm:block">
                    <p class="font-bold text-sm text-purple-900">Admin User</p>
                    <p class="text-xs text-pink-500">Super Admin</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 via-pink-500 to-yellow-400 rounded-xl flex items-center justify-center text-white font-bold text-lg shadow-lg">
                    AU
                </div>
            </div>
        </div>
    </div>
</header>
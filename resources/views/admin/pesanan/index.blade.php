@extends('layouts.app')

@section('title', 'Manajemen Pesanan - Konveksi')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-50 via-pink-50 to-yellow-50 p-4 lg:p-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-2">Manajemen Pesanan</h1>
                    <p class="text-gray-600">Daftar semua pesanan yang masuk</p>
                </div>
                <a href="{{ route('admin.pesanan.create') }}" 
                   class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-semibold px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Tambah Pesanan
                </a>
            </div>
            <!-- Filter Card -->
            <div class="bg-white/80 glass rounded-2xl shadow-xl border border-white/20 overflow-hidden mb-6">
                <div class="px-6 py-4 bg-gradient-to-r from-purple-600 to-pink-600 text-white">
                    <h2 class="text-xl font-bold">Filter Pesanan</h2>
                </div>
                <div class="p-6">
                    <form id="filterForm" action="{{ route('admin.pesanan.filter') }}" method="GET" class="space-y-4">
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
                            <!-- Status Filter -->
                            <div class="lg:col-span-3">
                                <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Status Pesanan
                                </label>
                                <select id="status" 
                                        name="status" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200">
                                    <option value="semua">Semua Status</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                    <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses</option>
                                    <option value="batal" {{ request('status') == 'Batal' ? 'selected' : '' }}>Batal</option>
                                </select>
                            </div>

                            <!-- Total Range -->
                            <div class="lg:col-span-3">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Rentang Total
                                </label>
                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <input type="number" 
                                            name="min_total" 
                                            value="{{ request('min_total') }}"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200"
                                            placeholder="Min. total"
                                            min="0">
                                    </div>
                                    <div>
                                        <input type="number" 
                                            name="max_total" 
                                            value="{{ request('max_total') }}"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200"
                                            placeholder="Max. total"
                                            min="0">
                                    </div>
                                </div>
                            </div>

                            <!-- Items Range -->
                            <div class="lg:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Jumlah Items
                                </label>
                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <input type="number" 
                                            name="min_items" 
                                            value="{{ request('min_items') }}"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200"
                                            placeholder="Min. items"
                                            min="1">
                                    </div>
                                    <div>
                                        <input type="number" 
                                            name="max_items" 
                                            value="{{ request('max_items') }}"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200"
                                            placeholder="Max. items"
                                            min="1">
                                    </div>
                                </div>
                            </div>

                            <!-- Sort By -->
                            <div class="lg:col-span-2">
                                <label for="sort_by" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Urutkan
                                </label>
                                <select id="sort_by" 
                                        name="sort_by" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200">
                                    <option value="terbaru" {{ request('sort_by', 'terbaru') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                                    <option value="terlama" {{ request('sort_by') == 'terlama' ? 'selected' : '' }}>Terlama</option>
                                    <option value="total_tertinggi" {{ request('sort_by') == 'total_tertinggi' ? 'selected' : '' }}>Total Tertinggi</option>
                                    <option value="total_terendah" {{ request('sort_by') == 'total_terendah' ? 'selected' : '' }}>Total Terendah</option>
                                    <option value="items_terbanyak" {{ request('sort_by') == 'items_terbanyak' ? 'selected' : '' }}>Items Terbanyak</option>
                                    <option value="items_tersedikit" {{ request('sort_by') == 'items_tersedikit' ? 'selected' : '' }}>Items Tersedikit</option>
                                    <option value="tanggal_pemesanan_asc" {{ request('sort_by') == 'tanggal_pemesanan_asc' ? 'selected' : '' }}>Tanggal (A-Z)</option>
                                    <option value="tanggal_pemesanan_desc" {{ request('sort_by') == 'tanggal_pemesanan_desc' ? 'selected' : '' }}>Tanggal (Z-A)</option>
                                </select>
                            </div>

                            <!-- Action Buttons -->
                            <div class="lg:col-span-2 flex items-end space-x-2">
                                <button type="submit" 
                                        class="w-full bg-gradient-to-r from-green-500 to-blue-500 hover:from-green-600 hover:to-blue-600 text-white font-semibold py-3 px-4 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z"/>
                                    </svg>
                                    Filter
                                </button>
                                
                                <a href="{{ route('admin.pesanan.index') }}" 
                                class="w-full bg-gray-500 hover:bg-gray-600 text-white font-semibold py-3 px-4 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 flex items-center justify-center gap-2"
                                title="Reset filter">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Results Info -->
            @if(request()->hasAny(['status', 'sort_by', 'min_total', 'max_total', 'min_items', 'max_items']))
                <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-xl">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-blue-800 font-medium">
                                Menampilkan hasil filter
                                @if(request('status') && request('status') !== 'semua')
                                    - Status: <strong>{{ ucfirst(request('status')) }}</strong>
                                @endif
                                @if(request('min_total') || request('max_total'))
                                    - Total: 
                                    @if(request('min_total'))
                                        <strong>Rp {{ number_format(request('min_total'), 0, ',', '.') }}</strong>
                                    @endif
                                    @if(request('min_total') && request('max_total'))
                                        sampai
                                    @endif
                                    @if(request('max_total'))
                                        <strong>Rp {{ number_format(request('max_total'), 0, ',', '.') }}</strong>
                                    @endif
                                @endif
                                @if(request('min_items') || request('max_items'))
                                    - Items: 
                                    @if(request('min_items'))
                                        <strong>{{ request('min_items') }}</strong>
                                    @endif
                                    @if(request('min_items') && request('max_items'))
                                        sampai
                                    @endif
                                    @if(request('max_items'))
                                        <strong>{{ request('max_items') }}</strong>
                                    @endif
                                @endif
                                ({{ $pesanan->total() }} hasil ditemukan)
                            </span>
                        </div>
                        <a href="{{ route('admin.pesanan.index') }}" 
                        class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            Tampilkan semua pesanan
                        </a>
                    </div>
                </div>
            @endif

        </div>

        </div>

        <!-- Alert Messages -->
        <div class="mb-6 space-y-3">
            @if(session('success'))
                <div id="success-alert" class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl shadow-sm">
                    <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="flex-1">{{ session('success') }}</span>
                    <button type="button" onclick="dismissAlert('success-alert')" class="text-green-500 hover:text-green-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div id="error-alert" class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl shadow-sm">
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="flex-1">{{ session('error') }}</span>
                    <button type="button" onclick="dismissAlert('error-alert')" class="text-red-500 hover:text-red-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            @endif
        </div>

        <!-- Content Card -->
        <div class="bg-white/80 glass rounded-2xl shadow-xl border border-white/20 overflow-hidden">
            @if($pesanan->isEmpty())
                <!-- Empty State -->
                <div class="text-center py-16 px-4">
                    <div class="w-24 h-24 mx-auto mb-4 bg-gradient-to-br from-purple-100 to-pink-100 rounded-full flex items-center justify-center">
                        <svg class="w-12 h-12 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">Belum ada pesanan</h3>
                    <p class="text-gray-500 mb-6">Mulai dengan menambahkan pesanan baru</p>
                    <a href="{{ route('admin.pesanan.create') }}" 
                       class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-semibold px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Tambah Pesanan Pertama
                    </a>
                </div>
            @else
                <!-- Table Section -->
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gradient-to-r from-purple-600 to-pink-600 text-white">
                                <th class="px-6 py-4 text-left font-semibold">ID Pesanan</th>
                                <th class="px-6 py-4 text-left font-semibold">Customer</th>
                                <th class="px-6 py-4 text-left font-semibold">Tanggal</th>
                                <th class="px-6 py-4 text-left font-semibold">Total</th>
                                <th class="px-6 py-4 text-left font-semibold">Status</th>
                                <th class="px-6 py-4 text-left font-semibold">Items</th>
                                <th class="px-6 py-4 text-center font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($pesanan as $item)
                            <tr class="hover:bg-gradient-to-r hover:from-purple-50 hover:to-pink-50 transition-colors duration-200">
                                <!-- ID Pesanan -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-900">#{{ $item->id_pemesanan }}</div>
                                </td>

                                <!-- Customer -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-500 rounded-full flex items-center justify-center text-white text-sm font-bold">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold text-gray-900">{{ $item->customer->nama_customer ?? 'N/A' }}</div>
                                            <div class="text-xs text-gray-500">{{ $item->customer->email ?? '-' }}</div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Tanggal -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-semibold text-gray-900">{{ $item->tanggal_pemesanan->format('d M Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $item->tanggal_pemesanan->format('H:i') }}</div>
                                </td>

                                <!-- Total -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-lg font-bold text-green-600">{{ $item->total_harga_formatted }}</div>
                                </td>

                                <!-- Status -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusConfig = [
                                            'pending' => ['color' => 'bg-yellow-100 text-yellow-800', 'icon' => 'clock'],
                                            'diproses' => ['color' => 'bg-blue-100 text-blue-800', 'icon' => 'cog'],
                                            'dikirim' => ['color' => 'bg-purple-100 text-purple-800', 'icon' => 'shipping-fast'],
                                            'selesai' => ['color' => 'bg-green-100 text-green-800', 'icon' => 'check'],
                                            'batal' => ['color' => 'bg-red-100 text-red-800', 'icon' => 'times']
                                        ];
                                        $config = $statusConfig[$item->status] ?? ['color' => 'bg-gray-100 text-gray-800', 'icon' => 'question'];
                                    @endphp
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-sm font-medium {{ $config['color'] }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="
                                                @if($config['icon'] == 'clock') M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z
                                                @elseif($config['icon'] == 'cog') M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z
                                                @elseif($config['icon'] == 'shipping-fast') M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0
                                                @elseif($config['icon'] == 'check') M5 13l4 4L19 7
                                                @elseif($config['icon'] == 'times') M6 18L18 6M6 6l12 12
                                                @else M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z
                                                @endif
                                            "/>
                                        </svg>
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>

                                <!-- Items -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2 text-gray-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                        </svg>
                                        <span class="font-semibold">{{ $item->total_items }}</span>
                                        <span class="text-sm">items</span>
                                    </div>
                                </td>

                                <!-- Actions -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-2">
                                        <!-- Detail Button -->
                                        <a href="{{ route('admin.pesanan.show', $item->id_pemesanan) }}" 
                                           class="inline-flex items-center gap-1 bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200 transform hover:scale-105"
                                           title="Lihat Detail">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>
                                        
                                        <!-- Edit Button -->
                                        <a href="{{ route('admin.pesanan.edit', $item->id_pemesanan) }}" 
                                           class="inline-flex items-center gap-1 bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200 transform hover:scale-105"
                                           title="Edit Pesanan">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>
                                        
                                        <!-- Delete Button -->
                                        <button type="button" 
                                                onclick="confirmDelete('{{ $item->id_pemesanan }}', '{{ $item->customer->nama_customer ?? 'N/A' }}')"
                                                class="inline-flex items-center gap-1 bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200 transform hover:scale-105"
                                                title="Hapus Pesanan">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Table Info -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <p class="text-sm text-gray-600">
                            Menampilkan <span class="font-semibold">{{ $pesanan->count() }}</span> pesanan
                        </p>
                        <p class="text-sm text-gray-500">
                            Terakhir diperbarui: {{ now()->format('d M Y H:i') }}
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Hidden Delete Form -->
<form id="deleteForm" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Dismiss alert function
    function dismissAlert(alertId) {
        const alert = document.getElementById(alertId);
        if (alert) {
            alert.style.opacity = '0';
            alert.style.transform = 'translateX(100%)';
            setTimeout(() => alert.remove(), 300);
        }
    }

    // Auto dismiss alerts after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('[id$="-alert"]');
        alerts.forEach(alert => {
            setTimeout(() => {
                if (alert && alert.parentNode) {
                    dismissAlert(alert.id);
                }
            }, 5000);
        });
    });

    // Confirm delete function
    function confirmDelete(orderId, customerName) {
        Swal.fire({
            title: 'Yakin ingin menghapus?',
            html: `Pesanan <strong>#${orderId}</strong> dari <strong>${customerName}</strong> akan dihapus permanen.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: {
                confirmButton: 'bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded-lg mr-2',
                cancelButton: 'bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Set form action and submit
                const form = document.getElementById('deleteForm');
                form.action = `{{ url('admin/pesanan') }}/${orderId}`;
                form.submit();
            }
        });
    }

    // Add smooth animations
    document.addEventListener('DOMContentLoaded', function() {
        // Animate table rows
        const tableRows = document.querySelectorAll('tbody tr');
        tableRows.forEach((row, index) => {
            row.style.opacity = '0';
            row.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                row.style.transition = 'all 0.5s ease';
                row.style.opacity = '1';
                row.style.transform = 'translateY(0)';
            }, index * 100);
        });
    });
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.getElementById('filterForm');
    const statusSelect = document.getElementById('status');
    const sortSelect = document.getElementById('sort_by');
    const minTotalInput = document.querySelector('input[name="min_total"]');
    const maxTotalInput = document.querySelector('input[name="max_total"]');
    const minItemsInput = document.querySelector('input[name="min_items"]');
    const maxItemsInput = document.querySelector('input[name="max_items"]');
    const pesananTableContainer = document.getElementById('pesananTableContainer');
    
    // Real-time filter ketika select berubah
    statusSelect.addEventListener('change', performFilter);
    sortSelect.addEventListener('change', performFilter);
    
    // Debounce untuk input
    let inputTimeout;
    [minTotalInput, maxTotalInput, minItemsInput, maxItemsInput].forEach(input => {
        input.addEventListener('input', function() {
            clearTimeout(inputTimeout);
            inputTimeout = setTimeout(performFilter, 800);
        });
    });
    
    function performFilter() {
        const formData = new FormData(filterForm);
        
        // Show loading
        pesananTableContainer.innerHTML = `
            <div class="flex justify-center items-center py-12">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-purple-600"></div>
                <span class="ml-3 text-gray-600">Memfilter pesanan...</span>
            </div>
        `;
        
        fetch(filterForm.action + '?' + new URLSearchParams(formData), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                pesananTableContainer.innerHTML = data.html;
                
                // Update pagination jika ada
                if (data.pagination) {
                    const existingPagination = document.querySelector('.pagination');
                    if (existingPagination) {
                        existingPagination.innerHTML = data.pagination;
                    }
                }
            } else {
                pesananTableContainer.innerHTML = `
                    <div class="text-center py-8 text-red-600">
                        <svg class="w-12 h-12 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                        <p>Terjadi kesalahan saat memfilter pesanan.</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Filter error:', error);
            pesananTableContainer.innerHTML = `
                <div class="text-center py-8 text-red-600">
                    <p>Terjadi kesalahan saat memfilter pesanan.</p>
                </div>
            `;
        });
    }
    
    // Handle pagination clicks (untuk AJAX pagination)
    document.addEventListener('click', function(e) {
        if (e.target.closest('.pagination a')) {
            e.preventDefault();
            const url = e.target.closest('a').href;
            
            pesananTableContainer.innerHTML = `
                <div class="flex justify-center items-center py-8">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-purple-600"></div>
                </div>
            `;
            
            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    pesananTableContainer.innerHTML = data.html;
                }
            })
            .catch(error => {
                console.error('Pagination error:', error);
                window.location.href = url; // Fallback ke normal page load
            });
        }
    });
});
</script>

<style>
    .swal2-popup {
        font-family: 'Inter', sans-serif;
        border-radius: 1rem;
    }
</style>
@endpush
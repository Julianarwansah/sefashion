@extends('layouts.app')

@section('title', 'Manajemen Pengiriman - Konveksi')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-50 via-pink-50 to-yellow-50 p-4 lg:p-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-2">Manajemen Pengiriman</h1>
                    <p class="text-gray-600">Daftar semua pengiriman barang</p>
                </div>
                <a href="{{ route('admin.pengiriman.create') }}" 
                   class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-semibold px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Tambah Pengiriman
                </a>
            </div>
            <!-- Filter Card -->
            <div class="bg-white/80 glass rounded-2xl shadow-xl border border-white/20 overflow-hidden mb-6">
                <div class="px-6 py-4 bg-gradient-to-r from-purple-600 to-pink-600 text-white">
                    <h2 class="text-xl font-bold">Filter Pengiriman</h2>
                </div>
                <div class="p-6">
                    <form id="filterForm" action="{{ route('admin.pengiriman.filter') }}" method="GET" class="space-y-4">
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
                            <!-- Status Filter -->
                            <div class="lg:col-span-3">
                                <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Status Pengiriman
                                </label>
                                <select id="status" 
                                        name="status" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200">
                                    <option value="semua">Semua Status</option>
                                    <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                                    <option value="dikirim" {{ request('status') == 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                                    <option value="diterima" {{ request('status') == 'diterima' ? 'selected' : '' }}>Diterima</option>
                                    <option value="gagal" {{ request('status') == 'gagal' ? 'selected' : '' }}>Gagal</option>
                                </select>
                            </div>

                            <!-- Ekspedisi Filter -->
                            <div class="lg:col-span-3">
                                <label for="ekspedisi" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Ekspedisi
                                </label>
                                <select id="ekspedisi" 
                                        name="ekspedisi" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200">
                                    <option value="semua">Semua Ekspedisi</option>
                                    <option value="JNE" {{ request('ekspedisi') == 'JNE' ? 'selected' : '' }}>JNE</option>
                                    <option value="TIKI" {{ request('ekspedisi') == 'TIKI' ? 'selected' : '' }}>TIKI</option>
                                    <option value="POS Indonesia" {{ request('ekspedisi') == 'POS Indonesia' ? 'selected' : '' }}>POS Indonesia</option>
                                    <option value="J&T" {{ request('ekspedisi') == 'J&T' ? 'selected' : '' }}>J&T</option>
                                    <option value="SiCepat" {{ request('ekspedisi') == 'SiCepat' ? 'selected' : '' }}>SiCepat</option>
                                    <option value="Ninja Express" {{ request('ekspedisi') == 'Ninja Express' ? 'selected' : '' }}>Ninja Express</option>
                                    <option value="Wahana" {{ request('ekspedisi') == 'Wahana' ? 'selected' : '' }}>Wahana</option>
                                    <option value="Lion Parcel" {{ request('ekspedisi') == 'Lion Parcel' ? 'selected' : '' }}>Lion Parcel</option>
                                </select>
                            </div>

                            <!-- Ongkir Range -->
                            <div class="lg:col-span-3">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Rentang Biaya Ongkir
                                </label>
                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <input type="number" 
                                            name="min_ongkir" 
                                            value="{{ request('min_ongkir') }}"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200"
                                            placeholder="Min. ongkir"
                                            min="0">
                                    </div>
                                    <div>
                                        <input type="number" 
                                            name="max_ongkir" 
                                            value="{{ request('max_ongkir') }}"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200"
                                            placeholder="Max. ongkir"
                                            min="0">
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
                                    <option value="ongkir_tertinggi" {{ request('sort_by') == 'ongkir_tertinggi' ? 'selected' : '' }}>Ongkir Tertinggi</option>
                                    <option value="ongkir_terendah" {{ request('sort_by') == 'ongkir_terendah' ? 'selected' : '' }}>Ongkir Terendah</option>
                                    <option value="tanggal_dikirim_asc" {{ request('sort_by') == 'tanggal_dikirim_asc' ? 'selected' : '' }}>Tanggal Kirim (A-Z)</option>
                                    <option value="tanggal_dikirim_desc" {{ request('sort_by') == 'tanggal_dikirim_desc' ? 'selected' : '' }}>Tanggal Kirim (Z-A)</option>
                                    <option value="tanggal_diterima_asc" {{ request('sort_by') == 'tanggal_diterima_asc' ? 'selected' : '' }}>Tanggal Terima (A-Z)</option>
                                    <option value="tanggal_diterima_desc" {{ request('sort_by') == 'tanggal_diterima_desc' ? 'selected' : '' }}>Tanggal Terima (Z-A)</option>
                                </select>
                            </div>

                            <!-- Action Buttons -->
                            <div class="lg:col-span-1 flex items-end space-x-2">
                                <button type="submit" 
                                        class="w-full bg-gradient-to-r from-green-500 to-blue-500 hover:from-green-600 hover:to-blue-600 text-white font-semibold py-3 px-4 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z"/>
                                    </svg>
                                </button>
                                
                                <a href="{{ route('admin.pengiriman.index') }}" 
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
            @if(request()->hasAny(['status', 'ekspedisi', 'sort_by', 'min_ongkir', 'max_ongkir']))
                <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-xl">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-blue-800 font-medium">
                                Menampilkan hasil filter
                                @if(request('status') && request('status') !== 'semua')
                                    - Status: <strong>{{ ucfirst(str_replace('_', ' ', request('status'))) }}</strong>
                                @endif
                                @if(request('ekspedisi') && request('ekspedisi') !== 'semua')
                                    - Ekspedisi: <strong>{{ request('ekspedisi') }}</strong>
                                @endif
                                @if(request('min_ongkir') || request('max_ongkir'))
                                    - Ongkir: 
                                    @if(request('min_ongkir'))
                                        <strong>Rp {{ number_format(request('min_ongkir'), 0, ',', '.') }}</strong>
                                    @endif
                                    @if(request('min_ongkir') && request('max_ongkir'))
                                        sampai
                                    @endif
                                    @if(request('max_ongkir'))
                                        <strong>Rp {{ number_format(request('max_ongkir'), 0, ',', '.') }}</strong>
                                    @endif
                                @endif
                                ({{ $pengiriman->total() }} hasil ditemukan)
                            </span>
                        </div>
                        <a href="{{ route('admin.pengiriman.index') }}" 
                        class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            Tampilkan semua pengiriman
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
            @if($pengiriman->isEmpty())
                <!-- Empty State -->
                <div class="text-center py-16 px-4">
                    <div class="w-24 h-24 mx-auto mb-4 bg-gradient-to-br from-purple-100 to-pink-100 rounded-full flex items-center justify-center">
                        <svg class="w-12 h-12 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">Belum ada pengiriman</h3>
                    <p class="text-gray-500 mb-6">Mulai dengan menambahkan pengiriman baru</p>
                    <a href="{{ route('admin.pengiriman.create') }}" 
                       class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-semibold px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Tambah Pengiriman Pertama
                    </a>
                </div>
            @else
                <!-- Table Section -->
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gradient-to-r from-purple-600 to-pink-600 text-white">
                                <th class="px-6 py-4 text-left font-semibold">ID Pengiriman</th>
                                <th class="px-6 py-4 text-left font-semibold">Pemesanan</th>
                                <th class="px-6 py-4 text-left font-semibold">Penerima</th>
                                <th class="px-6 py-4 text-left font-semibold">Ekspedisi</th>
                                <th class="px-6 py-4 text-left font-semibold">No. Resi</th>
                                <th class="px-6 py-4 text-left font-semibold">Status</th>
                                <th class="px-6 py-4 text-left font-semibold">Biaya Ongkir</th>
                                <th class="px-6 py-4 text-center font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($pengiriman as $item)
                            <tr class="hover:bg-gradient-to-r hover:from-purple-50 hover:to-pink-50 transition-colors duration-200">
                                <!-- ID Pengiriman -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-900">#{{ $item->id_pengiriman }}</div>
                                </td>

                                <!-- Pemesanan -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-500 rounded-full flex items-center justify-center text-white text-sm">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold text-gray-900">#{{ $item->pemesanan->id_pemesanan }}</div>
                                            <div class="text-xs text-gray-500">{{ $item->pemesanan->customer->nama_customer ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Penerima -->
                                <td class="px-6 py-4">
                                    <div>
                                        <div class="text-sm font-semibold text-gray-900">{{ $item->nama_penerima }}</div>
                                        <div class="text-xs text-gray-500">{{ $item->no_hp_penerima }}</div>
                                    </div>
                                </td>

                                <!-- Ekspedisi -->
                                <td class="px-6 py-4">
                                    <div>
                                        <div class="text-sm font-semibold text-gray-900">{{ $item->ekspedisi }}</div>
                                        <div class="text-xs text-gray-500">{{ $item->layanan }}</div>
                                    </div>
                                </td>

                                <!-- No. Resi -->
                                <td class="px-6 py-4">
                                    @if($item->no_resi)
                                        <div class="flex flex-col gap-1">
                                            <code class="text-sm text-blue-600 font-mono">{{ $item->no_resi }}</code>
                                            @if($item->tracking_url)
                                                <a href="{{ $item->tracking_url }}" target="_blank" 
                                                   class="inline-flex items-center gap-1 text-xs text-green-600 hover:text-green-700 transition-colors">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                                    </svg>
                                                    Track
                                                </a>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-sm text-gray-400">-</span>
                                    @endif
                                </td>

                                <!-- Status -->
                                <td class="px-6 py-4">
                                    @php
                                        $statusConfig = [
                                            'menunggu' => ['color' => 'bg-yellow-100 text-yellow-800', 'icon' => 'clock'],
                                            'dikirim' => ['color' => 'bg-blue-100 text-blue-800', 'icon' => 'shipping-fast'],
                                            'diterima' => ['color' => 'bg-green-100 text-green-800', 'icon' => 'check'],
                                            'gagal' => ['color' => 'bg-red-100 text-red-800', 'icon' => 'times']
                                        ];
                                        $config = $statusConfig[$item->status_pengiriman] ?? ['color' => 'bg-gray-100 text-gray-800', 'icon' => 'question'];
                                    @endphp
                                    <div class="space-y-1">
                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-sm font-medium {{ $config['color'] }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="
                                                    @if($config['icon'] == 'clock') M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z
                                                    @elseif($config['icon'] == 'shipping-fast') M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0
                                                    @elseif($config['icon'] == 'check') M5 13l4 4L19 7
                                                    @elseif($config['icon'] == 'times') M6 18L18 6M6 6l12 12
                                                    @else M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z
                                                @endif
                                                "/>
                                            </svg>
                                            {{ $item->status_pengiriman_text }}
                                        </span>
                                        @if($item->isLate())
                                            <div class="flex items-center gap-1 text-xs text-red-600">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                                </svg>
                                                Terlambat {{ $item->days_late }} hari
                                            </div>
                                        @endif
                                    </div>
                                </td>

                                <!-- Biaya Ongkir -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-lg font-bold text-green-600">{{ $item->biaya_ongkir_formatted }}</div>
                                </td>

                                <!-- Actions -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-2">
                                        <!-- Detail Button -->
                                        <a href="{{ route('admin.pengiriman.show', $item->id_pengiriman) }}" 
                                           class="inline-flex items-center gap-1 bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200 transform hover:scale-105"
                                           title="Lihat Detail">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>
                                        
                                        <!-- Edit Button -->
                                        <a href="{{ route('admin.pengiriman.edit', $item->id_pengiriman) }}" 
                                           class="inline-flex items-center gap-1 bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200 transform hover:scale-105"
                                           title="Edit Pengiriman">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>

                                        <!-- Quick Actions -->
                                        @if($item->isInTransit())
                                            <!-- Mark as Received Button -->
                                            <form action="{{ route('admin.pengiriman.update', $item->id_pengiriman) }}" 
                                                  method="POST" class="inline">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="update_only_status" value="true">
                                                <input type="hidden" name="status_pengiriman" value="diterima">
                                                <button type="button" 
                                                        onclick="confirmStatusChange('{{ $item->id_pengiriman }}', 'diterima', this)"
                                                        class="inline-flex items-center gap-1 bg-green-500 hover:bg-green-600 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200 transform hover:scale-105"
                                                        title="Tandai Diterima">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                        
                                        <!-- Delete Button -->
                                        <button type="button" 
                                                onclick="confirmDelete('{{ $item->id_pengiriman }}', '{{ $item->pemesanan->customer->nama_customer ?? 'N/A' }}')"
                                                class="inline-flex items-center gap-1 bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200 transform hover:scale-105"
                                                title="Hapus Pengiriman">
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

                <!-- Table Info & Stats -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                        <p class="text-sm text-gray-600">
                            Menampilkan <span class="font-semibold">{{ $pengiriman->count() }}</span> pengiriman
                        </p>
                        <div class="flex flex-wrap gap-2">
                            @php
                                $stats = [
                                    'dikirim' => $pengiriman->where('status_pengiriman', 'dikirim')->count(),
                                    'menunggu' => $pengiriman->where('status_pengiriman', 'menunggu')->count(),
                                    'diterima' => $pengiriman->where('status_pengiriman', 'diterima')->count(),
                                    'gagal' => $pengiriman->where('status_pengiriman', 'gagal')->count()
                                ];
                            @endphp
                            <span class="inline-flex items-center gap-1 bg-blue-100 text-blue-800 text-xs font-semibold px-3 py-1 rounded-full">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                                </svg>
                                Dikirim: {{ $stats['dikirim'] }}
                            </span>
                            <span class="inline-flex items-center gap-1 bg-yellow-100 text-yellow-800 text-xs font-semibold px-3 py-1 rounded-full">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Menunggu: {{ $stats['menunggu'] }}
                            </span>
                            <span class="inline-flex items-center gap-1 bg-green-100 text-green-800 text-xs font-semibold px-3 py-1 rounded-full">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Diterima: {{ $stats['diterima'] }}
                            </span>
                            <span class="inline-flex items-center gap-1 bg-red-100 text-red-800 text-xs font-semibold px-3 py-1 rounded-full">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Gagal: {{ $stats['gagal'] }}
                            </span>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Hidden Forms -->
<form id="deleteForm" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>

<form id="statusForm" method="POST" class="hidden">
    @csrf
    @method('PUT')
    <input type="hidden" name="update_only_status" value="true">
    <input type="hidden" name="status_pengiriman" value="">
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
    function confirmDelete(pengirimanId, customerName) {
        Swal.fire({
            title: 'Yakin ingin menghapus?',
            html: `Pengiriman <strong>#${pengirimanId}</strong> untuk <strong>${customerName}</strong> akan dihapus permanen.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Set form action and submit
                const form = document.getElementById('deleteForm');
                form.action = `{{ url('admin/pengiriman') }}/${pengirimanId}`;
                form.submit();
            }
        });
    }

    // Confirm status change function
    function confirmStatusChange(pengirimanId, status, button) {
        const statusText = {
            'diterima': 'diterima',
            'gagal': 'gagal'
        }[status] || status;

        Swal.fire({
            title: 'Konfirmasi Status',
            html: `Tandai pengiriman <strong>#${pengirimanId}</strong> sebagai <strong>${statusText}</strong>?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Update!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Set form action and submit
                const form = document.getElementById('statusForm');
                form.action = `{{ url('admin/pengiriman') }}/${pengirimanId}`;
                form.querySelector('input[name="status_pengiriman"]').value = status;
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
    const ekspedisiSelect = document.getElementById('ekspedisi');
    const sortSelect = document.getElementById('sort_by');
    const minOngkirInput = document.querySelector('input[name="min_ongkir"]');
    const maxOngkirInput = document.querySelector('input[name="max_ongkir"]');
    const pengirimanTableContainer = document.getElementById('pengirimanTableContainer');
    
    // Real-time filter ketika select berubah
    statusSelect.addEventListener('change', performFilter);
    ekspedisiSelect.addEventListener('change', performFilter);
    sortSelect.addEventListener('change', performFilter);
    
    // Debounce untuk input ongkir
    let ongkirTimeout;
    minOngkirInput.addEventListener('input', function() {
        clearTimeout(ongkirTimeout);
        ongkirTimeout = setTimeout(performFilter, 800);
    });
    
    maxOngkirInput.addEventListener('input', function() {
        clearTimeout(ongkirTimeout);
        ongkirTimeout = setTimeout(performFilter, 800);
    });
    
    function performFilter() {
        const formData = new FormData(filterForm);
        
        // Show loading
        pengirimanTableContainer.innerHTML = `
            <div class="flex justify-center items-center py-12">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-purple-600"></div>
                <span class="ml-3 text-gray-600">Memfilter pengiriman...</span>
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
                pengirimanTableContainer.innerHTML = data.html;
                
                // Update pagination jika ada
                if (data.pagination) {
                    const existingPagination = document.querySelector('.pagination');
                    if (existingPagination) {
                        existingPagination.innerHTML = data.pagination;
                    }
                }
            } else {
                pengirimanTableContainer.innerHTML = `
                    <div class="text-center py-8 text-red-600">
                        <svg class="w-12 h-12 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                        <p>Terjadi kesalahan saat memfilter pengiriman.</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Filter error:', error);
            pengirimanTableContainer.innerHTML = `
                <div class="text-center py-8 text-red-600">
                    <p>Terjadi kesalahan saat memfilter pengiriman.</p>
                </div>
            `;
        });
    }
    
    // Handle pagination clicks (untuk AJAX pagination)
    document.addEventListener('click', function(e) {
        if (e.target.closest('.pagination a')) {
            e.preventDefault();
            const url = e.target.closest('a').href;
            
            pengirimanTableContainer.innerHTML = `
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
                    pengirimanTableContainer.innerHTML = data.html;
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
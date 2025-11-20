@extends('layouts.app')

@section('title', 'Detail Pengiriman - Konveksi')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-50 via-pink-50 to-yellow-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header Section -->
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-8">
            <div>
                <nav class="flex mb-4" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2 text-sm">
                        <li>
                            <a href="{{ route('admin.pengiriman.index') }}" class="text-purple-600 hover:text-purple-700 transition-colors">
                                Pengiriman
                            </a>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                            <span class="ml-2 text-gray-500">Detail #{{ $pengiriman->id_pengiriman }}</span>
                        </li>
                    </ol>
                </nav>
                <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-2">Detail Pengiriman</h1>
                <p class="text-gray-600 text-lg">#{{ $pengiriman->id_pengiriman }} - {{ $pengiriman->pemesanan->customer->nama_customer ?? 'N/A' }}</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-3">
                <a href="{{ route('admin.pengiriman.index') }}" 
                   class="inline-flex items-center justify-center gap-2 bg-gray-500 hover:bg-gray-600 text-white font-semibold px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali
                </a>
                <a href="{{ route('admin.pengiriman.edit', $pengiriman->id_pengiriman) }}" 
                   class="inline-flex items-center justify-center gap-2 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit
                </a>
            </div>
        </div>

        <!-- Alert Messages -->
        @if(session('success'))
            <div id="success-alert" class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl shadow-sm mb-6">
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

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Informasi Pengiriman -->
            <div class="bg-white/80 glass rounded-2xl shadow-xl border border-white/20 overflow-hidden">
                <div class="bg-gradient-to-r from-purple-600 to-pink-600 p-6 text-white">
                    <h2 class="text-xl font-bold flex items-center gap-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                        </svg>
                        Informasi Pengiriman
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">ID Pengiriman</label>
                            <div class="text-lg font-bold text-gray-900">#{{ $pengiriman->id_pengiriman }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Status</label>
                            <div>
                                @php
                                    $statusConfig = [
                                        'menunggu' => ['color' => 'bg-yellow-100 text-yellow-800', 'icon' => 'clock'],
                                        'dikirim' => ['color' => 'bg-blue-100 text-blue-800', 'icon' => 'shipping-fast'],
                                        'diterima' => ['color' => 'bg-green-100 text-green-800', 'icon' => 'check'],
                                        'gagal' => ['color' => 'bg-red-100 text-red-800', 'icon' => 'times']
                                    ];
                                    $config = $statusConfig[$pengiriman->status_pengiriman] ?? ['color' => 'bg-gray-100 text-gray-800', 'icon' => 'question'];
                                @endphp
                                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-lg font-bold {{ $config['color'] }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="
                                            @if($config['icon'] == 'clock') M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z
                                            @elseif($config['icon'] == 'shipping-fast') M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0
                                            @elseif($config['icon'] == 'check') M5 13l4 4L19 7
                                            @elseif($config['icon'] == 'times') M6 18L18 6M6 6l12 12
                                            @else M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z
                                        @endif
                                        "/>
                                    </svg>
                                    {{ $pengiriman->status_pengiriman_text }}
                                </span>
                                @if($pengiriman->isLate())
                                    <div class="flex items-center gap-2 mt-2 text-red-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                        </svg>
                                        <span class="text-sm font-semibold">Terlambat {{ $pengiriman->days_late }} hari</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Ekspedisi</label>
                            <div class="text-lg font-bold text-gray-900">{{ $pengiriman->ekspedisi }}</div>
                            <div class="text-sm text-gray-600">{{ $pengiriman->layanan }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Biaya Ongkir</label>
                            <div class="text-2xl font-bold text-green-600">{{ $pengiriman->biaya_ongkir_formatted }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Tanggal Dikirim</label>
                            <div class="text-lg font-bold text-gray-900">{{ $pengiriman->tanggal_dikirim_formatted }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Tanggal Diterima</label>
                            <div class="text-lg font-bold text-gray-900">{{ $pengiriman->tanggal_diterima_formatted }}</div>
                        </div>
                        @if($pengiriman->no_resi)
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-500 mb-1">Nomor Resi</label>
                            <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                                <code class="text-xl font-mono text-blue-600 bg-blue-50 px-4 py-2 rounded-lg">{{ $pengiriman->no_resi }}</code>
                                @if($pengiriman->tracking_url)
                                <a href="{{ $pengiriman->tracking_url }}" target="_blank" 
                                   class="inline-flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white font-semibold px-4 py-2 rounded-lg transition-colors duration-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                    </svg>
                                    Lacak Pengiriman
                                </a>
                                @endif
                            </div>
                        </div>
                        @endif
                        @if($pengiriman->estimasi_pengiriman)
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-500 mb-1">Estimasi Sampai</label>
                            <div class="text-lg font-bold text-gray-900 flex items-center gap-2">
                                {{ $pengiriman->estimasi_pengiriman_formatted }}
                                @if($pengiriman->isLate())
                                    <span class="inline-flex items-center gap-1 bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-semibold">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                        </svg>
                                        Terlambat
                                    </span>
                                @elseif($pengiriman->isShipped())
                                    <span class="inline-flex items-center gap-1 bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-semibold">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Dalam Perjalanan
                                    </span>
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Informasi Penerima -->
            <div class="bg-white/80 glass rounded-2xl shadow-xl border border-white/20 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-cyan-600 p-6 text-white">
                    <h2 class="text-xl font-bold flex items-center gap-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Informasi Penerima
                    </h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Nama Penerima</label>
                            <div class="text-xl font-bold text-gray-900">{{ $pengiriman->nama_penerima }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">No. HP Penerima</label>
                            <div class="text-lg font-bold text-gray-900 flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                {{ $pengiriman->no_hp_penerima }}
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Alamat Tujuan</label>
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                <p class="text-gray-900 leading-relaxed">{{ $pengiriman->alamat_tujuan }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informasi Pemesanan -->
        <div class="bg-white/80 glass rounded-2xl shadow-xl border border-white/20 overflow-hidden mb-6">
            <div class="bg-gradient-to-r from-green-600 to-emerald-600 p-6 text-white">
                <h2 class="text-xl font-bold flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    Informasi Pemesanan
                </h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">ID Pemesanan</label>
                        <div class="text-lg font-bold text-gray-900">#{{ $pengiriman->pemesanan->id_pemesanan }}</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Customer</label>
                        <div class="text-lg font-bold text-gray-900">{{ $pengiriman->pemesanan->customer->nama_customer ?? 'N/A' }}</div>
                        <div class="text-sm text-gray-600">{{ $pengiriman->pemesanan->customer->email ?? '-' }}</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Total Pemesanan</label>
                        <div class="text-xl font-bold text-green-600">{{ $pengiriman->pemesanan->total_harga_formatted }}</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Status Pemesanan</label>
                        <div>
                            @php
                                $statusPemesananConfig = [
                                    'pending' => ['color' => 'bg-yellow-100 text-yellow-800', 'icon' => 'clock'],
                                    'diproses' => ['color' => 'bg-blue-100 text-blue-800', 'icon' => 'cog'],
                                    'dikirim' => ['color' => 'bg-purple-100 text-purple-800', 'icon' => 'shipping-fast'],
                                    'selesai' => ['color' => 'bg-green-100 text-green-800', 'icon' => 'check'],
                                    'batal' => ['color' => 'bg-red-100 text-red-800', 'icon' => 'times']
                                ];
                                $configPemesanan = $statusPemesananConfig[$pengiriman->pemesanan->status] ?? ['color' => 'bg-gray-100 text-gray-800', 'icon' => 'question'];
                            @endphp
                            <span class="inline-flex items-center gap-1 px-3 py-2 rounded-full text-sm font-medium {{ $configPemesanan['color'] }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="
                                        @if($configPemesanan['icon'] == 'clock') M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z
                                        @elseif($configPemesanan['icon'] == 'cog') M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z
                                        @elseif($configPemesanan['icon'] == 'shipping-fast') M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0
                                        @elseif($configPemesanan['icon'] == 'check') M5 13l4 4L19 7
                                        @elseif($configPemesanan['icon'] == 'times') M6 18L18 6M6 6l12 12
                                        @else M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z
                                    @endif
                                    "/>
                                </svg>
                                {{ ucfirst($pengiriman->pemesanan->status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Items Pemesanan -->
        <div class="bg-white/80 glass rounded-2xl shadow-xl border border-white/20 overflow-hidden mb-6">
            <div class="bg-gradient-to-r from-orange-500 to-yellow-500 p-6 text-white">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <h2 class="text-xl font-bold flex items-center gap-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        Items Pemesanan
                    </h2>
                    <span class="inline-flex items-center gap-2 bg-white/20 px-4 py-2 rounded-full text-sm font-bold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        {{ $pengiriman->pemesanan->detailPemesanan->count() }} Items
                    </span>
                </div>
            </div>
            <div class="p-0">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-6 py-4 text-left font-semibold text-gray-700">Produk</th>
                                <th class="px-6 py-4 text-left font-semibold text-gray-700">Ukuran</th>
                                <th class="px-6 py-4 text-center font-semibold text-gray-700">Harga Satuan</th>
                                <th class="px-6 py-4 text-center font-semibold text-gray-700">Jumlah</th>
                                <th class="px-6 py-4 text-right font-semibold text-gray-700">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($pengiriman->pemesanan->detailPemesanan as $detail)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="font-semibold text-gray-900">{{ $detail->detailUkuran->produk->nama_produk ?? 'N/A' }}</div>
                                            <div class="text-sm text-gray-500">SKU: {{ $detail->detailUkuran->produk->sku ?? '-' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                        {{ $detail->detailUkuran->ukuran ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="font-semibold text-gray-900">{{ $detail->harga_satuan_formatted }}</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="font-bold text-gray-900 text-lg">{{ $detail->jumlah }}</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="font-bold text-green-600 text-lg">{{ $detail->subtotal_formatted }}</div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="3"></td>
                                <td class="px-6 py-4 text-center font-bold text-gray-900 text-lg">Total Pemesanan:</td>
                                <td class="px-6 py-4 text-right">
                                    <div class="text-2xl font-bold text-blue-600">{{ $pengiriman->pemesanan->total_harga_formatted }}</div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3"></td>
                                <td class="px-6 py-4 text-center font-bold text-gray-900 text-lg">Biaya Ongkir:</td>
                                <td class="px-6 py-4 text-right">
                                    <div class="text-2xl font-bold text-yellow-600">{{ $pengiriman->biaya_ongkir_formatted }}</div>
                                </td>
                            </tr>
                            <tr class="bg-green-50">
                                <td colspan="3"></td>
                                <td class="px-6 py-4 text-center font-bold text-gray-900 text-lg">Total + Ongkir:</td>
                                <td class="px-6 py-4 text-right">
                                    <div class="text-3xl font-bold text-green-600">{{ $pengiriman->total_harga_dengan_ongkir_formatted }}</div>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        @if($pengiriman->isInTransit())
        <div class="bg-white/80 glass rounded-2xl shadow-xl border border-white/20 overflow-hidden">
            <div class="bg-gradient-to-r from-purple-600 to-pink-600 p-6 text-white">
                <h2 class="text-xl font-bold flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    Quick Actions
                </h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @if($pengiriman->status_pengiriman == 'menunggu' && !$pengiriman->no_resi)
                    <div>
                        <form action="{{ route('admin.pengiriman.update', $pengiriman->id_pengiriman) }}" method="POST" id="markShippedForm">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="mark_as_shipped" value="true">
                            <div class="mb-4">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor Resi</label>
                                <input type="text" name="no_resi" 
                                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-purple-200 focus:border-purple-400 transition-all duration-300" 
                                       placeholder="Masukkan nomor resi" 
                                       required>
                            </div>
                            <button type="submit" 
                                    class="w-full inline-flex items-center justify-center gap-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                                </svg>
                                Tandai Dikirim
                            </button>
                        </form>
                    </div>
                    @endif

                    @if($pengiriman->status_pengiriman == 'dikirim')
                    <div>
                        <form action="{{ route('admin.pengiriman.update', $pengiriman->id_pengiriman) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="update_only_status" value="true">
                            <input type="hidden" name="status_pengiriman" value="diterima">
                            <button type="button" 
                                    onclick="confirmStatusChange('diterima')"
                                    class="w-full h-full inline-flex items-center justify-center gap-2 bg-green-500 hover:bg-green-600 text-white font-semibold px-6 py-8 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Tandai Diterima
                            </button>
                        </form>
                    </div>
                    @endif

                    @if($pengiriman->isInTransit())
                    <div>
                        <form action="{{ route('admin.pengiriman.update', $pengiriman->id_pengiriman) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="update_only_status" value="true">
                            <input type="hidden" name="status_pengiriman" value="gagal">
                            <button type="button" 
                                    onclick="confirmStatusChange('gagal')"
                                    class="w-full h-full inline-flex items-center justify-center gap-2 bg-red-500 hover:bg-red-600 text-white font-semibold px-6 py-8 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Tandai Gagal
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Hidden Status Form -->
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

    // Confirm status change function
    function confirmStatusChange(status) {
        const statusText = {
            'diterima': 'diterima',
            'gagal': 'gagal'
        }[status] || status;

        Swal.fire({
            title: 'Konfirmasi Status',
            html: `Tandai pengiriman <strong>#{{ $pengiriman->id_pengiriman }}</strong> sebagai <strong>${statusText}</strong>?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: status === 'diterima' ? '#10b981' : '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Update!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Set form action and submit
                const form = document.getElementById('statusForm');
                form.action = `{{ route('admin.pengiriman.update', $pengiriman->id_pengiriman) }}`;
                form.querySelector('input[name="status_pengiriman"]').value = status;
                form.submit();
            }
        });
    }

    // Form validation for mark as shipped
    const markShippedForm = document.getElementById('markShippedForm');
    if (markShippedForm) {
        markShippedForm.addEventListener('submit', function(e) {
            const resiInput = this.querySelector('input[name="no_resi"]');
            if (!resiInput.value.trim()) {
                e.preventDefault();
                Swal.fire({
                    title: 'Nomor Resi Diperlukan',
                    text: 'Harap masukkan nomor resi sebelum menandai sebagai dikirim.',
                    icon: 'warning',
                    confirmButtonColor: '#3b82f6'
                });
            }
        });
    }
</script>

<style>
    .swal2-popup {
        font-family: 'Inter', sans-serif;
        border-radius: 1rem;
    }
</style>
@endpush
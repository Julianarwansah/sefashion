@extends('layouts.app')

@section('title', 'Detail Pembayaran - Konveksi')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-50 via-pink-50 to-yellow-50 p-4 lg:p-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header Section -->
        <div class="mb-8">
            <!-- Breadcrumb -->
            <nav class="mb-4" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2 text-sm">
                    <li>
                        <a href="{{ route('admin.pembayaran.index') }}" class="text-purple-600 hover:text-purple-700 transition-colors duration-200">
                            Pembayaran
                        </a>
                    </li>
                    <li class="flex items-center">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                        <span class="ml-2 text-gray-500">Detail #{{ $pembayaran->id_pembayaran }}</span>
                    </li>
                </ol>
            </nav>

            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-2">
                        Detail Pembayaran
                    </h1>
                    <p class="text-gray-600">#{{ $pembayaran->id_pembayaran }} - {{ $pembayaran->pemesanan->customer->nama_customer ?? 'N/A' }}</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('admin.pembayaran.index') }}" 
                       class="inline-flex items-center gap-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-semibold px-5 py-2.5 rounded-xl shadow-sm transition-all duration-300 transform hover:scale-105">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Kembali
                    </a>
                    <a href="{{ route('admin.pembayaran.edit', $pembayaran->id_pembayaran) }}" 
                       class="inline-flex items-center gap-2 bg-gradient-to-r from-yellow-500 to-orange-500 hover:from-yellow-600 hover:to-orange-600 text-white font-semibold px-5 py-2.5 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit
                    </a>
                </div>
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
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Informasi Pembayaran Card -->
            <div class="bg-white/80 glass rounded-2xl shadow-xl border border-white/20 overflow-hidden">
                <div class="bg-gradient-to-r from-purple-600 to-pink-600 px-6 py-4">
                    <h2 class="text-lg font-semibold text-white flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Informasi Pembayaran
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">ID Pembayaran</label>
                            <div class="text-lg font-semibold text-gray-900">#{{ $pembayaran->id_pembayaran }}</div>
                            @if($pembayaran->external_id)
                                <div class="text-sm text-gray-500 mt-1">{{ $pembayaran->external_id }}</div>
                            @endif
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Tanggal Pembayaran</label>
                            <div class="text-lg font-semibold text-gray-900">{{ $pembayaran->tanggal_pembayaran_formatted }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Metode Pembayaran</label>
                            <div class="flex items-center gap-2">
                                @php
                                    $methodIcons = [
                                        'transfer' => 'university',
                                        'cod' => 'money-bill',
                                        'ewallet' => 'mobile-alt',
                                        'va' => 'credit-card',
                                        'credit_card' => 'credit-card'
                                    ];
                                    $icon = $methodIcons[$pembayaran->metode_pembayaran] ?? 'money-bill';
                                @endphp
                                <span class="inline-flex items-center gap-1 bg-blue-100 text-blue-800 text-sm font-medium px-3 py-1 rounded-full">
                                    <i class="fas fa-{{ $icon }} text-xs"></i>
                                    {{ $pembayaran->metode_pembayaran_text }}
                                </span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Channel</label>
                            <div class="text-lg font-semibold text-gray-900">{{ $pembayaran->channel ?? '-' }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Status</label>
                            @php
                                $statusColors = [
                                    'belum_bayar' => 'yellow',
                                    'menunggu' => 'blue',
                                    'sudah_bayar' => 'green',
                                    'gagal' => 'red',
                                    'expired' => 'gray',
                                    'refund' => 'purple'
                                ];
                                $statusIcons = [
                                    'belum_bayar' => 'clock',
                                    'menunggu' => 'hourglass-half',
                                    'sudah_bayar' => 'check',
                                    'gagal' => 'times',
                                    'expired' => 'calendar-times',
                                    'refund' => 'undo'
                                ];
                                $color = $statusColors[$pembayaran->status_pembayaran] ?? 'gray';
                                $icon = $statusIcons[$pembayaran->status_pembayaran] ?? 'question';
                            @endphp
                            <span class="inline-flex items-center gap-1 bg-{{ $color }}-100 text-{{ $color }}-800 text-sm font-medium px-3 py-1 rounded-full">
                                <i class="fas fa-{{ $icon }} text-xs"></i>
                                {{ $pembayaran->status_pembayaran_text }}
                            </span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Jumlah Bayar</label>
                            <div class="text-2xl font-bold text-green-600">{{ $pembayaran->jumlah_bayar_formatted }}</div>
                        </div>
                        @if($pembayaran->invoice_id)
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-500 mb-1">Invoice ID</label>
                            <div class="text-lg font-semibold text-gray-900">{{ $pembayaran->invoice_id }}</div>
                        </div>
                        @endif
                        @if($pembayaran->payment_url)
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-500 mb-1">Payment URL</label>
                            <a href="{{ $pembayaran->payment_url }}" target="_blank" 
                               class="inline-flex items-center gap-2 text-purple-600 hover:text-purple-700 transition-colors duration-200">
                                <span class="truncate max-w-xs">{{ $pembayaran->payment_url }}</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Informasi Pemesanan Card -->
            <div class="bg-white/80 glass rounded-2xl shadow-xl border border-white/20 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-cyan-600 px-6 py-4">
                    <h2 class="text-lg font-semibold text-white flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        Informasi Pemesanan
                    </h2>
                </div>
                <div class="p-6">
                    <div class="space-y-6">
                        <!-- ID Pemesanan -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-3">ID Pemesanan</label>
                            <div class="flex items-center gap-4">
                                <div class="bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center" 
                                     style="width: 60px; height: 60px;">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900">#{{ $pembayaran->pemesanan->id_pemesanan }}</h3>
                                    <p class="text-gray-600">Total: {{ $pembayaran->pemesanan->total_harga_formatted }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Customer -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-3">Customer</label>
                            <div class="flex items-center gap-4">
                                <div class="bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center" 
                                     style="width: 50px; height: 50px;">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-900">{{ $pembayaran->pemesanan->customer->nama_customer ?? 'N/A' }}</h4>
                                    <p class="text-gray-600">{{ $pembayaran->pemesanan->customer->email ?? '-' }}</p>
                                    @if($pembayaran->pemesanan->customer->telepon ?? false)
                                        <p class="text-gray-600 flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                            </svg>
                                            {{ $pembayaran->pemesanan->customer->telepon }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Status & Tanggal -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Status Pemesanan</label>
                                @php
                                    $statusPemesananColors = [
                                        'pending' => 'yellow',
                                        'diproses' => 'blue',
                                        'dikirim' => 'indigo',
                                        'selesai' => 'green',
                                        'batal' => 'red'
                                    ];
                                    $statusPemesananIcons = [
                                        'pending' => 'clock',
                                        'diproses' => 'cog',
                                        'dikirim' => 'shipping-fast',
                                        'selesai' => 'check',
                                        'batal' => 'times'
                                    ];
                                    $colorPemesanan = $statusPemesananColors[$pembayaran->pemesanan->status] ?? 'gray';
                                    $iconPemesanan = $statusPemesananIcons[$pembayaran->pemesanan->status] ?? 'question';
                                @endphp
                                <span class="inline-flex items-center gap-1 bg-{{ $colorPemesanan }}-100 text-{{ $colorPemesanan }}-800 text-sm font-medium px-3 py-1 rounded-full">
                                    <i class="fas fa-{{ $iconPemesanan }} text-xs"></i>
                                    {{ ucfirst($pembayaran->pemesanan->status) }}
                                </span>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Tanggal Pemesanan</label>
                                <div class="text-lg font-semibold text-gray-900">{{ $pembayaran->pemesanan->tanggal_pemesanan_formatted }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Items Pemesanan Card -->
        <div class="bg-white/80 glass rounded-2xl shadow-xl border border-white/20 overflow-hidden mb-6">
            <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-4">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <h2 class="text-lg font-semibold text-white flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        Items Pemesanan
                    </h2>
                    <span class="inline-flex items-center gap-1 bg-white/20 text-white text-sm font-medium px-3 py-1 rounded-full">
                        {{ $pembayaran->pemesanan->detailPemesanan->count() }} Items
                    </span>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ukuran</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Harga Satuan</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($pembayaran->pemesanan->detailPemesanan as $detail)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="bg-gradient-to-r from-gray-100 to-gray-200 rounded-lg flex items-center justify-center" 
                                         style="width: 40px; height: 40px;">
                                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-sm font-semibold text-gray-900">{{ $detail->detailUkuran->produk->nama_produk ?? 'N/A' }}</div>
                                        <div class="text-xs text-gray-500">SKU: {{ $detail->detailUkuran->produk->sku ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ $detail->detailUkuran->ukuran ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-semibold text-gray-900">
                                {{ $detail->harga_satuan_formatted }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="inline-flex items-center justify-center w-8 h-8 bg-blue-100 text-blue-800 text-sm font-semibold rounded-full">
                                    {{ $detail->jumlah }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-semibold text-green-600">
                                {{ $detail->subtotal_formatted }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td colspan="3" class="px-6 py-4"></td>
                            <td class="px-6 py-4 text-center text-sm font-semibold text-gray-900">Total Pemesanan:</td>
                            <td class="px-6 py-4 text-right text-lg font-bold text-blue-600">
                                {{ $pembayaran->pemesanan->total_harga_formatted }}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3" class="px-6 py-4"></td>
                            <td class="px-6 py-4 text-center text-sm font-semibold text-gray-900">Jumlah Bayar:</td>
                            <td class="px-6 py-4 text-right text-lg font-bold text-green-600">
                                {{ $pembayaran->jumlah_bayar_formatted }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Update Status Card -->
        <div class="bg-white/80 glass rounded-2xl shadow-xl border border-white/20 overflow-hidden">
            <div class="bg-gradient-to-r from-orange-500 to-amber-600 px-6 py-4">
                <h2 class="text-lg font-semibold text-white flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Update Status Pembayaran
                </h2>
            </div>
            <div class="p-6">
                <form action="{{ route('admin.pembayaran.update-status', $pembayaran->id_pembayaran) }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 items-end">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Ubah Status</label>
                            <select name="status_pembayaran" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200" required>
                                <option value="belum_bayar" {{ $pembayaran->status_pembayaran == 'belum_bayar' ? 'selected' : '' }}>Belum Bayar</option>
                                <option value="menunggu" {{ $pembayaran->status_pembayaran == 'menunggu' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                                <option value="sudah_bayar" {{ $pembayaran->status_pembayaran == 'sudah_bayar' ? 'selected' : '' }}>Sudah Bayar</option>
                                <option value="gagal" {{ $pembayaran->status_pembayaran == 'gagal' ? 'selected' : '' }}>Gagal</option>
                                <option value="expired" {{ $pembayaran->status_pembayaran == 'expired' ? 'selected' : '' }}>Kadaluarsa</option>
                                <option value="refund" {{ $pembayaran->status_pembayaran == 'refund' ? 'selected' : '' }}>Refund</option>
                            </select>
                        </div>
                        <div>
                            <button type="submit" 
                                    class="w-full bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-semibold px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Update Status
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Quick Actions -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h3 class="text-sm font-medium text-gray-700 mb-3">Quick Actions</h3>
                    <div class="flex flex-wrap gap-3">
                        @if($pembayaran->isPending())
                        <form action="{{ route('admin.pembayaran.mark-paid', $pembayaran->id_pembayaran) }}" method="POST" class="inline">
                            @csrf
                            <button type="button" 
                                    onclick="confirmMarkPaid('{{ $pembayaran->id_pembayaran }}', this)"
                                    class="inline-flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white font-medium px-4 py-2 rounded-lg transition-all duration-200 transform hover:scale-105">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Tandai Sudah Bayar
                            </button>
                        </form>
                        @endif
                        
                        @if(!$pembayaran->isExpired() && !$pembayaran->isFailed())
                        <form action="{{ route('admin.pembayaran.mark-expired', $pembayaran->id_pembayaran) }}" method="POST" class="inline">
                            @csrf
                            <button type="button" 
                                    onclick="confirmMarkExpired('{{ $pembayaran->id_pembayaran }}', this)"
                                    class="inline-flex items-center gap-2 bg-gray-500 hover:bg-gray-600 text-white font-medium px-4 py-2 rounded-lg transition-all duration-200 transform hover:scale-105">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Tandai Kadaluarsa
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
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

    // Confirm mark as paid function
    function confirmMarkPaid(paymentId, button) {
        Swal.fire({
            title: 'Konfirmasi Pembayaran',
            html: `Tandai pembayaran <strong>#${paymentId}</strong> sebagai sudah dibayar?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Tandai Sudah Bayar',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: {
                confirmButton: 'bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded-lg mr-2',
                cancelButton: 'bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                button.closest('form').submit();
            }
        });
    }

    // Confirm mark as expired function
    function confirmMarkExpired(paymentId, button) {
        Swal.fire({
            title: 'Konfirmasi Kadaluarsa',
            html: `Tandai pembayaran <strong>#${paymentId}</strong> sebagai kadaluarsa?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#6b7280',
            cancelButtonColor: '#ef4444',
            confirmButtonText: 'Ya, Tandai Kadaluarsa',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: {
                confirmButton: 'bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg mr-2',
                cancelButton: 'bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded-lg'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                button.closest('form').submit();
            }
        });
    }

    // Add smooth animations
    document.addEventListener('DOMContentLoaded', function() {
        // Animate cards
        const cards = document.querySelectorAll('.glass');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                card.style.transition = 'all 0.5s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });
    });
</script>

<!-- Include SweetAlert2 for beautiful confirm dialogs -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    .swal2-popup {
        font-family: 'Inter', sans-serif;
        border-radius: 1rem;
    }
</style>
@endsection
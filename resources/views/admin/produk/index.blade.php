@extends('layouts.app')

@section('title', 'Manajemen Produk - Konveksi')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-50 via-pink-50 to-yellow-50 p-4 lg:p-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-2">Manajemen Produk</h1>
                    <p class="text-gray-600">Daftar semua produk konveksi</p>
                </div>
                <a href="{{ route('admin.produk.create') }}" 
                   class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-semibold px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Tambah Produk
                </a>
            </div>
        </div>

            <!-- Search and Filter Card -->
    <div class="bg-white/80 glass rounded-2xl shadow-xl border border-white/20 overflow-hidden mb-6">
        <div class="px-6 py-4 bg-gradient-to-r from-purple-600 to-pink-600 text-white">
            <h2 class="text-xl font-bold">Pencarian & Filter Produk</h2>
        </div>
        <div class="p-6">
            <form id="searchForm" action="{{ route('admin.produk.search') }}" method="GET" class="space-y-4">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
                    <!-- Search Input -->
                    <div class="lg:col-span-4">
                        <label for="search" class="block text-sm font-semibold text-gray-700 mb-2">
                            Cari Produk
                        </label>
                        <div class="relative">
                            <input type="text" 
                                   id="search" 
                                   name="search" 
                                   value="{{ request('search') }}"
                                   class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200"
                                   placeholder="Cari berdasarkan nama, deskripsi, kategori..."
                                   maxlength="100">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Category Filter -->
                    <div class="lg:col-span-3">
                        <label for="kategori" class="block text-sm font-semibold text-gray-700 mb-2">
                            Filter Kategori
                        </label>
                        <select id="kategori" 
                                name="kategori" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200">
                            <option value="semua">Semua Kategori</option>
                            @foreach($kategoriList ?? [] as $kat)
                                <option value="{{ $kat }}" {{ request('kategori') == $kat ? 'selected' : '' }}>
                                    {{ $kat }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Sort By -->
                    <div class="lg:col-span-3">
                        <label for="sort_by" class="block text-sm font-semibold text-gray-700 mb-2">
                            Urutkan Berdasarkan
                        </label>
                        <select id="sort_by" 
                                name="sort_by" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200">
                            <option value="terbaru" {{ request('sort_by', 'terbaru') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                            <option value="terlama" {{ request('sort_by') == 'terlama' ? 'selected' : '' }}>Terlama</option>
                            <option value="nama_asc" {{ request('sort_by') == 'nama_asc' ? 'selected' : '' }}>Nama A-Z</option>
                            <option value="nama_desc" {{ request('sort_by') == 'nama_desc' ? 'selected' : '' }}>Nama Z-A</option>
                            <option value="stok_terbanyak" {{ request('sort_by') == 'stok_terbanyak' ? 'selected' : '' }}>Stok Terbanyak</option>
                            <option value="stok_tersedikit" {{ request('sort_by') == 'stok_tersedikit' ? 'selected' : '' }}>Stok Tersedikit</option>
                        </select>
                    </div>

                    <!-- Action Buttons -->
                    <div class="lg:col-span-2 flex items-end space-x-2">
                        <button type="submit" 
                                class="w-full bg-gradient-to-r from-green-500 to-blue-500 hover:from-green-600 hover:to-blue-600 text-white font-semibold py-3 px-4 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Cari
                        </button>
                        
                        <a href="{{ route('admin.produk.index') }}" 
                           class="w-full bg-gray-500 hover:bg-gray-600 text-white font-semibold py-3 px-4 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 flex items-center justify-center gap-2"
                           title="Reset pencarian">
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
    @if(request()->has('search') || request()->has('kategori') || request()->has('sort_by'))
        <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-xl">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-blue-800 font-medium">
                        Menampilkan 
                        @if(request('search'))
                            hasil pencarian "<strong>{{ request('search') }}</strong>"
                        @endif
                        @if(request('kategori') && request('kategori') !== 'semua')
                            dalam kategori "<strong>{{ request('kategori') }}</strong>"
                        @endif
                        ({{ $produk->total() }} hasil ditemukan)
                    </span>
                </div>
                <a href="{{ route('admin.produk.index') }}" 
                   class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    Tampilkan semua produk
                </a>
            </div>
        </div>
    @endif

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
            @if($produk->isEmpty())
                <!-- Empty State -->
                <div class="text-center py-16 px-4">
                    <div class="w-24 h-24 mx-auto mb-4 bg-gradient-to-br from-purple-100 to-pink-100 rounded-full flex items-center justify-center">
                        <svg class="w-12 h-12 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">Belum ada produk</h3>
                    <p class="text-gray-500 mb-6">Mulai dengan menambahkan produk baru</p>
                    <a href="{{ route('admin.produk.create') }}" 
                       class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-semibold px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Tambah Produk Pertama
                    </a>
                </div>
            @else
                <!-- Table Section -->
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gradient-to-r from-purple-600 to-pink-600 text-white">
                                <th class="px-6 py-4 text-left font-semibold">#</th>
                                <th class="px-6 py-4 text-left font-semibold">Produk</th>
                                <th class="px-6 py-4 text-left font-semibold">Kategori</th>
                                <th class="px-6 py-4 text-left font-semibold">Varian</th>
                                <th class="px-6 py-4 text-left font-semibold">Stok</th>
                                <th class="px-6 py-4 text-left font-semibold">Harga</th>
                                <th class="px-6 py-4 text-center font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($produk as $item)
                            <tr class="hover:bg-gradient-to-r hover:from-purple-50 hover:to-pink-50 transition-colors duration-200">
                                <!-- No. Urut -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-900">{{ $loop->iteration }}</div>
                                </td>

                                <!-- Produk -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-500 rounded-xl flex items-center justify-center text-white overflow-hidden">
                                            @if($item->gambar_url)
                                                <img src="{{ $item->gambar_url }}" alt="{{ $item->nama_produk }}" 
                                                     class="w-full h-full object-cover">
                                            @else
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                                </svg>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold text-gray-900">{{ $item->nama_produk }}</div>
                                            <div class="text-xs text-gray-500 line-clamp-2">
                                                {{ Str::limit($item->deskripsi, 50) }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Kategori -->
                                <td class="px-6 py-4">
                                    @if($item->kategori)
                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                            </svg>
                                            {{ $item->kategori }}
                                        </span>
                                    @else
                                        <span class="text-sm text-gray-400">-</span>
                                    @endif
                                </td>

                                <!-- Varian -->
                                <td class="px-6 py-4">
                                    <div class="space-y-2">
                                        <!-- Warna -->
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($item->detailWarna as $warna)
                                                <div class="flex items-center gap-1" title="{{ $warna->nama_warna }}">
                                                    <div class="w-4 h-4 rounded-full border border-gray-300" 
                                                         style="background-color: {{ $warna->kode_warna_hex ?? '#ccc' }}"></div>
                                                    <span class="text-xs text-gray-600">{{ $warna->nama_warna }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                        <!-- Ukuran -->
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($item->detailUkuran->unique('ukuran') as $ukuran)
                                                <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-gray-100 text-gray-800">
                                                    {{ $ukuran->ukuran }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                </td>

                                <!-- Stok -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-sm font-medium {{ $item->total_stok > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                                            </svg>
                                            {{ $item->total_stok }}
                                        </span>
                                    </div>
                                </td>

                                <!-- Harga -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $hargaTerendah = $item->detailUkuran->min('harga');
                                        $hargaTertinggi = $item->detailUkuran->max('harga');
                                    @endphp
                                    <div class="text-right">
                                        <div class="text-lg font-bold text-green-600">
                                            Rp {{ number_format($hargaTerendah, 0, ',', '.') }}
                                        </div>
                                        @if($hargaTertinggi != $hargaTerendah)
                                            <div class="text-xs text-gray-500">
                                                hingga Rp {{ number_format($hargaTertinggi, 0, ',', '.') }}
                                            </div>
                                        @endif
                                    </div>
                                </td>

                                <!-- Actions -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-2">
                                        <!-- Detail Button -->
                                        <a href="{{ route('admin.produk.show', $item->id_produk) }}" 
                                           class="inline-flex items-center gap-1 bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200 transform hover:scale-105"
                                           title="Lihat Detail">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>
                                        
                                        <!-- Edit Button -->
                                        <a href="{{ route('admin.produk.edit', $item->id_produk) }}" 
                                           class="inline-flex items-center gap-1 bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200 transform hover:scale-105"
                                           title="Edit Produk">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>

                                        <!-- Delete Button -->
                                        <button type="button" 
                                                onclick="confirmDelete('{{ $item->id_produk }}', '{{ $item->nama_produk }}')"
                                                class="inline-flex items-center gap-1 bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200 transform hover:scale-105"
                                                title="Hapus Produk">
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
                            Menampilkan <span class="font-semibold">{{ $produk->count() }}</span> produk
                        </p>
                        <div class="flex flex-wrap gap-2">
                            @php
                                $totalStok = $produk->sum('total_stok');
                                $totalVarian = $produk->sum(function($item) {
                                    return $item->detailWarna->count() * $item->detailUkuran->unique('ukuran')->count();
                                });
                            @endphp
                            <span class="inline-flex items-center gap-1 bg-purple-100 text-purple-800 text-xs font-semibold px-3 py-1 rounded-full">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                                Total: {{ $produk->count() }} produk
                            </span>
                            <span class="inline-flex items-center gap-1 bg-green-100 text-green-800 text-xs font-semibold px-3 py-1 rounded-full">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                                </svg>
                                Stok: {{ $totalStok }} item
                            </span>
                            <span class="inline-flex items-center gap-1 bg-blue-100 text-blue-800 text-xs font-semibold px-3 py-1 rounded-full">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                                </svg>
                                Varian: {{ $totalVarian }}
                            </span>
                        </div>
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
    function confirmDelete(produkId, produkNama) {
        Swal.fire({
            title: 'Yakin ingin menghapus?',
            html: `Produk <strong>${produkNama}</strong> akan dihapus permanen.`,
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
                form.action = `{{ url('admin/produk') }}/${produkId}`;
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
    const searchForm = document.getElementById('searchForm');
    const searchInput = document.getElementById('search');
    const kategoriSelect = document.getElementById('kategori');
    const sortSelect = document.getElementById('sort_by');
    const produkTableContainer = document.getElementById('produkTableContainer');
    
    let searchTimeout;
    
    // Real-time search dengan debounce
    searchInput.addEventListener('input', function(e) {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            performSearch();
        }, 500);
    });
    
    // Instant search untuk select changes
    kategoriSelect.addEventListener('change', performSearch);
    sortSelect.addEventListener('change', performSearch);
    
    function performSearch() {
        const formData = new FormData(searchForm);
        
        // Show loading
        produkTableContainer.innerHTML = `
            <div class="flex justify-center items-center py-12">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-purple-600"></div>
                <span class="ml-3 text-gray-600">Mencari produk...</span>
            </div>
        `;
        
        fetch(searchForm.action + '?' + new URLSearchParams(formData), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                produkTableContainer.innerHTML = data.html;
                
                // Update pagination jika ada
                if (data.pagination) {
                    const existingPagination = document.querySelector('.pagination');
                    if (existingPagination) {
                        existingPagination.innerHTML = data.pagination;
                    }
                }
                
                // Update results info
                updateResultsInfo(data.total);
            } else {
                produkTableContainer.innerHTML = `
                    <div class="text-center py-8 text-red-600">
                        <svg class="w-12 h-12 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                        <p>Terjadi kesalahan saat mencari produk.</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Search error:', error);
            produkTableContainer.innerHTML = `
                <div class="text-center py-8 text-red-600">
                    <p>Terjadi kesalahan saat mencari produk.</p>
                </div>
            `;
        });
    }
    
    function updateResultsInfo(total) {
        // Implement update results info jika diperlukan
        console.log('Total results:', total);
    }
    
    // Handle pagination clicks (untuk AJAX pagination)
    document.addEventListener('click', function(e) {
        if (e.target.closest('.pagination a')) {
            e.preventDefault();
            const url = e.target.closest('a').href;
            
            produkTableContainer.innerHTML = `
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
                    produkTableContainer.innerHTML = data.html;
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
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endpush
@extends('layouts.app')

@section('title', 'Detail Produk - ' . $produk->nama_produk)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-50 via-pink-50 to-yellow-50 p-4 lg:p-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-2">Detail Produk</h1>
                    <p class="text-gray-600">{{ $produk->nama_produk }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.produk.index') }}" 
                       class="inline-flex items-center gap-2 bg-gray-500 hover:bg-gray-600 text-white font-semibold px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Kembali
                    </a>
                    <a href="{{ route('admin.produk.edit', $produk->id_produk) }}" 
                       class="inline-flex items-center gap-2 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit Produk
                    </a>
                    <button type="button" 
                            onclick="confirmDelete('{{ $produk->id_produk }}', '{{ $produk->nama_produk }}')"
                            class="inline-flex items-center gap-2 bg-red-500 hover:bg-red-600 text-white font-semibold px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Hapus
                    </button>
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

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Product Information Card -->
                <div class="bg-white/80 glass rounded-2xl shadow-xl border border-white/20 overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-purple-600 to-pink-600 text-white">
                        <h2 class="text-xl font-bold">Informasi Produk</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Product Image -->
                            <div class="flex justify-center">
                                <div class="w-64 h-64 bg-gradient-to-br from-blue-500 to-purple-500 rounded-2xl flex items-center justify-center text-white overflow-hidden shadow-lg">
                                    @if($produk->gambar_url)
                                        <img src="{{ $produk->gambar_url }}" alt="{{ $produk->nama_produk }}" 
                                             class="w-full h-full object-cover">
                                    @else
                                        <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                        </svg>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Product Details -->
                            <div class="space-y-4">
                                <div>
                                    <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $produk->nama_produk }}</h3>
                                    @if($produk->kategori)
                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                            </svg>
                                            {{ $produk->kategori }}
                                        </span>
                                    @endif
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div class="text-center p-4 bg-gradient-to-br from-green-50 to-blue-50 rounded-xl">
                                        <div class="text-2xl font-bold text-green-600">{{ $produk->total_stok }}</div>
                                        <div class="text-sm text-gray-600">Total Stok</div>
                                    </div>
                                    <div class="text-center p-4 bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl">
                                        <div class="text-2xl font-bold text-purple-600">{{ $produk->detailWarna->count() }}</div>
                                        <div class="text-sm text-gray-600">Varian Warna</div>
                                    </div>
                                </div>

                                <div>
                                    <h4 class="font-semibold text-gray-900 mb-2">Deskripsi</h4>
                                    <p class="text-gray-600 leading-relaxed">
                                        {{ $produk->deskripsi ?: 'Tidak ada deskripsi' }}
                                    </p>
                                </div>

                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <span class="font-semibold text-gray-700">Dibuat:</span>
                                        <p class="text-gray-600">{{ $produk->created_at->translatedFormat('d F Y H:i') }}</p>
                                    </div>
                                    <div>
                                        <span class="font-semibold text-gray-700">Diupdate:</span>
                                        <p class="text-gray-600">{{ $produk->updated_at->translatedFormat('d F Y H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Variants & Stock Card -->
                <div class="bg-white/80 glass rounded-2xl shadow-xl border border-white/20 overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-purple-600 to-pink-600 text-white">
                        <h2 class="text-xl font-bold">Varian & Stok Produk</h2>
                    </div>
                    <div class="p-6">
                        @if($produk->detailWarna->count() > 0)
                            <div class="space-y-6">
                                @foreach($produk->detailWarna as $warna)
                                    <div class="border border-gray-200 rounded-xl p-4 hover:shadow-lg transition-shadow duration-300">
                                        <!-- Warna Header -->
                                        <div class="flex items-center gap-3 mb-4">
                                            <div class="w-8 h-8 rounded-full border-2 border-white shadow-lg" 
                                                 style="background-color: {{ $warna->kode_warna_hex ?? '#ccc' }}"></div>
                                            <div>
                                                <h4 class="font-bold text-gray-900">{{ $warna->nama_warna }}</h4>
                                                <p class="text-sm text-gray-500">Kode: {{ $warna->kode_warna ?? '-' }}</p>
                                            </div>
                                            <span class="ml-auto inline-flex items-center gap-1 px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                                                </svg>
                                                Stok: {{ $warna->total_stok }}
                                            </span>
                                        </div>

                                        <!-- Ukuran Table -->
                                        @if($warna->detailUkuran->count() > 0)
                                            <div class="overflow-x-auto">
                                                <table class="w-full">
                                                    <thead>
                                                        <tr class="bg-gray-50">
                                                            <th class="px-4 py-2 text-left font-semibold text-gray-700">Ukuran</th>
                                                            <th class="px-4 py-2 text-left font-semibold text-gray-700">Harga</th>
                                                            <th class="px-4 py-2 text-left font-semibold text-gray-700">Stok</th>
                                                            <th class="px-4 py-2 text-left font-semibold text-gray-700">Keterangan</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="divide-y divide-gray-200">
                                                        @foreach($warna->detailUkuran as $ukuran)
                                                            <tr class="{{ $ukuran->stok == 0 ? 'bg-red-50' : 'hover:bg-gray-50' }} transition-colors duration-200">
                                                                <td class="px-4 py-3">
                                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                                                        {{ $ukuran->ukuran }}
                                                                    </span>
                                                                </td>
                                                                <td class="px-4 py-3 font-semibold text-green-600">
                                                                    Rp {{ number_format($ukuran->harga, 0, ',', '.') }}
                                                                </td>
                                                                <td class="px-4 py-3">
                                                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-sm font-medium {{ $ukuran->stok == 0 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                                                        {{ $ukuran->stok }}
                                                                    </span>
                                                                </td>
                                                                <td class="px-4 py-3 text-gray-600">
                                                                    {{ $ukuran->tambahan ?: '-' }}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                            <div class="text-center py-8 text-gray-500">
                                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                                Tidak ada ukuran untuk warna ini.
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
                                <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-br from-purple-100 to-pink-100 rounded-full flex items-center justify-center">
                                    <svg class="w-8 h-8 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-700 mb-2">Belum ada varian</h3>
                                <p class="text-gray-500">Produk ini belum memiliki varian warna dan ukuran.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Images Card -->
                <div class="bg-white/80 glass rounded-2xl shadow-xl border border-white/20 overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-purple-600 to-pink-600 text-white">
                        <h2 class="text-xl font-bold">Gambar Produk</h2>
                    </div>
                    <div class="p-6">
                        @if($produk->gambarProduk->count() > 0)
                            <div class="grid grid-cols-2 gap-4">
                                @foreach($produk->gambarProduk as $gambar)
                                    <div class="relative group">
                                        <div class="aspect-square bg-gradient-to-br from-blue-500 to-purple-500 rounded-xl overflow-hidden shadow-lg">
                                            <img src="{{ $gambar->gambar_url }}" 
                                                 alt="Gambar Produk" 
                                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                        </div>
                                        @if($gambar->is_primary)
                                            <span class="absolute top-2 left-2 inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium bg-yellow-500 text-white">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                                Utama
                                            </span>
                                        @endif
                                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-300 rounded-xl flex items-center justify-center opacity-0 group-hover:opacity-100">
                                            <div class="flex gap-2">
                                                @if(!$gambar->is_primary)
                                                    <form action="{{ route('admin.produk.set-primary-image', ['produk' => $produk->id_produk, 'gambar' => $gambar->id_gambar]) }}" 
                                                          method="POST">
                                                        @csrf
                                                        <button type="submit" 
                                                                class="inline-flex items-center gap-1 bg-yellow-500 hover:bg-yellow-600 text-white p-2 rounded-lg text-xs font-medium transition-colors duration-200"
                                                                title="Jadikan gambar utama">
                                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                @endif
                                                <form action="{{ route('admin.produk.delete-image', ['produk' => $produk->id_produk, 'gambar' => $gambar->id_gambar]) }}" 
                                                      method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            onclick="return confirm('Hapus gambar ini?')"
                                                            class="inline-flex items-center gap-1 bg-red-500 hover:bg-red-600 text-white p-2 rounded-lg text-xs font-medium transition-colors duration-200"
                                                            title="Hapus gambar">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8 text-gray-500">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Belum ada gambar tambahan.
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Price Summary Card -->
                <div class="bg-white/80 glass rounded-2xl shadow-xl border border-white/20 overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-purple-600 to-pink-600 text-white">
                        <h2 class="text-xl font-bold">Ringkasan Harga</h2>
                    </div>
                    <div class="p-6">
                        @if($produk->detailUkuran->count() > 0)
                            @php
                                $hargaTertinggi = $produk->detailUkuran->max('harga');
                                $hargaTerendah = $produk->detailUkuran->min('harga');
                            @endphp
                            
                            <div class="text-center mb-4">
                                <div class="text-3xl font-bold text-green-600 mb-2">
                                    Rp {{ number_format($hargaTerendah, 0, ',', '.') }}
                                </div>
                                <div class="text-sm text-gray-600">Harga mulai dari</div>
                            </div>
                            
                            @if($hargaTertinggi != $hargaTerendah)
                                <div class="space-y-2">
                                    <div class="flex justify-between text-sm text-gray-600">
                                        <span>Terendah</span>
                                        <span>Tertinggi</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-gradient-to-r from-green-500 to-purple-500 h-2 rounded-full"></div>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-green-600 font-semibold">Rp {{ number_format($hargaTerendah, 0, ',', '.') }}</span>
                                        <span class="text-purple-600 font-semibold">Rp {{ number_format($hargaTertinggi, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            @endif
                        @else
                            <div class="text-center py-4 text-gray-500">
                                <svg class="w-8 h-8 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Belum ada informasi harga
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Statistics Card -->
                <div class="bg-white/80 glass rounded-2xl shadow-xl border border-white/20 overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-purple-600 to-pink-600 text-white">
                        <h2 class="text-xl font-bold">Statistik Produk</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-center p-4 bg-gradient-to-br from-blue-50 to-purple-50 rounded-xl">
                                <div class="text-2xl font-bold text-blue-600">{{ $produk->detailWarna->count() }}</div>
                                <div class="text-sm text-gray-600">Varian Warna</div>
                            </div>
                            <div class="text-center p-4 bg-gradient-to-br from-green-50 to-blue-50 rounded-xl">
                                <div class="text-2xl font-bold text-green-600">{{ $produk->detailUkuran->count() }}</div>
                                <div class="text-sm text-gray-600">Varian Ukuran</div>
                            </div>
                            <div class="text-center p-4 bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl">
                                <div class="text-2xl font-bold text-purple-600">{{ $produk->gambarProduk->count() }}</div>
                                <div class="text-sm text-gray-600">Total Gambar</div>
                            </div>
                            <div class="text-center p-4 bg-gradient-to-br from-yellow-50 to-orange-50 rounded-xl">
                                <div class="text-2xl font-bold text-orange-600">{{ $produk->total_stok }}</div>
                                <div class="text-sm text-gray-600">Total Stok</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
            html: `Produk <strong>${produkNama}</strong> akan dihapus permanen beserta semua varian dan gambar.`,
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
</script>

<style>
    .swal2-popup {
        font-family: 'Inter', sans-serif;
        border-radius: 1rem;
    }
    .glass {
        backdrop-filter: blur(16px) saturate(180%);
        -webkit-backdrop-filter: blur(16px) saturate(180%);
    }
</style>
@endpush
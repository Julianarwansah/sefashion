@extends('layouts.app')

@section('title', 'Tambah Produk Baru - Konveksi')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-50 via-pink-50 to-yellow-50 p-4 lg:p-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-2">Tambah Produk Baru</h1>
                    <p class="text-gray-600">Buat produk konveksi baru dengan varian warna, ukuran, dan multiple gambar</p>
                </div>
                <a href="{{ route('admin.produk.index') }}" 
                   class="inline-flex items-center gap-2 bg-gray-500 hover:bg-gray-600 text-white font-semibold px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali ke Daftar
                </a>
            </div>
        </div>

        <!-- Alert Messages -->
        <div class="mb-6 space-y-3">
            @if($errors->any())
                <div id="error-alert" class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl shadow-sm">
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="flex-1">Terjadi kesalahan! Silakan periksa form di bawah.</span>
                    <button type="button" onclick="dismissAlert('error-alert')" class="text-red-500 hover:text-red-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            @endif
        </div>

        <!-- Form Section -->
        <form action="{{ route('admin.produk.store') }}" method="POST" enctype="multipart/form-data" id="produkForm">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Form -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Basic Information Card -->
                    <div class="bg-white/80 glass rounded-2xl shadow-xl border border-white/20 overflow-hidden">
                        <div class="px-6 py-4 bg-gradient-to-r from-purple-600 to-pink-600 text-white">
                            <h2 class="text-xl font-bold">Informasi Dasar Produk</h2>
                        </div>
                        <div class="p-6 space-y-4">
                            <!-- Nama Produk -->
                            <div>
                                <label for="nama_produk" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Nama Produk <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="nama_produk" 
                                       name="nama_produk" 
                                       value="{{ old('nama_produk') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200 @error('nama_produk') border-red-500 @enderror"
                                       placeholder="Masukkan nama produk"
                                       required
                                       maxlength="100">
                                @error('nama_produk')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Deskripsi -->
                            <div>
                                <label for="deskripsi" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Deskripsi <span class="text-red-500">*</span>
                                </label>
                                <textarea 
                                    id="deskripsi" 
                                    name="deskripsi" 
                                    rows="4"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200 @error('deskripsi') border-red-500 @enderror"
                                    placeholder="Masukkan deskripsi produk"
                                    required>{{ old('deskripsi') }}</textarea>
                                @error('deskripsi')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Kategori -->
                            <div>
                                <label for="kategori" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Kategori <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="kategori" 
                                       name="kategori" 
                                       value="{{ old('kategori') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200 @error('kategori') border-red-500 @enderror"
                                       placeholder="Masukkan kategori produk"
                                       required
                                       maxlength="50">
                                @error('kategori')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Warna Produk Card -->
                    <div class="bg-white/80 glass rounded-2xl shadow-xl border border-white/20 overflow-hidden">
                        <div class="px-6 py-4 bg-gradient-to-r from-purple-600 to-pink-600 text-white flex justify-between items-center">
                            <h2 class="text-xl font-bold">Varian Warna</h2>
                            <button type="button" 
                                    id="tambahWarna"
                                    class="inline-flex items-center gap-2 bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 transform hover:scale-105">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                Tambah Warna
                            </button>
                        </div>
                        <div class="p-6">
                            <div id="warnaContainer" class="space-y-4">
                                @if(old('warna'))
                                    @foreach(old('warna') as $index => $warna)
                                        <div class="warna-item bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl p-4 border border-blue-200">
                                            <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-end">
                                                <div class="lg:col-span-5">
                                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                                        Nama Warna <span class="text-red-500">*</span>
                                                    </label>
                                                    <input type="text" 
                                                           name="warna[{{ $index }}][nama_warna]" 
                                                           value="{{ $warna['nama_warna'] ?? '' }}"
                                                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('warna.'.$index.'.nama_warna') border-red-500 @enderror"
                                                           placeholder="Contoh: Merah, Biru, Hitam"
                                                           required
                                                           maxlength="100">
                                                    @error('warna.'.$index.'.nama_warna')
                                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                                <div class="lg:col-span-5">
                                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                                        Kode Warna <span class="text-red-500">*</span>
                                                    </label>
                                                    <input type="text" 
                                                           name="warna[{{ $index }}][kode_warna]" 
                                                           value="{{ $warna['kode_warna'] ?? '' }}"
                                                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('warna.'.$index.'.kode_warna') border-red-500 @enderror"
                                                           placeholder="Contoh: #FF0000 atau red"
                                                           required
                                                           maxlength="50">
                                                    @error('warna.'.$index.'.kode_warna')
                                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                                <div class="lg:col-span-2">
                                                    <button type="button" 
                                                            class="hapus-warna w-full bg-red-500 hover:bg-red-600 text-white px-4 py-3 rounded-xl font-medium transition-all duration-200 transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100"
                                                            {{ $loop->first ? 'disabled' : '' }}>
                                                        <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <!-- Default satu warna -->
                                    <div class="warna-item bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl p-4 border border-blue-200">
                                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-end">
                                            <div class="lg:col-span-5">
                                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                                    Nama Warna <span class="text-red-500">*</span>
                                                </label>
                                                <input type="text" 
                                                       name="warna[0][nama_warna]" 
                                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                                       placeholder="Contoh: Merah, Biru, Hitam"
                                                       required
                                                       maxlength="100">
                                            </div>
                                            <div class="lg:col-span-5">
                                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                                    Kode Warna <span class="text-red-500">*</span>
                                                </label>
                                                <input type="text" 
                                                       name="warna[0][kode_warna]" 
                                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                                       placeholder="Contoh: #FF0000 atau red"
                                                       required
                                                       maxlength="50">
                                            </div>
                                            <div class="lg:col-span-2">
                                                <button type="button" 
                                                        class="hapus-warna w-full bg-red-500 hover:bg-red-600 text-white px-4 py-3 rounded-xl font-medium transition-all duration-200 transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100"
                                                        disabled>
                                                    <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Ukuran dan Harga Card -->
                    <div class="bg-white/80 glass rounded-2xl shadow-xl border border-white/20 overflow-hidden">
                        <div class="px-6 py-4 bg-gradient-to-r from-purple-600 to-pink-600 text-white flex justify-between items-center">
                            <h2 class="text-xl font-bold">Varian Ukuran & Harga</h2>
                            <button type="button" 
                                    id="tambahUkuran"
                                    class="inline-flex items-center gap-2 bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 transform hover:scale-105">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                Tambah Ukuran
                            </button>
                        </div>
                        <div class="p-6">
                            <div id="ukuranContainer" class="space-y-4">
                                @if(old('ukuran'))
                                    @foreach(old('ukuran') as $index => $ukuran)
                                        <div class="ukuran-item bg-gradient-to-r from-green-50 to-blue-50 rounded-xl p-4 border border-green-200">
                                            <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-end">
                                                <div class="lg:col-span-3">
                                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                                        Warna <span class="text-red-500">*</span>
                                                    </label>
                                                    <select name="ukuran[{{ $index }}][id_warna_index]" 
                                                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 @error('ukuran.'.$index.'.id_warna_index') border-red-500 @enderror"
                                                            required>
                                                        <option value="">Pilih Warna</option>
                                                        <!-- Options akan diisi oleh JavaScript -->
                                                    </select>
                                                    @error('ukuran.'.$index.'.id_warna_index')
                                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                                <div class="lg:col-span-2">
                                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                                        Ukuran <span class="text-red-500">*</span>
                                                    </label>
                                                    <input type="text" 
                                                           name="ukuran[{{ $index }}][ukuran]" 
                                                           value="{{ $ukuran['ukuran'] ?? '' }}"
                                                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 @error('ukuran.'.$index.'.ukuran') border-red-500 @enderror"
                                                           placeholder="S, M, L, XL"
                                                           required
                                                           maxlength="50">
                                                    @error('ukuran.'.$index.'.ukuran')
                                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                                <div class="lg:col-span-2">
                                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                                        Harga (Rp) <span class="text-red-500">*</span>
                                                    </label>
                                                    <input type="number" 
                                                           name="ukuran[{{ $index }}][harga]" 
                                                           value="{{ $ukuran['harga'] ?? '' }}"
                                                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 @error('ukuran.'.$index.'.harga') border-red-500 @enderror"
                                                           min="0" 
                                                           step="1000" 
                                                           placeholder="0"
                                                           required>
                                                    @error('ukuran.'.$index.'.harga')
                                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                                <div class="lg:col-span-2">
                                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                                        Stok <span class="text-red-500">*</span>
                                                    </label>
                                                    <input type="number" 
                                                           name="ukuran[{{ $index }}][stok]" 
                                                           value="{{ $ukuran['stok'] ?? '' }}"
                                                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 @error('ukuran.'.$index.'.stok') border-red-500 @enderror"
                                                           min="0" 
                                                           placeholder="0"
                                                           required>
                                                    @error('ukuran.'.$index.'.stok')
                                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                                <div class="lg:col-span-2">
                                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                                        Keterangan
                                                    </label>
                                                    <input type="text" 
                                                           name="ukuran[{{ $index }}][tambahan]" 
                                                           value="{{ $ukuran['tambahan'] ?? '' }}"
                                                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200"
                                                           placeholder="Opsional"
                                                           maxlength="255">
                                                </div>
                                                <div class="lg:col-span-1">
                                                    <button type="button" 
                                                            class="hapus-ukuran w-full bg-red-500 hover:bg-red-600 text-white px-4 py-3 rounded-xl font-medium transition-all duration-200 transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100"
                                                            {{ $loop->first ? 'disabled' : '' }}>
                                                        <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <!-- Default satu ukuran -->
                                    <div class="ukuran-item bg-gradient-to-r from-green-50 to-blue-50 rounded-xl p-4 border border-green-200">
                                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-end">
                                            <div class="lg:col-span-3">
                                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                                    Warna <span class="text-red-500">*</span>
                                                </label>
                                                <select name="ukuran[0][id_warna_index]" 
                                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200"
                                                        required>
                                                    <option value="">Pilih Warna</option>
                                                </select>
                                            </div>
                                            <div class="lg:col-span-2">
                                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                                    Ukuran <span class="text-red-500">*</span>
                                                </label>
                                                <input type="text" 
                                                       name="ukuran[0][ukuran]" 
                                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200"
                                                       placeholder="S, M, L, XL"
                                                       required
                                                       maxlength="50">
                                            </div>
                                            <div class="lg:col-span-2">
                                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                                    Harga (Rp) <span class="text-red-500">*</span>
                                                </label>
                                                <input type="number" 
                                                       name="ukuran[0][harga]" 
                                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200"
                                                       min="0" 
                                                       step="1000" 
                                                       placeholder="0"
                                                       required>
                                            </div>
                                            <div class="lg:col-span-2">
                                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                                    Stok <span class="text-red-500">*</span>
                                                </label>
                                                <input type="number" 
                                                       name="ukuran[0][stok]" 
                                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200"
                                                       min="0" 
                                                       placeholder="0"
                                                       required>
                                            </div>
                                            <div class="lg:col-span-2">
                                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                                    Keterangan
                                                </label>
                                                <input type="text" 
                                                       name="ukuran[0][tambahan]" 
                                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200"
                                                       placeholder="Opsional"
                                                       maxlength="255">
                                            </div>
                                            <div class="lg:col-span-1">
                                                <button type="button" 
                                                        class="hapus-ukuran w-full bg-red-500 hover:bg-red-600 text-white px-4 py-3 rounded-xl font-medium transition-all duration-200 transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100"
                                                        disabled>
                                                    <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Gambar Tambahan Card -->
                    <div class="bg-white/80 glass rounded-2xl shadow-xl border border-white/20 overflow-hidden">
                        <div class="px-6 py-4 bg-gradient-to-r from-purple-600 to-pink-600 text-white flex justify-between items-center">
                            <h2 class="text-xl font-bold">Gambar Tambahan</h2>
                            <button type="button" 
                                    id="tambahGambarGroup"
                                    class="inline-flex items-center gap-2 bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 transform hover:scale-105">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                Tambah Group Gambar
                            </button>
                        </div>
                        <div class="p-6">
                            <div id="gambarContainer" class="space-y-6">
                                <!-- Default group gambar -->
                                <div class="gambar-group bg-gradient-to-r from-orange-50 to-red-50 rounded-xl p-4 border border-orange-200">
                                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-start">
                                        <div class="lg:col-span-3">
                                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                                Warna (Opsional)
                                            </label>
                                            <select name="gambar_produk[0][id_warna_index]" 
                                                    class="warna-select-group w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200">
                                                <option value="">Pilih Warna</option>
                                            </select>
                                        </div>
                                        <div class="lg:col-span-8">
                                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                                Upload Multiple Gambar
                                            </label>
                                            <div class="space-y-3">
                                                <div class="file-input-group">
                                                    <input type="file" 
                                                           name="gambar_produk[0][gambar][]" 
                                                           accept="image/*"
                                                           class="gambar-input hidden"
                                                           multiple>
                                                    <label class="upload-label flex flex-col items-center justify-center w-full h-24 border-2 border-dashed border-gray-300 rounded-xl cursor-pointer hover:border-orange-400 hover:bg-orange-50 transition-all duration-200">
                                                        <svg class="w-6 h-6 text-gray-400 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                        </svg>
                                                        <span class="text-sm text-gray-600">Klik untuk upload gambar</span>
                                                        <span class="text-xs text-gray-500">Bisa multiple (Maks. 2MB per gambar)</span>
                                                    </label>
                                                </div>
                                                <div class="preview-container grid grid-cols-2 md:grid-cols-4 gap-2 mt-2">
                                                    <!-- Preview akan muncul di sini -->
                                                </div>
                                            </div>
                                        </div>
                                        <div class="lg:col-span-1">
                                            <button type="button" 
                                                    class="hapus-gambar-group w-full bg-red-500 hover:bg-red-600 text-white px-4 py-3 rounded-xl font-medium transition-all duration-200 transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100"
                                                    disabled>
                                                <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Gambar Utama Card -->
                    <div class="bg-white/80 glass rounded-2xl shadow-xl border border-white/20 overflow-hidden">
                        <div class="px-6 py-4 bg-gradient-to-r from-purple-600 to-pink-600 text-white">
                            <h2 class="text-xl font-bold">Gambar Utama</h2>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <!-- File Input -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Upload Gambar Utama
                                    </label>
                                    <div class="relative">
                                        <input type="file" 
                                               id="gambar" 
                                               name="gambar" 
                                               accept="image/*"
                                               class="hidden">
                                        <label for="gambar" 
                                               class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-300 rounded-xl cursor-pointer hover:border-purple-400 hover:bg-purple-50 transition-all duration-200">
                                            <svg class="w-8 h-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            <span class="text-sm text-gray-600">Klik untuk upload gambar</span>
                                            <span class="text-xs text-gray-500">JPEG, PNG, JPG, GIF (Maks. 2MB)</span>
                                        </label>
                                    </div>
                                    @error('gambar')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Preview -->
                                <div id="previewGambar" class="text-center">
                                    <div class="w-32 h-32 mx-auto bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl flex items-center justify-center">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <p class="text-sm text-gray-500 mt-2">Preview gambar akan muncul di sini</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Card -->
                    <div class="bg-white/80 glass rounded-2xl shadow-xl border border-white/20 overflow-hidden">
                        <div class="px-6 py-4 bg-gradient-to-r from-purple-600 to-pink-600 text-white">
                            <h2 class="text-xl font-bold">Aksi</h2>
                        </div>
                        <div class="p-6 space-y-4">
                            <button type="submit" 
                                    class="w-full bg-gradient-to-r from-green-500 to-blue-500 hover:from-green-600 hover:to-blue-600 text-white font-semibold py-3 px-4 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Simpan Produk
                            </button>
                            
                            <a href="{{ route('admin.produk.index') }}" 
                               class="w-full bg-gray-500 hover:bg-gray-600 text-white font-semibold py-3 px-4 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Batal
                            </a>
                        </div>
                    </div>

                    <!-- Info Card -->
                    <div class="bg-gradient-to-br from-blue-50 to-purple-50 rounded-2xl p-6 border border-blue-200">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center text-white">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <h3 class="font-semibold text-gray-900">Tips</h3>
                        </div>
                        <ul class="space-y-2 text-sm text-gray-600">
                            <li class="flex items-start gap-2">
                                <span class="text-green-500 mt-1">•</span>
                                Pastikan semua field wajib diisi
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="text-green-500 mt-1">•</span>
                                Minimal 1 varian warna dan ukuran
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="text-green-500 mt-1">•</span>
                                Gunakan kode warna HEX untuk konsistensi
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="text-green-500 mt-1">•</span>
                                Harga dalam Rupiah (tanpa titik)
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="text-green-500 mt-1">•</span>
                                Upload multiple gambar untuk variasi produk
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Counter untuk warna, ukuran, dan gambar group
        let warnaCounter = {{ old('warna') ? count(old('warna')) : 1 }};
        let ukuranCounter = {{ old('ukuran') ? count(old('ukuran')) : 1 }};
        let gambarGroupCounter = 1;
        
        // Preview gambar utama
        const gambarInput = document.getElementById('gambar');
        const previewGambar = document.getElementById('previewGambar');
        
        gambarInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewGambar.innerHTML = `
                        <div class="flex flex-col items-center">
                            <img src="${e.target.result}" 
                                 class="w-32 h-32 object-cover rounded-xl shadow-lg border border-gray-200"
                                 alt="Preview Gambar">
                            <p class="text-sm text-green-600 mt-2">Gambar siap diupload</p>
                        </div>
                    `;
                };
                reader.readAsDataURL(file);
            }
        });
        
        // Tambah Warna
        document.getElementById('tambahWarna').addEventListener('click', function() {
            const container = document.getElementById('warnaContainer');
            const newItem = document.createElement('div');
            newItem.className = 'warna-item bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl p-4 border border-blue-200';
            newItem.innerHTML = `
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-end">
                    <div class="lg:col-span-5">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Nama Warna <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="warna[${warnaCounter}][nama_warna]" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                               placeholder="Contoh: Merah, Biru, Hitam"
                               required
                               maxlength="100">
                    </div>
                    <div class="lg:col-span-5">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Kode Warna <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="warna[${warnaCounter}][kode_warna]" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                               placeholder="Contoh: #FF0000 atau red"
                               required
                               maxlength="50">
                    </div>
                    <div class="lg:col-span-2">
                        <button type="button" 
                                class="hapus-warna w-full bg-red-500 hover:bg-red-600 text-white px-4 py-3 rounded-xl font-medium transition-all duration-200 transform hover:scale-105">
                            <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </div>
            `;
            container.appendChild(newItem);
            warnaCounter++;
            
            updateHapusButtons();
            updateWarnaOptions();
        });
        
        // Tambah Ukuran
        document.getElementById('tambahUkuran').addEventListener('click', function() {
            const container = document.getElementById('ukuranContainer');
            const newItem = document.createElement('div');
            newItem.className = 'ukuran-item bg-gradient-to-r from-green-50 to-blue-50 rounded-xl p-4 border border-green-200';
            newItem.innerHTML = `
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-end">
                    <div class="lg:col-span-3">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Warna <span class="text-red-500">*</span>
                        </label>
                        <select name="ukuran[${ukuranCounter}][id_warna_index]" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200"
                                required>
                            <option value="">Pilih Warna</option>
                        </select>
                    </div>
                    <div class="lg:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Ukuran <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="ukuran[${ukuranCounter}][ukuran]" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200"
                               placeholder="S, M, L, XL"
                               required
                               maxlength="50">
                    </div>
                    <div class="lg:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Harga (Rp) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               name="ukuran[${ukuranCounter}][harga]" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200"
                               min="0" 
                               step="1000" 
                               placeholder="0"
                               required>
                    </div>
                    <div class="lg:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Stok <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               name="ukuran[${ukuranCounter}][stok]" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200"
                               min="0" 
                               placeholder="0"
                               required>
                    </div>
                    <div class="lg:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Keterangan
                        </label>
                        <input type="text" 
                               name="ukuran[${ukuranCounter}][tambahan]" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200"
                               placeholder="Opsional"
                               maxlength="255">
                    </div>
                    <div class="lg:col-span-1">
                        <button type="button" 
                                class="hapus-ukuran w-full bg-red-500 hover:bg-red-600 text-white px-4 py-3 rounded-xl font-medium transition-all duration-200 transform hover:scale-105">
                            <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </div>
            `;
            container.appendChild(newItem);
            ukuranCounter++;
            
            updateWarnaOptions();
            updateHapusButtons();
        });

        // Tambah Group Gambar
        document.getElementById('tambahGambarGroup').addEventListener('click', function() {
            const container = document.getElementById('gambarContainer');
            const newGroup = document.createElement('div');
            newGroup.className = 'gambar-group bg-gradient-to-r from-orange-50 to-red-50 rounded-xl p-4 border border-orange-200';
            newGroup.innerHTML = `
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-start">
                    <div class="lg:col-span-3">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Warna (Opsional)
                        </label>
                        <select name="gambar_produk[${gambarGroupCounter}][id_warna_index]" 
                                class="warna-select-group w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200">
                            <option value="">Pilih Warna</option>
                        </select>
                    </div>
                    <div class="lg:col-span-8">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Upload Multiple Gambar
                        </label>
                        <div class="space-y-3">
                            <div class="file-input-group">
                                <input type="file" 
                                       name="gambar_produk[${gambarGroupCounter}][gambar][]" 
                                       accept="image/*"
                                       class="gambar-input hidden"
                                       multiple>
                                <label class="upload-label flex flex-col items-center justify-center w-full h-24 border-2 border-dashed border-gray-300 rounded-xl cursor-pointer hover:border-orange-400 hover:bg-orange-50 transition-all duration-200">
                                    <svg class="w-6 h-6 text-gray-400 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span class="text-sm text-gray-600">Klik untuk upload gambar</span>
                                    <span class="text-xs text-gray-500">Bisa multiple (Maks. 2MB per gambar)</span>
                                </label>
                            </div>
                            <div class="preview-container grid grid-cols-2 md:grid-cols-4 gap-2 mt-2">
                                <!-- Preview akan muncul di sini -->
                            </div>
                        </div>
                    </div>
                    <div class="lg:col-span-1">
                        <button type="button" 
                                class="hapus-gambar-group w-full bg-red-500 hover:bg-red-600 text-white px-4 py-3 rounded-xl font-medium transition-all duration-200 transform hover:scale-105">
                            <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </div>
            `;
            container.appendChild(newGroup);
            gambarGroupCounter++;
            
            updateWarnaOptions();
            updateHapusButtons();
            initGambarUpload();
        });
        
        // Hapus items
        document.addEventListener('click', function(e) {
            if (e.target.closest('.hapus-warna')) {
                const item = e.target.closest('.warna-item');
                if (document.querySelectorAll('.warna-item').length > 1) {
                    item.style.opacity = '0';
                    item.style.transform = 'translateX(-20px)';
                    setTimeout(() => {
                        item.remove();
                        updateHapusButtons();
                        updateWarnaOptions();
                    }, 300);
                }
            }
            
            if (e.target.closest('.hapus-ukuran')) {
                const item = e.target.closest('.ukuran-item');
                if (document.querySelectorAll('.ukuran-item').length > 1) {
                    item.style.opacity = '0';
                    item.style.transform = 'translateX(-20px)';
                    setTimeout(() => {
                        item.remove();
                        updateHapusButtons();
                    }, 300);
                }
            }

            if (e.target.closest('.hapus-gambar-group')) {
                const group = e.target.closest('.gambar-group');
                if (document.querySelectorAll('.gambar-group').length > 1) {
                    group.style.opacity = '0';
                    group.style.transform = 'translateX(-20px)';
                    setTimeout(() => {
                        group.remove();
                        updateHapusButtons();
                    }, 300);
                }
            }
        });
        
        // Update tombol hapus
        function updateHapusButtons() {
            const warnaItems = document.querySelectorAll('.warna-item');
            const ukuranItems = document.querySelectorAll('.ukuran-item');
            const gambarGroups = document.querySelectorAll('.gambar-group');
            
            document.querySelectorAll('.hapus-warna').forEach(btn => {
                btn.disabled = warnaItems.length <= 1;
            });
            
            document.querySelectorAll('.hapus-ukuran').forEach(btn => {
                btn.disabled = ukuranItems.length <= 1;
            });

            document.querySelectorAll('.hapus-gambar-group').forEach(btn => {
                btn.disabled = gambarGroups.length <= 1;
            });
        }
        
        // Update opsi warna di semua select
        function updateWarnaOptions() {
            const warnaInputs = document.querySelectorAll('input[name^="warna["][name$="[nama_warna]"]');
            const semuaSelect = document.querySelectorAll('select[name^="ukuran["], .warna-select-group');
            
            semuaSelect.forEach(select => {
                const currentValue = select.value;
                select.innerHTML = '<option value="">Pilih Warna</option>';
                
                warnaInputs.forEach((input, index) => {
                    const option = document.createElement('option');
                    option.value = index;
                    option.textContent = input.value || `Warna ${index + 1}`;
                    select.appendChild(option);
                });
                
                if (currentValue) {
                    select.value = currentValue;
                }
            });
        }

        // Handle multiple image upload preview
        function initGambarUpload() {
            document.querySelectorAll('.file-input-group').forEach(group => {
                const input = group.querySelector('.gambar-input');
                const label = group.querySelector('.upload-label');
                const previewContainer = group.nextElementSibling;
                
                // Reset event listener
                input.removeEventListener('change', handleFileChange);
                input.addEventListener('change', handleFileChange);
                
                function handleFileChange(e) {
                    const files = e.target.files;
                    previewContainer.innerHTML = '';
                    
                    if (files.length > 0) {
                        Array.from(files).forEach(file => {
                            if (file.type.startsWith('image/')) {
                                const reader = new FileReader();
                                reader.onload = function(e) {
                                    const preview = document.createElement('div');
                                    preview.className = 'relative group';
                                    preview.innerHTML = `
                                        <img src="${e.target.result}" 
                                             class="w-20 h-20 object-cover rounded-lg border border-gray-200"
                                             alt="Preview">
                                        <div class="absolute inset-0 bg-black bg-opacity-50 rounded-lg flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    `;
                                    previewContainer.appendChild(preview);
                                };
                                reader.readAsDataURL(file);
                            }
                        });
                        
                        // Update label text
                        const fileCount = files.length;
                        const labelText = label.querySelector('span:nth-child(2)');
                        const subText = label.querySelector('span:nth-child(3)');
                        
                        labelText.textContent = `${fileCount} gambar dipilih`;
                        subText.textContent = `${fileCount} file selected (Maks. 2MB per gambar)`;
                    }
                }
                
                // Click handler untuk label
                label.addEventListener('click', function(e) {
                    e.preventDefault();
                    input.click();
                });
            });
        }
        
        // Update ketika input nama warna berubah
        document.addEventListener('input', function(e) {
            if (e.target.name && e.target.name.includes('[nama_warna]')) {
                updateWarnaOptions();
            }
        });
        
        // Initialize
        updateWarnaOptions();
        updateHapusButtons();
        initGambarUpload();
        
        // Add animation to new items
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                mutation.addedNodes.forEach(function(node) {
                    if (node.nodeType === 1 && (node.classList.contains('warna-item') || node.classList.contains('ukuran-item') || node.classList.contains('gambar-group'))) {
                        node.style.opacity = '0';
                        node.style.transform = 'translateY(20px)';
                        setTimeout(() => {
                            node.style.transition = 'all 0.3s ease';
                            node.style.opacity = '1';
                            node.style.transform = 'translateY(0)';
                        }, 10);
                    }
                });
            });
        });
        
        observer.observe(document.getElementById('warnaContainer'), { childList: true });
        observer.observe(document.getElementById('ukuranContainer'), { childList: true });
        observer.observe(document.getElementById('gambarContainer'), { childList: true });
    });

    // Dismiss alert function
    function dismissAlert(alertId) {
        const alert = document.getElementById(alertId);
        if (alert) {
            alert.style.opacity = '0';
            alert.style.transform = 'translateX(100%)';
            setTimeout(() => alert.remove(), 300);
        }
    }
</script>

<style>
    .glass {
        backdrop-filter: blur(16px) saturate(180%);
        -webkit-backdrop-filter: blur(16px) saturate(180%);
    }
    
    input:focus, select:focus, textarea:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(168, 85, 247, 0.1);
    }
    
    .warna-item, .ukuran-item, .gambar-group {
        transition: all 0.3s ease;
    }

    .preview-container img {
        transition: transform 0.2s ease;
    }

    .preview-container img:hover {
        transform: scale(1.05);
    }
</style>
@endsection
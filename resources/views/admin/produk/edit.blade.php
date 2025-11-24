@extends('layouts.app')

@section('title', 'Edit Produk - ' . $produk->nama_produk)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-50 via-pink-50 to-yellow-50 p-4 lg:p-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-2">Edit Produk</h1>
                    <p class="text-gray-600">{{ $produk->nama_produk }}</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('admin.produk.show', $produk->id_produk) }}" 
                       class="inline-flex items-center gap-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        Lihat Detail
                    </a>
                    <a href="{{ route('admin.produk.index') }}" 
                       class="inline-flex items-center gap-2 bg-gray-500 hover:bg-gray-600 text-white font-semibold px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Kembali
                    </a>
                </div>
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

        <!-- Form Section -->
        <form action="{{ route('admin.produk.update', $produk->id_produk) }}" method="POST" enctype="multipart/form-data" id="produkForm">
            @csrf
            @method('PUT')
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
                                       value="{{ old('nama_produk', $produk->nama_produk) }}"
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
                                    Deskripsi
                                </label>
                                <textarea 
                                    id="deskripsi" 
                                    name="deskripsi" 
                                    rows="4"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200 @error('deskripsi') border-red-500 @enderror"
                                    placeholder="Masukkan deskripsi produk">{{ old('deskripsi', $produk->deskripsi) }}</textarea>
                                @error('deskripsi')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Kategori -->
                            <div>
                                <label for="kategori" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Kategori
                                </label>
                                <select id="kategori" 
                                        name="kategori" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200 @error('kategori') border-red-500 @enderror">
                                    <option value="">Pilih Kategori (Opsional)</option>
                                    <option value="Baju Pria" {{ old('kategori', $produk->kategori) == 'Baju Pria' ? 'selected' : '' }}>Baju Pria</option>
                                    <option value="Baju Wanita" {{ old('kategori', $produk->kategori) == 'Baju Wanita' ? 'selected' : '' }}>Baju Wanita</option>
                                    <option value="Baju Anak" {{ old('kategori', $produk->kategori) == 'Baju Anak' ? 'selected' : '' }}>Baju Anak</option>
                                    <option value="Aksesoris" {{ old('kategori', $produk->kategori) == 'Aksesoris' ? 'selected' : '' }}>Aksesoris</option>
                                </select>
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
                                @php
                                    $warnaData = old('warna', $produk->detailWarna->map(function($warna) {
                                        return [
                                            'id_warna' => $warna->id_warna,
                                            'nama_warna' => $warna->nama_warna,
                                            'kode_warna' => $warna->kode_warna
                                        ];
                                    })->toArray());
                                @endphp

                                @foreach($warnaData as $index => $warna)
                                    <div class="warna-item bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl p-4 border border-blue-200">
                                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-end">
                                            <div class="lg:col-span-5">
                                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                                    Nama Warna <span class="text-red-500">*</span>
                                                </label>
                                                <input type="text" 
                                                       name="warna[{{ $index }}][nama_warna]" 
                                                       value="{{ $warna['nama_warna'] }}"
                                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('warna.'.$index.'.nama_warna') border-red-500 @enderror"
                                                       placeholder="Contoh: Merah, Biru, Hitam"
                                                       required
                                                       maxlength="100">
                                                @if(isset($warna['id_warna']) && $warna['id_warna'])
                                                    <input type="hidden" name="warna[{{ $index }}][id_warna]" value="{{ $warna['id_warna'] }}">
                                                @endif
                                                @error('warna.'.$index.'.nama_warna')
                                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="lg:col-span-5">
                                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                                    Kode Warna
                                                </label>
                                                <input type="text" 
                                                       name="warna[{{ $index }}][kode_warna]" 
                                                       value="{{ $warna['kode_warna'] }}"
                                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('warna.'.$index.'.kode_warna') border-red-500 @enderror"
                                                       placeholder="Contoh: #FF0000 atau red"
                                                       maxlength="50">
                                                @error('warna.'.$index.'.kode_warna')
                                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="lg:col-span-2">
                                                <button type="button" 
                                                        class="hapus-warna w-full bg-red-500 hover:bg-red-600 text-white px-4 py-3 rounded-xl font-medium transition-all duration-200 transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100"
                                                        {{ $loop->first && $produk->detailWarna->count() <= 1 ? 'disabled' : '' }}>
                                                    <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
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
                                @php
                                    $ukuranData = old('ukuran', $produk->detailUkuran->map(function($ukuran) {
                                        return [
                                            'id_ukuran' => $ukuran->id_ukuran,
                                            'id_warna' => $ukuran->id_warna,
                                            'ukuran' => $ukuran->ukuran,
                                            'harga' => $ukuran->harga,
                                            'stok' => $ukuran->stok,
                                            'tambahan' => $ukuran->tambahan
                                        ];
                                    })->toArray());
                                @endphp

                                @foreach($ukuranData as $index => $ukuran)
                                    <div class="ukuran-item bg-gradient-to-r from-green-50 to-blue-50 rounded-xl p-4 border border-green-200">
                                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-end">
                                            <div class="lg:col-span-3">
                                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                                    Warna <span class="text-red-500">*</span>
                                                </label>
                                                <select name="ukuran[{{ $index }}][id_warna]" 
                                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 @error('ukuran.'.$index.'.id_warna') border-red-500 @enderror"
                                                        required>
                                                    <option value="">Pilih Warna</option>
                                                    @foreach($produk->detailWarna as $warna)
                                                        <option value="{{ $warna->id_warna }}" 
                                                                {{ ($ukuran['id_warna'] == $warna->id_warna) ? 'selected' : '' }}>
                                                            {{ $warna->nama_warna }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @if(isset($ukuran['id_ukuran']) && $ukuran['id_ukuran'])
                                                    <input type="hidden" name="ukuran[{{ $index }}][id_ukuran]" value="{{ $ukuran['id_ukuran'] }}">
                                                @endif
                                                @error('ukuran.'.$index.'.id_warna')
                                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="lg:col-span-2">
                                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                                    Ukuran <span class="text-red-500">*</span>
                                                </label>
                                                <input type="text" 
                                                       name="ukuran[{{ $index }}][ukuran]" 
                                                       value="{{ $ukuran['ukuran'] }}"
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
                                                       value="{{ $ukuran['harga'] }}"
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
                                                       value="{{ $ukuran['stok'] }}"
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
                                                       value="{{ $ukuran['tambahan'] }}"
                                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 @error('ukuran.'.$index.'.tambahan') border-red-500 @enderror"
                                                       placeholder="Opsional"
                                                       maxlength="255">
                                                @error('ukuran.'.$index.'.tambahan')
                                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="lg:col-span-1">
                                                <button type="button" 
                                                        class="hapus-ukuran w-full bg-red-500 hover:bg-red-600 text-white px-4 py-3 rounded-xl font-medium transition-all duration-200 transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100"
                                                        {{ $loop->first && $produk->detailUkuran->count() <= 1 ? 'disabled' : '' }}>
                                                    <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
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
                                Tambah Gambar
                            </button>
                        </div>
                        <div class="p-6">
                            <!-- Existing Images -->
                            @if($produk->gambarProduk->count() > 0)
                                <div class="mb-6">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Gambar Saat Ini</h3>
                                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                        @foreach($produk->gambarProduk as $gambar)
                                            <div class="relative group bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
                                                <img src="{{ $gambar->gambar_url }}" 
                                                    alt="Gambar Produk" 
                                                    class="w-full h-32 object-cover">
                                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition-all duration-200 flex items-center justify-center space-x-2 opacity-0 group-hover:opacity-100">
                                                    @if($gambar->is_primary)
                                                        <span class="bg-green-500 text-white text-xs px-2 py-1 rounded-full">
                                                            Primary
                                                        </span>
                                                    @else
                                                        <form action="{{ route('admin.produk.set-primary-image', ['produk' => $produk->id_produk, 'gambar' => $gambar->id_gambar]) }}" method="POST" class="inline">
                                                            @csrf
                                                            <button type="submit" 
                                                                    class="bg-blue-500 hover:bg-blue-600 text-white text-xs px-2 py-1 rounded-full transition-colors"
                                                                    title="Set sebagai primary">
                                                                Set Primary
                                                            </button>
                                                        </form>
                                                    @endif
                                                    
                                                    {{-- Form Delete dengan method DELETE yang benar --}}
                                                    <form action="{{ route('admin.produk.delete-image', ['produk' => $produk->id_produk, 'gambar' => $gambar->id_gambar]) }}" 
                                                        method="POST" 
                                                        class="inline delete-image-form"
                                                        data-gambar-id="{{ $gambar->id_gambar }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" 
                                                                class="bg-red-500 hover:bg-red-600 text-white text-xs px-2 py-1 rounded-full transition-colors delete-image-btn"
                                                                title="Hapus gambar">
                                                            Hapus
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Add New Images -->
                            <div id="gambarContainer" class="space-y-6">
                                <!-- Default group gambar baru -->
                                <div class="gambar-group bg-gradient-to-r from-orange-50 to-red-50 rounded-xl p-4 border border-orange-200">
                                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-start">
                                        <div class="lg:col-span-3">
                                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                                Warna (Opsional)
                                            </label>
                                            <select name="gambar_produk[0][id_warna]" 
                                                    class="warna-select-group w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200">
                                                <option value="">Pilih Warna</option>
                                                @foreach($produk->detailWarna as $warna)
                                                    <option value="{{ $warna->id_warna }}">{{ $warna->nama_warna }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="lg:col-span-8">
                                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                                Upload Gambar Baru
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
                                                        <span class="text-sm text-gray-600">Klik untuk upload gambar baru</span>
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
                        <div class="p-6 space-y-4">
                            <!-- Current Image -->
                            @if($produk->gambar)
                                <div class="text-center">
                                    <div class="w-32 h-32 mx-auto bg-gradient-to-br from-blue-500 to-purple-500 rounded-xl overflow-hidden shadow-lg">
                                        <img src="{{ $produk->gambar_url }}" 
                                             class="w-full h-full object-cover"
                                             alt="{{ $produk->nama_produk }}">
                                    </div>
                                    <p class="text-sm text-green-600 mt-2 font-medium">Gambar saat ini</p>
                                </div>
                            @endif

                            <!-- File Input -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Ganti Gambar Utama
                                </label>
                                <div class="relative">
                                    <input type="file" 
                                           id="gambar" 
                                           name="gambar" 
                                           accept="image/*"
                                           class="hidden">
                                    <label for="gambar" 
                                           class="upload-label-main flex flex-col items-center justify-center w-full h-24 border-2 border-dashed border-gray-300 rounded-xl cursor-pointer hover:border-purple-400 hover:bg-purple-50 transition-all duration-200">
                                        <svg class="w-6 h-6 text-gray-400 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <span class="text-xs text-gray-600">Klik untuk ganti gambar</span>
                                        <span class="text-xs text-gray-500 mt-1">JPEG, PNG, JPG, GIF (Maks. 2MB)</span>
                                    </label>
                                </div>
                                @error('gambar')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Preview New Image -->
                            <div id="previewGambar" class="text-center hidden">
                                <div class="w-24 h-24 mx-auto bg-gradient-to-br from-green-500 to-blue-500 rounded-xl overflow-hidden shadow-lg mb-2">
                                    <!-- Preview image will be inserted here -->
                                </div>
                                <p class="text-sm text-blue-600 mt-1">Preview gambar baru</p>
                            </div>
                        </div>
                    </div>

                    <!-- Statistics Card -->
                    <div class="bg-gradient-to-br from-green-50 to-blue-50 rounded-2xl p-6 border border-green-200">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-blue-500 rounded-full flex items-center justify-center text-white">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </div>
                            <h3 class="font-semibold text-gray-900">Statistik Produk</h3>
                        </div>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Total Stok</span>
                                <span class="font-semibold text-green-600">{{ $produk->total_stok }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Varian Warna</span>
                                <span class="font-semibold text-blue-600">{{ $produk->detailWarna->count() }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Varian Ukuran</span>
                                <span class="font-semibold text-purple-600">{{ $produk->detailUkuran->count() }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Total Gambar</span>
                                <span class="font-semibold text-pink-600">{{ $produk->gambarProduk->count() }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Action Card -->
                    <div class="bg-white/80 glass rounded-2xl shadow-xl border border-white/20 overflow-hidden">
                        <div class="px-6 py-4 bg-gradient-to-r from-purple-600 to-pink-600 text-white">
                            <h2 class="text-xl font-bold">Aksi</h2>
                        </div>
                        <div class="p-6 space-y-3">
                            <button type="submit" 
                                    class="w-full bg-gradient-to-r from-green-500 to-blue-500 hover:from-green-600 hover:to-blue-600 text-white font-semibold py-3 px-4 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Update Produk
                            </button>
                            
                            <a href="{{ route('admin.produk.show', $produk->id_produk) }}" 
                               class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 px-4 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Lihat Detail
                            </a>

                            <a href="{{ route('admin.produk.index') }}" 
                               class="w-full bg-gray-500 hover:bg-gray-600 text-white font-semibold py-3 px-4 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Batal
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Counter untuk warna, ukuran, dan gambar group
        let warnaCounter = {{ count($warnaData) }};
        let ukuranCounter = {{ count($ukuranData) }};
        let gambarGroupCounter = 1;
        
        // Preview gambar utama
        const gambarInput = document.getElementById('gambar');
        const previewGambar = document.getElementById('previewGambar');
        const uploadLabelMain = document.querySelector('.upload-label-main');
        
        // Handle click untuk gambar utama
        uploadLabelMain.addEventListener('click', function(e) {
            e.preventDefault();
            gambarInput.click();
        });
        
        gambarInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    let previewImg = previewGambar.querySelector('img');
                    if (!previewImg) {
                        previewImg = document.createElement('img');
                        previewImg.className = 'w-full h-full object-cover';
                        previewGambar.querySelector('div').appendChild(previewImg);
                    }
                    previewImg.src = e.target.result;
                    
                    previewGambar.classList.remove('hidden');
                    
                    // Update label text
                    uploadLabelMain.innerHTML = `
                        <svg class="w-6 h-6 text-green-500 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span class="text-xs text-green-600">Gambar dipilih</span>
                        <span class="text-xs text-gray-500 mt-1">${file.name}</span>
                    `;
                };
                reader.readAsDataURL(file);
            }
        });
        // Pastikan semua form hapus gambar menggunakan route yang benar
    document.querySelectorAll('form[action*="delete-image"]').forEach(form => {
        form.addEventListener('submit', function(e) {
            console.log('URL yang akan dipanggil:', this.action);
            // Verifikasi URL mengandung 'delete-image' bukan hanya 'produk/2'
        });
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
                            Kode Warna
                        </label>
                        <input type="text" 
                               name="warna[${warnaCounter}][kode_warna]" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                               placeholder="Contoh: #FF0000 atau red"
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
            updateUkuranWarnaOptions();
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
                        <select name="ukuran[${ukuranCounter}][id_warna]" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200"
                                required>
                            <option value="">Pilih Warna</option>
                            @foreach($produk->detailWarna as $warna)
                                <option value="{{ $warna->id_warna }}">{{ $warna->nama_warna }}</option>
                            @endforeach
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
                        <select name="gambar_produk[${gambarGroupCounter}][id_warna]" 
                                class="warna-select-group w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200">
                            <option value="">Pilih Warna</option>
                            @foreach($produk->detailWarna as $warna)
                                <option value="{{ $warna->id_warna }}">{{ $warna->nama_warna }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="lg:col-span-8">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Upload Gambar Baru
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
                                    <span class="text-sm text-gray-600">Klik untuk upload gambar baru</span>
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
                        updateUkuranWarnaOptions();
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
        
        // Update opsi warna di ukuran ketika warna baru ditambahkan
        function updateUkuranWarnaOptions() {
            // Untuk form edit, kita menggunakan ID warna yang sebenarnya
            // Fungsi ini akan diimplementasikan jika perlu menambah warna baru secara dinamis
        }

        // Handle multiple image upload preview
        function initGambarUpload() {
            document.querySelectorAll('.file-input-group').forEach(group => {
                const input = group.querySelector('.gambar-input');
                const label = group.querySelector('.upload-label');
                const previewContainer = group.nextElementSibling;
                
                // Reset event listener
                const newInput = input.cloneNode(true);
                input.parentNode.replaceChild(newInput, input);
                
                newInput.addEventListener('change', handleFileChange);
                
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
                        label.innerHTML = `
                            <svg class="w-6 h-6 text-green-500 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-sm text-green-600">${fileCount} gambar dipilih</span>
                            <span class="text-xs text-gray-500">${fileCount} file selected</span>
                        `;
                    }
                }
                
                // Click handler untuk label
                label.addEventListener('click', function(e) {
                    e.preventDefault();
                    newInput.click();
                });
            });
        }
        
        // Initialize
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

    // Konfirmasi delete gambar
    document.querySelectorAll('.delete-image-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('form');
            const gambarId = form.getAttribute('data-gambar-id');
            
            if (confirm('Apakah Anda yakin ingin menghapus gambar ini?')) {
                console.log('Menghapus gambar ID:', gambarId);
                console.log('URL:', form.action);
                form.submit();
            }
        });
    });
    
    // Debug: Log semua form delete
    document.querySelectorAll('.delete-image-form').forEach(form => {
        console.log('Form delete gambar:', {
            action: form.action,
            method: form.method,
            gambarId: form.getAttribute('data-gambar-id')
        });
    });
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
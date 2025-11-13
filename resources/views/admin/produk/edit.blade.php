@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Produk - {{ $produk->nama_produk }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.produk.show', $produk->id_produk) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i> Detail
                        </a>
                        <a href="{{ route('admin.produk.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <form action="{{ route('admin.produk.update', $produk->id_produk) }}" method="POST" enctype="multipart/form-data" id="produkForm">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <!-- Alert -->
                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Terjadi kesalahan!</strong> Silakan periksa form di bawah.
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <!-- Informasi Produk -->
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="nama_produk">Nama Produk <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nama_produk') is-invalid @enderror" 
                                           id="nama_produk" name="nama_produk" 
                                           value="{{ old('nama_produk', $produk->nama_produk) }}" 
                                           placeholder="Masukkan nama produk" required maxlength="100">
                                    @error('nama_produk')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="deskripsi">Deskripsi</label>
                                    <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                              id="deskripsi" name="deskripsi" rows="4" 
                                              placeholder="Masukkan deskripsi produk">{{ old('deskripsi', $produk->deskripsi) }}</textarea>
                                    @error('deskripsi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="kategori">Kategori</label>
                                    <input type="text" class="form-control @error('kategori') is-invalid @enderror" 
                                           id="kategori" name="kategori" 
                                           value="{{ old('kategori', $produk->kategori) }}" 
                                           placeholder="Masukkan kategori produk" maxlength="50">
                                    @error('kategori')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="gambar">Gambar Utama</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input @error('gambar') is-invalid @enderror" 
                                               id="gambar" name="gambar" accept="image/*">
                                        <label class="custom-file-label" for="gambar">Pilih gambar baru...</label>
                                    </div>
                                    @error('gambar')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Format: JPEG, PNG, JPG, GIF, WEBP. Maksimal 2MB</small>
                                    
                                    @if($produk->gambar)
                                        <div class="mt-2" id="previewGambar">
                                            <div class="preview-container">
                                                <img src="{{ $produk->gambar_url }}" class="preview-gambar" alt="Gambar Utama">
                                            </div>
                                            <small class="form-text text-info">
                                                <i class="fas fa-info-circle"></i> Gambar saat ini
                                            </small>
                                        </div>
                                    @else
                                        <div class="mt-2" id="previewGambar"></div>
                                    @endif
                                </div>

                                <!-- Info Stok -->
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title">Informasi Stok</h6>
                                        <p class="mb-1">Total Stok: <strong>{{ $produk->total_stok }}</strong></p>
                                        <p class="mb-1">Varian Warna: <strong>{{ $produk->detailWarna->count() }}</strong></p>
                                        <p class="mb-0">Varian Ukuran: <strong>{{ $produk->detailUkuran->count() }}</strong></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Warna Produk -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5>Warna Produk <span class="text-danger">*</span></h5>
                                    <button type="button" class="btn btn-sm btn-primary" id="tambahWarna">
                                        <i class="fas fa-plus"></i> Tambah Warna
                                    </button>
                                </div>
                                
                                <div id="warnaContainer">
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
                                        <div class="card mb-3 warna-item">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-5">
                                                        <div class="form-group">
                                                            <label>Nama Warna</label>
                                                            <input type="text" class="form-control @error('warna.'.$index.'.nama_warna') is-invalid @enderror" 
                                                                   name="warna[{{ $index }}][nama_warna]" 
                                                                   value="{{ $warna['nama_warna'] }}" 
                                                                   placeholder="Contoh: Merah, Biru, Hitam" required maxlength="100">
                                                            @if(isset($warna['id_warna']))
                                                                <input type="hidden" name="warna[{{ $index }}][id_warna]" value="{{ $warna['id_warna'] }}">
                                                            @endif
                                                            @error('warna.'.$index.'.nama_warna')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <div class="form-group">
                                                            <label>Kode Warna</label>
                                                            <input type="text" class="form-control @error('warna.'.$index.'.kode_warna') is-invalid @enderror" 
                                                                   name="warna[{{ $index }}][kode_warna]" 
                                                                   value="{{ $warna['kode_warna'] }}" 
                                                                   placeholder="Contoh: #FF0000 atau red" maxlength="50">
                                                            @error('warna.'.$index.'.kode_warna')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label>&nbsp;</label>
                                                            <button type="button" class="btn btn-danger btn-block hapus-warna" {{ $loop->first && $produk->detailWarna->count() <= 1 ? 'disabled' : '' }}>
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Ukuran dan Harga -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5>Ukuran dan Harga <span class="text-danger">*</span></h5>
                                    <button type="button" class="btn btn-sm btn-primary" id="tambahUkuran">
                                        <i class="fas fa-plus"></i> Tambah Ukuran
                                    </button>
                                </div>
                                
                                <div id="ukuranContainer">
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
                                        <div class="card mb-3 ukuran-item">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label>Warna <span class="text-danger">*</span></label>
                                                            <select class="form-control @error('ukuran.'.$index.'.id_warna') is-invalid @enderror" 
                                                                    name="ukuran[{{ $index }}][id_warna]" required>
                                                                <option value="">Pilih Warna</option>
                                                                @foreach($produk->detailWarna as $warna)
                                                                    <option value="{{ $warna->id_warna }}" 
                                                                            {{ ($ukuran['id_warna'] == $warna->id_warna) ? 'selected' : '' }}>
                                                                        {{ $warna->nama_warna }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            @if(isset($ukuran['id_ukuran']))
                                                                <input type="hidden" name="ukuran[{{ $index }}][id_ukuran]" value="{{ $ukuran['id_ukuran'] }}">
                                                            @endif
                                                            @error('ukuran.'.$index.'.id_warna')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label>Ukuran <span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control @error('ukuran.'.$index.'.ukuran') is-invalid @enderror" 
                                                                   name="ukuran[{{ $index }}][ukuran]" 
                                                                   value="{{ $ukuran['ukuran'] }}" 
                                                                   placeholder="S, M, L, XL" required maxlength="50">
                                                            @error('ukuran.'.$index.'.ukuran')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label>Harga (Rp) <span class="text-danger">*</span></label>
                                                            <input type="number" class="form-control @error('ukuran.'.$index.'.harga') is-invalid @enderror" 
                                                                   name="ukuran[{{ $index }}][harga]" 
                                                                   value="{{ $ukuran['harga'] }}" 
                                                                   min="0" step="1000" placeholder="0" required>
                                                            @error('ukuran.'.$index.'.harga')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label>Stok <span class="text-danger">*</span></label>
                                                            <input type="number" class="form-control @error('ukuran.'.$index.'.stok') is-invalid @enderror" 
                                                                   name="ukuran[{{ $index }}][stok]" 
                                                                   value="{{ $ukuran['stok'] }}" 
                                                                   min="0" placeholder="0" required>
                                                            @error('ukuran.'.$index.'.stok')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label>Keterangan</label>
                                                            <input type="text" class="form-control @error('ukuran.'.$index.'.tambahan') is-invalid @enderror" 
                                                                   name="ukuran[{{ $index }}][tambahan]" 
                                                                   value="{{ $ukuran['tambahan'] }}" 
                                                                   placeholder="Opsional" maxlength="255">
                                                            @error('ukuran.'.$index.'.tambahan')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <div class="form-group">
                                                            <label>&nbsp;</label>
                                                            <button type="button" class="btn btn-danger btn-block hapus-ukuran" {{ $loop->first && $produk->detailUkuran->count() <= 1 ? 'disabled' : '' }}>
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Gambar Produk Tambahan -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5>Gambar Produk Tambahan</h5>
                                    <div>
                                        <a href="{{ route('admin.produk.show', $produk->id_produk) }}#gambar" class="btn btn-sm btn-info mr-2">
                                            <i class="fas fa-images"></i> Kelola Gambar
                                        </a>
                                        <button type="button" class="btn btn-sm btn-primary" id="tambahGambarGroup">
                                            <i class="fas fa-plus"></i> Tambah Gambar
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i>
                                    Untuk mengatur gambar utama atau menghapus gambar, silakan gunakan menu "Kelola Gambar" atau pergi ke halaman detail produk.
                                </div>
                                
                                <div id="gambarContainer">
                                    @php
                                        $gambarData = old('gambar_produk', []);
                                    @endphp

                                    @if(count($gambarData) > 0)
                                        @foreach($gambarData as $index => $gambarGroup)
                                            <div class="card mb-3 gambar-group-item">
                                                <div class="card-header">
                                                    <h6 class="card-title mb-0">Gambar Baru {{ $index + 1 }}</h6>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Pilih Warna (Opsional)</label>
                                                                <select class="form-control" name="gambar_produk[{{ $index }}][id_warna]">
                                                                    <option value="">Pilih Warna</option>
                                                                    @foreach($produk->detailWarna as $warna)
                                                                        <option value="{{ $warna->id_warna }}" 
                                                                                {{ (isset($gambarGroup['id_warna']) && $gambarGroup['id_warna'] == $warna->id_warna) ? 'selected' : '' }}>
                                                                            {{ $warna->nama_warna }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Upload Gambar</label>
                                                                <div class="custom-file">
                                                                    <input type="file" class="custom-file-input @error('gambar_produk.'.$index.'.gambar') is-invalid @enderror" 
                                                                           name="gambar_produk[{{ $index }}][gambar][]" 
                                                                           multiple accept="image/*">
                                                                    <label class="custom-file-label">Pilih beberapa gambar...</label>
                                                                </div>
                                                                @error('gambar_produk.'.$index.'.gambar')
                                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                                @enderror
                                                                <small class="form-text text-muted">Format: JPEG, PNG, JPG, GIF, WEBP. Maksimal 2MB per gambar</small>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label>&nbsp;</label>
                                                                <button type="button" class="btn btn-danger btn-block hapus-gambar-group">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mt-2 preview-gambar-group" id="previewGambarGroup{{ $index }}"></div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <!-- Default empty state -->
                                        <div class="text-center py-4 border rounded bg-light">
                                            <i class="fas fa-images fa-2x text-muted mb-3"></i>
                                            <p class="text-muted mb-0">Tidak ada gambar baru yang akan ditambahkan.</p>
                                            <small class="text-muted">Klik "Tambah Gambar" untuk menambahkan gambar baru.</small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Produk
                        </button>
                        <a href="{{ route('admin.produk.show', $produk->id_produk) }}" class="btn btn-info">
                            <i class="fas fa-eye"></i> Lihat Detail
                        </a>
                        <a href="{{ route('admin.produk.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .warna-item, .ukuran-item, .gambar-group-item {
        border-left: 4px solid #007bff;
    }
    
    .preview-gambar {
        max-width: 150px;
        max-height: 150px;
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 5px;
        margin: 5px;
        object-fit: cover;
    }
    
    .preview-container {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 10px;
    }
    
    .gambar-group-item .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
    }
    
    .card.bg-light {
        border-left: 4px solid #28a745;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Counter untuk warna, ukuran, dan gambar
        let warnaCounter = {{ count($warnaData) }};
        let ukuranCounter = {{ count($ukuranData) }};
        let gambarCounter = {{ count($gambarData) }};
        
        // Preview gambar utama
        const gambarInput = document.getElementById('gambar');
        const previewGambar = document.getElementById('previewGambar');
        
        gambarInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    if (!previewGambar.querySelector('.preview-container')) {
                        previewGambar.innerHTML = '<div class="preview-container"></div>';
                    }
                    const container = previewGambar.querySelector('.preview-container');
                    container.innerHTML = `<img src="${e.target.result}" class="preview-gambar" alt="Preview Gambar">`;
                    
                    // Hapus info gambar lama jika ada
                    const infoText = previewGambar.querySelector('.text-info');
                    if (infoText) {
                        infoText.remove();
                    }
                };
                reader.readAsDataURL(file);
            }
        });
        
        // Update label file input
        document.querySelectorAll('.custom-file-input').forEach(function(input) {
            input.addEventListener('change', function(e) {
                const files = e.target.files;
                let fileName = 'Pilih file...';
                
                if (files.length > 0) {
                    if (files.length === 1) {
                        fileName = files[0].name;
                    } else {
                        fileName = `${files.length} file dipilih`;
                    }
                }
                
                const label = e.target.nextElementSibling;
                label.textContent = fileName;
                
                // Handle preview untuk gambar group
                if (e.target.name && e.target.name.includes('gambar_produk') && e.target.name.includes('[gambar]')) {
                    const groupIndex = e.target.name.match(/\[(\d+)\]/)[1];
                    const previewContainer = document.getElementById(`previewGambarGroup${groupIndex}`);
                    previewContainer.innerHTML = '';
                    
                    if (files.length > 0) {
                        const container = document.createElement('div');
                        container.className = 'preview-container';
                        
                        for (let file of files) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                const img = document.createElement('img');
                                img.src = e.target.result;
                                img.className = 'preview-gambar';
                                img.alt = 'Preview Gambar';
                                container.appendChild(img);
                            };
                            reader.readAsDataURL(file);
                        }
                        
                        previewContainer.appendChild(container);
                    }
                }
            });
        });
        
        // Tambah Warna
        document.getElementById('tambahWarna').addEventListener('click', function() {
            const container = document.getElementById('warnaContainer');
            const newItem = document.createElement('div');
            newItem.className = 'card mb-3 warna-item';
            newItem.innerHTML = `
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Nama Warna</label>
                                <input type="text" class="form-control" 
                                       name="warna[${warnaCounter}][nama_warna]" 
                                       placeholder="Contoh: Merah, Biru, Hitam" required maxlength="100">
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Kode Warna</label>
                                <input type="text" class="form-control" 
                                       name="warna[${warnaCounter}][kode_warna]" 
                                       placeholder="Contoh: #FF0000 atau red" maxlength="50">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <button type="button" class="btn btn-danger btn-block hapus-warna">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
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
            newItem.className = 'card mb-3 ukuran-item';
            newItem.innerHTML = `
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Warna</label>
                                <select class="form-control" name="ukuran[${ukuranCounter}][id_warna]" required>
                                    <option value="">Pilih Warna</option>
                                    @foreach($produk->detailWarna as $warna)
                                        <option value="{{ $warna->id_warna }}">{{ $warna->nama_warna }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Ukuran</label>
                                <input type="text" class="form-control" 
                                       name="ukuran[${ukuranCounter}][ukuran]" 
                                       placeholder="S, M, L, XL" required maxlength="50">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Harga (Rp)</label>
                                <input type="number" class="form-control" 
                                       name="ukuran[${ukuranCounter}][harga]" 
                                       min="0" step="1000" placeholder="0" required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Stok</label>
                                <input type="number" class="form-control" 
                                       name="ukuran[${ukuranCounter}][stok]" 
                                       min="0" placeholder="0" required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Keterangan</label>
                                <input type="text" class="form-control" 
                                       name="ukuran[${ukuranCounter}][tambahan]" 
                                       placeholder="Opsional" maxlength="255">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <button type="button" class="btn btn-danger btn-block hapus-ukuran">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
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
            
            // Hapus empty state jika ada
            if (container.querySelector('.text-center')) {
                container.innerHTML = '';
            }
            
            const newItem = document.createElement('div');
            newItem.className = 'card mb-3 gambar-group-item';
            newItem.innerHTML = `
                <div class="card-header">
                    <h6 class="card-title mb-0">Gambar Baru ${gambarCounter + 1}</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Pilih Warna (Opsional)</label>
                                <select class="form-control" name="gambar_produk[${gambarCounter}][id_warna]">
                                    <option value="">Pilih Warna</option>
                                    @foreach($produk->detailWarna as $warna)
                                        <option value="{{ $warna->id_warna }}">{{ $warna->nama_warna }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Upload Gambar</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" 
                                           name="gambar_produk[${gambarCounter}][gambar][]" 
                                           multiple accept="image/*">
                                    <label class="custom-file-label">Pilih beberapa gambar...</label>
                                </div>
                                <small class="form-text text-muted">Format: JPEG, PNG, JPG, GIF, WEBP. Maksimal 2MB per gambar</small>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <button type="button" class="btn btn-danger btn-block hapus-gambar-group">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="mt-2 preview-gambar-group" id="previewGambarGroup${gambarCounter}"></div>
                </div>
            `;
            container.appendChild(newItem);
            gambarCounter++;
            
            // Re-attach event listeners untuk file input baru
            const newFileInput = newItem.querySelector('.custom-file-input');
            newFileInput.addEventListener('change', function(e) {
                const files = e.target.files;
                let fileName = 'Pilih file...';
                
                if (files.length > 0) {
                    fileName = files.length === 1 ? files[0].name : `${files.length} file dipilih`;
                }
                
                const label = e.target.nextElementSibling;
                label.textContent = fileName;
                
                // Preview gambar
                const groupIndex = e.target.name.match(/\[(\d+)\]/)[1];
                const previewContainer = document.getElementById(`previewGambarGroup${groupIndex}`);
                previewContainer.innerHTML = '';
                
                if (files.length > 0) {
                    const container = document.createElement('div');
                    container.className = 'preview-container';
                    
                    for (let file of files) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const img = document.createElement('img');
                            img.src = e.target.result;
                            img.className = 'preview-gambar';
                            img.alt = 'Preview Gambar';
                            container.appendChild(img);
                        };
                        reader.readAsDataURL(file);
                    }
                    
                    previewContainer.appendChild(container);
                }
            });
            
            updateHapusButtons();
        });
        
        // Hapus items
        document.addEventListener('click', function(e) {
            if (e.target.closest('.hapus-warna')) {
                const item = e.target.closest('.warna-item');
                if (document.querySelectorAll('.warna-item').length > 1) {
                    item.remove();
                    updateHapusButtons();
                    updateUkuranWarnaOptions();
                }
            }
            
            if (e.target.closest('.hapus-ukuran')) {
                const item = e.target.closest('.ukuran-item');
                if (document.querySelectorAll('.ukuran-item').length > 1) {
                    item.remove();
                    updateHapusButtons();
                }
            }
            
            if (e.target.closest('.hapus-gambar-group')) {
                const item = e.target.closest('.gambar-group-item');
                item.remove();
                updateHapusButtons();
                
                // Tampilkan empty state jika tidak ada gambar group
                const container = document.getElementById('gambarContainer');
                if (container.children.length === 0) {
                    container.innerHTML = `
                        <div class="text-center py-4 border rounded bg-light">
                            <i class="fas fa-images fa-2x text-muted mb-3"></i>
                            <p class="text-muted mb-0">Tidak ada gambar baru yang akan ditambahkan.</p>
                            <small class="text-muted">Klik "Tambah Gambar" untuk menambahkan gambar baru.</small>
                        </div>
                    `;
                }
            }
        });
        
        // Update tombol hapus
        function updateHapusButtons() {
            const warnaItems = document.querySelectorAll('.warna-item');
            const ukuranItems = document.querySelectorAll('.ukuran-item');
            const gambarItems = document.querySelectorAll('.gambar-group-item');
            
            document.querySelectorAll('.hapus-warna').forEach(btn => {
                btn.disabled = warnaItems.length <= 1;
            });
            
            document.querySelectorAll('.hapus-ukuran').forEach(btn => {
                btn.disabled = ukuranItems.length <= 1;
            });
        }
        
        // Update opsi warna di ukuran ketika warna ditambah/dihapus
        function updateUkuranWarnaOptions() {
            // Untuk form edit, kita menggunakan ID warna yang sebenarnya
            // Fungsi ini akan diimplementasikan jika perlu menambah warna baru secara dinamis
        }
        
        // Initialize
        updateHapusButtons();
    });
</script>
@endsection
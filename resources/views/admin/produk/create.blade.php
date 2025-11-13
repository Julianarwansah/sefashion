@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Tambah Produk Baru</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.produk.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <form action="{{ route('admin.produk.store') }}" method="POST" enctype="multipart/form-data" id="produkForm">
                    @csrf
                    <div class="card-body">
                        <!-- Informasi Produk -->
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="nama_produk">Nama Produk <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nama_produk') is-invalid @enderror" 
                                           id="nama_produk" name="nama_produk" 
                                           value="{{ old('nama_produk') }}" placeholder="Masukkan nama produk" required maxlength="100">
                                    @error('nama_produk')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="deskripsi">Deskripsi <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                              id="deskripsi" name="deskripsi" rows="4" 
                                              placeholder="Masukkan deskripsi produk" required>{{ old('deskripsi') }}</textarea>
                                    @error('deskripsi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="kategori">Kategori <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('kategori') is-invalid @enderror" 
                                           id="kategori" name="kategori" 
                                           value="{{ old('kategori') }}" placeholder="Masukkan kategori produk" required maxlength="50">
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
                                        <label class="custom-file-label" for="gambar">Pilih gambar...</label>
                                    </div>
                                    @error('gambar')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Format: JPEG, PNG, JPG, GIF. Maksimal 2MB</small>
                                    <div class="mt-2" id="previewGambar"></div>
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
                                    @if(old('warna'))
                                        @foreach(old('warna') as $index => $warna)
                                            <div class="card mb-3 warna-item">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-5">
                                                            <div class="form-group">
                                                                <label>Nama Warna</label>
                                                                <input type="text" class="form-control @error('warna.'.$index.'.nama_warna') is-invalid @enderror" 
                                                                       name="warna[{{ $index }}][nama_warna]" 
                                                                       value="{{ $warna['nama_warna'] ?? '' }}" 
                                                                       placeholder="Contoh: Merah, Biru, Hitam" required maxlength="100">
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
                                                                       value="{{ $warna['kode_warna'] ?? '' }}" 
                                                                       placeholder="Contoh: #FF0000 atau red" required maxlength="50">
                                                                @error('warna.'.$index.'.kode_warna')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label>&nbsp;</label>
                                                                <button type="button" class="btn btn-danger btn-block hapus-warna" {{ $loop->first ? 'disabled' : '' }}>
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <!-- Default satu warna -->
                                        <div class="card mb-3 warna-item">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-5">
                                                        <div class="form-group">
                                                            <label>Nama Warna</label>
                                                            <input type="text" class="form-control" 
                                                                   name="warna[0][nama_warna]" 
                                                                   placeholder="Contoh: Merah, Biru, Hitam" required maxlength="100">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <div class="form-group">
                                                            <label>Kode Warna</label>
                                                            <input type="text" class="form-control" 
                                                                   name="warna[0][kode_warna]" 
                                                                   placeholder="Contoh: #FF0000 atau red" required maxlength="50">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label>&nbsp;</label>
                                                            <button type="button" class="btn btn-danger btn-block hapus-warna" disabled>
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
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
                                    @if(old('ukuran'))
                                        @foreach(old('ukuran') as $index => $ukuran)
                                            <div class="card mb-3 ukuran-item">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label>Warna</label>
                                                                <select class="form-control @error('ukuran.'.$index.'.id_warna_index') is-invalid @enderror" 
                                                                        name="ukuran[{{ $index }}][id_warna_index]" required>
                                                                    <option value="">Pilih Warna</option>
                                                                    <!-- Options akan diisi oleh JavaScript -->
                                                                </select>
                                                                @error('ukuran.'.$index.'.id_warna_index')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label>Ukuran</label>
                                                                <input type="text" class="form-control @error('ukuran.'.$index.'.ukuran') is-invalid @enderror" 
                                                                       name="ukuran[{{ $index }}][ukuran]" 
                                                                       value="{{ $ukuran['ukuran'] ?? '' }}" 
                                                                       placeholder="S, M, L, XL" required maxlength="50">
                                                                @error('ukuran.'.$index.'.ukuran')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label>Harga (Rp)</label>
                                                                <input type="number" class="form-control @error('ukuran.'.$index.'.harga') is-invalid @enderror" 
                                                                       name="ukuran[{{ $index }}][harga]" 
                                                                       value="{{ $ukuran['harga'] ?? '' }}" 
                                                                       min="0" step="1000" placeholder="0" required>
                                                                @error('ukuran.'.$index.'.harga')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label>Stok</label>
                                                                <input type="number" class="form-control @error('ukuran.'.$index.'.stok') is-invalid @enderror" 
                                                                       name="ukuran[{{ $index }}][stok]" 
                                                                       value="{{ $ukuran['stok'] ?? '' }}" 
                                                                       min="0" placeholder="0" required>
                                                                @error('ukuran.'.$index.'.stok')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label>&nbsp;</label>
                                                                <button type="button" class="btn btn-danger btn-block hapus-ukuran" {{ $loop->first ? 'disabled' : '' }}>
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <!-- Default satu ukuran -->
                                        <div class="card mb-3 ukuran-item">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label>Warna</label>
                                                            <select class="form-control" name="ukuran[0][id_warna_index]" required>
                                                                <option value="">Pilih Warna</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label>Ukuran</label>
                                                            <input type="text" class="form-control" 
                                                                   name="ukuran[0][ukuran]" 
                                                                   placeholder="S, M, L, XL" required maxlength="50">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label>Harga (Rp)</label>
                                                            <input type="number" class="form-control" 
                                                                   name="ukuran[0][harga]" 
                                                                   min="0" step="1000" placeholder="0" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label>Stok</label>
                                                            <input type="number" class="form-control" 
                                                                   name="ukuran[0][stok]" 
                                                                   min="0" placeholder="0" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label>&nbsp;</label>
                                                            <button type="button" class="btn btn-danger btn-block hapus-ukuran" disabled>
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Gambar Produk Tambahan -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5>Gambar Produk Tambahan</h5>
                                    <button type="button" class="btn btn-sm btn-primary" id="tambahGambarGroup">
                                        <i class="fas fa-plus"></i> Tambah Group Gambar
                                    </button>
                                </div>
                                
                                <div id="gambarContainer">
                                    @if(old('gambar_produk'))
                                        @foreach(old('gambar_produk') as $index => $gambarGroup)
                                            <div class="card mb-3 gambar-group-item">
                                                <div class="card-header">
                                                    <h6 class="card-title mb-0">Group Gambar {{ $index + 1 }}</h6>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Pilih Warna (Opsional)</label>
                                                                <select class="form-control" name="gambar_produk[{{ $index }}][id_warna_index]">
                                                                    <option value="">Pilih Warna</option>
                                                                    <!-- Options akan diisi oleh JavaScript -->
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Upload Gambar</label>
                                                                <div class="custom-file">
                                                                    <input type="file" class="custom-file-input" 
                                                                           name="gambar_produk[{{ $index }}][gambar][]" 
                                                                           multiple accept="image/*">
                                                                    <label class="custom-file-label">Pilih beberapa gambar...</label>
                                                                </div>
                                                                <small class="form-text text-muted">Format: JPEG, PNG, JPG, GIF. Maksimal 2MB per gambar</small>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label>&nbsp;</label>
                                                                <button type="button" class="btn btn-danger btn-block hapus-gambar-group" {{ $loop->first ? 'disabled' : '' }}>
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
                                        <!-- Default satu group gambar -->
                                        <div class="card mb-3 gambar-group-item">
                                            <div class="card-header">
                                                <h6 class="card-title mb-0">Group Gambar 1</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Pilih Warna (Opsional)</label>
                                                            <select class="form-control" name="gambar_produk[0][id_warna_index]">
                                                                <option value="">Pilih Warna</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Upload Gambar</label>
                                                            <div class="custom-file">
                                                                <input type="file" class="custom-file-input" 
                                                                       name="gambar_produk[0][gambar][]" 
                                                                       multiple accept="image/*">
                                                                <label class="custom-file-label">Pilih beberapa gambar...</label>
                                                            </div>
                                                            <small class="form-text text-muted">Format: JPEG, PNG, JPG, GIF. Maksimal 2MB per gambar</small>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label>&nbsp;</label>
                                                            <button type="button" class="btn btn-danger btn-block hapus-gambar-group" disabled>
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mt-2 preview-gambar-group" id="previewGambarGroup0"></div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Produk
                        </button>
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
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Counter untuk warna, ukuran, dan gambar
        let warnaCounter = {{ old('warna') ? count(old('warna')) : 1 }};
        let ukuranCounter = {{ old('ukuran') ? count(old('ukuran')) : 1 }};
        let gambarCounter = {{ old('gambar_produk') ? count(old('gambar_produk')) : 1 }};
        
        // Preview gambar utama
        const gambarInput = document.getElementById('gambar');
        const previewGambar = document.getElementById('previewGambar');
        
        gambarInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewGambar.innerHTML = `
                        <div class="preview-container">
                            <img src="${e.target.result}" class="preview-gambar" alt="Preview Gambar">
                        </div>
                    `;
                };
                reader.readAsDataURL(file);
            } else {
                previewGambar.innerHTML = '';
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
                                       placeholder="Contoh: #FF0000 atau red" required maxlength="50">
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
            updateWarnaOptions();
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
                                <select class="form-control" name="ukuran[${ukuranCounter}][id_warna_index]" required>
                                    <option value="">Pilih Warna</option>
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
            
            updateWarnaOptions();
            updateHapusButtons();
        });
        
        // Tambah Group Gambar
        document.getElementById('tambahGambarGroup').addEventListener('click', function() {
            const container = document.getElementById('gambarContainer');
            const newItem = document.createElement('div');
            newItem.className = 'card mb-3 gambar-group-item';
            newItem.innerHTML = `
                <div class="card-header">
                    <h6 class="card-title mb-0">Group Gambar ${gambarCounter + 1}</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Pilih Warna (Opsional)</label>
                                <select class="form-control" name="gambar_produk[${gambarCounter}][id_warna_index]">
                                    <option value="">Pilih Warna</option>
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
                                <small class="form-text text-muted">Format: JPEG, PNG, JPG, GIF. Maksimal 2MB per gambar</small>
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
            
            updateWarnaOptions();
            updateHapusButtons();
        });
        
        // Hapus items
        document.addEventListener('click', function(e) {
            if (e.target.closest('.hapus-warna')) {
                const item = e.target.closest('.warna-item');
                if (document.querySelectorAll('.warna-item').length > 1) {
                    item.remove();
                    updateHapusButtons();
                    updateWarnaOptions();
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
                if (document.querySelectorAll('.gambar-group-item').length > 1) {
                    item.remove();
                    updateHapusButtons();
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
            
            document.querySelectorAll('.hapus-gambar-group').forEach(btn => {
                btn.disabled = gambarItems.length <= 1;
            });
        }
        
        // Update opsi warna di semua select
        function updateWarnaOptions() {
            const warnaInputs = document.querySelectorAll('input[name^="warna["][name$="[nama_warna]"]');
            const semuaSelect = document.querySelectorAll('select[name^="ukuran["], select[name^="gambar_produk["]');
            
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
        
        // Update ketika input nama warna berubah
        document.addEventListener('input', function(e) {
            if (e.target.name && e.target.name.includes('[nama_warna]')) {
                updateWarnaOptions();
            }
        });
        
        // Initialize
        updateWarnaOptions();
        updateHapusButtons();
    });
</script>
@endsection
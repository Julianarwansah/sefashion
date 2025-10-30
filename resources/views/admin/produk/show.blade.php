@extends('layouts.app')

@section('title', 'Detail Produk - ' . $produk->nama_produk)

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Produk</h1>
        <div class="btn-group">
            <a href="{{ route('produk.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <a href="{{ route('produk.edit', $produk->id_produk) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit
            </a>
            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal">
                <i class="fas fa-trash"></i> Hapus
            </button>
        </div>
    </div>

    <!-- Alert -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <!-- Informasi Produk -->
        <div class="col-lg-8">
            <!-- Card Informasi Utama -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Produk</h6>
                    <span class="badge badge-{{ $produk->total_stok > 0 ? 'success' : 'danger' }}">
                        {{ $produk->total_stok > 0 ? 'Tersedia' : 'Habis' }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center">
                            @if($produk->gambar)
                                <img src="{{ $produk->gambar_url }}" alt="{{ $produk->nama_produk }}" 
                                     class="img-fluid rounded shadow-sm" style="max-height: 200px;">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                     style="height: 200px;">
                                    <i class="fas fa-image fa-3x text-muted"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-8">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">Nama Produk</th>
                                    <td>{{ $produk->nama_produk }}</td>
                                </tr>
                                <tr>
                                    <th>Kategori</th>
                                    <td>
                                        @if($produk->kategori)
                                            <span class="badge badge-info">{{ $produk->kategori }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Total Stok</th>
                                    <td>
                                        <span class="font-weight-bold {{ $produk->total_stok > 0 ? 'text-success' : 'text-danger' }}">
                                            {{ $produk->total_stok }} item
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Deskripsi</th>
                                    <td>
                                        @if($produk->deskripsi)
                                            {{ $produk->deskripsi }}
                                        @else
                                            <span class="text-muted">Tidak ada deskripsi</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Tanggal Dibuat</th>
                                    <td>{{ $produk->created_at->translatedFormat('d F Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Terakhir Diupdate</th>
                                    <td>{{ $produk->updated_at->translatedFormat('d F Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Varian & Stok -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Varian & Stok Produk</h6>
                </div>
                <div class="card-body">
                    @if($produk->detailWarna->count() > 0)
                        @foreach($produk->detailWarna as $warna)
                            <div class="mb-4 border-bottom pb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="color-preview mr-2" 
                                         style="width: 20px; height: 20px; background-color: {{ $warna->kode_warna_hex ?? '#ccc' }}; border-radius: 50%; border: 1px solid #dee2e6;">
                                    </div>
                                    <h6 class="mb-0 font-weight-bold">{{ $warna->nama_warna }}</h6>
                                    <small class="text-muted ml-2">(Kode: {{ $warna->kode_warna ?? '-' }})</small>
                                    <span class="badge badge-secondary ml-2">
                                        Stok: {{ $warna->total_stok }}
                                    </span>
                                </div>
                                
                                @if($warna->detailUkuran->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th>Ukuran</th>
                                                    <th>Harga</th>
                                                    <th>Stok</th>
                                                    <th>Keterangan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($warna->detailUkuran as $ukuran)
                                                    <tr class="{{ $ukuran->stok == 0 ? 'table-danger' : '' }}">
                                                        <td class="font-weight-bold">{{ $ukuran->ukuran }}</td>
                                                        <td class="text-success font-weight-bold">
                                                            Rp {{ number_format($ukuran->harga, 0, ',', '.') }}
                                                        </td>
                                                        <td>
                                                            <span class="{{ $ukuran->stok == 0 ? 'text-danger' : 'text-success' }} font-weight-bold">
                                                                {{ $ukuran->stok }}
                                                            </span>
                                                        </td>
                                                        <td class="text-muted">
                                                            {{ $ukuran->tambahan ?: '-' }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p class="text-muted mb-0">Tidak ada ukuran untuk warna ini.</p>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-palette fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Belum ada varian warna untuk produk ini.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Card Gambar Produk -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Gambar Produk</h6>
                </div>
                <div class="card-body">
                    @if($produk->gambarProduk->count() > 0)
                        <div class="row">
                            @foreach($produk->gambarProduk as $gambar)
                                <div class="col-6 mb-3">
                                    <div class="position-relative">
                                        <img src="{{ $gambar->gambar_url }}" 
                                             alt="Gambar Produk" 
                                             class="img-fluid rounded shadow-sm"
                                             style="height: 100px; width: 100%; object-fit: cover;">
                                        @if($gambar->is_primary)
                                            <span class="position-absolute top-0 start-0 badge badge-primary m-1">
                                                Utama
                                            </span>
                                        @endif
                                        <div class="mt-1 text-center">
                                            @if(!$gambar->is_primary)
                                                <form action="{{ route('produk.set-primary-image', ['produk' => $produk->id_produk, 'gambar' => $gambar->id_gambar]) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-primary" 
                                                            title="Jadikan gambar utama">
                                                        <i class="fas fa-star"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            <form action="{{ route('produk.delete-image', ['produk' => $produk->id_produk, 'gambar' => $gambar->id_gambar]) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                        onclick="return confirm('Hapus gambar ini?')"
                                                        title="Hapus gambar">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-images fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Belum ada gambar tambahan untuk produk ini.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Card Statistik -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Statistik Produk</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="border rounded p-3">
                                <div class="text-primary font-weight-bold" style="font-size: 1.5rem;">
                                    {{ $produk->detailWarna->count() }}
                                </div>
                                <small class="text-muted">Varian Warna</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="border rounded p-3">
                                <div class="text-success font-weight-bold" style="font-size: 1.5rem;">
                                    {{ $produk->detailUkuran->count() }}
                                </div>
                                <small class="text-muted">Varian Ukuran</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-3">
                                <div class="text-info font-weight-bold" style="font-size: 1.5rem;">
                                    {{ $produk->gambarProduk->count() }}
                                </div>
                                <small class="text-muted">Total Gambar</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-3">
                                <div class="{{ $produk->total_stok > 0 ? 'text-success' : 'text-danger' }} font-weight-bold" style="font-size: 1.5rem;">
                                    {{ $produk->total_stok }}
                                </div>
                                <small class="text-muted">Total Stok</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Harga -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Ringkasan Harga</h6>
                </div>
                <div class="card-body">
                    @if($produk->detailUkuran->count() > 0)
                        @php
                            $hargaTertinggi = $produk->detailUkuran->max('harga');
                            $hargaTerendah = $produk->detailUkuran->min('harga');
                        @endphp
                        
                        <div class="text-center mb-3">
                            <div class="h4 text-success font-weight-bold">
                                Rp {{ number_format($hargaTerendah, 0, ',', '.') }}
                            </div>
                            <small class="text-muted">Harga mulai dari</small>
                        </div>
                        
                        @if($hargaTertinggi != $hargaTerendah)
                            <div class="d-flex justify-content-between text-muted">
                                <small>Terendah</small>
                                <small>Tertinggi</small>
                            </div>
                            <div class="progress mb-2" style="height: 5px;">
                                <div class="progress-bar bg-success" style="width: 100%"></div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <small class="text-success">Rp {{ number_format($hargaTerendah, 0, ',', '.') }}</small>
                                <small class="text-success">Rp {{ number_format($hargaTertinggi, 0, ',', '.') }}</small>
                            </div>
                        @endif
                    @else
                        <p class="text-muted text-center">Belum ada informasi harga</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus Produk</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus produk <strong>{{ $produk->nama_produk }}</strong>?</p>
                <div class="alert alert-warning">
                    <small>
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Peringatan:</strong> Tindakan ini akan menghapus semua data terkait termasuk varian warna, ukuran, dan gambar. Tindakan ini tidak dapat dibatalkan.
                    </small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <form action="{{ route('produk.destroy', $produk->id_produk) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus Produk</button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.color-preview {
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
.card {
    border: none;
    border-radius: 10px;
}
.table th {
    border-top: none;
    font-weight: 600;
}
.badge {
    font-size: 0.75em;
}
</style>
<script>
    $(document).ready(function() {
        // Auto dismiss alert setelah 5 detik
        setTimeout(function() {
            $('.alert').alert('close');
        }, 5000);
    });
</script>
@endsection


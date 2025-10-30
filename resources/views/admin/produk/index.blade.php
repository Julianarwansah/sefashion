@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Data Produk</h3>
                    <a href="{{ route('produk.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Produk
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($produk->isEmpty())
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle"></i> Belum ada data produk.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Gambar</th>
                                        <th>Nama Produk</th>
                                        <th>Kategori</th>
                                        <th>Warna</th>
                                        <th>Ukuran</th>
                                        <th>Stok</th>
                                        <th width="150">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($produk as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <img src="{{ $item->gambar_url }}" alt="{{ $item->nama_produk }}" 
                                                 class="img-thumbnail" style="width: 60px; height: 60px; object-fit: cover;">
                                        </td>
                                        <td>{{ $item->nama_produk }}</td>
                                        <td>
                                            <span class="badge bg-info">{{ $item->kategori }}</span>
                                        </td>
                                        <td>
                                            @foreach($item->detailWarna as $warna)
                                                <span class="badge bg-secondary me-1">{{ $warna->nama_warna }}</span>
                                            @endforeach
                                        </td>
                                        <td>
                                            @foreach($item->detailUkuran->unique('ukuran') as $ukuran)
                                                <span class="badge bg-light text-dark me-1">{{ $ukuran->ukuran }}</span>
                                            @endforeach
                                        </td>
                                        <td>
                                            <span class="badge {{ $item->total_stok > 0 ? 'bg-success' : 'bg-danger' }}">
                                                {{ $item->total_stok }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('produk.show', $item->id_produk) }}" 
                                                   class="btn btn-info" title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('produk.edit', $item->id_produk) }}" 
                                                   class="btn btn-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('produk.destroy', $item->id_produk) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger" 
                                                            title="Hapus"
                                                            onclick="return confirm('Yakin ingin menghapus produk {{ $item->nama_produk }}?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
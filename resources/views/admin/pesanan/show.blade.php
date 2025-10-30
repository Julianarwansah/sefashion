@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('pesanan.index') }}" class="text-decoration-none">Pesanan</a></li>
                    <li class="breadcrumb-item active">Detail #{{ $pesanan->id_pemesanan }}</li>
                </ol>
            </nav>
            <h1 class="h3 text-gray-800 mb-1">
                <i class="fas fa-file-invoice me-2"></i>Detail Pesanan
            </h1>
            <p class="text-muted mb-0">#{{ $pesanan->id_pemesanan }} - {{ $pesanan->customer->nama_customer ?? 'N/A' }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('pesanan.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
            <a href="{{ route('pesanan.edit', $pesanan->id_pemesanan) }}" class="btn btn-warning">
                <i class="fas fa-edit me-2"></i>Edit
            </a>
        </div>
    </div>

    <!-- Alert Notifikasi -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Informasi Pesanan -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2 text-primary"></i>Informasi Pesanan
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small mb-1">ID Pesanan</label>
                            <div class="fw-bold">#{{ $pesanan->id_pemesanan }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small mb-1">Tanggal Pemesanan</label>
                            <div class="fw-bold">{{ $pesanan->tanggal_pemesanan_formatted }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small mb-1">Status</label>
                            <div>
                                @php
                                    $statusColors = [
                                        'pending' => 'warning',
                                        'diproses' => 'info',
                                        'dikirim' => 'primary',
                                        'selesai' => 'success',
                                        'batal' => 'danger'
                                    ];
                                    $color = $statusColors[$pesanan->status] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $color }} fs-6">
                                    <i class="fas 
                                        @if($pesanan->status == 'pending') fa-clock 
                                        @elseif($pesanan->status == 'diproses') fa-cog 
                                        @elseif($pesanan->status == 'dikirim') fa-shipping-fast 
                                        @elseif($pesanan->status == 'selesai') fa-check 
                                        @elseif($pesanan->status == 'batal') fa-times 
                                        @endif me-1">
                                    </i>
                                    {{ ucfirst($pesanan->status) }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small mb-1">Total Harga</label>
                            <div class="h5 text-success fw-bold">{{ $pesanan->total_harga_formatted }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informasi Customer -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user me-2 text-info"></i>Informasi Customer
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <div class="bg-info rounded-circle d-flex align-items-center justify-content-center" 
                             style="width: 60px; height: 60px;">
                            <i class="fas fa-user text-white fa-lg"></i>
                        </div>
                        <div class="ms-3">
                            <h5 class="mb-1">{{ $pesanan->customer->nama_customer ?? 'N/A' }}</h5>
                            <p class="text-muted mb-0">{{ $pesanan->customer->email ?? '-' }}</p>
                            @if($pesanan->customer->telepon ?? false)
                                <p class="text-muted mb-0">
                                    <i class="fas fa-phone me-1"></i>{{ $pesanan->customer->telepon }}
                                </p>
                            @endif
                        </div>
                    </div>
                    @if($pesanan->customer->alamat ?? false)
                    <div>
                        <label class="form-label text-muted small mb-1">Alamat</label>
                        <p class="mb-0">{{ $pesanan->customer->alamat }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Items Pesanan -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-boxes me-2 text-success"></i>Items Pesanan
                    </h5>
                    <span class="badge bg-primary fs-6">
                        {{ $pesanan->detailPemesanan->count() }} Items
                    </span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="px-4">Produk</th>
                                    <th>Ukuran</th>
                                    <th class="text-center">Harga Satuan</th>
                                    <th class="text-center">Jumlah</th>
                                    <th class="text-end px-4">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pesanan->detailPemesanan as $detail)
                                <tr>
                                    <td class="px-4">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                                 style="width: 40px; height: 40px;">
                                                <i class="fas fa-box text-muted"></i>
                                            </div>
                                            <div class="ms-3">
                                                <strong class="d-block">{{ $detail->detailUkuran->produk->nama_produk ?? 'N/A' }}</strong>
                                                <small class="text-muted">SKU: {{ $detail->detailUkuran->produk->sku ?? '-' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $detail->detailUkuran->ukuran ?? 'N/A' }}</span>
                                    </td>
                                    <td class="text-center">
                                        <strong>{{ $detail->harga_satuan_formatted }}</strong>
                                    </td>
                                    <td class="text-center">
                                        <span class="fw-bold">{{ $detail->jumlah }}</span>
                                    </td>
                                    <td class="text-end px-4">
                                        <strong class="text-success">{{ $detail->subtotal_formatted }}</strong>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="3"></td>
                                    <td class="text-center fw-bold">Total:</td>
                                    <td class="text-end px-4">
                                        <h5 class="text-success fw-bold mb-0">{{ $pesanan->total_harga_formatted }}</h5>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Status -->
    @if($pesanan->canBeCancelled())
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-sync-alt me-2 text-warning"></i>Update Status Pesanan
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('pesanan.update', $pesanan->id_pemesanan) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="update_only_status" value="true">
                        <div class="row align-items-end">
                            <div class="col-md-6">
                                <label class="form-label">Ubah Status</label>
                                <select name="status" class="form-select" required>
                                    <option value="pending" {{ $pesanan->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="diproses" {{ $pesanan->status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                                    <option value="dikirim" {{ $pesanan->status == 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                                    <option value="selesai" {{ $pesanan->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                    <option value="batal" {{ $pesanan->status == 'batal' ? 'selected' : '' }}>Batal</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-save me-2"></i>Update Status
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
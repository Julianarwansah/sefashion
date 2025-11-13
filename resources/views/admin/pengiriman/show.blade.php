@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.pengiriman.index') }}" class="text-decoration-none">Pengiriman</a></li>
                    <li class="breadcrumb-item active">Detail #{{ $pengiriman->id_pengiriman }}</li>
                </ol>
            </nav>
            <h1 class="h3 text-gray-800 mb-1">
                <i class="fas fa-shipping-fast me-2"></i>Detail Pengiriman
            </h1>
            <p class="text-muted mb-0">#{{ $pengiriman->id_pengiriman }} - {{ $pengiriman->pemesanan->customer->nama_customer ?? 'N/A' }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.pengiriman.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
            <a href="{{ route('admin.pengiriman.edit', $pengiriman->id_pengiriman) }}" class="btn btn-warning">
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
        <!-- Informasi Pengiriman -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2 text-primary"></i>Informasi Pengiriman
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small mb-1">ID Pengiriman</label>
                            <div class="fw-bold">#{{ $pengiriman->id_pengiriman }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small mb-1">Status</label>
                            <div>
                                @php
                                    $statusColors = [
                                        'menunggu' => 'warning',
                                        'dikirim' => 'primary',
                                        'diterima' => 'success',
                                        'gagal' => 'danger'
                                    ];
                                    $color = $statusColors[$pengiriman->status_pengiriman] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $color }} fs-6">
                                    <i class="fas 
                                        @if($pengiriman->status_pengiriman == 'menunggu') fa-clock 
                                        @elseif($pengiriman->status_pengiriman == 'dikirim') fa-shipping-fast 
                                        @elseif($pengiriman->status_pengiriman == 'diterima') fa-check 
                                        @elseif($pengiriman->status_pengiriman == 'gagal') fa-times 
                                        @endif me-1">
                                    </i>
                                    {{ $pengiriman->status_pengiriman_text }}
                                </span>
                                @if($pengiriman->isLate())
                                    <br>
                                    <small class="text-danger mt-1 d-block">
                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                        Terlambat {{ $pengiriman->days_late }} hari
                                    </small>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small mb-1">Ekspedisi</label>
                            <div class="fw-bold">{{ $pengiriman->ekspedisi }}</div>
                            <small class="text-muted">{{ $pengiriman->layanan }}</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small mb-1">Biaya Ongkir</label>
                            <div class="h5 text-success fw-bold">{{ $pengiriman->biaya_ongkir_formatted }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small mb-1">Tanggal Dikirim</label>
                            <div class="fw-bold">{{ $pengiriman->tanggal_dikirim_formatted }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small mb-1">Tanggal Diterima</label>
                            <div class="fw-bold">{{ $pengiriman->tanggal_diterima_formatted }}</div>
                        </div>
                        @if($pengiriman->no_resi)
                        <div class="col-12 mb-3">
                            <label class="form-label text-muted small mb-1">Nomor Resi</label>
                            <div class="d-flex align-items-center gap-2">
                                <code class="fs-5 text-primary">{{ $pengiriman->no_resi }}</code>
                                @if($pengiriman->tracking_url)
                                <a href="{{ $pengiriman->tracking_url }}" target="_blank" class="btn btn-sm btn-outline-info">
                                    <i class="fas fa-external-link-alt me-1"></i>Lacak Pengiriman
                                </a>
                                @endif
                            </div>
                        </div>
                        @endif
                        @if($pengiriman->estimasi_pengiriman)
                        <div class="col-12 mb-3">
                            <label class="form-label text-muted small mb-1">Estimasi Sampai</label>
                            <div class="fw-bold">
                                {{ $pengiriman->estimasi_pengiriman_formatted }}
                                @if($pengiriman->isLate())
                                    <span class="badge bg-danger ms-2">Terlambat</span>
                                @elseif($pengiriman->isShipped())
                                    <span class="badge bg-warning ms-2">Dalam Perjalanan</span>
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Informasi Penerima -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user me-2 text-info"></i>Informasi Penerima
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <label class="form-label text-muted small mb-1">Nama Penerima</label>
                        <div class="fw-bold fs-5">{{ $pengiriman->nama_penerima }}</div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label text-muted small mb-1">No. HP Penerima</label>
                        <div class="fw-bold">
                            <i class="fas fa-phone me-2 text-muted"></i>
                            {{ $pengiriman->no_hp_penerima }}
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small mb-1">Alamat Tujuan</label>
                        <div class="border rounded p-3 bg-light">
                            {{ $pengiriman->alamat_tujuan }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Informasi Pemesanan -->
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-shopping-cart me-2 text-success"></i>Informasi Pemesanan
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label text-muted small mb-1">ID Pemesanan</label>
                            <div class="fw-bold">#{{ $pengiriman->pemesanan->id_pemesanan }}</div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label text-muted small mb-1">Customer</label>
                            <div class="fw-bold">{{ $pengiriman->pemesanan->customer->nama_customer ?? 'N/A' }}</div>
                            <small class="text-muted">{{ $pengiriman->pemesanan->customer->email ?? '-' }}</small>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label text-muted small mb-1">Total Pemesanan</label>
                            <div class="fw-bold text-success">{{ $pengiriman->pemesanan->total_harga_formatted }}</div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label text-muted small mb-1">Status Pemesanan</label>
                            <div>
                                @php
                                    $statusPemesananColors = [
                                        'pending' => 'warning',
                                        'diproses' => 'info',
                                        'dikirim' => 'primary',
                                        'selesai' => 'success',
                                        'batal' => 'danger'
                                    ];
                                    $colorPemesanan = $statusPemesananColors[$pengiriman->pemesanan->status] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $colorPemesanan }}">
                                    {{ ucfirst($pengiriman->pemesanan->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Items Pemesanan -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-boxes me-2 text-warning"></i>Items Pemesanan
                    </h5>
                    <span class="badge bg-primary fs-6">
                        {{ $pengiriman->pemesanan->detailPemesanan->count() }} Items
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
                                @foreach($pengiriman->pemesanan->detailPemesanan as $detail)
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
                                    <td class="text-center fw-bold">Total Pemesanan:</td>
                                    <td class="text-end px-4">
                                        <h5 class="text-primary fw-bold mb-0">{{ $pengiriman->pemesanan->total_harga_formatted }}</h5>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3"></td>
                                    <td class="text-center fw-bold">Biaya Ongkir:</td>
                                    <td class="text-end px-4">
                                        <h5 class="text-warning fw-bold mb-0">{{ $pengiriman->biaya_ongkir_formatted }}</h5>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3"></td>
                                    <td class="text-center fw-bold">Total + Ongkir:</td>
                                    <td class="text-end px-4">
                                        <h4 class="text-success fw-bold mb-0">{{ $pengiriman->total_harga_dengan_ongkir_formatted }}</h4>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    @if($pengiriman->isInTransit())
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bolt me-2 text-warning"></i>Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @if($pengiriman->status_pengiriman == 'menunggu' && !$pengiriman->no_resi)
                        <div class="col-md-4 mb-3">
                            <form action="{{ route('admin.pengiriman.update', $pengiriman->id_pengiriman) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="mark_as_shipped" value="true">
                                <div class="mb-2">
                                    <label class="form-label">Nomor Resi</label>
                                    <input type="text" name="no_resi" class="form-control" placeholder="Masukkan nomor resi" required>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-shipping-fast me-2"></i>Tandai Dikirim
                                </button>
                            </form>
                        </div>
                        @endif

                        @if($pengiriman->status_pengiriman == 'dikirim')
                        <div class="col-md-4 mb-3">
                            <form action="{{ route('admin.pengiriman.update', $pengiriman->id_pengiriman) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="update_only_status" value="true">
                                <input type="hidden" name="status_pengiriman" value="diterima">
                                <button type="submit" class="btn btn-success w-100 h-100" 
                                        onclick="return confirm('Tandai pengiriman sebagai sudah diterima?')">
                                    <i class="fas fa-check me-2"></i>Tandai Diterima
                                </button>
                            </form>
                        </div>
                        @endif

                        @if($pengiriman->isInTransit())
                        <div class="col-md-4 mb-3">
                            <form action="{{ route('admin.pengiriman.update', $pengiriman->id_pengiriman) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="update_only_status" value="true">
                                <input type="hidden" name="status_pengiriman" value="gagal">
                                <button type="submit" class="btn btn-danger w-100 h-100" 
                                        onclick="return confirm('Tandai pengiriman sebagai gagal?')">
                                    <i class="fas fa-times me-2"></i>Tandai Gagal
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
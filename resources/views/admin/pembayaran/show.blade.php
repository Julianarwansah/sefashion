@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('pembayaran.index') }}" class="text-decoration-none">Pembayaran</a></li>
                    <li class="breadcrumb-item active">Detail #{{ $pembayaran->id_pembayaran }}</li>
                </ol>
            </nav>
            <h1 class="h3 text-gray-800 mb-1">
                <i class="fas fa-file-invoice-dollar me-2"></i>Detail Pembayaran
            </h1>
            <p class="text-muted mb-0">#{{ $pembayaran->id_pembayaran }} - {{ $pembayaran->pemesanan->customer->nama_customer ?? 'N/A' }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('pembayaran.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
            <a href="{{ route('pembayaran.edit', $pembayaran->id_pembayaran) }}" class="btn btn-warning">
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
        <!-- Informasi Pembayaran -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2 text-primary"></i>Informasi Pembayaran
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small mb-1">ID Pembayaran</label>
                            <div class="fw-bold">#{{ $pembayaran->id_pembayaran }}</div>
                            @if($pembayaran->external_id)
                                <small class="text-muted">{{ $pembayaran->external_id }}</small>
                            @endif
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small mb-1">Tanggal Pembayaran</label>
                            <div class="fw-bold">{{ $pembayaran->tanggal_pembayaran_formatted }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small mb-1">Metode Pembayaran</label>
                            <div>
                                <span class="badge bg-info fs-6">
                                    <i class="fas 
                                        @if($pembayaran->metode_pembayaran == 'transfer') fa-university 
                                        @elseif($pembayaran->metode_pembayaran == 'cod') fa-money-bill 
                                        @elseif($pembayaran->metode_pembayaran == 'ewallet') fa-mobile-alt 
                                        @elseif($pembayaran->metode_pembayaran == 'va') fa-credit-card 
                                        @elseif($pembayaran->metode_pembayaran == 'qris') fa-qrcode 
                                        @elseif($pembayaran->metode_pembayaran == 'credit_card') fa-credit-card 
                                        @else fa-money-bill 
                                        @endif me-1">
                                    </i>
                                    {{ $pembayaran->metode_pembayaran_text }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small mb-1">Channel</label>
                            <div class="fw-bold">{{ $pembayaran->channel ?? '-' }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small mb-1">Status</label>
                            <div>
                                @php
                                    $statusColors = [
                                        'belum_bayar' => 'warning',
                                        'menunggu' => 'info',
                                        'sudah_bayar' => 'success',
                                        'gagal' => 'danger',
                                        'expired' => 'secondary',
                                        'refund' => 'dark'
                                    ];
                                    $color = $statusColors[$pembayaran->status_pembayaran] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $color }} fs-6">
                                    <i class="fas 
                                        @if($pembayaran->status_pembayaran == 'belum_bayar') fa-clock 
                                        @elseif($pembayaran->status_pembayaran == 'menunggu') fa-hourglass-half 
                                        @elseif($pembayaran->status_pembayaran == 'sudah_bayar') fa-check 
                                        @elseif($pembayaran->status_pembayaran == 'gagal') fa-times 
                                        @elseif($pembayaran->status_pembayaran == 'expired') fa-calendar-times 
                                        @elseif($pembayaran->status_pembayaran == 'refund') fa-undo 
                                        @endif me-1">
                                    </i>
                                    {{ $pembayaran->status_pembayaran_text }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small mb-1">Jumlah Bayar</label>
                            <div class="h5 text-success fw-bold">{{ $pembayaran->jumlah_bayar_formatted }}</div>
                        </div>
                        @if($pembayaran->invoice_id)
                        <div class="col-12 mb-3">
                            <label class="form-label text-muted small mb-1">Invoice ID</label>
                            <div class="fw-bold">{{ $pembayaran->invoice_id }}</div>
                        </div>
                        @endif
                        @if($pembayaran->payment_url)
                        <div class="col-12 mb-3">
                            <label class="form-label text-muted small mb-1">Payment URL</label>
                            <div>
                                <a href="{{ $pembayaran->payment_url }}" target="_blank" class="text-decoration-none">
                                    {{ Str::limit($pembayaran->payment_url, 50) }}
                                    <i class="fas fa-external-link-alt ms-1"></i>
                                </a>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Informasi Pemesanan -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-shopping-cart me-2 text-info"></i>Informasi Pemesanan
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <label class="form-label text-muted small mb-1">ID Pemesanan</label>
                        <div class="d-flex align-items-center">
                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" 
                                 style="width: 50px; height: 50px;">
                                <i class="fas fa-receipt text-white"></i>
                            </div>
                            <div class="ms-3">
                                <h5 class="mb-1">#{{ $pembayaran->pemesanan->id_pemesanan }}</h5>
                                <p class="text-muted mb-0">Total: {{ $pembayaran->pemesanan->total_harga_formatted }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-muted small mb-1">Customer</label>
                        <div class="d-flex align-items-center">
                            <div class="bg-info rounded-circle d-flex align-items-center justify-content-center" 
                                 style="width: 40px; height: 40px;">
                                <i class="fas fa-user text-white"></i>
                            </div>
                            <div class="ms-3">
                                <h6 class="mb-1">{{ $pembayaran->pemesanan->customer->nama_customer ?? 'N/A' }}</h6>
                                <p class="text-muted mb-0">{{ $pembayaran->pemesanan->customer->email ?? '-' }}</p>
                                @if($pembayaran->pemesanan->customer->telepon ?? false)
                                    <p class="text-muted mb-0">
                                        <i class="fas fa-phone me-1"></i>{{ $pembayaran->pemesanan->customer->telepon }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
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
                                $colorPemesanan = $statusPemesananColors[$pembayaran->pemesanan->status] ?? 'secondary';
                            @endphp
                            <span class="badge bg-{{ $colorPemesanan }}">
                                <i class="fas 
                                    @if($pembayaran->pemesanan->status == 'pending') fa-clock 
                                    @elseif($pembayaran->pemesanan->status == 'diproses') fa-cog 
                                    @elseif($pembayaran->pemesanan->status == 'dikirim') fa-shipping-fast 
                                    @elseif($pembayaran->pemesanan->status == 'selesai') fa-check 
                                    @elseif($pembayaran->pemesanan->status == 'batal') fa-times 
                                    @endif me-1">
                                </i>
                                {{ ucfirst($pembayaran->pemesanan->status) }}
                            </span>
                        </div>
                    </div>

                    <div>
                        <label class="form-label text-muted small mb-1">Tanggal Pemesanan</label>
                        <div class="fw-bold">{{ $pembayaran->pemesanan->tanggal_pemesanan_formatted }}</div>
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
                        <i class="fas fa-boxes me-2 text-success"></i>Items Pemesanan
                    </h5>
                    <span class="badge bg-primary fs-6">
                        {{ $pembayaran->pemesanan->detailPemesanan->count() }} Items
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
                                @foreach($pembayaran->pemesanan->detailPemesanan as $detail)
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
                                        <h5 class="text-primary fw-bold mb-0">{{ $pembayaran->pemesanan->total_harga_formatted }}</h5>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3"></td>
                                    <td class="text-center fw-bold">Jumlah Bayar:</td>
                                    <td class="text-end px-4">
                                        <h5 class="text-success fw-bold mb-0">{{ $pembayaran->jumlah_bayar_formatted }}</h5>
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
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-sync-alt me-2 text-warning"></i>Update Status Pembayaran
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('pembayaran.update-status', $pembayaran->id_pembayaran) }}" method="POST">
                        @csrf
                        <div class="row align-items-end">
                            <div class="col-md-6">
                                <label class="form-label">Ubah Status</label>
                                <select name="status_pembayaran" class="form-select" required>
                                    <option value="belum_bayar" {{ $pembayaran->status_pembayaran == 'belum_bayar' ? 'selected' : '' }}>Belum Bayar</option>
                                    <option value="menunggu" {{ $pembayaran->status_pembayaran == 'menunggu' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                                    <option value="sudah_bayar" {{ $pembayaran->status_pembayaran == 'sudah_bayar' ? 'selected' : '' }}>Sudah Bayar</option>
                                    <option value="gagal" {{ $pembayaran->status_pembayaran == 'gagal' ? 'selected' : '' }}>Gagal</option>
                                    <option value="expired" {{ $pembayaran->status_pembayaran == 'expired' ? 'selected' : '' }}>Kadaluarsa</option>
                                    <option value="refund" {{ $pembayaran->status_pembayaran == 'refund' ? 'selected' : '' }}>Refund</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-save me-2"></i>Update Status
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Quick Actions -->
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="d-flex gap-2 flex-wrap">
                                @if($pembayaran->isPending())
                                <form action="{{ route('pembayaran.mark-paid', $pembayaran->id_pembayaran) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="fas fa-check me-1"></i>Tandai Sudah Bayar
                                    </button>
                                </form>
                                @endif
                                
                                @if(!$pembayaran->isExpired() && !$pembayaran->isFailed())
                                <form action="{{ route('pembayaran.mark-expired', $pembayaran->id_pembayaran) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-secondary btn-sm" 
                                            onclick="return confirm('Tandai pembayaran sebagai kadaluarsa?')">
                                        <i class="fas fa-calendar-times me-1"></i>Tandai Kadaluarsa
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
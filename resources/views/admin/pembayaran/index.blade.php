@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 text-gray-800">
                <i class="fas fa-credit-card me-2"></i>Manajemen Pembayaran
            </h1>
            <p class="text-muted">Daftar semua transaksi pembayaran</p>
        </div>
        <a href="{{ route('pembayaran.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Tambah Pembayaran
        </a>
    </div>

    <!-- Alert Notifikasi -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Card Table -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <h5 class="card-title mb-0">
                <i class="fas fa-list me-2"></i>Daftar Pembayaran
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-4">ID Pembayaran</th>
                            <th>Pemesanan</th>
                            <th>Customer</th>
                            <th>Metode</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pembayaran as $item)
                        <tr>
                            <td class="px-4">
                                <strong>#{{ $item->id_pembayaran }}</strong>
                                @if($item->external_id)
                                    <br>
                                    <small class="text-muted">{{ $item->external_id }}</small>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" 
                                         style="width: 40px; height: 40px;">
                                        <i class="fas fa-receipt text-white"></i>
                                    </div>
                                    <div class="ms-3">
                                        <strong class="d-block">#{{ $item->pemesanan->id_pemesanan }}</strong>
                                        <small class="text-muted">{{ $item->pemesanan->total_harga_formatted }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <strong class="d-block">{{ $item->pemesanan->customer->nama_customer ?? 'N/A' }}</strong>
                                    <small class="text-muted">{{ $item->pemesanan->customer->email ?? '-' }}</small>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-info">
                                    <i class="fas 
                                        @if($item->metode_pembayaran == 'transfer') fa-university 
                                        @elseif($item->metode_pembayaran == 'cod') fa-money-bill 
                                        @elseif($item->metode_pembayaran == 'ewallet') fa-mobile-alt 
                                        @elseif($item->metode_pembayaran == 'va') fa-credit-card 
                                        @elseif($item->metode_pembayaran == 'qris') fa-qrcode 
                                        @elseif($item->metode_pembayaran == 'credit_card') fa-credit-card 
                                        @else fa-money-bill 
                                        @endif me-1">
                                    </i>
                                    {{ $item->metode_pembayaran_text }}
                                </span>
                                @if($item->channel)
                                    <br>
                                    <small class="text-muted">{{ $item->channel }}</small>
                                @endif
                            </td>
                            <td>
                                <strong class="text-success">{{ $item->jumlah_bayar_formatted }}</strong>
                            </td>
                            <td>
                                @php
                                    $statusColors = [
                                        'belum_bayar' => 'warning',
                                        'menunggu' => 'info',
                                        'sudah_bayar' => 'success',
                                        'gagal' => 'danger',
                                        'expired' => 'secondary',
                                        'refund' => 'dark'
                                    ];
                                    $color = $statusColors[$item->status_pembayaran] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $color }}">
                                    <i class="fas 
                                        @if($item->status_pembayaran == 'belum_bayar') fa-clock 
                                        @elseif($item->status_pembayaran == 'menunggu') fa-hourglass-half 
                                        @elseif($item->status_pembayaran == 'sudah_bayar') fa-check 
                                        @elseif($item->status_pembayaran == 'gagal') fa-times 
                                        @elseif($item->status_pembayaran == 'expired') fa-calendar-times 
                                        @elseif($item->status_pembayaran == 'refund') fa-undo 
                                        @endif me-1">
                                    </i>
                                    {{ $item->status_pembayaran_text }}
                                </span>
                            </td>
                            <td>
                                <div>
                                    @if($item->tanggal_pembayaran)
                                        <strong>{{ $item->tanggal_pembayaran->format('d M Y') }}</strong>
                                        <div class="text-muted small">{{ $item->tanggal_pembayaran->format('H:i') }}</div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('pembayaran.show', $item->id_pembayaran) }}" 
                                       class="btn btn-sm btn-outline-info" 
                                       data-bs-toggle="tooltip" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('pembayaran.edit', $item->id_pembayaran) }}" 
                                       class="btn btn-sm btn-outline-warning"
                                       data-bs-toggle="tooltip" title="Edit Pembayaran">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($item->isPending())
                                    <form action="{{ route('pembayaran.mark-paid', $item->id_pembayaran) }}" 
                                          method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" 
                                                class="btn btn-sm btn-outline-success"
                                                data-bs-toggle="tooltip" 
                                                title="Tandai Sudah Bayar"
                                                onclick="return confirm('Tandai pembayaran #{{ $item->id_pembayaran }} sebagai sudah dibayar?')">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    @endif
                                    <form action="{{ route('pembayaran.destroy', $item->id_pembayaran) }}" 
                                          method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm btn-outline-danger"
                                                data-bs-toggle="tooltip" 
                                                title="Hapus Pembayaran"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus pembayaran #{{ $item->id_pembayaran }}?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="py-5">
                                    <i class="fas fa-credit-card fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">Belum ada pembayaran</h5>
                                    <p class="text-muted">Silakan tambah pembayaran baru</p>
                                    <a href="{{ route('pembayaran.create') }}" class="btn btn-primary mt-2">
                                        <i class="fas fa-plus me-2"></i>Tambah Pembayaran Pertama
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($pembayaran->count() > 0)
        <div class="card-footer bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted">
                    Menampilkan <strong>{{ $pembayaran->count() }}</strong> pembayaran
                </div>
                <div class="d-flex gap-2">
                    @php
                        $stats = [
                            'success' => $pembayaran->where('status_pembayaran', 'sudah_bayar')->count(),
                            'pending' => $pembayaran->whereIn('status_pembayaran', ['belum_bayar', 'menunggu'])->count(),
                            'failed' => $pembayaran->whereIn('status_pembayaran', ['gagal', 'expired'])->count()
                        ];
                    @endphp
                    <span class="badge bg-success">Sukses: {{ $stats['success'] }}</span>
                    <span class="badge bg-warning">Pending: {{ $stats['pending'] }}</span>
                    <span class="badge bg-danger">Gagal: {{ $stats['failed'] }}</span>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
<script>
    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>
@endsection

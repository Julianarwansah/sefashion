@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 text-gray-800">
                <i class="fas fa-shipping-fast me-2"></i>Manajemen Pengiriman
            </h1>
            <p class="text-muted">Daftar semua pengiriman barang</p>
        </div>
        <a href="{{ route('pengiriman.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Tambah Pengiriman
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
                <i class="fas fa-list me-2"></i>Daftar Pengiriman
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-4">ID Pengiriman</th>
                            <th>Pemesanan</th>
                            <th>Penerima</th>
                            <th>Ekspedisi</th>
                            <th>No. Resi</th>
                            <th>Status</th>
                            <th>Biaya Ongkir</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pengiriman as $item)
                        <tr>
                            <td class="px-4">
                                <strong>#{{ $item->id_pengiriman }}</strong>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" 
                                         style="width: 40px; height: 40px;">
                                        <i class="fas fa-receipt text-white"></i>
                                    </div>
                                    <div class="ms-3">
                                        <strong class="d-block">#{{ $item->pemesanan->id_pemesanan }}</strong>
                                        <small class="text-muted">{{ $item->pemesanan->customer->nama_customer ?? 'N/A' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <strong class="d-block">{{ $item->nama_penerima }}</strong>
                                    <small class="text-muted">{{ $item->no_hp_penerima }}</small>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <strong class="d-block">{{ $item->ekspedisi }}</strong>
                                    <small class="text-muted">{{ $item->layanan }}</small>
                                </div>
                            </td>
                            <td>
                                @if($item->no_resi)
                                    <code class="text-primary">{{ $item->no_resi }}</code>
                                    @if($item->tracking_url)
                                        <br>
                                        <a href="{{ $item->tracking_url }}" target="_blank" class="btn btn-sm btn-outline-info btn-xs mt-1">
                                            <i class="fas fa-external-link-alt me-1"></i>Track
                                        </a>
                                    @endif
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $statusColors = [
                                        'menunggu' => 'warning',
                                        'dikirim' => 'primary',
                                        'diterima' => 'success',
                                        'gagal' => 'danger'
                                    ];
                                    $color = $statusColors[$item->status_pengiriman] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $color }}">
                                    <i class="fas 
                                        @if($item->status_pengiriman == 'menunggu') fa-clock 
                                        @elseif($item->status_pengiriman == 'dikirim') fa-shipping-fast 
                                        @elseif($item->status_pengiriman == 'diterima') fa-check 
                                        @elseif($item->status_pengiriman == 'gagal') fa-times 
                                        @endif me-1">
                                    </i>
                                    {{ $item->status_pengiriman_text }}
                                </span>
                                @if($item->isLate())
                                    <br>
                                    <small class="text-danger">
                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                        Terlambat {{ $item->days_late }} hari
                                    </small>
                                @endif
                            </td>
                            <td>
                                <strong class="text-success">{{ $item->biaya_ongkir_formatted }}</strong>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('pengiriman.show', $item->id_pengiriman) }}" 
                                       class="btn btn-sm btn-outline-info" 
                                       data-bs-toggle="tooltip" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('pengiriman.edit', $item->id_pengiriman) }}" 
                                       class="btn btn-sm btn-outline-warning"
                                       data-bs-toggle="tooltip" title="Edit Pengiriman">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($item->isInTransit())
                                    <form action="{{ route('pengiriman.update', $item->id_pengiriman) }}" 
                                          method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="update_only_status" value="true">
                                        <input type="hidden" name="status_pengiriman" value="diterima">
                                        <button type="submit" 
                                                class="btn btn-sm btn-outline-success"
                                                data-bs-toggle="tooltip" 
                                                title="Tandai Diterima"
                                                onclick="return confirm('Tandai pengiriman #{{ $item->id_pengiriman }} sebagai sudah diterima?')">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    @endif
                                    <form action="{{ route('pengiriman.destroy', $item->id_pengiriman) }}" 
                                          method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm btn-outline-danger"
                                                data-bs-toggle="tooltip" 
                                                title="Hapus Pengiriman"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus pengiriman #{{ $item->id_pengiriman }}?')">
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
                                    <i class="fas fa-shipping-fast fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">Belum ada pengiriman</h5>
                                    <p class="text-muted">Silakan tambah pengiriman baru</p>
                                    <a href="{{ route('pengiriman.create') }}" class="btn btn-primary mt-2">
                                        <i class="fas fa-plus me-2"></i>Tambah Pengiriman Pertama
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($pengiriman->count() > 0)
        <div class="card-footer bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted">
                    Menampilkan <strong>{{ $pengiriman->count() }}</strong> pengiriman
                </div>
                <div class="d-flex gap-2">
                    @php
                        $stats = [
                            'dikirim' => $pengiriman->where('status_pengiriman', 'dikirim')->count(),
                            'menunggu' => $pengiriman->where('status_pengiriman', 'menunggu')->count(),
                            'diterima' => $pengiriman->where('status_pengiriman', 'diterima')->count(),
                            'gagal' => $pengiriman->where('status_pengiriman', 'gagal')->count()
                        ];
                    @endphp
                    <span class="badge bg-primary">Dikirim: {{ $stats['dikirim'] }}</span>
                    <span class="badge bg-warning">Menunggu: {{ $stats['menunggu'] }}</span>
                    <span class="badge bg-success">Diterima: {{ $stats['diterima'] }}</span>
                    <span class="badge bg-danger">Gagal: {{ $stats['gagal'] }}</span>
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


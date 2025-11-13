@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 text-gray-800">
                <i class="fas fa-shopping-cart me-2"></i>Manajemen Pesanan
            </h1>
            <p class="text-muted">Daftar semua pesanan yang masuk</p>
        </div>
        <a href="{{ route('admin.pesanan.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Tambah Pesanan
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
                <i class="fas fa-list me-2"></i>Daftar Pesanan
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-4">ID Pesanan</th>
                            <th>Customer</th>
                            <th>Tanggal</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Items</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pesanan as $item)
                        <tr>
                            <td class="px-4">
                                <strong>#{{ $item->id_pemesanan }}</strong>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" 
                                         style="width: 40px; height: 40px;">
                                        <i class="fas fa-user text-white"></i>
                                    </div>
                                    <div class="ms-3">
                                        <strong class="d-block">{{ $item->customer->nama_customer ?? 'N/A' }}</strong>
                                        <small class="text-muted">{{ $item->customer->email ?? '-' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $item->tanggal_pemesanan->format('d M Y') }}</strong>
                                    <div class="text-muted small">{{ $item->tanggal_pemesanan->format('H:i') }}</div>
                                </div>
                            </td>
                            <td>
                                <strong class="text-success">{{ $item->total_harga_formatted }}</strong>
                            </td>
                            <td>
                                @php
                                    $statusColors = [
                                        'pending' => 'warning',
                                        'diproses' => 'info',
                                        'dikirim' => 'primary',
                                        'selesai' => 'success',
                                        'batal' => 'danger'
                                    ];
                                    $color = $statusColors[$item->status] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $color }}">
                                    <i class="fas 
                                        @if($item->status == 'pending') fa-clock 
                                        @elseif($item->status == 'diproses') fa-cog 
                                        @elseif($item->status == 'dikirim') fa-shipping-fast 
                                        @elseif($item->status == 'selesai') fa-check 
                                        @elseif($item->status == 'batal') fa-times 
                                        @endif me-1">
                                    </i>
                                    {{ ucfirst($item->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-box me-2 text-muted"></i>
                                    <span class="fw-bold">{{ $item->total_items }}</span>
                                    <small class="text-muted ms-1">items</small>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('admin.pesanan.show', $item->id_pemesanan) }}" 
                                       class="btn btn-sm btn-outline-info" 
                                       data-bs-toggle="tooltip" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.pesanan.edit', $item->id_pemesanan) }}" 
                                       class="btn btn-sm btn-outline-warning"
                                       data-bs-toggle="tooltip" title="Edit Pesanan">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.pesanan.destroy', $item->id_pemesanan) }}" 
                                          method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm btn-outline-danger"
                                                data-bs-toggle="tooltip" 
                                                title="Hapus Pesanan"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus pesanan #{{ $item->id_pemesanan }}?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="py-5">
                                    <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">Belum ada pesanan</h5>
                                    <p class="text-muted">Silakan tambah pesanan baru</p>
                                    <a href="{{ route('admin.pesanan.create') }}" class="btn btn-primary mt-2">
                                        <i class="fas fa-plus me-2"></i>Tambah Pesanan Pertama
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($pesanan->count() > 0)
        <div class="card-footer bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted">
                    Menampilkan <strong>{{ $pesanan->count() }}</strong> pesanan
                </div>
                <div>
                    <small class="text-muted">Terakhir diperbarui: {{ now()->format('d M Y H:i') }}</small>
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

@extends('layouts.app')

@section('title', 'Manajemen Pembayaran - Konveksi')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-50 via-pink-50 to-yellow-50 p-4 lg:p-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-2">
                        <i class="fas fa-credit-card me-2"></i>Manajemen Pembayaran
                    </h1>
                    <p class="text-gray-600">Daftar semua transaksi pembayaran</p>
                </div>
                <a href="{{ route('admin.pembayaran.create') }}" 
                   class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-semibold px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Tambah Pembayaran
                </a>
            </div>
        </div>

        <!-- Alert Messages -->
        <div class="mb-6 space-y-3">
            @if(session('success'))
                <div id="success-alert" class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl shadow-sm">
                    <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="flex-1">{{ session('success') }}</span>
                    <button type="button" onclick="dismissAlert('success-alert')" class="text-green-500 hover:text-green-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div id="error-alert" class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl shadow-sm">
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="flex-1">{{ session('error') }}</span>
                    <button type="button" onclick="dismissAlert('error-alert')" class="text-red-500 hover:text-red-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            @endif
        </div>

        <!-- Content Card -->
        <div class="bg-white/80 glass rounded-2xl shadow-xl border border-white/20 overflow-hidden">
            @if($pembayaran->isEmpty())
                <!-- Empty State -->
                <div class="text-center py-16 px-4">
                    <div class="w-24 h-24 mx-auto mb-4 bg-gradient-to-br from-purple-100 to-pink-100 rounded-full flex items-center justify-center">
                        <svg class="w-12 h-12 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">Belum ada pembayaran</h3>
                    <p class="text-gray-500 mb-6">Silakan tambah pembayaran baru</p>
                    <a href="{{ route('admin.pembayaran.create') }}" 
                       class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-semibold px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Tambah Pembayaran Pertama
                    </a>
                </div>
            @else
                <!-- Table Section -->
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gradient-to-r from-purple-600 to-pink-600 text-white">
                                <th class="px-6 py-4 text-left font-semibold">ID Pembayaran</th>
                                <th class="px-6 py-4 text-left font-semibold">Pemesanan</th>
                                <th class="px-6 py-4 text-left font-semibold">Customer</th>
                                <th class="px-6 py-4 text-left font-semibold">Metode</th>
                                <th class="px-6 py-4 text-left font-semibold">Jumlah</th>
                                <th class="px-6 py-4 text-left font-semibold">Status</th>
                                <th class="px-6 py-4 text-left font-semibold">Tanggal</th>
                                <th class="px-6 py-4 text-left font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($pembayaran as $item)
                            <tr class="hover:bg-gradient-to-r hover:from-purple-50 hover:to-pink-50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-semibold text-gray-900">#{{ $item->id_pembayaran }}</div>
                                    @if($item->external_id)
                                        <div class="text-xs text-gray-500">{{ $item->external_id }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="bg-gradient-to-r from-purple-500 to-pink-500 rounded-lg flex items-center justify-center" 
                                             style="width: 40px; height: 40px;">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold text-gray-900">#{{ $item->pemesanan->id_pemesanan }}</div>
                                            <div class="text-xs text-gray-500">{{ $item->pemesanan->total_harga_formatted }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-semibold text-gray-900">{{ $item->pemesanan->customer->nama_customer ?? 'N/A' }}</div>
                                    <div class="text-xs text-gray-500">{{ $item->pemesanan->customer->email ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col gap-1">
                                        <span class="inline-flex items-center gap-1 bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                            @php
                                                $methodIcons = [
                                                    'transfer' => 'university',
                                                    'cod' => 'money-bill',
                                                    'ewallet' => 'mobile-alt',
                                                    'va' => 'credit-card',
                                                    'credit_card' => 'credit-card'
                                                ];
                                                $icon = $methodIcons[$item->metode_pembayaran] ?? 'money-bill';
                                            @endphp
                                            <i class="fas fa-{{ $icon }} text-xs"></i>
                                            {{ $item->metode_pembayaran_text }}
                                        </span>
                                        @if($item->channel)
                                            <div class="text-xs text-gray-500">{{ $item->channel }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-semibold text-green-600">{{ $item->jumlah_bayar_formatted }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusColors = [
                                            'belum_bayar' => 'yellow',
                                            'menunggu' => 'blue',
                                            'sudah_bayar' => 'green',
                                            'gagal' => 'red',
                                            'expired' => 'gray',
                                            'refund' => 'purple'
                                        ];
                                        $statusIcons = [
                                            'belum_bayar' => 'clock',
                                            'menunggu' => 'hourglass-half',
                                            'sudah_bayar' => 'check',
                                            'gagal' => 'times',
                                            'expired' => 'calendar-times',
                                            'refund' => 'undo'
                                        ];
                                        $color = $statusColors[$item->status_pembayaran] ?? 'gray';
                                        $icon = $statusIcons[$item->status_pembayaran] ?? 'question';
                                    @endphp
                                    <span class="inline-flex items-center gap-1 bg-{{ $color }}-100 text-{{ $color }}-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                        <i class="fas fa-{{ $icon }} text-xs"></i>
                                        {{ $item->status_pembayaran_text }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($item->tanggal_pembayaran)
                                        <div class="text-sm font-semibold text-gray-900">{{ $item->tanggal_pembayaran->format('d M Y') }}</div>
                                        <div class="text-xs text-gray-500">{{ $item->tanggal_pembayaran->format('H:i') }}</div>
                                    @else
                                        <span class="text-sm text-gray-500">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <!-- Detail Button -->
                                        <a href="{{ route('admin.pembayaran.show', $item->id_pembayaran) }}" 
                                           class="inline-flex items-center gap-1 bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200 transform hover:scale-105"
                                           title="Lihat Detail">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>
                                        
                                        <!-- Edit Button -->
                                        <a href="{{ route('admin.pembayaran.edit', $item->id_pembayaran) }}" 
                                           class="inline-flex items-center gap-1 bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200 transform hover:scale-105"
                                           title="Edit Pembayaran">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>
                                        
                                        <!-- Mark as Paid Button -->
                                        @if($item->isPending())
                                        <form action="{{ route('admin.pembayaran.mark-paid', $item->id_pembayaran) }}" 
                                              method="POST" class="inline">
                                            @csrf
                                            <button type="button" 
                                                    onclick="confirmMarkPaid('{{ $item->id_pembayaran }}', this)"
                                                    class="inline-flex items-center gap-1 bg-green-500 hover:bg-green-600 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200 transform hover:scale-105"
                                                    title="Tandai Sudah Bayar">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                            </button>
                                        </form>
                                        @endif
                                        
                                        <!-- Delete Button -->
                                        <form action="{{ route('admin.pembayaran.destroy', $item->id_pembayaran) }}" 
                                              method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" 
                                                    onclick="confirmDelete('{{ $item->id_pembayaran }}', this)"
                                                    class="inline-flex items-center gap-1 bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200 transform hover:scale-105"
                                                    title="Hapus Pembayaran">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Table Info -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                        <p class="text-sm text-gray-600">
                            Menampilkan <span class="font-semibold">{{ $pembayaran->count() }}</span> pembayaran
                        </p>
                        <div class="flex gap-2">
                            @php
                                $stats = [
                                    'success' => $pembayaran->where('status_pembayaran', 'sudah_bayar')->count(),
                                    'pending' => $pembayaran->whereIn('status_pembayaran', ['belum_bayar', 'menunggu'])->count(),
                                    'failed' => $pembayaran->whereIn('status_pembayaran', ['gagal', 'expired'])->count()
                                ];
                            @endphp
                            <span class="inline-flex items-center gap-1 bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                Sukses: {{ $stats['success'] }}
                            </span>
                            <span class="inline-flex items-center gap-1 bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                Pending: {{ $stats['pending'] }}
                            </span>
                            <span class="inline-flex items-center gap-1 bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                Gagal: {{ $stats['failed'] }}
                            </span>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
<script>
    // Dismiss alert function
    function dismissAlert(alertId) {
        const alert = document.getElementById(alertId);
        if (alert) {
            alert.style.opacity = '0';
            alert.style.transform = 'translateX(100%)';
            setTimeout(() => alert.remove(), 300);
        }
    }

    // Auto dismiss alerts after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('[id$="-alert"]');
        alerts.forEach(alert => {
            setTimeout(() => {
                if (alert && alert.parentNode) {
                    dismissAlert(alert.id);
                }
            }, 5000);
        });
    });

    // Confirm delete function
    function confirmDelete(paymentId, button) {
        Swal.fire({
            title: 'Yakin ingin menghapus?',
            html: `Pembayaran <strong>#${paymentId}</strong> akan dihapus permanen.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: {
                confirmButton: 'bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded-lg mr-2',
                cancelButton: 'bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit the form
                button.closest('form').submit();
            }
        });
    }

    // Confirm mark as paid function
    function confirmMarkPaid(paymentId, button) {
        Swal.fire({
            title: 'Konfirmasi Pembayaran',
            html: `Tandai pembayaran <strong>#${paymentId}</strong> sebagai sudah dibayar?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Tandai Sudah Bayar',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: {
                confirmButton: 'bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded-lg mr-2',
                cancelButton: 'bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit the form
                button.closest('form').submit();
            }
        });
    }

    // Add smooth animations
    document.addEventListener('DOMContentLoaded', function() {
        // Animate table rows
        const tableRows = document.querySelectorAll('tbody tr');
        tableRows.forEach((row, index) => {
            row.style.opacity = '0';
            row.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                row.style.transition = 'all 0.5s ease';
                row.style.opacity = '1';
                row.style.transform = 'translateY(0)';
            }, index * 100);
        });
    });
</script>

<!-- Include SweetAlert2 for beautiful confirm dialogs -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    .swal2-popup {
        font-family: 'Inter', sans-serif;
        border-radius: 1rem;
    }
</style>
@endsection


@extends('layouts.app')

@section('title', 'Edit Pembayaran - Konveksi')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-50 via-pink-50 to-yellow-50 p-4 lg:p-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header Section -->
        <div class="mb-8">
            <!-- Breadcrumb -->
            <nav class="mb-4" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2 text-sm">
                    <li>
                        <a href="{{ route('admin.pembayaran.index') }}" class="text-purple-600 hover:text-purple-700 transition-colors duration-200">
                            Pembayaran
                        </a>
                    </li>
                    <li class="flex items-center">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                        <a href="{{ route('admin.pembayaran.show', $pembayaran->id_pembayaran) }}" class="ml-2 text-purple-600 hover:text-purple-700 transition-colors duration-200">
                            #{{ $pembayaran->id_pembayaran }}
                        </a>
                    </li>
                    <li class="flex items-center">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                        <span class="ml-2 text-gray-500">Edit</span>
                    </li>
                </ol>
            </nav>

            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-2">
                        Edit Pembayaran
                    </h1>
                    <p class="text-gray-600">#{{ $pembayaran->id_pembayaran }} - {{ $pembayaran->pemesanan->customer->nama_customer ?? 'N/A' }}</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('admin.pembayaran.show', $pembayaran->id_pembayaran) }}" 
                       class="inline-flex items-center gap-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold px-5 py-2.5 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        Detail
                    </a>
                    <a href="{{ route('admin.pembayaran.index') }}" 
                       class="inline-flex items-center gap-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-semibold px-5 py-2.5 rounded-xl shadow-sm transition-all duration-300 transform hover:scale-105">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Kembali
                    </a>
                </div>
            </div>
        </div>

        <!-- Alert Messages -->
        <div class="mb-6 space-y-3">
            @if($errors->any())
                <div id="error-alert" class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl shadow-sm">
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="flex-1">Terjadi kesalahan. Silakan periksa form di bawah.</span>
                    <button type="button" onclick="dismissAlert('error-alert')" class="text-red-500 hover:text-red-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            @endif
        </div>

        <!-- Form Card -->
        <div class="bg-white/80 glass rounded-2xl shadow-xl border border-white/20 overflow-hidden">
            <div class="bg-gradient-to-r from-purple-600 to-pink-600 px-6 py-4">
                <h2 class="text-lg font-semibold text-white flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Form Edit Pembayaran
                </h2>
            </div>
            
            <div class="p-6">
                <form action="{{ route('admin.pembayaran.update', $pembayaran->id_pembayaran) }}" method="POST" id="pembayaranForm">
                    @csrf
                    @method('PUT')
                    
                    <!-- Informasi Pemesanan (Readonly) -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            Informasi Pemesanan
                        </h3>
                        
                        <div class="bg-gradient-to-br from-blue-50 to-cyan-50 border border-blue-200 rounded-xl p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                <div>
                                    <p class="text-sm text-blue-700 mb-1">ID Pemesanan</p>
                                    <p class="font-semibold text-blue-900">#{{ $pembayaran->pemesanan->id_pemesanan }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-blue-700 mb-1">Customer</p>
                                    <p class="font-semibold text-blue-900">{{ $pembayaran->pemesanan->customer->nama_customer ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-blue-700 mb-1">Total Pemesanan</p>
                                    <p class="font-semibold text-green-600">{{ $pembayaran->pemesanan->total_harga_formatted }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-blue-700 mb-1">Status Pemesanan</p>
                                    @php
                                        $statusPemesananColors = [
                                            'pending' => 'yellow',
                                            'diproses' => 'blue',
                                            'dikirim' => 'indigo',
                                            'selesai' => 'green',
                                            'batal' => 'red'
                                        ];
                                        $statusPemesananIcons = [
                                            'pending' => 'clock',
                                            'diproses' => 'cog',
                                            'dikirim' => 'shipping-fast',
                                            'selesai' => 'check',
                                            'batal' => 'times'
                                        ];
                                        $colorPemesanan = $statusPemesananColors[$pembayaran->pemesanan->status] ?? 'gray';
                                        $iconPemesanan = $statusPemesananIcons[$pembayaran->pemesanan->status] ?? 'question';
                                    @endphp
                                    <span class="inline-flex items-center gap-1 bg-{{ $colorPemesanan }}-100 text-{{ $colorPemesanan }}-800 text-sm font-medium px-3 py-1 rounded-full">
                                        <i class="fas fa-{{ $iconPemesanan }} text-xs"></i>
                                        {{ ucfirst($pembayaran->pemesanan->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 my-8"></div>

                    <!-- Informasi Pembayaran -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Informasi Pembayaran
                        </h3>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Left Column -->
                            <div class="space-y-4">
                                <div>
                                    <label for="metode_pembayaran" class="block text-sm font-medium text-gray-700 mb-2">
                                        Metode Pembayaran <span class="text-red-500">*</span>
                                    </label>
                                    <select name="metode_pembayaran" id="metode_pembayaran" 
                                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 @error('metode_pembayaran') border-red-500 @enderror" 
                                            required>
                                        <option value="">Pilih Metode</option>
                                        @foreach($metodePembayaran as $key => $value)
                                            <option value="{{ $key }}" {{ $pembayaran->metode_pembayaran == $key ? 'selected' : '' }}>
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('metode_pembayaran')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="channel" class="block text-sm font-medium text-gray-700 mb-2">
                                        Channel Pembayaran
                                    </label>
                                    <input type="text" name="channel" id="channel" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 @error('channel') border-red-500 @enderror" 
                                           value="{{ old('channel', $pembayaran->channel) }}" 
                                           placeholder="Contoh: BCA, BRI, Gopay, dll.">
                                    @error('channel')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        External ID
                                    </label>
                                    <input type="text" class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-50 text-gray-500" 
                                           value="{{ $pembayaran->external_id }}" readonly>
                                    <p class="mt-1 text-sm text-gray-500">ID unik untuk referensi payment gateway</p>
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="space-y-4">
                                <div>
                                    <label for="jumlah_bayar" class="block text-sm font-medium text-gray-700 mb-2">
                                        Jumlah Bayar <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500">Rp</span>
                                        </div>
                                        <input type="number" name="jumlah_bayar" id="jumlah_bayar" 
                                               class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 @error('jumlah_bayar') border-red-500 @enderror" 
                                               value="{{ old('jumlah_bayar', $pembayaran->jumlah_bayar) }}" 
                                               min="0" step="0.01" required
                                               placeholder="0">
                                    </div>
                                    @error('jumlah_bayar')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    <div class="mt-2 text-sm text-gray-600">
                                        Sisa: <span id="sisaPembayaran" class="font-semibold">
                                            @php
                                                $sisa = $pembayaran->pemesanan->total_harga - $pembayaran->jumlah_bayar;
                                                if ($sisa > 0) {
                                                    echo 'Rp ' . number_format($sisa, 0, ',', '.');
                                                } elseif ($sisa < 0) {
                                                    echo 'Kelebihan: Rp ' . number_format(abs($sisa), 0, ',', '.');
                                                } else {
                                                    echo 'Lunas';
                                                }
                                            @endphp
                                        </span>
                                    </div>
                                </div>

                                <div>
                                    <label for="status_pembayaran" class="block text-sm font-medium text-gray-700 mb-2">
                                        Status Pembayaran <span class="text-red-500">*</span>
                                    </label>
                                    <select name="status_pembayaran" id="status_pembayaran" 
                                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 @error('status_pembayaran') border-red-500 @enderror" 
                                            required>
                                        @foreach($statusPembayaran as $key => $value)
                                            <option value="{{ $key }}" {{ $pembayaran->status_pembayaran == $key ? 'selected' : '' }}>
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('status_pembayaran')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div id="tanggalPembayaranGroup">
                                    <label for="tanggal_pembayaran" class="block text-sm font-medium text-gray-700 mb-2">
                                        Tanggal Pembayaran
                                    </label>
                                    <input type="datetime-local" name="tanggal_pembayaran" id="tanggal_pembayaran" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 @error('tanggal_pembayaran') border-red-500 @enderror" 
                                           value="{{ old('tanggal_pembayaran', $pembayaran->tanggal_pembayaran ? $pembayaran->tanggal_pembayaran->format('Y-m-d\TH:i') : '') }}">
                                    @error('tanggal_pembayaran')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1 text-sm text-gray-500">Diisi otomatis ketika status diubah menjadi "Sudah Bayar"</p>
                                </div>

                                @if($pembayaran->invoice_id)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Invoice ID
                                    </label>
                                    <input type="text" class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-50 text-gray-500" 
                                           value="{{ $pembayaran->invoice_id }}" readonly>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Tambahan -->
                    @if($pembayaran->payment_url || $pembayaran->raw_response)
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Informasi Tambahan
                        </h3>
                        
                        <div class="bg-gradient-to-br from-gray-50 to-gray-100 border border-gray-200 rounded-xl p-6 space-y-4">
                            @if($pembayaran->payment_url)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Payment URL</label>
                                <a href="{{ $pembayaran->payment_url }}" target="_blank" 
                                   class="inline-flex items-center gap-2 text-purple-600 hover:text-purple-700 transition-colors duration-200 break-all">
                                    <span class="truncate">{{ $pembayaran->payment_url }}</span>
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                    </svg>
                                </a>
                            </div>
                            @endif
                            
                            @if($pembayaran->raw_response)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Raw Response</label>
                                <div class="bg-gray-900 text-gray-100 rounded-lg p-4 max-h-48 overflow-y-auto">
                                    <pre class="text-sm whitespace-pre-wrap"><code>{{ json_encode($pembayaran->raw_response, JSON_PRETTY_PRINT) }}</code></pre>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Submit Button -->
                    <div class="flex flex-col sm:flex-row gap-3 justify-end pt-6 border-t border-gray-200">
                        <a href="{{ route('admin.pembayaran.show', $pembayaran->id_pembayaran) }}" 
                           class="inline-flex items-center justify-center gap-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-semibold px-6 py-3 rounded-xl shadow-sm transition-all duration-300 transform hover:scale-105">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Batal
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-semibold px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Update Pembayaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
function updateSisaPembayaran() {
    const totalPemesanan = {{ $pembayaran->pemesanan->total_harga }};
    const jumlahBayar = document.getElementById('jumlah_bayar').value;
    const sisaPembayaran = document.getElementById('sisaPembayaran');

    if (jumlahBayar) {
        const bayar = parseFloat(jumlahBayar);
        const sisa = totalPemesanan - bayar;
        
        if (sisa > 0) {
            sisaPembayaran.textContent = 'Rp ' + formatNumber(sisa);
            sisaPembayaran.className = 'font-semibold text-red-600';
        } else if (sisa < 0) {
            sisaPembayaran.textContent = 'Kelebihan: Rp ' + formatNumber(Math.abs(sisa));
            sisaPembayaran.className = 'font-semibold text-yellow-600';
        } else {
            sisaPembayaran.textContent = 'Lunas';
            sisaPembayaran.className = 'font-semibold text-green-600';
        }
    }
}

function formatNumber(number) {
    return new Intl.NumberFormat('id-ID').format(number);
}

// Event Listeners
document.addEventListener('DOMContentLoaded', function() {
    // Update sisa pembayaran ketika jumlah bayar berubah
    document.getElementById('jumlah_bayar').addEventListener('input', updateSisaPembayaran);
    
    // Auto-set tanggal pembayaran jika status diubah menjadi sudah bayar
    document.getElementById('status_pembayaran').addEventListener('change', function() {
        const tanggalInput = document.getElementById('tanggal_pembayaran');
        if (this.value === 'sudah_bayar' && !tanggalInput.value) {
            const now = new Date();
            const localDateTime = now.toISOString().slice(0, 16);
            tanggalInput.value = localDateTime;
        }
    });
});

// Form validation
document.getElementById('pembayaranForm').addEventListener('submit', function(e) {
    const jumlahBayar = document.getElementById('jumlah_bayar').value;
    
    if (!jumlahBayar || parseFloat(jumlahBayar) <= 0) {
        e.preventDefault();
        Swal.fire({
            title: 'Peringatan',
            text: 'Jumlah bayar harus lebih dari 0.',
            icon: 'warning',
            confirmButtonColor: '#8b5cf6',
            confirmButtonText: 'Mengerti'
        });
        return;
    }
});

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
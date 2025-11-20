@extends('layouts.app')

@section('title', 'Tambah Pembayaran - Konveksi')

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
                        <span class="ml-2 text-gray-500">Tambah Pembayaran</span>
                    </li>
                </ol>
            </nav>

            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-2">
                        Tambah Pembayaran Baru
                    </h1>
                    <p class="text-gray-600">Buat pembayaran baru untuk pemesanan</p>
                </div>
                <a href="{{ route('admin.pembayaran.index') }}" 
                   class="inline-flex items-center gap-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-semibold px-5 py-2.5 rounded-xl shadow-sm transition-all duration-300 transform hover:scale-105">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali
                </a>
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Form Pembayaran
                </h2>
            </div>
            
            <div class="p-6">
                <form action="{{ route('admin.pembayaran.store') }}" method="POST" id="pembayaranForm">
                    @csrf
                    
                    <!-- Informasi Pemesanan -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            Informasi Pemesanan
                        </h3>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div>
                                <label for="id_pemesanan" class="block text-sm font-medium text-gray-700 mb-2">
                                    Pilih Pemesanan <span class="text-red-500">*</span>
                                </label>
                                <select name="id_pemesanan" id="id_pemesanan" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 @error('id_pemesanan') border-red-500 @enderror" 
                                        required onchange="updatePemesananInfo()">
                                    <option value="">Pilih Pemesanan</option>
                                    @foreach($pemesanan as $order)
                                        <option value="{{ $order->id_pemesanan }}" 
                                                data-total="{{ $order->total_harga }}"
                                                data-customer="{{ $order->customer->nama_customer ?? 'N/A' }}"
                                                data-email="{{ $order->customer->email ?? '' }}"
                                                {{ old('id_pemesanan') == $order->id_pemesanan ? 'selected' : '' }}>
                                            #{{ $order->id_pemesanan }} - {{ $order->customer->nama_customer ?? 'N/A' }} - {{ $order->total_harga_formatted }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_pemesanan')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-2 text-sm text-gray-500">Hanya menampilkan pemesanan yang belum memiliki pembayaran aktif</p>
                            </div>
                            
                            <div id="pemesananInfo" class="bg-gradient-to-br from-blue-50 to-cyan-50 border border-blue-200 rounded-xl p-4" style="display: none;">
                                <h4 class="font-semibold text-blue-900 mb-3">Informasi Pemesanan</h4>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-sm text-blue-700 mb-1">Customer</p>
                                        <p id="customerName" class="font-semibold text-blue-900">-</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-blue-700 mb-1">Total Pemesanan</p>
                                        <p id="totalHarga" class="font-semibold text-green-600">-</p>
                                    </div>
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
                                            <option value="{{ $key }}" {{ old('metode_pembayaran') == $key ? 'selected' : '' }}>
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
                                           value="{{ old('channel') }}" 
                                           placeholder="Contoh: BCA, BRI, Gopay, dll.">
                                    @error('channel')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
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
                                               value="{{ old('jumlah_bayar') }}" 
                                               min="0" step="0.01" required
                                               placeholder="0">
                                    </div>
                                    @error('jumlah_bayar')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    <div class="mt-2 text-sm text-gray-600">
                                        Sisa: <span id="sisaPembayaran" class="font-semibold">-</span>
                                    </div>
                                </div>

                                <div>
                                    <label for="status_pembayaran" class="block text-sm font-medium text-gray-700 mb-2">
                                        Status Pembayaran <span class="text-red-500">*</span>
                                    </label>
                                    <select name="status_pembayaran" id="status_pembayaran" 
                                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 @error('status_pembayaran') border-red-500 @enderror" 
                                            required>
                                        <option value="belum_bayar" {{ old('status_pembayaran') == 'belum_bayar' ? 'selected' : '' }}>Belum Bayar</option>
                                        <option value="menunggu" {{ old('status_pembayaran') == 'menunggu' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                                        <option value="sudah_bayar" {{ old('status_pembayaran') == 'sudah_bayar' ? 'selected' : '' }}>Sudah Bayar</option>
                                        <option value="gagal" {{ old('status_pembayaran') == 'gagal' ? 'selected' : '' }}>Gagal</option>
                                        <option value="expired" {{ old('status_pembayaran') == 'expired' ? 'selected' : '' }}>Kadaluarsa</option>
                                        <option value="refund" {{ old('status_pembayaran') == 'refund' ? 'selected' : '' }}>Refund</option>
                                    </select>
                                    @error('status_pembayaran')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div id="tanggalPembayaranGroup" class="transition-all duration-300" style="display: none;">
                                    <label for="tanggal_pembayaran" class="block text-sm font-medium text-gray-700 mb-2">
                                        Tanggal Pembayaran
                                    </label>
                                    <input type="datetime-local" name="tanggal_pembayaran" id="tanggal_pembayaran" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 @error('tanggal_pembayaran') border-red-500 @enderror" 
                                           value="{{ old('tanggal_pembayaran', now()->format('Y-m-d\TH:i')) }}">
                                    @error('tanggal_pembayaran')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex flex-col sm:flex-row gap-3 justify-end pt-6 border-t border-gray-200">
                        <a href="{{ route('admin.pembayaran.index') }}" 
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
                            Simpan Pembayaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
function updatePemesananInfo() {
    const select = document.getElementById('id_pemesanan');
    const selectedOption = select.options[select.selectedIndex];
    const infoCard = document.getElementById('pemesananInfo');
    const customerName = document.getElementById('customerName');
    const totalHarga = document.getElementById('totalHarga');
    const jumlahBayar = document.getElementById('jumlah_bayar');
    const sisaPembayaran = document.getElementById('sisaPembayaran');

    if (selectedOption.value) {
        const total = selectedOption.getAttribute('data-total');
        const customer = selectedOption.getAttribute('data-customer');
        const email = selectedOption.getAttribute('data-email');

        customerName.textContent = customer;
        if (email) {
            customerName.textContent += ` (${email})`;
        }
        totalHarga.textContent = 'Rp ' + formatNumber(total);
        
        // Set jumlah bayar default ke total pemesanan
        jumlahBayar.value = total;
        
        // Update sisa pembayaran
        updateSisaPembayaran();
        
        // Show info card with animation
        infoCard.style.display = 'block';
        setTimeout(() => {
            infoCard.style.opacity = '1';
            infoCard.style.transform = 'translateY(0)';
        }, 10);
    } else {
        infoCard.style.opacity = '0';
        infoCard.style.transform = 'translateY(-10px)';
        setTimeout(() => {
            infoCard.style.display = 'none';
        }, 300);
        customerName.textContent = '-';
        totalHarga.textContent = '-';
        jumlahBayar.value = '';
        sisaPembayaran.textContent = '-';
    }
}

function updateSisaPembayaran() {
    const select = document.getElementById('id_pemesanan');
    const selectedOption = select.options[select.selectedIndex];
    const jumlahBayar = document.getElementById('jumlah_bayar').value;
    const sisaPembayaran = document.getElementById('sisaPembayaran');

    if (selectedOption.value && jumlahBayar) {
        const total = parseFloat(selectedOption.getAttribute('data-total'));
        const bayar = parseFloat(jumlahBayar);
        const sisa = total - bayar;
        
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
    } else {
        sisaPembayaran.textContent = '-';
        sisaPembayaran.className = 'font-semibold';
    }
}

function formatNumber(number) {
    return new Intl.NumberFormat('id-ID').format(number);
}

// Event Listeners
document.addEventListener('DOMContentLoaded', function() {
    // Initialize info card styles
    const infoCard = document.getElementById('pemesananInfo');
    infoCard.style.opacity = '0';
    infoCard.style.transform = 'translateY(-10px)';
    infoCard.style.transition = 'all 0.3s ease';
    
    // Update info ketika jumlah bayar berubah
    document.getElementById('jumlah_bayar').addEventListener('input', updateSisaPembayaran);
    
    // Tampilkan tanggal pembayaran jika status sudah bayar
    document.getElementById('status_pembayaran').addEventListener('change', function() {
        const tanggalGroup = document.getElementById('tanggalPembayaranGroup');
        if (this.value === 'sudah_bayar') {
            tanggalGroup.style.display = 'block';
            setTimeout(() => {
                tanggalGroup.style.opacity = '1';
                tanggalGroup.style.transform = 'translateY(0)';
            }, 10);
        } else {
            tanggalGroup.style.opacity = '0';
            tanggalGroup.style.transform = 'translateY(-10px)';
            setTimeout(() => {
                tanggalGroup.style.display = 'none';
            }, 300);
        }
    });
    
    // Initialize jika ada nilai old
    if (document.getElementById('id_pemesanan').value) {
        updatePemesananInfo();
    }
    if (document.getElementById('status_pembayaran').value === 'sudah_bayar') {
        const tanggalGroup = document.getElementById('tanggalPembayaranGroup');
        tanggalGroup.style.display = 'block';
        tanggalGroup.style.opacity = '1';
        tanggalGroup.style.transform = 'translateY(0)';
    }
});

// Form validation
document.getElementById('pembayaranForm').addEventListener('submit', function(e) {
    const idPemesanan = document.getElementById('id_pemesanan').value;
    const jumlahBayar = document.getElementById('jumlah_bayar').value;
    
    if (!idPemesanan) {
        e.preventDefault();
        Swal.fire({
            title: 'Peringatan',
            text: 'Harap pilih pemesanan terlebih dahulu.',
            icon: 'warning',
            confirmButtonColor: '#8b5cf6',
            confirmButtonText: 'Mengerti'
        });
        return;
    }
    
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

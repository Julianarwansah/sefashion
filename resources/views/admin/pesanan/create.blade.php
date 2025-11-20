@extends('layouts.app')

@section('title', 'Tambah Pesanan - Konveksi')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-50 via-pink-50 to-yellow-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header Section -->
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-8">
            <div>
                <nav class="flex mb-4" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2 text-sm">
                        <li>
                            <a href="{{ route('admin.pesanan.index') }}" class="text-purple-600 hover:text-purple-700 transition-colors">
                                Pesanan
                            </a>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                            <span class="ml-2 text-gray-500">Tambah Pesanan</span>
                        </li>
                    </ol>
                </nav>
                <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-2">Tambah Pesanan Baru</h1>
                <p class="text-gray-600 text-lg">Buat pesanan baru untuk customer</p>
            </div>
            <a href="{{ route('admin.pesanan.index') }}" 
               class="inline-flex items-center gap-2 bg-gray-500 hover:bg-gray-600 text-white font-semibold px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>
        </div>

        <!-- Form Card -->
        <div class="bg-white/80 glass rounded-2xl shadow-xl border border-white/20 overflow-hidden">
            <div class="bg-gradient-to-r from-purple-600 to-pink-600 p-6 text-white">
                <h2 class="text-xl font-bold flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Form Pesanan
                </h2>
            </div>
            
            <div class="p-6 sm:p-8">
                <form action="{{ route('admin.pesanan.store') }}" method="POST" id="pesananForm">
                    @csrf
                    
                    <!-- Informasi Dasar -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <!-- Customer -->
                        <div>
                            <label for="id_customer" class="block text-sm font-semibold text-gray-700 mb-2">
                                Customer <span class="text-red-500">*</span>
                            </label>
                            <select name="id_customer" id="id_customer" 
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-purple-200 focus:border-purple-400 transition-all duration-300 @error('id_customer') border-red-500 focus:border-red-500 focus:ring-red-200 @enderror" 
                                    required>
                                <option value="">Pilih Customer</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id_customer }}" {{ old('id_customer') == $customer->id_customer ? 'selected' : '' }}>
                                        {{ $customer->nama_customer }} - {{ $customer->email }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_customer')
                                <div class="flex items-center gap-2 mt-2 text-red-600 text-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Tanggal Pemesanan -->
                        <div>
                            <label for="tanggal_pemesanan" class="block text-sm font-semibold text-gray-700 mb-2">
                                Tanggal Pemesanan <span class="text-red-500">*</span>
                            </label>
                            <input type="datetime-local" name="tanggal_pemesanan" id="tanggal_pemesanan" 
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-purple-200 focus:border-purple-400 transition-all duration-300 @error('tanggal_pemesanan') border-red-500 focus:border-red-500 focus:ring-red-200 @enderror" 
                                   value="{{ old('tanggal_pemesanan', now()->format('Y-m-d\TH:i')) }}" 
                                   required>
                            @error('tanggal_pemesanan')
                                <div class="flex items-center gap-2 mt-2 text-red-600 text-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <!-- Items Pesanan -->
                    <div class="mb-8">
                        <div class="flex items-center gap-2 mb-6">
                            <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">Items Pesanan</h3>
                        </div>

                        <div id="items-container" class="space-y-4">
                            <!-- Item Row Template -->
                            <div class="item-row bg-gray-50 rounded-xl p-6 border-2 border-gray-200">
                                <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-end">
                                    <!-- Produk & Ukuran -->
                                    <div class="lg:col-span-5">
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            Produk & Ukuran <span class="text-red-500">*</span>
                                        </label>
                                        <select name="items[0][id_ukuran]" 
                                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-purple-200 focus:border-purple-400 transition-all duration-300 select-ukuran" 
                                                required 
                                                onchange="getDetailUkuran(this, 0)">
                                            <option value="">Pilih Produk</option>
                                            @foreach($detailUkuran as $ukuran)
                                                <option value="{{ $ukuran->id_ukuran }}" 
                                                        data-harga="{{ $ukuran->harga }}"
                                                        data-stok="{{ $ukuran->stok }}">
                                                    {{ $ukuran->produk->nama_produk ?? 'N/A' }} - {{ $ukuran->ukuran }} (Stok: {{ $ukuran->stok }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Harga Satuan -->
                                    <div class="lg:col-span-2">
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Harga Satuan</label>
                                        <input type="text" 
                                               class="w-full px-4 py-3 bg-gray-100 border-2 border-gray-200 rounded-xl harga-satuan-0" 
                                               readonly 
                                               placeholder="Pilih produk">
                                    </div>

                                    <!-- Jumlah -->
                                    <div class="lg:col-span-2">
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            Jumlah <span class="text-red-500">*</span>
                                        </label>
                                        <input type="number" 
                                               name="items[0][jumlah]" 
                                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-purple-200 focus:border-purple-400 transition-all duration-300 jumlah-0" 
                                               min="1" 
                                               value="1" 
                                               required 
                                               onchange="updateSubtotal(0)">
                                    </div>

                                    <!-- Subtotal -->
                                    <div class="lg:col-span-2">
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Subtotal</label>
                                        <input type="text" 
                                               class="w-full px-4 py-3 bg-gray-100 border-2 border-gray-200 rounded-xl subtotal-0" 
                                               readonly 
                                               placeholder="Rp 0">
                                    </div>

                                    <!-- Delete Button -->
                                    <div class="lg:col-span-1">
                                        <button type="button" 
                                                class="w-full px-4 py-3 bg-red-500 hover:bg-red-600 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 delete-btn"
                                                onclick="removeItem(this)"
                                                disabled>
                                            <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Add Item Button -->
                        <button type="button" 
                                class="mt-4 inline-flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white font-semibold px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105"
                                onclick="addItem()">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Tambah Item
                        </button>
                    </div>

                    <!-- Total Harga -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                        <div></div> <!-- Spacer -->
                        <div class="bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl p-6 text-white shadow-lg">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-lg font-semibold">Total Harga</p>
                                    <p class="text-3xl font-bold" id="total-harga">Rp 0</p>
                                </div>
                                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-gray-200">
                        <a href="{{ route('admin.pesanan.index') }}" 
                           class="flex-1 inline-flex items-center justify-center gap-2 bg-gray-500 hover:bg-gray-600 text-white font-semibold px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Batal
                        </a>
                        <button type="submit" 
                                id="submitBtn"
                                class="flex-1 inline-flex items-center justify-center gap-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-semibold px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Simpan Pesanan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let itemCount = 1;

function addItem() {
    const container = document.getElementById('items-container');
    const newRow = document.createElement('div');
    newRow.className = 'item-row bg-gray-50 rounded-xl p-6 border-2 border-gray-200';
    newRow.innerHTML = `
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-end">
            <div class="lg:col-span-5">
                <select name="items[${itemCount}][id_ukuran]" 
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-purple-200 focus:border-purple-400 transition-all duration-300 select-ukuran" 
                        required 
                        onchange="getDetailUkuran(this, ${itemCount})">
                    <option value="">Pilih Produk</option>
                    @foreach($detailUkuran as $ukuran)
                        <option value="{{ $ukuran->id_ukuran }}" 
                                data-harga="{{ $ukuran->harga }}"
                                data-stok="{{ $ukuran->stok }}">
                            {{ $ukuran->produk->nama_produk ?? 'N/A' }} - {{ $ukuran->ukuran }} (Stok: {{ $ukuran->stok }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="lg:col-span-2">
                <input type="text" 
                       class="w-full px-4 py-3 bg-gray-100 border-2 border-gray-200 rounded-xl harga-satuan-${itemCount}" 
                       readonly 
                       placeholder="Pilih produk">
            </div>
            <div class="lg:col-span-2">
                <input type="number" 
                       name="items[${itemCount}][jumlah]" 
                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-purple-200 focus:border-purple-400 transition-all duration-300 jumlah-${itemCount}" 
                       min="1" 
                       value="1" 
                       required 
                       onchange="updateSubtotal(${itemCount})">
            </div>
            <div class="lg:col-span-2">
                <input type="text" 
                       class="w-full px-4 py-3 bg-gray-100 border-2 border-gray-200 rounded-xl subtotal-${itemCount}" 
                       readonly 
                       placeholder="Rp 0">
            </div>
            <div class="lg:col-span-1">
                <button type="button" 
                        class="w-full px-4 py-3 bg-red-500 hover:bg-red-600 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 delete-btn"
                        onclick="removeItem(this)">
                    <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
            </div>
        </div>
    `;
    container.appendChild(newRow);
    itemCount++;
    updateRemoveButtons();
}

function removeItem(button) {
    if (document.querySelectorAll('.item-row').length > 1) {
        button.closest('.item-row').remove();
        calculateTotal();
        updateRemoveButtons();
    }
}

function updateRemoveButtons() {
    const deleteButtons = document.querySelectorAll('.delete-btn');
    deleteButtons.forEach(button => {
        button.disabled = deleteButtons.length === 1;
        if (button.disabled) {
            button.classList.remove('hover:bg-red-600', 'hover:shadow-xl', 'hover:scale-105');
            button.classList.add('bg-red-400', 'cursor-not-allowed');
        } else {
            button.classList.add('hover:bg-red-600', 'hover:shadow-xl', 'hover:scale-105');
            button.classList.remove('bg-red-400', 'cursor-not-allowed');
        }
    });
}

function getDetailUkuran(select, index) {
    const selectedOption = select.options[select.selectedIndex];
    const harga = selectedOption ? selectedOption.getAttribute('data-harga') : 0;
    const stok = selectedOption ? selectedOption.getAttribute('data-stok') : 0;
    
    // Update harga satuan
    document.querySelector(`.harga-satuan-${index}`).value = 'Rp ' + formatNumber(harga);
    
    // Update jumlah maksimum berdasarkan stok
    const jumlahInput = document.querySelector(`.jumlah-${index}`);
    jumlahInput.max = stok;
    
    if (parseInt(jumlahInput.value) > parseInt(stok)) {
        jumlahInput.value = stok;
        showAlert('warning', 'Jumlah melebihi stok yang tersedia. Disesuaikan ke stok maksimum: ' + stok);
    }
    
    updateSubtotal(index);
}

function updateSubtotal(index) {
    const select = document.querySelector(`select[name="items[${index}][id_ukuran]"]`);
    const jumlah = document.querySelector(`.jumlah-${index}`).value;
    const selectedOption = select.options[select.selectedIndex];
    const harga = selectedOption ? selectedOption.getAttribute('data-harga') : 0;
    
    const subtotal = harga * jumlah;
    document.querySelector(`.subtotal-${index}`).value = 'Rp ' + formatNumber(subtotal);
    calculateTotal();
}

function calculateTotal() {
    let total = 0;
    document.querySelectorAll('.item-row').forEach((row, index) => {
        const select = row.querySelector('.select-ukuran');
        const jumlah = row.querySelector(`.jumlah-${index}`).value;
        const selectedOption = select.options[select.selectedIndex];
        const harga = selectedOption ? selectedOption.getAttribute('data-harga') : 0;
        total += harga * jumlah;
    });
    
    document.getElementById('total-harga').textContent = 'Rp ' + formatNumber(total);
}

function formatNumber(number) {
    return new Intl.NumberFormat('id-ID').format(number);
}

function showAlert(type, message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `fixed top-4 right-4 z-50 flex items-center gap-3 p-4 rounded-xl shadow-lg border transform transition-all duration-300 ${
        type === 'warning' 
            ? 'bg-yellow-50 border-yellow-200 text-yellow-700' 
            : 'bg-red-50 border-red-200 text-red-700'
    }`;
    
    alertDiv.innerHTML = `
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${
                type === 'warning' 
                    ? 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z'
                    : 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'
            }"/>
        </svg>
        <span>${message}</span>
        <button onclick="this.parentElement.remove()" class="ml-auto text-gray-400 hover:text-gray-600">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    `;
    
    document.body.appendChild(alertDiv);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (alertDiv.parentElement) {
            alertDiv.remove();
        }
    }, 5000);
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    updateRemoveButtons();
    calculateTotal();

    // Form validation
    const form = document.getElementById('pesananForm');
    const submitBtn = document.getElementById('submitBtn');
    
    form.addEventListener('submit', function(e) {
        const items = document.querySelectorAll('.item-row');
        let valid = true;

        items.forEach((row, index) => {
            const select = row.querySelector('.select-ukuran');
            const jumlah = row.querySelector(`.jumlah-${index}`);
            
            if (!select.value) {
                valid = false;
                select.classList.add('border-red-500', 'focus:border-red-500', 'focus:ring-red-200');
            } else {
                select.classList.remove('border-red-500', 'focus:border-red-500', 'focus:ring-red-200');
            }
            
            if (!jumlah.value || parseInt(jumlah.value) < 1) {
                valid = false;
                jumlah.classList.add('border-red-500', 'focus:border-red-500', 'focus:ring-red-200');
            } else {
                jumlah.classList.remove('border-red-500', 'focus:border-red-500', 'focus:ring-red-200');
            }
        });

        if (!valid) {
            e.preventDefault();
            showAlert('warning', 'Harap lengkapi semua item pesanan dengan benar.');
            return;
        }

        // Show loading state
        submitBtn.innerHTML = `
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Menyimpan...
        `;
        submitBtn.disabled = true;
    });
});
</script>
@endpush
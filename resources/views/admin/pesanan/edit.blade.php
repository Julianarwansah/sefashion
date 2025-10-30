@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('pesanan.index') }}" class="text-decoration-none">Pesanan</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('pesanan.show', $pesanan->id_pemesanan) }}" class="text-decoration-none">#{{ $pesanan->id_pemesanan }}</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </nav>
            <h1 class="h3 text-gray-800 mb-1">
                <i class="fas fa-edit me-2"></i>Edit Pesanan
            </h1>
            <p class="text-muted mb-0">#{{ $pesanan->id_pemesanan }} - {{ $pesanan->customer->nama_customer ?? 'N/A' }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('pesanan.show', $pesanan->id_pemesanan) }}" class="btn btn-outline-info">
                <i class="fas fa-eye me-2"></i>Detail
            </a>
            <a href="{{ route('pesanan.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <h5 class="card-title mb-0">
                <i class="fas fa-edit me-2 text-primary"></i>Form Edit Pesanan
            </h5>
        </div>
        <div class="card-body">
            <form action="{{ route('pesanan.update', $pesanan->id_pemesanan) }}" method="POST" id="pesananForm">
                @csrf
                @method('PUT')
                
                <!-- Informasi Dasar -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="id_customer" class="form-label">Customer <span class="text-danger">*</span></label>
                            <select name="id_customer" id="id_customer" class="form-select @error('id_customer') is-invalid @enderror" required>
                                <option value="">Pilih Customer</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id_customer }}" {{ $pesanan->id_customer == $customer->id_customer ? 'selected' : '' }}>
                                        {{ $customer->nama_customer }} - {{ $customer->email }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_customer')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="tanggal_pemesanan" class="form-label">Tanggal Pemesanan <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="tanggal_pemesanan" id="tanggal_pemesanan" 
                                   class="form-control @error('tanggal_pemesanan') is-invalid @enderror" 
                                   value="{{ old('tanggal_pemesanan', $pesanan->tanggal_pemesanan->format('Y-m-d\TH:i')) }}" required>
                            @error('tanggal_pemesanan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="pending" {{ $pesanan->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="diproses" {{ $pesanan->status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                                <option value="dikirim" {{ $pesanan->status == 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                                <option value="selesai" {{ $pesanan->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                <option value="batal" {{ $pesanan->status == 'batal' ? 'selected' : '' }}>Batal</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <!-- Items Pesanan -->
                <h5 class="mb-3">
                    <i class="fas fa-boxes me-2 text-success"></i>Items Pesanan
                </h5>

                <div id="items-container">
                    @foreach($pesanan->detailPemesanan as $index => $detail)
                    <div class="item-row row mb-3 border-bottom pb-3">
                        <div class="col-md-5">
                            <label class="form-label">Produk & Ukuran <span class="text-danger">*</span></label>
                            <select name="items[{{ $index }}][id_ukuran]" class="form-select select-ukuran" required onchange="getDetailUkuran(this, {{ $index }})">
                                <option value="">Pilih Produk</option>
                                @foreach($detailUkuran as $ukuran)
                                    <option value="{{ $ukuran->id_ukuran }}" 
                                            data-harga="{{ $ukuran->harga }}"
                                            data-stok="{{ $ukuran->stok }}"
                                            {{ $detail->id_ukuran == $ukuran->id_ukuran ? 'selected' : '' }}>
                                        {{ $ukuran->produk->nama_produk ?? 'N/A' }} - {{ $ukuran->ukuran }} (Stok: {{ $ukuran->stok }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Harga Satuan</label>
                            <input type="text" class="form-control harga-satuan-{{ $index }}" 
                                   value="{{ 'Rp ' . number_format($detail->harga_satuan, 0, ',', '.') }}" readonly>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Jumlah <span class="text-danger">*</span></label>
                            <input type="number" name="items[{{ $index }}][jumlah]" 
                                   class="form-control jumlah-{{ $index }}" 
                                   min="1" value="{{ $detail->jumlah }}" required 
                                   onchange="updateSubtotal({{ $index }})">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Subtotal</label>
                            <input type="text" class="form-control subtotal-{{ $index }}" 
                                   value="{{ $detail->subtotal_formatted }}" readonly>
                        </div>
                        <div class="col-md-1">
                            <label class="form-label">&nbsp;</label>
                            <button type="button" class="btn btn-danger btn-block w-100" onclick="removeItem(this)" 
                                    {{ $pesanan->detailPemesanan->count() == 1 ? 'disabled' : '' }}>
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>

                <button type="button" class="btn btn-success mb-4" onclick="addItem()">
                    <i class="fas fa-plus me-2"></i>Tambah Item
                </button>

                <hr class="my-4">

                <!-- Total Harga -->
                <div class="row">
                    <div class="col-md-6 offset-md-6">
                        <div class="card bg-light">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-6">
                                        <h5 class="mb-0">Total Harga:</h5>
                                    </div>
                                    <div class="col-6 text-end">
                                        <h4 class="text-success fw-bold mb-0" id="total-harga">{{ $pesanan->total_harga_formatted }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('pesanan.show', $pesanan->id_pemesanan) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Update Pesanan
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
let itemCount = {{ $pesanan->detailPemesanan->count() }};

function addItem() {
    const container = document.getElementById('items-container');
    const newRow = document.createElement('div');
    newRow.className = 'item-row row mb-3 border-bottom pb-3';
    newRow.innerHTML = `
        <div class="col-md-5">
            <select name="items[${itemCount}][id_ukuran]" class="form-select select-ukuran" required onchange="getDetailUkuran(this, ${itemCount})">
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
        <div class="col-md-2">
            <input type="text" class="form-control harga-satuan-${itemCount}" readonly placeholder="Pilih produk">
        </div>
        <div class="col-md-2">
            <input type="number" name="items[${itemCount}][jumlah]" class="form-control jumlah-${itemCount}" min="1" value="1" required onchange="updateSubtotal(${itemCount})">
        </div>
        <div class="col-md-2">
            <input type="text" class="form-control subtotal-${itemCount}" readonly placeholder="Rp 0">
        </div>
        <div class="col-md-1">
            <button type="button" class="btn btn-danger btn-block w-100" onclick="removeItem(this)">
                <i class="fas fa-trash"></i>
            </button>
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
    const removeButtons = document.querySelectorAll('.item-row .btn-danger');
    removeButtons.forEach(button => {
        button.disabled = removeButtons.length === 1;
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
        alert('Jumlah melebihi stok yang tersedia. Disesuaikan ke stok maksimum: ' + stok);
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
        const subtotalText = document.querySelector(`.subtotal-${index}`).value;
        const subtotal = parseFloat(subtotalText.replace(/[^\d]/g, '')) || 0;
        total += subtotal;
    });
    
    document.getElementById('total-harga').textContent = 'Rp ' + formatNumber(total);
}

function formatNumber(number) {
    return new Intl.NumberFormat('id-ID').format(number);
}

// Initialize calculation
document.addEventListener('DOMContentLoaded', function() {
    updateRemoveButtons();
    calculateTotal();
});

// Form validation
document.getElementById('pesananForm').addEventListener('submit', function(e) {
    const items = document.querySelectorAll('.item-row');
    let valid = true;

    items.forEach((row, index) => {
        const select = row.querySelector('.select-ukuran');
        const jumlah = row.querySelector(`.jumlah-${index}`);
        
        if (!select.value) {
            valid = false;
            select.classList.add('is-invalid');
        } else {
            select.classList.remove('is-invalid');
        }
        
        if (!jumlah.value || parseInt(jumlah.value) < 1) {
            valid = false;
            jumlah.classList.add('is-invalid');
        } else {
            jumlah.classList.remove('is-invalid');
        }
    });

    if (!valid) {
        e.preventDefault();
        alert('Harap lengkapi semua item pesanan dengan benar.');
    }
});
</script>
@endsection
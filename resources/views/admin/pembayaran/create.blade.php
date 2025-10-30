@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('pembayaran.index') }}" class="text-decoration-none">Pembayaran</a></li>
                    <li class="breadcrumb-item active">Tambah Pembayaran</li>
                </ol>
            </nav>
            <h1 class="h3 text-gray-800 mb-1">
                <i class="fas fa-plus-circle me-2"></i>Tambah Pembayaran Baru
            </h1>
            <p class="text-muted mb-0">Buat pembayaran baru untuk pemesanan</p>
        </div>
        <a href="{{ route('pembayaran.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <!-- Form -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <h5 class="card-title mb-0">
                <i class="fas fa-credit-card me-2 text-primary"></i>Form Pembayaran
            </h5>
        </div>
        <div class="card-body">
            <form action="{{ route('pembayaran.store') }}" method="POST" id="pembayaranForm">
                @csrf
                
                <!-- Informasi Pemesanan -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="id_pemesanan" class="form-label">Pilih Pemesanan <span class="text-danger">*</span></label>
                            <select name="id_pemesanan" id="id_pemesanan" class="form-select @error('id_pemesanan') is-invalid @enderror" required onchange="updatePemesananInfo()">
                                <option value="">Pilih Pemesanan</option>
                                @foreach($pemesanan as $order)
                                    <option value="{{ $order->id_pemesanan }}" 
                                            data-total="{{ $order->total_harga }}"
                                            data-customer="{{ $order->customer->nama_customer ?? 'N/A' }}"
                                            data-email="{{ $order->customer->email ?? '' }}">
                                        #{{ $order->id_pemesanan }} - {{ $order->customer->nama_customer ?? 'N/A' }} - {{ $order->total_harga_formatted }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_pemesanan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Hanya menampilkan pemesanan yang belum memiliki pembayaran aktif</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div id="pemesananInfo" class="card bg-light" style="display: none;">
                            <div class="card-body">
                                <h6 class="card-title">Informasi Pemesanan</h6>
                                <div class="row">
                                    <div class="col-6">
                                        <small class="text-muted">Customer:</small>
                                        <div id="customerName" class="fw-bold">-</div>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted">Total:</small>
                                        <div id="totalHarga" class="fw-bold text-success">-</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <!-- Informasi Pembayaran -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="metode_pembayaran" class="form-label">Metode Pembayaran <span class="text-danger">*</span></label>
                            <select name="metode_pembayaran" id="metode_pembayaran" class="form-select @error('metode_pembayaran') is-invalid @enderror" required>
                                <option value="">Pilih Metode</option>
                                @foreach($metodePembayaran as $key => $value)
                                    <option value="{{ $key }}" {{ old('metode_pembayaran') == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                            @error('metode_pembayaran')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="channel" class="form-label">Channel Pembayaran</label>
                            <input type="text" name="channel" id="channel" 
                                   class="form-control @error('channel') is-invalid @enderror" 
                                   value="{{ old('channel') }}" 
                                   placeholder="Contoh: BCA, BRI, Gopay, dll.">
                            @error('channel')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="jumlah_bayar" class="form-label">Jumlah Bayar <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="jumlah_bayar" id="jumlah_bayar" 
                                       class="form-control @error('jumlah_bayar') is-invalid @enderror" 
                                       value="{{ old('jumlah_bayar') }}" 
                                       min="0" step="0.01" required
                                       placeholder="0">
                            </div>
                            @error('jumlah_bayar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                Sisa: <span id="sisaPembayaran" class="fw-bold">-</span>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="status_pembayaran" class="form-label">Status Pembayaran <span class="text-danger">*</span></label>
                            <select name="status_pembayaran" id="status_pembayaran" class="form-select @error('status_pembayaran') is-invalid @enderror" required>
                                <option value="belum_bayar" {{ old('status_pembayaran') == 'belum_bayar' ? 'selected' : '' }}>Belum Bayar</option>
                                <option value="menunggu" {{ old('status_pembayaran') == 'menunggu' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                                <option value="sudah_bayar" {{ old('status_pembayaran') == 'sudah_bayar' ? 'selected' : '' }}>Sudah Bayar</option>
                                <option value="gagal" {{ old('status_pembayaran') == 'gagal' ? 'selected' : '' }}>Gagal</option>
                                <option value="expired" {{ old('status_pembayaran') == 'expired' ? 'selected' : '' }}>Kadaluarsa</option>
                                <option value="refund" {{ old('status_pembayaran') == 'refund' ? 'selected' : '' }}>Refund</option>
                            </select>
                            @error('status_pembayaran')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3" id="tanggalPembayaranGroup" style="display: none;">
                            <label for="tanggal_pembayaran" class="form-label">Tanggal Pembayaran</label>
                            <input type="datetime-local" name="tanggal_pembayaran" id="tanggal_pembayaran" 
                                   class="form-control @error('tanggal_pembayaran') is-invalid @enderror" 
                                   value="{{ old('tanggal_pembayaran', now()->format('Y-m-d\TH:i')) }}">
                            @error('tanggal_pembayaran')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('pembayaran.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Simpan Pembayaran
                            </button>
                        </div>
                    </div>
                </div>
            </form>
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
        
        infoCard.style.display = 'block';
    } else {
        infoCard.style.display = 'none';
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
            sisaPembayaran.className = 'fw-bold text-danger';
        } else if (sisa < 0) {
            sisaPembayaran.textContent = 'Kelebihan: Rp ' + formatNumber(Math.abs(sisa));
            sisaPembayaran.className = 'fw-bold text-warning';
        } else {
            sisaPembayaran.textContent = 'Lunas';
            sisaPembayaran.className = 'fw-bold text-success';
        }
    } else {
        sisaPembayaran.textContent = '-';
    }
}

function formatNumber(number) {
    return new Intl.NumberFormat('id-ID').format(number);
}

// Event Listeners
document.addEventListener('DOMContentLoaded', function() {
    // Update info ketika jumlah bayar berubah
    document.getElementById('jumlah_bayar').addEventListener('input', updateSisaPembayaran);
    
    // Tampilkan tanggal pembayaran jika status sudah bayar
    document.getElementById('status_pembayaran').addEventListener('change', function() {
        const tanggalGroup = document.getElementById('tanggalPembayaranGroup');
        if (this.value === 'sudah_bayar') {
            tanggalGroup.style.display = 'block';
        } else {
            tanggalGroup.style.display = 'none';
        }
    });
    
    // Initialize jika ada nilai old
    if (document.getElementById('id_pemesanan').value) {
        updatePemesananInfo();
    }
    if (document.getElementById('status_pembayaran').value === 'sudah_bayar') {
        document.getElementById('tanggalPembayaranGroup').style.display = 'block';
    }
});

// Form validation
document.getElementById('pembayaranForm').addEventListener('submit', function(e) {
    const idPemesanan = document.getElementById('id_pemesanan').value;
    const jumlahBayar = document.getElementById('jumlah_bayar').value;
    
    if (!idPemesanan) {
        e.preventDefault();
        alert('Harap pilih pemesanan terlebih dahulu.');
        return;
    }
    
    if (!jumlahBayar || parseFloat(jumlahBayar) <= 0) {
        e.preventDefault();
        alert('Jumlah bayar harus lebih dari 0.');
        return;
    }
});
</script>
@endsection

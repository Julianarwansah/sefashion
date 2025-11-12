@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.pengiriman.index') }}" class="text-decoration-none">Pengiriman</a></li>
                    <li class="breadcrumb-item active">Tambah Pengiriman</li>
                </ol>
            </nav>
            <h1 class="h3 text-gray-800 mb-1">
                <i class="fas fa-plus-circle me-2"></i>Tambah Pengiriman Baru
            </h1>
            <p class="text-muted mb-0">Buat pengiriman baru untuk pemesanan</p>
        </div>
        <a href="{{ route('admin.pengiriman.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <!-- Form -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <h5 class="card-title mb-0">
                <i class="fas fa-shipping-fast me-2 text-primary"></i>Form Pengiriman
            </h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.pengiriman.store') }}" method="POST" id="pengirimanForm">
                @csrf
                
                <!-- Informasi Pemesanan -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="id_pemesanan" class="form-label">Pilih Pemesanan <span class="text-danger">*</span></label>
                            <select name="id_pemesanan" id="id_pemesanan" class="form-select @error('id_pemesanan') is-invalid @enderror" required onchange="updateCustomerInfo()">
                                <option value="">Pilih Pemesanan</option>
                                @foreach($pemesanan as $order)
                                    <option value="{{ $order->id_pemesanan }}" 
                                            data-customer="{{ $order->customer->nama_customer ?? 'N/A' }}"
                                            data-phone="{{ $order->customer->telepon ?? '' }}"
                                            data-address="{{ $order->customer->alamat ?? '' }}">
                                        #{{ $order->id_pemesanan }} - {{ $order->customer->nama_customer ?? 'N/A' }} - {{ $order->total_harga_formatted }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_pemesanan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Hanya menampilkan pemesanan yang belum memiliki pengiriman aktif</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div id="customerInfo" class="card bg-light" style="display: none;">
                            <div class="card-body">
                                <h6 class="card-title">Informasi Customer</h6>
                                <div class="row">
                                    <div class="col-12 mb-2">
                                        <small class="text-muted">Nama:</small>
                                        <div id="customerName" class="fw-bold">-</div>
                                    </div>
                                    <div class="col-12 mb-2">
                                        <small class="text-muted">No. HP:</small>
                                        <div id="customerPhone" class="fw-bold">-</div>
                                    </div>
                                    <div class="col-12">
                                        <small class="text-muted">Alamat:</small>
                                        <div id="customerAddress" class="fw-bold">-</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <!-- Informasi Penerima -->
                <h5 class="mb-3">
                    <i class="fas fa-user me-2 text-info"></i>Informasi Penerima
                </h5>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="nama_penerima" class="form-label">Nama Penerima <span class="text-danger">*</span></label>
                            <input type="text" name="nama_penerima" id="nama_penerima" 
                                   class="form-control @error('nama_penerima') is-invalid @enderror" 
                                   value="{{ old('nama_penerima') }}" 
                                   placeholder="Masukkan nama penerima" required>
                            @error('nama_penerima')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="no_hp_penerima" class="form-label">No. HP Penerima <span class="text-danger">*</span></label>
                            <input type="text" name="no_hp_penerima" id="no_hp_penerima" 
                                   class="form-control @error('no_hp_penerima') is-invalid @enderror" 
                                   value="{{ old('no_hp_penerima') }}" 
                                   placeholder="Contoh: 081234567890" required>
                            @error('no_hp_penerima')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label for="alamat_tujuan" class="form-label">Alamat Tujuan <span class="text-danger">*</span></label>
                    <textarea name="alamat_tujuan" id="alamat_tujuan" 
                              class="form-control @error('alamat_tujuan') is-invalid @enderror" 
                              rows="3" placeholder="Masukkan alamat lengkap tujuan pengiriman" required>{{ old('alamat_tujuan') }}</textarea>
                    @error('alamat_tujuan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <hr class="my-4">

                <!-- Informasi Pengiriman -->
                <h5 class="mb-3">
                    <i class="fas fa-truck me-2 text-success"></i>Informasi Pengiriman
                </h5>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="ekspedisi" class="form-label">Ekspedisi <span class="text-danger">*</span></label>
                            <select name="ekspedisi" id="ekspedisi" class="form-select @error('ekspedisi') is-invalid @enderror" required>
                                <option value="">Pilih Ekspedisi</option>
                                @foreach($ekspedisiList as $key => $value)
                                    <option value="{{ $key }}" {{ old('ekspedisi') == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                            @error('ekspedisi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="layanan" class="form-label">Layanan <span class="text-danger">*</span></label>
                            <select name="layanan" id="layanan" class="form-select @error('layanan') is-invalid @enderror" required>
                                <option value="">Pilih Layanan</option>
                                @foreach($layananList as $key => $value)
                                    <option value="{{ $key }}" {{ old('layanan') == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                            @error('layanan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="biaya_ongkir" class="form-label">Biaya Ongkir <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="biaya_ongkir" id="biaya_ongkir" 
                                       class="form-control @error('biaya_ongkir') is-invalid @enderror" 
                                       value="{{ old('biaya_ongkir') }}" 
                                       min="0" step="0.01" required
                                       placeholder="0">
                            </div>
                            @error('biaya_ongkir')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="no_resi" class="form-label">Nomor Resi</label>
                            <input type="text" name="no_resi" id="no_resi" 
                                   class="form-control @error('no_resi') is-invalid @enderror" 
                                   value="{{ old('no_resi') }}" 
                                   placeholder="Masukkan nomor resi (opsional)">
                            @error('no_resi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <!-- Status Pengiriman -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="status_pengiriman" class="form-label">Status Pengiriman <span class="text-danger">*</span></label>
                            <select name="status_pengiriman" id="status_pengiriman" class="form-select @error('status_pengiriman') is-invalid @enderror" required onchange="toggleTanggalFields()">
                                <option value="menunggu" {{ old('status_pengiriman') == 'menunggu' ? 'selected' : '' }}>Menunggu Pengiriman</option>
                                <option value="dikirim" {{ old('status_pengiriman') == 'dikirim' ? 'selected' : '' }}>Sedang Dikirim</option>
                                <option value="diterima" {{ old('status_pengiriman') == 'diterima' ? 'selected' : '' }}>Sudah Diterima</option>
                                <option value="gagal" {{ old('status_pengiriman') == 'gagal' ? 'selected' : '' }}>Gagal Dikirim</option>
                            </select>
                            @error('status_pengiriman')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3" id="tanggalDikirimGroup" style="display: none;">
                            <label for="tanggal_dikirim" class="form-label">Tanggal Dikirim</label>
                            <input type="datetime-local" name="tanggal_dikirim" id="tanggal_dikirim" 
                                   class="form-control @error('tanggal_dikirim') is-invalid @enderror" 
                                   value="{{ old('tanggal_dikirim') }}">
                            @error('tanggal_dikirim')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3" id="tanggalDiterimaGroup" style="display: none;">
                            <label for="tanggal_diterima" class="form-label">Tanggal Diterima</label>
                            <input type="datetime-local" name="tanggal_diterima" id="tanggal_diterima" 
                                   class="form-control @error('tanggal_diterima') is-invalid @enderror" 
                                   value="{{ old('tanggal_diterima') }}">
                            @error('tanggal_diterima')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('admin.pengiriman.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Simpan Pengiriman
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
function updateCustomerInfo() {
    const select = document.getElementById('id_pemesanan');
    const selectedOption = select.options[select.selectedIndex];
    const infoCard = document.getElementById('customerInfo');
    const customerName = document.getElementById('customerName');
    const customerPhone = document.getElementById('customerPhone');
    const customerAddress = document.getElementById('customerAddress');
    const namaPenerima = document.getElementById('nama_penerima');
    const noHpPenerima = document.getElementById('no_hp_penerima');
    const alamatTujuan = document.getElementById('alamat_tujuan');

    if (selectedOption.value) {
        const customer = selectedOption.getAttribute('data-customer');
        const phone = selectedOption.getAttribute('data-phone');
        const address = selectedOption.getAttribute('data-address');

        customerName.textContent = customer;
        customerPhone.textContent = phone || '-';
        customerAddress.textContent = address || '-';
        
        // Auto-fill form fields
        if (!namaPenerima.value) {
            namaPenerima.value = customer;
        }
        if (!noHpPenerima.value && phone) {
            noHpPenerima.value = phone;
        }
        if (!alamatTujuan.value && address) {
            alamatTujuan.value = address;
        }
        
        infoCard.style.display = 'block';
    } else {
        infoCard.style.display = 'none';
        customerName.textContent = '-';
        customerPhone.textContent = '-';
        customerAddress.textContent = '-';
    }
}

function toggleTanggalFields() {
    const status = document.getElementById('status_pengiriman').value;
    const tanggalDikirimGroup = document.getElementById('tanggalDikirimGroup');
    const tanggalDiterimaGroup = document.getElementById('tanggalDiterimaGroup');
    const tanggalDikirim = document.getElementById('tanggal_dikirim');
    const tanggalDiterima = document.getElementById('tanggal_diterima');

    // Reset display
    tanggalDikirimGroup.style.display = 'none';
    tanggalDiterimaGroup.style.display = 'none';

    // Show/hide based on status
    if (status === 'dikirim' || status === 'diterima') {
        tanggalDikirimGroup.style.display = 'block';
        if (!tanggalDikirim.value) {
            const now = new Date();
            const localDateTime = now.toISOString().slice(0, 16);
            tanggalDikirim.value = localDateTime;
        }
    }

    if (status === 'diterima') {
        tanggalDiterimaGroup.style.display = 'block';
        if (!tanggalDiterima.value) {
            const now = new Date();
            const localDateTime = now.toISOString().slice(0, 16);
            tanggalDiterima.value = localDateTime;
        }
    }
}

// Event Listeners
document.addEventListener('DOMContentLoaded', function() {
    // Initialize customer info if there's old data
    if (document.getElementById('id_pemesanan').value) {
        updateCustomerInfo();
    }
    
    // Initialize tanggal fields based on status
    toggleTanggalFields();
    
    // Add event listener for status change
    document.getElementById('status_pengiriman').addEventListener('change', toggleTanggalFields);
});

// Form validation
document.getElementById('pengirimanForm').addEventListener('submit', function(e) {
    const idPemesanan = document.getElementById('id_pemesanan').value;
    const biayaOngkir = document.getElementById('biaya_ongkir').value;
    
    if (!idPemesanan) {
        e.preventDefault();
        alert('Harap pilih pemesanan terlebih dahulu.');
        return;
    }
    
    if (!biayaOngkir || parseFloat(biayaOngkir) < 0) {
        e.preventDefault();
        alert('Biaya ongkir harus diisi dengan nilai yang valid.');
        return;
    }
});
</script>
@endsection
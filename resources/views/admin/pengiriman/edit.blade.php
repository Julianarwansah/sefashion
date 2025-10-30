@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('pengiriman.index') }}" class="text-decoration-none">Pengiriman</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('pengiriman.show', $pengiriman->id_pengiriman) }}" class="text-decoration-none">#{{ $pengiriman->id_pengiriman }}</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </nav>
            <h1 class="h3 text-gray-800 mb-1">
                <i class="fas fa-edit me-2"></i>Edit Pengiriman
            </h1>
            <p class="text-muted mb-0">#{{ $pengiriman->id_pengiriman }} - {{ $pengiriman->pemesanan->customer->nama_customer ?? 'N/A' }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('pengiriman.show', $pengiriman->id_pengiriman) }}" class="btn btn-outline-info">
                <i class="fas fa-eye me-2"></i>Detail
            </a>
            <a href="{{ route('pengiriman.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <h5 class="card-title mb-0">
                <i class="fas fa-shipping-fast me-2 text-primary"></i>Form Edit Pengiriman
            </h5>
        </div>
        <div class="card-body">
            <form action="{{ route('pengiriman.update', $pengiriman->id_pengiriman) }}" method="POST" id="pengirimanForm">
                @csrf
                @method('PUT')
                
                <!-- Informasi Pemesanan (Readonly) -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title">Informasi Pemesanan</h6>
                                <div class="row">
                                    <div class="col-md-3">
                                        <small class="text-muted">ID Pemesanan:</small>
                                        <div class="fw-bold">#{{ $pengiriman->pemesanan->id_pemesanan }}</div>
                                    </div>
                                    <div class="col-md-3">
                                        <small class="text-muted">Customer:</small>
                                        <div class="fw-bold">{{ $pengiriman->pemesanan->customer->nama_customer ?? 'N/A' }}</div>
                                    </div>
                                    <div class="col-md-3">
                                        <small class="text-muted">Total Pemesanan:</small>
                                        <div class="fw-bold text-success">{{ $pengiriman->pemesanan->total_harga_formatted }}</div>
                                    </div>
                                    <div class="col-md-3">
                                        <small class="text-muted">Status Pemesanan:</small>
                                        <div>
                                            @php
                                                $statusPemesananColors = [
                                                    'pending' => 'warning',
                                                    'diproses' => 'info',
                                                    'dikirim' => 'primary',
                                                    'selesai' => 'success',
                                                    'batal' => 'danger'
                                                ];
                                                $colorPemesanan = $statusPemesananColors[$pengiriman->pemesanan->status] ?? 'secondary';
                                            @endphp
                                            <span class="badge bg-{{ $colorPemesanan }}">
                                                {{ ucfirst($pengiriman->pemesanan->status) }}
                                            </span>
                                        </div>
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
                                   value="{{ old('nama_penerima', $pengiriman->nama_penerima) }}" 
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
                                   value="{{ old('no_hp_penerima', $pengiriman->no_hp_penerima) }}" 
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
                              rows="3" placeholder="Masukkan alamat lengkap tujuan pengiriman" required>{{ old('alamat_tujuan', $pengiriman->alamat_tujuan) }}</textarea>
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
                                    <option value="{{ $key }}" {{ old('ekspedisi', $pengiriman->ekspedisi) == $key ? 'selected' : '' }}>
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
                                    <option value="{{ $key }}" {{ old('layanan', $pengiriman->layanan) == $key ? 'selected' : '' }}>
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
                                       value="{{ old('biaya_ongkir', $pengiriman->biaya_ongkir) }}" 
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
                                   value="{{ old('no_resi', $pengiriman->no_resi) }}" 
                                   placeholder="Masukkan nomor resi (opsional)">
                            @error('no_resi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if($pengiriman->no_resi && $pengiriman->tracking_url)
                            <div class="form-text">
                                <a href="{{ $pengiriman->tracking_url }}" target="_blank" class="text-decoration-none">
                                    <i class="fas fa-external-link-alt me-1"></i>Lacak Pengiriman
                                </a>
                            </div>
                            @endif
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
                                @foreach($statusPengiriman as $key => $value)
                                    <option value="{{ $key }}" {{ old('status_pengiriman', $pengiriman->status_pengiriman) == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status_pengiriman')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3" id="tanggalDikirimGroup">
                            <label for="tanggal_dikirim" class="form-label">Tanggal Dikirim</label>
                            <input type="datetime-local" name="tanggal_dikirim" id="tanggal_dikirim" 
                                   class="form-control @error('tanggal_dikirim') is-invalid @enderror" 
                                   value="{{ old('tanggal_dikirim', $pengiriman->tanggal_dikirim ? $pengiriman->tanggal_dikirim->format('Y-m-d\TH:i') : '') }}">
                            @error('tanggal_dikirim')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3" id="tanggalDiterimaGroup">
                            <label for="tanggal_diterima" class="form-label">Tanggal Diterima</label>
                            <input type="datetime-local" name="tanggal_diterima" id="tanggal_diterima" 
                                   class="form-control @error('tanggal_diterima') is-invalid @enderror" 
                                   value="{{ old('tanggal_diterima', $pengiriman->tanggal_diterima ? $pengiriman->tanggal_diterima->format('Y-m-d\TH:i') : '') }}">
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
                            <a href="{{ route('pengiriman.show', $pengiriman->id_pengiriman) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Update Pengiriman
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
function toggleTanggalFields() {
    const status = document.getElementById('status_pengiriman').value;
    const tanggalDikirim = document.getElementById('tanggal_dikirim');
    const tanggalDiterima = document.getElementById('tanggal_diterima');

    // Auto-set tanggal jika status berubah
    if (status === 'dikirim' && !tanggalDikirim.value) {
        const now = new Date();
        const localDateTime = now.toISOString().slice(0, 16);
        tanggalDikirim.value = localDateTime;
    }

    if (status === 'diterima') {
        if (!tanggalDikirim.value) {
            const now = new Date();
            const localDateTime = now.toISOString().slice(0, 16);
            tanggalDikirim.value = localDateTime;
        }
        if (!tanggalDiterima.value) {
            const now = new Date();
            const localDateTime = now.toISOString().slice(0, 16);
            tanggalDiterima.value = localDateTime;
        }
    }
}

// Event Listeners
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tanggal fields based on current status
    toggleTanggalFields();
    
    // Add event listener for status change
    document.getElementById('status_pengiriman').addEventListener('change', toggleTanggalFields);
});

// Form validation
document.getElementById('pengirimanForm').addEventListener('submit', function(e) {
    const biayaOngkir = document.getElementById('biaya_ongkir').value;
    
    if (!biayaOngkir || parseFloat(biayaOngkir) < 0) {
        e.preventDefault();
        alert('Biaya ongkir harus diisi dengan nilai yang valid.');
        return;
    }
});
</script>
@endsection


@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('pembayaran.index') }}" class="text-decoration-none">Pembayaran</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('pembayaran.show', $pembayaran->id_pembayaran) }}" class="text-decoration-none">#{{ $pembayaran->id_pembayaran }}</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </nav>
            <h1 class="h3 text-gray-800 mb-1">
                <i class="fas fa-edit me-2"></i>Edit Pembayaran
            </h1>
            <p class="text-muted mb-0">#{{ $pembayaran->id_pembayaran }} - {{ $pembayaran->pemesanan->customer->nama_customer ?? 'N/A' }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('pembayaran.show', $pembayaran->id_pembayaran) }}" class="btn btn-outline-info">
                <i class="fas fa-eye me-2"></i>Detail
            </a>
            <a href="{{ route('pembayaran.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <h5 class="card-title mb-0">
                <i class="fas fa-credit-card me-2 text-primary"></i>Form Edit Pembayaran
            </h5>
        </div>
        <div class="card-body">
            <form action="{{ route('pembayaran.update', $pembayaran->id_pembayaran) }}" method="POST" id="pembayaranForm">
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
                                        <div class="fw-bold">#{{ $pembayaran->pemesanan->id_pemesanan }}</div>
                                    </div>
                                    <div class="col-md-3">
                                        <small class="text-muted">Customer:</small>
                                        <div class="fw-bold">{{ $pembayaran->pemesanan->customer->nama_customer ?? 'N/A' }}</div>
                                    </div>
                                    <div class="col-md-3">
                                        <small class="text-muted">Total Pemesanan:</small>
                                        <div class="fw-bold text-success">{{ $pembayaran->pemesanan->total_harga_formatted }}</div>
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
                                                $colorPemesanan = $statusPemesananColors[$pembayaran->pemesanan->status] ?? 'secondary';
                                            @endphp
                                            <span class="badge bg-{{ $colorPemesanan }}">
                                                {{ ucfirst($pembayaran->pemesanan->status) }}
                                            </span>
                                        </div>
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
                                    <option value="{{ $key }}" {{ $pembayaran->metode_pembayaran == $key ? 'selected' : '' }}>
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
                                   value="{{ old('channel', $pembayaran->channel) }}" 
                                   placeholder="Contoh: BCA, BRI, Gopay, dll.">
                            @error('channel')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label">External ID</label>
                            <input type="text" class="form-control" value="{{ $pembayaran->external_id }}" readonly>
                            <div class="form-text">ID unik untuk referensi payment gateway</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="jumlah_bayar" class="form-label">Jumlah Bayar <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="jumlah_bayar" id="jumlah_bayar" 
                                       class="form-control @error('jumlah_bayar') is-invalid @enderror" 
                                       value="{{ old('jumlah_bayar', $pembayaran->jumlah_bayar) }}" 
                                       min="0" step="0.01" required
                                       placeholder="0">
                            </div>
                            @error('jumlah_bayar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                Sisa: <span id="sisaPembayaran" class="fw-bold">
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

                        <div class="form-group mb-3">
                            <label for="status_pembayaran" class="form-label">Status Pembayaran <span class="text-danger">*</span></label>
                            <select name="status_pembayaran" id="status_pembayaran" class="form-select @error('status_pembayaran') is-invalid @enderror" required>
                                @foreach($statusPembayaran as $key => $value)
                                    <option value="{{ $key }}" {{ $pembayaran->status_pembayaran == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status_pembayaran')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3" id="tanggalPembayaranGroup">
                            <label for="tanggal_pembayaran" class="form-label">Tanggal Pembayaran</label>
                            <input type="datetime-local" name="tanggal_pembayaran" id="tanggal_pembayaran" 
                                   class="form-control @error('tanggal_pembayaran') is-invalid @enderror" 
                                   value="{{ old('tanggal_pembayaran', $pembayaran->tanggal_pembayaran ? $pembayaran->tanggal_pembayaran->format('Y-m-d\TH:i') : '') }}">
                            @error('tanggal_pembayaran')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Diisi otomatis ketika status diubah menjadi "Sudah Bayar"</div>
                        </div>

                        @if($pembayaran->invoice_id)
                        <div class="form-group mb-3">
                            <label class="form-label">Invoice ID</label>
                            <input type="text" class="form-control" value="{{ $pembayaran->invoice_id }}" readonly>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Informasi Tambahan -->
                @if($pembayaran->payment_url || $pembayaran->raw_response)
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="card border-0 bg-light">
                            <div class="card-body">
                                <h6 class="card-title">Informasi Tambahan</h6>
                                @if($pembayaran->payment_url)
                                <div class="mb-2">
                                    <label class="form-label small text-muted">Payment URL</label>
                                    <div>
                                        <a href="{{ $pembayaran->payment_url }}" target="_blank" class="text-decoration-none">
                                            {{ Str::limit($pembayaran->payment_url, 50) }}
                                            <i class="fas fa-external-link-alt ms-1"></i>
                                        </a>
                                    </div>
                                </div>
                                @endif
                                @if($pembayaran->raw_response)
                                <div>
                                    <label class="form-label small text-muted">Raw Response</label>
                                    <pre class="bg-dark text-light p-2 rounded small" style="max-height: 150px; overflow-y: auto;"><code>{{ json_encode($pembayaran->raw_response, JSON_PRETTY_PRINT) }}</code></pre>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Submit Button -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('pembayaran.show', $pembayaran->id_pembayaran) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Update Pembayaran
                            </button>
                        </div>
                    </div>
                </div>
            </form>
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
            sisaPembayaran.className = 'fw-bold text-danger';
        } else if (sisa < 0) {
            sisaPembayaran.textContent = 'Kelebihan: Rp ' + formatNumber(Math.abs(sisa));
            sisaPembayaran.className = 'fw-bold text-warning';
        } else {
            sisaPembayaran.textContent = 'Lunas';
            sisaPembayaran.className = 'fw-bold text-success';
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
        alert('Jumlah bayar harus lebih dari 0.');
        return;
    }
});
</script>
@endsection
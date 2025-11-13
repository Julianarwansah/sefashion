@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Detail Customer</h4>
                    <a href="{{ route('admin.customer.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label fw-bold">ID Customer</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext">{{ $customer->id_customer }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label fw-bold">Nama Lengkap</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext">{{ $customer->nama }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label fw-bold">Email</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext">{{ $customer->email }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label fw-bold">No. HP</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext">{{ $customer->no_hp }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label fw-bold">Alamat</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext">{{ $customer->alamat }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label fw-bold">Total Pesanan</label>
                        <div class="col-sm-9">
                            <span class="badge bg-primary fs-6">{{ $customer->pemesanan->count() }}</span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label fw-bold">Item dalam Cart</label>
                        <div class="col-sm-9">
                            <span class="badge bg-warning fs-6">{{ $customer->cart->count() }}</span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label fw-bold">Dibuat Pada</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext">{{ $customer->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <a href="{{ route('admin.customer.edit', $customer->id_customer) }}" class="btn btn-warning me-2">Edit</a>
                        <form action="{{ route('admin.customer.destroy', $customer->id_customer) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" 
                                    onclick="return confirm('Yakin ingin menghapus customer ini?')">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
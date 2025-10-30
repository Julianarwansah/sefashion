@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Detail Admin</h4>
                    <a href="{{ route('admin.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label fw-bold">ID Admin</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext">{{ $admin->id_admin }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label fw-bold">Nama Lengkap</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext">{{ $admin->nama }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label fw-bold">Email</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext">{{ $admin->email }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label fw-bold">No. HP</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext">{{ $admin->no_hp ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label fw-bold">Alamat</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext">{{ $admin->alamat ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label fw-bold">Dibuat Pada</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext">{{ $admin->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <a href="{{ route('admin.edit', $admin->id_admin) }}" class="btn btn-warning me-2">Edit</a>
                        <form action="{{ route('admin.destroy', $admin->id_admin) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" 
                                    onclick="return confirm('Yakin ingin menghapus admin ini?')">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container py-4">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Beranda /</span> Dashboard
    </h4>

    <!-- Statistik Utama -->
    <div class="row mb-4">
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card bg-primary text-white shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-2">TOTAL PRODUK</h6>
                        <h3 class="mb-0">120</h3>
                        <small class="text-white-50">Semua Produk</small>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded-circle bg-white-10">
                            <i class="bx bx-tshirt bx-lg"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card bg-success text-white shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-2">KATEGORI</h6>
                        <h3 class="mb-0">10</h3>
                        <small class="text-white-50">Total Kategori</small>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded-circle bg-white-10">
                            <i class="bx bx-category bx-lg"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card bg-info text-white shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-2">BRAND</h6>
                        <h3 class="mb-0">5</h3>
                        <small class="text-white-50">Total Brand</small>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded-circle bg-white-10">
                            <i class="bx bx-store bx-lg"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card bg-warning text-white shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-2">SLIDER</h6>
                        <h3 class="mb-0">4</h3>
                        <small class="text-white-50">Total Slider</small>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded-circle bg-white-10">
                            <i class="bx bx-slider-alt bx-lg"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafik Produk per Kategori -->
    <div class="row mb-4">
        <div class="col-lg-12 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center bg-white">
                    <h5 class="mb-0">Distribusi Produk per Kategori</h5>
                </div>
                <div class="card-body pt-0">
                    <canvas id="produkKategoriChart" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Produk Terbaru -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Produk Terbaru</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Produk</th>
                                <th>Kategori</th>
                                <th>Brand</th>
                                <th>Tanggal Dibuat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Kaos Polos</td>
                                <td>Pakaian</td>
                                <td>Uniqlo</td>
                                <td>12 Okt 2025</td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <button class="btn btn-sm btn-outline-info"><i class="bx bx-show"></i></button>
                                        <button class="btn btn-sm btn-outline-warning"><i class="bx bx-edit"></i></button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Celana Jeans</td>
                                <td>Pakaian</td>
                                <td>Levi’s</td>
                                <td>10 Okt 2025</td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <button class="btn btn-sm btn-outline-info"><i class="bx bx-show"></i></button>
                                        <button class="btn btn-sm btn-outline-warning"><i class="bx bx-edit"></i></button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- ChartJS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const produkKategoriCtx = document.getElementById('produkKategoriChart').getContext('2d');
        const produkKategoriChart = new Chart(produkKategoriCtx, {
            type: 'bar',
            data: {
                labels: ['Pakaian', 'Aksesoris', 'Sepatu', 'Tas'],
                datasets: [{
                    label: 'Jumlah Produk',
                    data: [40, 25, 15, 10],
                    backgroundColor: 'rgba(105, 108, 255, 0.7)',
                    borderColor: 'rgba(105, 108, 255, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.raw + ' produk';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value + ' produk';
                            }
                        }
                    }
                }
            }
        });
    </script>
</div>
@endsection

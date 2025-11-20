@extends('layouts.app')

@section('title', 'Detail Customer - Konveksi')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-50 via-pink-50 to-yellow-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header Section -->
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-8">
            <div>
                <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-2">Detail Customer</h1>
                <p class="text-gray-600 text-lg">Informasi lengkap customer</p>
            </div>
            <a href="{{ route('admin.customer.index') }}" 
               class="inline-flex items-center gap-2 bg-gray-500 hover:bg-gray-600 text-white font-semibold px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Daftar
            </a>
        </div>

        <!-- Profile Card -->
        <div class="bg-white/80 glass rounded-2xl shadow-xl border border-white/20 overflow-hidden">
            <!-- Profile Header -->
            <div class="bg-gradient-to-r from-purple-600 to-pink-600 p-6 text-white">
                <div class="flex items-center gap-4">
                    <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm text-2xl font-bold">
                        {{ substr($customer->nama, 0, 1) }}
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold">{{ $customer->nama }}</h2>
                        <p class="text-purple-200">Customer</p>
                    </div>
                </div>
            </div>

            <!-- Profile Details -->
            <div class="p-6 sm:p-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Basic Information -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">
                            Informasi Dasar
                        </h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">ID Customer</label>
                                <p class="text-lg font-semibold text-gray-900 bg-gray-50 px-4 py-2 rounded-lg">
                                    {{ $customer->id_customer }}
                                </p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Nama Lengkap</label>
                                <p class="text-lg font-semibold text-gray-900 bg-gray-50 px-4 py-2 rounded-lg">
                                    {{ $customer->nama }}
                                </p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Email</label>
                                <p class="text-lg font-semibold text-gray-900 bg-gray-50 px-4 py-2 rounded-lg break-all">
                                    {{ $customer->email }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Contact & Activity Information -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">
                            Kontak & Aktivitas
                        </h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">No. HP</label>
                                <p class="text-lg font-semibold text-gray-900 bg-gray-50 px-4 py-2 rounded-lg">
                                    {{ $customer->no_hp }}
                                </p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Alamat</label>
                                <p class="text-lg font-semibold text-gray-900 bg-gray-50 px-4 py-2 rounded-lg min-h-[60px]">
                                    {{ $customer->alamat }}
                                </p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Bergabung Pada</label>
                                <p class="text-lg font-semibold text-gray-900 bg-gray-50 px-4 py-2 rounded-lg">
                                    {{ $customer->created_at->format('d F Y H:i') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
                    <!-- Total Pesanan -->
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-blue-600">Total Pesanan</p>
                                <p class="text-2xl font-bold text-blue-900">{{ $customer->pemesanan->count() }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Item dalam Cart -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-yellow-500 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-yellow-600">Item dalam Cart</p>
                                <p class="text-2xl font-bold text-yellow-900">{{ $customer->cart->count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 pt-8 mt-8 border-t border-gray-200">
                    <a href="{{ route('admin.customer.edit', $customer->id_customer) }}" 
                       class="flex-1 inline-flex items-center justify-center gap-2 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit Data
                    </a>
                    
                    <button type="button" 
                            onclick="confirmDelete()"
                            class="flex-1 inline-flex items-center justify-center gap-2 bg-red-500 hover:bg-red-600 text-white font-semibold px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Hapus Customer
                    </button>
                </div>

                <!-- Hidden Delete Form -->
                <form id="deleteForm" action="{{ route('admin.customer.destroy', $customer->id_customer) }}" method="POST" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Confirm delete function
    function confirmDelete() {
        Swal.fire({
            title: 'Yakin ingin menghapus?',
            html: `Customer <strong>"{{ $customer->nama }}"</strong> akan dihapus permanen dari sistem.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: {
                confirmButton: 'bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded-lg mr-2',
                cancelButton: 'bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit the delete form
                document.getElementById('deleteForm').submit();
            }
        });
    }

    // Add page load animations
    document.addEventListener('DOMContentLoaded', function() {
        const elements = document.querySelectorAll('.bg-gray-50, .bg-blue-50, .bg-yellow-50');
        elements.forEach((element, index) => {
            element.style.opacity = '0';
            element.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                element.style.transition = 'all 0.5s ease';
                element.style.opacity = '1';
                element.style.transform = 'translateY(0)';
            }, index * 100);
        });
    });
</script>

<style>
    .swal2-popup {
        font-family: 'Inter', sans-serif;
        border-radius: 1rem;
    }
</style>
@endpush
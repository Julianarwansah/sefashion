@extends('layouts.app')

@section('title', 'Data Admin - Konveksi')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-50 via-pink-50 to-yellow-50 p-4 lg:p-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-2">Data Admin</h1>
                    <p class="text-gray-600">Kelola data administrator sistem</p>
                </div>
                <a href="{{ route('admin.adminn.create') }}" 
                   class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-semibold px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Tambah Admin
                </a>
            </div>
            <!-- Search and Filter Card -->
            <div class="bg-white/80 glass rounded-2xl shadow-xl border border-white/20 overflow-hidden mb-6">
                <div class="px-6 py-4 bg-gradient-to-r from-purple-600 to-pink-600 text-white">
                    <h2 class="text-xl font-bold">Pencarian & Filter Admin</h2>
                </div>
                <div class="p-6">
                    <form id="searchForm" action="{{ route('admin.adminn.search') }}" method="GET" class="space-y-4">
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
                            <!-- Search Input -->
                            <div class="lg:col-span-5">
                                <label for="search" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Cari Admin
                                </label>
                                <div class="relative">
                                    <input type="text" 
                                        id="search" 
                                        name="search" 
                                        value="{{ request('search') }}"
                                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200"
                                        placeholder="Cari berdasarkan nama, email, no HP, alamat..."
                                        maxlength="100">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Sort By -->
                            <div class="lg:col-span-4">
                                <label for="sort_by" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Urutkan Berdasarkan
                                </label>
                                <select id="sort_by" 
                                        name="sort_by" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200">
                                    <option value="terbaru" {{ request('sort_by', 'terbaru') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                                    <option value="terlama" {{ request('sort_by') == 'terlama' ? 'selected' : '' }}>Terlama</option>
                                    <option value="nama_asc" {{ request('sort_by') == 'nama_asc' ? 'selected' : '' }}>Nama A-Z</option>
                                    <option value="nama_desc" {{ request('sort_by') == 'nama_desc' ? 'selected' : '' }}>Nama Z-A</option>
                                    <option value="email_asc" {{ request('sort_by') == 'email_asc' ? 'selected' : '' }}>Email A-Z</option>
                                    <option value="email_desc" {{ request('sort_by') == 'email_desc' ? 'selected' : '' }}>Email Z-A</option>
                                </select>
                            </div>

                            <!-- Action Buttons -->
                            <div class="lg:col-span-3 flex items-end space-x-2">
                                <button type="submit" 
                                        class="w-full bg-gradient-to-r from-green-500 to-blue-500 hover:from-green-600 hover:to-blue-600 text-white font-semibold py-3 px-4 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                    Cari
                                </button>
                                
                                <a href="{{ route('admin.adminn.index') }}" 
                                class="w-full bg-gray-500 hover:bg-gray-600 text-white font-semibold py-3 px-4 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 flex items-center justify-center gap-2"
                                title="Reset pencarian">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Results Info -->
            @if(request()->has('search') || request()->has('sort_by'))
                <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-xl">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-blue-800 font-medium">
                                Menampilkan 
                                @if(request('search'))
                                    hasil pencarian "<strong>{{ request('search') }}</strong>"
                                @endif
                                ({{ $admin->total() }} hasil ditemukan)
                            </span>
                        </div>
                        <a href="{{ route('admin.adminn.index') }}" 
                        class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            Tampilkan semua admin
                        </a>
                    </div>
                </div>
            @endif

                <!-- Admin Table -->
                <div id="adminTableContainer">
                    @include('admin.adminn.partials.admin-table')
                </div>
            </div>
        </div>

        <!-- Alert Messages -->
        <div class="mb-6 space-y-3">
            @if(session('success'))
                <div id="success-alert" class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl shadow-sm">
                    <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="flex-1">{{ session('success') }}</span>
                    <button type="button" onclick="dismissAlert('success-alert')" class="text-green-500 hover:text-green-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div id="error-alert" class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl shadow-sm">
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="flex-1">{{ session('error') }}</span>
                    <button type="button" onclick="dismissAlert('error-alert')" class="text-red-500 hover:text-red-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            @endif
        </div>

        <!-- Content Card -->
        <div class="bg-white/80 glass rounded-2xl shadow-xl border border-white/20 overflow-hidden">
            @if($admin->isEmpty())
                <!-- Empty State -->
                <div class="text-center py-16 px-4">
                    <div class="w-24 h-24 mx-auto mb-4 bg-gradient-to-br from-purple-100 to-pink-100 rounded-full flex items-center justify-center">
                        <svg class="w-12 h-12 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">Belum ada data admin</h3>
                    <p class="text-gray-500 mb-6">Mulai dengan menambahkan admin baru ke sistem</p>
                    <a href="{{ route('admin.adminn.create') }}" 
                       class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-semibold px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Tambah Admin Pertama
                    </a>
                </div>
            @else
                <!-- Table Section -->
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gradient-to-r from-purple-600 to-pink-600 text-white">
                                <th class="px-6 py-4 text-left font-semibold">#</th>
                                <th class="px-6 py-4 text-left font-semibold">Nama</th>
                                <th class="px-6 py-4 text-left font-semibold">Email</th>
                                <th class="px-6 py-4 text-left font-semibold">No HP</th>
                                <th class="px-6 py-4 text-left font-semibold">Alamat</th>
                                <th class="px-6 py-4 text-left font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($admin as $admin)
                            <tr class="hover:bg-gradient-to-r hover:from-purple-50 hover:to-pink-50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-semibold text-gray-900">{{ $admin->nama }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-600">{{ $admin->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-600">{{ $admin->no_hp ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-600 max-w-xs truncate">
                                        {{ $admin->alamat ?? '-' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <!-- Detail Button -->
                                        <a href="{{ route('admin.adminn.show', $admin->id_admin) }}" 
                                           class="inline-flex items-center gap-1 bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200 transform hover:scale-105"
                                           title="Detail">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>
                                        
                                        <!-- Edit Button -->
                                        <a href="{{ route('admin.adminn.edit', $admin->id_admin) }}" 
                                           class="inline-flex items-center gap-1 bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200 transform hover:scale-105"
                                           title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>
                                        
                                        <!-- Delete Button -->
                                        <form action="{{ route('admin.adminn.destroy', $admin->id_admin) }}" 
                                              method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" 
                                                    onclick="confirmDelete('{{ $admin->nama }}', this)"
                                                    class="inline-flex items-center gap-1 bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200 transform hover:scale-105"
                                                    title="Hapus">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Table Info -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    <p class="text-sm text-gray-600">
                        Menampilkan <span class="font-semibold">{{ $admin->count() }}</span> data admin
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Dismiss alert function
    function dismissAlert(alertId) {
        const alert = document.getElementById(alertId);
        if (alert) {
            alert.style.opacity = '0';
            alert.style.transform = 'translateX(100%)';
            setTimeout(() => alert.remove(), 300);
        }
    }

    // Auto dismiss alerts after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('[id$="-alert"]');
        alerts.forEach(alert => {
            setTimeout(() => {
                if (alert && alert.parentNode) {
                    dismissAlert(alert.id);
                }
            }, 5000);
        });
    });

    // Confirm delete function
    function confirmDelete(adminName, button) {
        Swal.fire({
            title: 'Yakin ingin menghapus?',
            html: `Admin <strong>"${adminName}"</strong> akan dihapus permanen.`,
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
                // Submit the form
                button.closest('form').submit();
            }
        });
    }

    // Add smooth animations
    document.addEventListener('DOMContentLoaded', function() {
        // Animate table rows
        const tableRows = document.querySelectorAll('tbody tr');
        tableRows.forEach((row, index) => {
            row.style.opacity = '0';
            row.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                row.style.transition = 'all 0.5s ease';
                row.style.opacity = '1';
                row.style.transform = 'translateY(0)';
            }, index * 100);
        });
    });
</script>

<!-- Include SweetAlert2 for beautiful confirm dialogs -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchForm = document.getElementById('searchForm');
    const searchInput = document.getElementById('search');
    const sortSelect = document.getElementById('sort_by');
    const adminTableContainer = document.getElementById('adminTableContainer');
    
    let searchTimeout;
    
    // Real-time search dengan debounce
    searchInput.addEventListener('input', function(e) {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            performSearch();
        }, 500);
    });
    
    // Instant search untuk select changes
    sortSelect.addEventListener('change', performSearch);
    
    function performSearch() {
        const formData = new FormData(searchForm);
        
        // Show loading
        adminTableContainer.innerHTML = `
            <div class="flex justify-center items-center py-12">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-purple-600"></div>
                <span class="ml-3 text-gray-600">Mencari admin...</span>
            </div>
        `;
        
        fetch(searchForm.action + '?' + new URLSearchParams(formData), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                adminTableContainer.innerHTML = data.html;
                
                // Update pagination jika ada
                if (data.pagination) {
                    const existingPagination = document.querySelector('.pagination');
                    if (existingPagination) {
                        existingPagination.innerHTML = data.pagination;
                    }
                }
            } else {
                adminTableContainer.innerHTML = `
                    <div class="text-center py-8 text-red-600">
                        <svg class="w-12 h-12 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                        <p>Terjadi kesalahan saat mencari admin.</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Search error:', error);
            adminTableContainer.innerHTML = `
                <div class="text-center py-8 text-red-600">
                    <p>Terjadi kesalahan saat mencari admin.</p>
                </div>
            `;
        });
    }
    
    // Handle pagination clicks (untuk AJAX pagination)
    document.addEventListener('click', function(e) {
        if (e.target.closest('.pagination a')) {
            e.preventDefault();
            const url = e.target.closest('a').href;
            
            adminTableContainer.innerHTML = `
                <div class="flex justify-center items-center py-8">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-purple-600"></div>
                </div>
            `;
            
            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    adminTableContainer.innerHTML = data.html;
                }
            })
            .catch(error => {
                console.error('Pagination error:', error);
                window.location.href = url; // Fallback ke normal page load
            });
        }
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
{{-- resources/views/admin/produk/partials/produk-table.blade.php --}}

@if($produk->count() > 0)
    <div class="bg-white/80 glass rounded-2xl shadow-xl border border-white/20 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gradient-to-r from-purple-600 to-pink-600 text-white">
                        <th class="px-6 py-4 text-left text-sm font-semibold">Produk</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Kategori</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Warna & Ukuran</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Stok</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Harga</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Status</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($produk as $item)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-500 rounded-lg overflow-hidden flex-shrink-0">
                                        @if($item->gambar)
                                            <img src="{{ $item->gambar_url }}" 
                                                 alt="{{ $item->nama_produk }}" 
                                                 class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-white text-xs">
                                                No Image
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-900">{{ $item->nama_produk }}</div>
                                        <div class="text-sm text-gray-500 line-clamp-2">
                                            {{ Str::limit($item->deskripsi, 60) }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($item->kategori)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $item->kategori }}
                                    </span>
                                @else
                                    <span class="text-gray-400 text-sm">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="space-y-1">
                                    <div class="text-sm text-gray-600">
                                        <strong>{{ $item->detailWarna->count() }}</strong> Warna
                                    </div>
                                    <div class="text-sm text-gray-600">
                                        <strong>{{ $item->detailUkuran->count() }}</strong> Ukuran
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <span class="font-semibold {{ $item->total_stok > 0 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $item->total_stok }}
                                    </span>
                                    @if($item->total_stok == 0)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                            Habis
                                        </span>
                                    @elseif($item->total_stok < 10)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Sedikit
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($item->detailUkuran->count() > 0)
                                    @php
                                        $minPrice = $item->detailUkuran->min('harga');
                                        $maxPrice = $item->detailUkuran->max('harga');
                                    @endphp
                                    <div class="text-sm font-semibold text-gray-900">
                                        Rp {{ number_format($minPrice, 0, ',', '.') }}
                                        @if($minPrice != $maxPrice)
                                            - Rp {{ number_format($maxPrice, 0, ',', '.') }}
                                        @endif
                                    </div>
                                @else
                                    <span class="text-gray-400 text-sm">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Aktif
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('admin.produk.show', $item->id_produk) }}" 
                                       class="text-blue-600 hover:text-blue-900 p-2 rounded-lg hover:bg-blue-50 transition-colors"
                                       title="Lihat detail">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    
                                    <a href="{{ route('admin.produk.edit', $item->id_produk) }}" 
                                       class="text-green-600 hover:text-green-900 p-2 rounded-lg hover:bg-green-50 transition-colors"
                                       title="Edit produk">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    
                                    <form action="{{ route('admin.produk.destroy', $item->id_produk) }}" 
                                          method="POST" 
                                          class="inline"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-900 p-2 rounded-lg hover:bg-red-50 transition-colors"
                                                title="Hapus produk">
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
        
        <!-- Pagination -->
        @if($produk->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                <div class="pagination">
                    {{ $produk->links() }}
                </div>
            </div>
        @endif
    </div>
@else
    <div class="text-center py-12">
        <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2M4 13h2m8-8V4a1 1 0 00-1-1h-2a1 1 0 00-1 1v1M9 7h6"/>
        </svg>
        <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada produk ditemukan</h3>
        <p class="text-gray-500 mb-6">
            @if(request()->has('search') || request()->has('kategori'))
                Coba ubah kata kunci pencarian atau filter yang digunakan.
            @else
                Belum ada produk yang ditambahkan.
            @endif
        </p>
        <a href="{{ route('admin.produk.create') }}" 
           class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-semibold py-2 px-4 rounded-lg transition-all duration-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Tambah Produk Pertama
        </a>
    </div>
@endif
{{-- resources/views/admin/pengiriman/partials/pengiriman-table.blade.php --}}

@if($pengiriman->count() > 0)
    <div class="bg-white/80 glass rounded-2xl shadow-xl border border-white/20 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gradient-to-r from-purple-600 to-pink-600 text-white">
                        <th class="px-6 py-4 text-left text-sm font-semibold">ID Pengiriman</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Pemesanan</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Penerima</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Ekspedisi</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">No. Resi</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Status</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Biaya Ongkir</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($pengiriman as $item)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-mono text-sm font-semibold text-gray-900">
                                    #{{ $item->id_pengiriman }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($item->pemesanan)
                                    <div class="text-sm text-gray-900">
                                        #{{ $item->pemesanan->id_pemesanan }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        Rp {{ number_format($item->pemesanan->total_harga ?? 0, 0, ',', '.') }}
                                    </div>
                                @else
                                    <span class="text-gray-400 text-sm">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $item->nama_penerima }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ $item->no_hp_penerima }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $item->ekspedisi }}
                                    </span>
                                    @if($item->layanan)
                                        <span class="text-xs text-gray-500">{{ $item->layanan }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($item->no_resi)
                                    <div class="font-mono text-sm text-gray-900">
                                        {{ $item->no_resi }}
                                    </div>
                                @else
                                    <span class="text-gray-400 text-sm">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'packed' => 'bg-blue-100 text-blue-800',
                                        'shipped' => 'bg-indigo-100 text-indigo-800',
                                        'in_transit' => 'bg-purple-100 text-purple-800',
                                        'delivered' => 'bg-green-100 text-green-800',
                                        'failed' => 'bg-red-100 text-red-800'
                                    ];
                                    $statusColor = $statusColors[$item->status_pengiriman] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColor }}">
                                    {{ strtoupper(str_replace('_', ' ', $item->status_pengiriman)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-semibold text-gray-900">
                                    Rp {{ number_format($item->biaya_ongkir, 0, ',', '.') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('admin.pengiriman.show', $item->id_pengiriman) }}" 
                                       class="text-blue-600 hover:text-blue-900 p-2 rounded-lg hover:bg-blue-50 transition-colors"
                                       title="Lihat detail">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    
                                    <a href="{{ route('admin.pengiriman.edit', $item->id_pengiriman) }}" 
                                       class="text-green-600 hover:text-green-900 p-2 rounded-lg hover:bg-green-50 transition-colors"
                                       title="Edit pengiriman">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    
                                    @if($item->status_pengiriman === 'shipped' && $item->no_resi)
                                        <a href="{{ route('admin.pengiriman.track', $item->id_pengiriman) }}" 
                                           class="text-purple-600 hover:text-purple-900 p-2 rounded-lg hover:bg-purple-50 transition-colors"
                                           title="Lacak pengiriman">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($pengiriman->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                <div class="pagination">
                    {{ $pengiriman->links() }}
                </div>
            </div>
        @endif
    </div>
@else
    <div class="text-center py-12">
        <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
        </svg>
        <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada pengiriman ditemukan</h3>
        <p class="text-gray-500 mb-6">
            @if(request()->hasAny(['status', 'ekspedisi', 'min_ongkir', 'max_ongkir']))
                Coba ubah filter yang digunakan.
            @else
                Belum ada data pengiriman.
            @endif
        </p>
        <a href="{{ route('admin.pengiriman.create') }}" 
           class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-semibold py-2 px-4 rounded-lg transition-all duration-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Tambah Pengiriman Pertama
        </a>
    </div>
@endif
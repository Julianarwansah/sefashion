{{-- resources/views/admin/pembayaran/partials/pembayaran-table.blade.php --}}

@if($pembayaran->count() > 0)
    <div class="bg-white/80 glass rounded-2xl shadow-xl border border-white/20 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gradient-to-r from-purple-600 to-pink-600 text-white">
                        <th class="px-6 py-4 text-left text-sm font-semibold">ID Pembayaran</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Pemesanan</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Customer</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Metode</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Jumlah</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Status</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Tanggal</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($pembayaran as $item)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-mono text-sm font-semibold text-gray-900">
                                    #{{ $item->id_pembayaran }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">
                                    #{{ $item->pemesanan->id_pemesanan ?? 'N/A' }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    Rp {{ number_format($item->pemesanan->total_harga ?? 0, 0, ',', '.') }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($item->pemesanan && $item->pemesanan->customer)
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $item->pemesanan->customer->nama }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $item->pemesanan->customer->email }}
                                    </div>
                                @else
                                    <span class="text-gray-400 text-sm">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $item->metode_pembayaran }}
                                    </span>
                                    @if($item->channel)
                                        <span class="text-xs text-gray-500">{{ $item->channel }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-semibold text-gray-900">
                                    Rp {{ number_format($item->jumlah_bayar, 0, ',', '.') }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'paid' => 'bg-green-100 text-green-800',
                                        'expired' => 'bg-red-100 text-red-800',
                                        'failed' => 'bg-red-100 text-red-800'
                                    ];
                                    $statusColor = $statusColors[$item->status_pembayaran] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColor }}">
                                    {{ strtoupper($item->status_pembayaran) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-600">
                                    {{ $item->created_at->format('d M Y') }}
                                </div>
                                <div class="text-xs text-gray-400">
                                    {{ $item->created_at->format('H:i') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('admin.pembayaran.show', $item->id_pembayaran) }}" 
                                       class="text-blue-600 hover:text-blue-900 p-2 rounded-lg hover:bg-blue-50 transition-colors"
                                       title="Lihat detail">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    
                                    @if($item->status_pembayaran === 'pending')
                                        <form action="{{ route('admin.pembayaran.mark-paid', $item->id_pembayaran) }}" 
                                              method="POST" 
                                              class="inline"
                                              onsubmit="return confirm('Tandai pembayaran ini sebagai sudah dibayar?')">
                                            @csrf
                                            <button type="submit" 
                                                    class="text-green-600 hover:text-green-900 p-2 rounded-lg hover:bg-green-50 transition-colors"
                                                    title="Tandai sudah dibayar">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($pembayaran->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                <div class="pagination">
                    {{ $pembayaran->links() }}
                </div>
            </div>
        @endif
    </div>
@else
    <div class="text-center py-12">
        <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
        </svg>
        <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada pembayaran ditemukan</h3>
        <p class="text-gray-500 mb-6">
            @if(request()->hasAny(['status', 'metode', 'min_amount', 'max_amount']))
                Coba ubah filter yang digunakan.
            @else
                Belum ada data pembayaran.
            @endif
        </p>
    </div>
@endif
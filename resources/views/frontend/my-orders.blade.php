@extends('layouts_public.app')

@section('title', 'My Orders • SeFashion')

@section('content')
<section class="py-12 bg-gray-50 min-h-screen">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    {{-- Header --}}
    <div class="mb-8">
      <h1 class="text-3xl font-extrabold text-gray-900 mb-2">My Orders</h1>
      <p class="text-gray-600">Track and manage your orders</p>
    </div>

    @if($orders->isEmpty())
      {{-- Empty State --}}
      <div class="bg-white rounded-2xl border border-gray-200 p-12 text-center">
        <div class="inline-block p-6 bg-gray-100 rounded-full mb-6">
          <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">No orders yet</h3>
        <p class="text-gray-600 mb-6">Start shopping and your orders will appear here</p>
        <a href="{{ route('shop') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gray-900 text-white rounded-xl hover:bg-black transition font-semibold">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
          </svg>
          Start Shopping
        </a>
      </div>
    @else
      {{-- Orders List --}}
      <div class="space-y-4">
        @foreach($orders as $order)
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden hover:shadow-lg transition">
          {{-- Order Header --}}
          <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
              <div class="flex items-center gap-4">
                <div>
                  <p class="text-sm text-gray-600">Order ID</p>
                  <p class="text-lg font-bold text-gray-900">#{{ str_pad($order->id_pemesanan, 6, '0', STR_PAD_LEFT) }}</p>
                </div>
                <div class="h-10 w-px bg-gray-300 hidden sm:block"></div>
                <div>
                  <p class="text-sm text-gray-600">Order Date</p>
                  <p class="text-sm font-semibold text-gray-900">{{ \Carbon\Carbon::parse($order->tanggal_pemesanan)->format('d M Y, H:i') }}</p>
                </div>
              </div>
              <div class="flex items-center gap-3">
                @php
                  $statusConfig = [
                    'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'label' => 'Pending Payment'],
                    'diproses' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'label' => 'Processing'],
                    'dikirim' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-800', 'label' => 'Shipped'],
                    'selesai' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'label' => 'Completed'],
                    'batal' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'label' => 'Cancelled'],
                  ];
                  $status = $statusConfig[$order->status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'label' => ucfirst($order->status)];
                @endphp
                <span class="px-4 py-2 rounded-full text-sm font-semibold {{ $status['bg'] }} {{ $status['text'] }}">
                  {{ $status['label'] }}
                </span>
              </div>
            </div>
          </div>

          {{-- Order Items --}}
          <div class="px-6 py-4">
            <div class="space-y-3">
              @foreach($order->detailPemesanan->take(2) as $item)
              <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-lg overflow-hidden bg-gray-100 flex-shrink-0">
                  @php
                    $primaryImage = $item->detailUkuran->produk->gambarProduk->where('is_primary', 1)->first();
                  @endphp
                  @if($primaryImage)
                    <img src="{{ asset('storage/produk/images/' . $primaryImage->gambar) }}"
                         alt="{{ $item->detailUkuran->produk->nama_produk }}"
                         class="w-full h-full object-cover">
                  @else
                    <div class="w-full h-full flex items-center justify-center">
                      <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                      </svg>
                    </div>
                  @endif
                </div>
                <div class="flex-1 min-w-0">
                  <p class="font-semibold text-gray-900 truncate">{{ $item->detailUkuran->produk->nama_produk }}</p>
                  <p class="text-sm text-gray-600">Size: {{ $item->detailUkuran->ukuran }} • Qty: {{ $item->jumlah }}</p>
                </div>
                <div class="text-right">
                  <p class="font-bold text-gray-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                </div>
              </div>
              @endforeach

              @if($order->detailPemesanan->count() > 2)
              <p class="text-sm text-gray-600 text-center py-2">
                + {{ $order->detailPemesanan->count() - 2 }} more item(s)
              </p>
              @endif
            </div>
          </div>

          {{-- Order Footer --}}
          <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
              <div>
                <p class="text-sm text-gray-600 mb-1">Total Amount</p>
                <p class="text-xl font-bold text-gray-900">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</p>
              </div>
              <div class="flex flex-wrap gap-2">
                {{-- Pay Now Button - Show if pending and not paid --}}
                @if($order->status === 'pending' && $order->pembayaran && $order->pembayaran->status_pembayaran !== 'sudah bayar')
                <a href="{{ route('order.success', $order->id_pemesanan) }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:from-blue-700 hover:to-indigo-700 transition font-bold shadow-md">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                  </svg>
                  Pay Now
                </a>
                @endif

                <a href="{{ route('order.detail', $order->id_pemesanan) }}"
                   class="inline-flex items-center gap-2 px-6 py-2.5 bg-gray-900 text-white rounded-xl hover:bg-black transition font-semibold">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                  </svg>
                  View Details
                </a>

                @if($order->status === 'pending')
                <form action="{{ route('order.cancel', $order->id_pemesanan) }}" method="POST"
                      onsubmit="return confirm('Are you sure you want to cancel this order?')">
                  @csrf
                  <button type="submit"
                          class="inline-flex items-center gap-2 px-6 py-2.5 border border-red-300 text-red-700 rounded-xl hover:bg-red-50 transition font-semibold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Cancel
                  </button>
                </form>
                @endif
              </div>
            </div>
          </div>
        </div>
        @endforeach
      </div>

      {{-- Pagination --}}
      @if($orders->hasPages())
      <div class="mt-8">
        {{ $orders->links() }}
      </div>
      @endif
    @endif

  </div>
</section>

@if(session('success'))
<script>
  document.addEventListener('DOMContentLoaded', function() {
    alert('{{ session('success') }}');
  });
</script>
@endif

@if(session('error'))
<script>
  document.addEventListener('DOMContentLoaded', function() {
    alert('{{ session('error') }}');
  });
</script>
@endif
@endsection

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
                  <p class="text-sm font-semibold text-gray-900">
                    {{ \Carbon\Carbon::parse($order->tanggal_pemesanan)->format('d M Y, H:i') }}
                  </p>
                </div>
              </div>
              <div class="flex flex-col items-start sm:items-end gap-2">
                @php
                  // STATUS ORDER
                  $statusConfig = [
                    'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'label' => 'Pending Payment'],
                    'diproses' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'label' => 'Processing'],
                    'dikirim' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-800', 'label' => 'Shipped'],
                    'selesai' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'label' => 'Completed'],
                    'batal' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'label' => 'Cancelled'],
                  ];
                  $status = $statusConfig[$order->status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'label' => ucfirst($order->status)];

                  // STATUS PEMBAYARAN
                  $paymentStatus = $order->pembayaran->status_pembayaran ?? null;
                  $normalizedPaymentStatus = $paymentStatus ? strtolower($paymentStatus) : null;

                  $paidStatuses    = ['sudah bayar', 'sudah_bayar', 'paid', 'completed'];
                  $failedStatuses  = ['gagal', 'failed', 'expired'];
                  $pendingStatuses = ['belum bayar', 'belum_bayar', 'menunggu', 'pending'];

                  if ($normalizedPaymentStatus && in_array($normalizedPaymentStatus, $paidStatuses)) {
                      $paymentBadgeBg = 'bg-green-100';
                      $paymentBadgeText = 'text-green-800';
                      $paymentLabel = 'Paid';
                      $isPaid = true;
                  } elseif ($normalizedPaymentStatus && in_array($normalizedPaymentStatus, $failedStatuses)) {
                      $paymentBadgeBg = 'bg-red-100';
                      $paymentBadgeText = 'text-red-800';
                      $paymentLabel = 'Failed';
                      $isPaid = false;
                  } else {
                      // default pending
                      $paymentBadgeBg = 'bg-yellow-100';
                      $paymentBadgeText = 'text-yellow-800';
                      $paymentLabel = 'Pending';
                      $isPaid = false;
                  }
                @endphp
                <div class="flex flex-wrap gap-2 justify-end">
                  <span class="px-4 py-2 rounded-full text-sm font-semibold {{ $status['bg'] }} {{ $status['text'] }}">
                    {{ $status['label'] }}
                  </span>

                  @if($order->pembayaran)
                  <span class="px-3 py-1.5 rounded-full text-xs font-semibold flex items-center gap-1 {{ $paymentBadgeBg }} {{ $paymentBadgeText }}">
                    <span class="inline-block w-2 h-2 rounded-full
                      {{ $isPaid ? 'bg-green-500' : ($paymentLabel === 'Failed' ? 'bg-red-500' : 'bg-yellow-500') }}">
                    </span>
                    Payment: {{ $paymentLabel }}
                  </span>
                  @endif
                </div>
              </div>
            </div>
          </div>

          {{-- Order Items --}}
          <div class="px-6 py-4 border-b border-gray-200">
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
                  <p class="text-sm text-gray-600">
                    Size: {{ $item->detailUkuran->ukuran }} • Qty: {{ $item->jumlah }}
                  </p>
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

          {{-- Shipment Tracking Status --}}
          @if($isPaid)
          <div class="px-6 py-4 bg-gradient-to-br from-gray-50 to-gray-100 border-t border-gray-200">
            <h4 class="text-sm font-bold text-gray-900 mb-4 flex items-center gap-2">
              <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
              </svg>
              Shipment Tracking
            </h4>

            @php
              // Define tracking stages
              $trackingStages = [
                ['key' => 'pending', 'label' => 'Payment Confirmed', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['key' => 'diproses', 'label' => 'Processing', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['key' => 'dikirim', 'label' => 'Shipped', 'icon' => 'M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4'],
                ['key' => 'selesai', 'label' => 'Delivered', 'icon' => 'M5 13l4 4L19 7']
              ];

              // Determine current stage index based on order status
              $trackingIndex = 0;
              if($order->status === 'pending' && $isPaid) {
                $trackingIndex = 0; // Payment confirmed
              } elseif($order->status === 'diproses') {
                $trackingIndex = 1; // Processing
              } elseif($order->status === 'dikirim') {
                $trackingIndex = 2; // Shipped
              } elseif($order->status === 'selesai') {
                $trackingIndex = 3; // Delivered
              }
            @endphp

            <div class="relative">
              <div class="grid grid-cols-4 gap-2">
                @foreach($trackingStages as $index => $stage)
                  @php
                    $isCompleted = $index <= $trackingIndex;
                    $isCurrent = $index === $trackingIndex;
                  @endphp
                  <div class="flex flex-col items-center relative">
                    {{-- Circle --}}
                    <div class="w-10 h-10 md:w-12 md:h-12 rounded-full flex items-center justify-center mb-2 transition-all
                      {{ $isCompleted ? 'bg-gray-900 text-white shadow-md' : 'bg-gray-300 text-gray-500' }}
                      {{ $isCurrent ? 'ring-4 ring-gray-400 scale-110' : '' }}">
                      <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $stage['icon'] }}"/>
                      </svg>
                    </div>

                    {{-- Label --}}
                    <p class="text-xs text-center font-semibold leading-tight {{ $isCompleted ? 'text-gray-900' : 'text-gray-500' }}">
                      {{ $stage['label'] }}
                    </p>

                    {{-- Connecting Line --}}
                    @if($index < count($trackingStages) - 1)
                    <div class="absolute top-5 md:top-6 left-1/2 w-full h-1 -z-10 transition-all
                      {{ $index < $trackingIndex ? 'bg-gray-900' : 'bg-gray-300' }}"
                      style="transform: translateX(50%);"></div>
                    @endif
                  </div>
                @endforeach
              </div>

              {{-- Status Message --}}
              <div class="mt-4 text-center">
                <p class="text-xs text-gray-600">
                  @if($order->status === 'pending' && $isPaid)
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-green-100 text-green-800 rounded-full font-semibold">
                      <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                      </svg>
                      Payment received - Your order is being prepared
                    </span>
                  @elseif($order->status === 'diproses')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-100 text-blue-800 rounded-full font-semibold">
                      <svg class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                      </svg>
                      Order is being processed
                    </span>
                  @elseif($order->status === 'dikirim')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-purple-100 text-purple-800 rounded-full font-semibold">
                      <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                      </svg>
                      Package is on the way
                    </span>
                  @elseif($order->status === 'selesai')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-green-100 text-green-800 rounded-full font-semibold">
                      <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                      </svg>
                      Order has been delivered
                    </span>
                  @endif
                </p>
              </div>
            </div>
          </div>
          @endif

          {{-- Order Footer --}}
          <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
              <div>
                <p class="text-sm text-gray-600 mb-1">Total Amount</p>
                <p class="text-xl font-bold text-gray-900">
                  Rp {{ number_format($order->total_harga, 0, ',', '.') }}
                </p>
              </div>
              <div class="flex flex-wrap gap-2">
                {{-- Pay Now Button - Show if pending and NOT paid --}}
                @if($order->status === 'pending' && $order->pembayaran && !$isPaid)
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

                @if($order->status === 'pending' && !$isPaid)
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
    alert(@json(session('success')));
  });
</script>
@endif

@if(session('error'))
<script>
  document.addEventListener('DOMContentLoaded', function() {
    alert(@json(session('error')));
  });
</script>
@endif
@endsection

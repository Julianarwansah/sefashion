@extends('layouts_public.app')

@section('title', 'Payment Success • SeFashion')

@section('content')
<section class="py-8 sm:py-12 bg-gradient-to-br from-gray-50 via-gray-100 to-gray-50 min-h-screen">
  <div class="max-w-2xl mx-auto px-4 sm:px-6">

    {{-- Success Animation --}}
    <div class="text-center mb-6 animate-scale-in">
      <div class="inline-block relative">
        {{-- Pulsing background --}}
        <div class="absolute inset-0 bg-green-400 rounded-full opacity-20 animate-ping"></div>

        {{-- Icon --}}
        <div class="relative bg-gradient-to-br from-green-500 to-emerald-600 p-6 rounded-full shadow-xl">
          <svg class="w-12 h-12 text-white animate-checkmark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
          </svg>
        </div>
      </div>
    </div>

    {{-- Main Success Card --}}
    <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden mb-6 animate-fade-in-up">
      {{-- Header --}}
      <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-5 text-center">
        <h1 class="text-xl sm:text-2xl font-extrabold text-white mb-1">
          Payment Successful!
        </h1>
        <p class="text-gray-300 text-sm">
          Your payment has been processed successfully
        </p>
      </div>

      {{-- Body --}}
      <div class="p-5 sm:p-6">
        {{-- Order Summary --}}
        <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-5 mb-5">
          <h3 class="font-bold text-gray-900 mb-3 flex items-center gap-2 text-sm">
            <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Order Summary
          </h3>
          <div class="space-y-2.5 text-sm">
            <div class="flex justify-between items-center">
              <span class="text-gray-600">Order ID</span>
              <span class="font-bold text-gray-900 font-mono">#{{ str_pad($pemesanan->id_pemesanan, 6, '0', STR_PAD_LEFT) }}</span>
            </div>
            <div class="flex justify-between items-center">
              <span class="text-gray-600">Payment Method</span>
              <span class="font-semibold text-gray-900">{{ $pemesanan->pembayaran->metode_pembayaran_display ?? 'N/A' }}</span>
            </div>
            <div class="flex justify-between items-center">
              <span class="text-gray-600">Total Paid</span>
              <span class="font-bold text-green-600">{{ $pemesanan->pembayaran->jumlah_bayar_formatted ?? 'Rp 0' }}</span>
            </div>
            <div class="flex justify-between items-center pt-2.5 border-t border-gray-300">
              <span class="text-gray-600">Payment Status</span>
              <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                Paid
              </span>
            </div>
          </div>
        </div>

        {{-- Order Items Preview - Compact --}}
        @if($pemesanan->detailPemesanan && $pemesanan->detailPemesanan->count() > 0)
        <div class="mb-5">
          <h3 class="font-bold text-gray-900 mb-3 flex items-center gap-2 text-sm">
            <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
            </svg>
            Order Items ({{ $pemesanan->detailPemesanan->count() }})
          </h3>
          <div class="space-y-2">
            @foreach($pemesanan->detailPemesanan->take(3) as $item)
            <div class="flex gap-3 p-3 bg-gray-50 rounded-lg">
              @php
                $primaryImage = $item->detailUkuran->produk->gambarProduk->where('is_primary', 1)->first();
                $imageUrl = $primaryImage ? asset('storage/produk/images/' . $primaryImage->gambar) : asset('images/placeholder.png');
              @endphp
              <img src="{{ $imageUrl }}" alt="{{ $item->detailUkuran->produk->nama_produk }}" class="w-14 h-14 object-cover rounded-lg">

              <div class="flex-1 min-w-0">
                <h4 class="font-semibold text-gray-900 truncate text-sm">{{ $item->detailUkuran->produk->nama_produk }}</h4>
                <p class="text-xs text-gray-600">
                  Size: {{ $item->detailUkuran->ukuran }} • Color: {{ $item->detailUkuran->detailWarna->nama_warna }}
                </p>
                <p class="text-xs text-gray-600">Qty: {{ $item->jumlah }}</p>
              </div>
            </div>
            @endforeach

            @if($pemesanan->detailPemesanan->count() > 3)
            <p class="text-center text-xs text-gray-600">+ {{ $pemesanan->detailPemesanan->count() - 3 }} more items</p>
            @endif
          </div>
        </div>
        @endif

        {{-- What's Next --}}
        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 mb-5">
          <h3 class="font-bold text-gray-900 mb-3 flex items-center gap-2 text-sm">
            <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            What's Next?
          </h3>
          <ul class="space-y-2 text-xs text-gray-700">
            <li class="flex items-start gap-2">
              <svg class="w-4 h-4 text-green-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              <span>Order confirmation email sent</span>
            </li>
            <li class="flex items-start gap-2">
              <svg class="w-4 h-4 text-green-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              <span>Order prepared for shipment</span>
            </li>
            <li class="flex items-start gap-2">
              <svg class="w-4 h-4 text-green-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              <span>Track shipment in "My Orders"</span>
            </li>
          </ul>
        </div>

        {{-- Action Buttons --}}
        <div class="flex flex-col sm:flex-row gap-3">
          <a href="{{ route('order.detail', $pemesanan->id_pemesanan) }}"
             class="flex-1 inline-flex items-center justify-center gap-2 px-5 py-3 bg-gradient-to-r from-gray-700 to-gray-900 text-white rounded-lg hover:from-gray-800 hover:to-black transition font-bold shadow-md text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
            View Order Details
          </a>
          <a href="{{ route('my-orders') }}"
             class="flex-1 inline-flex items-center justify-center gap-2 px-5 py-3 border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-bold text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            My Orders
          </a>
        </div>

        {{-- Continue Shopping Link --}}
        <div class="mt-3 text-center">
          <a href="{{ route('shop') }}" class="text-gray-600 hover:text-gray-900 text-xs font-medium hover:underline">
            ← Continue Shopping
          </a>
        </div>
      </div>
    </div>

    {{-- Help Section --}}
    <div class="text-center animate-fade-in-up animation-delay-300">
      <p class="text-xs text-gray-600 mb-1">Need help with your order?</p>
      <a href="{{ route('contact') }}" class="text-gray-900 font-semibold hover:underline text-sm">
        Contact Customer Support
      </a>
    </div>
  </div>
</section>

@push('styles')
<style>
@keyframes scaleIn {
  from {
    opacity: 0;
    transform: scale(0.8);
  }
  to {
    opacity: 1;
    transform: scale(1);
  }
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes checkmark {
  0% {
    stroke-dasharray: 0, 100;
  }
  100% {
    stroke-dasharray: 100, 0;
  }
}

.animate-scale-in {
  animation: scaleIn 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
}

.animate-fade-in-up {
  animation: fadeInUp 0.8s ease-out forwards;
  opacity: 0;
}

.animate-checkmark {
  stroke-dasharray: 100;
  animation: checkmark 0.6s ease-out 0.3s forwards;
}

.animation-delay-300 {
  animation-delay: 0.3s;
}
</style>
@endpush
@endsection
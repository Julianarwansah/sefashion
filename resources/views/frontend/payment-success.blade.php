@extends('layouts_public.app')

@section('title', 'Payment Success • SeFashion')

@section('content')
<section class="min-h-screen bg-gradient-to-br from-green-50 to-white py-12">
    <div class="max-w-2xl mx-auto px-4 sm:px-6">
        {{-- Success Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-green-200 p-8 text-center">
            {{-- Success Icon --}}
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>

            {{-- Success Message --}}
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Payment Successful!</h1>
            <p class="text-gray-600 mb-8">Thank you for your order. Your payment has been processed successfully.</p>

            {{-- Order Details --}}
            <div class="bg-gray-50 rounded-xl p-6 mb-8 text-left">
                <h3 class="font-semibold text-gray-900 mb-4">Order Details</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Order ID:</span>
                        <span class="font-semibold">#{{ $pemesanan->id_pemesanan }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Payment Method:</span>
                        <span class="font-semibold">{{ $pemesanan->pembayaran->metode_pembayaran_display }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total Amount:</span>
                        <span class="font-semibold text-green-600">{{ $pemesanan->pembayaran->jumlah_bayar_formatted }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Status:</span>
                        <span class="font-semibold text-green-600 capitalize">{{ $pemesanan->pembayaran->status_pembayaran }}</span>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('order.tracking', $pemesanan->id_pemesanan) }}" 
                   class="px-6 py-3 bg-gray-900 text-white rounded-xl hover:bg-black transition font-semibold">
                    Track Your Order
                </a>
                <a href="{{ route('home') }}" 
                   class="px-6 py-3 border border-gray-300 text-gray-900 rounded-xl hover:bg-gray-50 transition font-semibold">
                    Continue Shopping
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
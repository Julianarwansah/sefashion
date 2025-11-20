@extends('layouts_public.app')

@section('title', 'Payment Failed • SeFashion')

@section('content')
<section class="py-12 sm:py-20 bg-gradient-to-br from-red-50 via-orange-50 to-yellow-50 min-h-screen flex items-center justify-center">
  <div class="max-w-2xl mx-auto px-4 sm:px-6">

    {{-- Animated Failed Icon --}}
    <div class="text-center mb-8">
      <div class="inline-block relative">
        {{-- Pulsing background --}}
        <div class="absolute inset-0 bg-red-400 rounded-full opacity-20 animate-ping"></div>

        {{-- Icon --}}
        <div class="relative bg-gradient-to-br from-red-500 to-orange-600 p-8 rounded-full shadow-2xl">
          <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </div>
      </div>
    </div>

    {{-- Main Content --}}
    <div class="bg-white rounded-3xl shadow-2xl border border-gray-100 overflow-hidden">
      {{-- Header --}}
      <div class="bg-gradient-to-r from-red-600 to-orange-600 px-6 py-6 sm:px-8 sm:py-8">
        <h1 class="text-2xl sm:text-3xl font-extrabold text-white text-center mb-2">
          Payment Failed
        </h1>
        <p class="text-red-100 text-center text-sm sm:text-base">
          Unfortunately, your payment could not be processed
        </p>
      </div>

      {{-- Body --}}
      <div class="p-6 sm:p-8">
        {{-- Order Info --}}
        <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl p-6 mb-6">
          <div class="grid grid-cols-2 gap-4 text-center">
            <div>
              <p class="text-sm text-gray-600 mb-1">Order ID</p>
              <p class="text-lg font-bold text-gray-900">#{{ $pemesanan->id_pemesanan }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-600 mb-1">Total Amount</p>
              <p class="text-lg font-bold text-gray-900">{{ $pemesanan->pembayaran->jumlah_bayar_formatted ?? 'Rp 0' }}</p>
            </div>
          </div>
        </div>

        {{-- Payment Status --}}
        <div class="text-center mb-6">
          <div class="inline-flex items-center gap-3 px-6 py-3 bg-red-100 text-red-800 rounded-full">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <span class="font-semibold">Payment {{ ucfirst($pemesanan->pembayaran->status_pembayaran ?? 'Failed') }}</span>
          </div>
        </div>

        {{-- Failure Reasons --}}
        <div class="bg-orange-50 border border-orange-200 rounded-2xl p-6 mb-6">
          <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            Possible Reasons
          </h3>
          <ul class="space-y-3 text-sm text-gray-700">
            <li class="flex items-start gap-3">
              <svg class="w-5 h-5 text-orange-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
              </svg>
              <span>Insufficient balance or credit limit</span>
            </li>
            <li class="flex items-start gap-3">
              <svg class="w-5 h-5 text-orange-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
              </svg>
              <span>Payment was cancelled or expired</span>
            </li>
            <li class="flex items-start gap-3">
              <svg class="w-5 h-5 text-orange-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
              </svg>
              <span>Incorrect payment information entered</span>
            </li>
            <li class="flex items-start gap-3">
              <svg class="w-5 h-5 text-orange-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
              </svg>
              <span>Network or connection issues</span>
            </li>
          </ul>
        </div>

        {{-- What's Next --}}
        <div class="bg-blue-50 border border-blue-200 rounded-2xl p-6 mb-6">
          <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            What to Do Next?
          </h3>
          <ul class="space-y-3 text-sm text-gray-700">
            <li class="flex items-start gap-3">
              <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              <span>Try again with a different payment method</span>
            </li>
            <li class="flex items-start gap-3">
              <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              <span>Check your account balance or credit limit</span>
            </li>
            <li class="flex items-start gap-3">
              <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              <span>Contact your bank or payment provider</span>
            </li>
            <li class="flex items-start gap-3">
              <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              <span>Contact our customer support for assistance</span>
            </li>
          </ul>
        </div>

        {{-- Action Buttons --}}
        <div class="flex flex-col sm:flex-row gap-3">
          <a href="{{ route('checkout') }}"
             class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-gradient-to-r from-red-600 to-orange-600 text-white rounded-xl hover:from-red-700 hover:to-orange-700 transition font-bold shadow-lg">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
            Try Again
          </a>
          <a href="{{ route('my-orders') }}"
             class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3.5 border-2 border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition font-bold">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            View My Orders
          </a>
        </div>

        {{-- Back to Home --}}
        <div class="mt-4 text-center">
          <a href="{{ route('home') }}" class="text-gray-600 hover:text-gray-900 text-sm font-medium hover:underline">
            ← Back to Home
          </a>
        </div>
      </div>
    </div>

    {{-- Help Section --}}
    <div class="mt-8 text-center">
      <p class="text-sm text-gray-600 mb-2">Need help with your payment?</p>
      <a href="{{ route('contact') }}" class="text-orange-600 font-semibold hover:underline">
        Contact Customer Support
      </a>
    </div>
  </div>
</section>
@endsection

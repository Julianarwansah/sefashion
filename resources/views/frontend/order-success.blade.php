@extends('layouts_public.app')

@section('title', 'Order Success • SeFashion')

@section('content')
<section class="py-16 sm:py-24 bg-gradient-to-br from-gray-50 to-gray-100 min-h-[80vh] flex items-center justify-center">
  <div class="max-w-2xl mx-auto px-4 sm:px-6 text-center">

    {{-- Success Animation --}}
    <div class="mb-8 animate-scale-in">
      <div class="inline-block p-6 bg-green-50 rounded-full">
        <div class="inline-block p-4 bg-green-100 rounded-full">
          <svg class="w-16 h-16 text-green-600 animate-check" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
          </svg>
        </div>
      </div>
    </div>

    {{-- Success Message --}}
    <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mb-4 animate-fade-in-up">
      Order Placed Successfully!
    </h1>
    <p class="text-lg text-gray-600 mb-8 animate-fade-in-up animation-delay-200">
      Thank you for your purchase. We've received your order and will process it shortly.
    </p>

    {{-- Order ID --}}
    <div class="bg-white rounded-2xl border-2 border-gray-200 p-6 mb-8 animate-fade-in-up animation-delay-300">
      <p class="text-sm text-gray-600 mb-2">Your Order ID</p>
      <p class="text-2xl font-bold text-gray-900 font-mono">#{{ str_pad($orderId, 6, '0', STR_PAD_LEFT) }}</p>
    </div>

    {{-- Next Steps --}}
    <div class="bg-blue-50 border border-blue-200 rounded-2xl p-6 mb-8 text-left animate-fade-in-up animation-delay-400">
      <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        What's Next?
      </h3>
      <ul class="space-y-3 text-sm text-gray-700">
        <li class="flex items-start gap-3">
          <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
          </svg>
          <span>You will receive an email confirmation shortly with order details</span>
        </li>
        <li class="flex items-start gap-3">
          <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
          </svg>
          <span>Complete your payment to process your order</span>
        </li>
        <li class="flex items-start gap-3">
          <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
          </svg>
          <span>Track your order status in "My Orders" section</span>
        </li>
        <li class="flex items-start gap-3">
          <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
          </svg>
          <span>Your order will be shipped once payment is confirmed</span>
        </li>
      </ul>
    </div>

    {{-- Action Buttons --}}
    <div class="flex flex-col sm:flex-row gap-4 justify-center animate-fade-in-up animation-delay-500">
      <a href="{{ route('my-orders') }}"
         class="inline-flex items-center justify-center gap-2 px-8 py-3 bg-gray-900 text-white rounded-xl hover:bg-black transition-all duration-300 hover:scale-105 hover:shadow-xl font-semibold">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
        </svg>
        View My Orders
      </a>

      <a href="{{ route('shop') }}"
         class="inline-flex items-center justify-center gap-2 px-8 py-3 border border-gray-300 text-gray-900 rounded-xl hover:bg-gray-50 transition font-semibold">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
        </svg>
        Continue Shopping
      </a>
    </div>

    {{-- Support Info --}}
    <div class="mt-12 pt-8 border-t border-gray-200 animate-fade-in-up animation-delay-600">
      <p class="text-sm text-gray-600 mb-2">Need help with your order?</p>
      <a href="{{ route('contact') }}" class="text-gray-900 font-semibold hover:underline">
        Contact our customer support
      </a>
    </div>

  </div>
</section>

@push('styles')
<style>
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

@keyframes checkmark {
  0% {
    stroke-dashoffset: 100;
  }
  100% {
    stroke-dashoffset: 0;
  }
}

.animate-fade-in-up {
  animation: fadeInUp 0.8s ease-out forwards;
  opacity: 0;
}

.animate-scale-in {
  animation: scaleIn 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
}

.animate-check {
  stroke-dasharray: 100;
  stroke-dashoffset: 100;
  animation: checkmark 0.6s ease-out 0.3s forwards;
}

.animation-delay-200 {
  animation-delay: 0.2s;
}

.animation-delay-300 {
  animation-delay: 0.3s;
}

.animation-delay-400 {
  animation-delay: 0.4s;
}

.animation-delay-500 {
  animation-delay: 0.5s;
}

.animation-delay-600 {
  animation-delay: 0.6s;
}
</style>
@endpush
@endsection

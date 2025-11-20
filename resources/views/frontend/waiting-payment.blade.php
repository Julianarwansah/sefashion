@extends('layouts_public.app')

@section('title', 'Waiting for Payment • SeFashion')

@section('content')
<section class="py-12 sm:py-20 bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 min-h-screen flex items-center justify-center">
  <div class="max-w-2xl mx-auto px-4 sm:px-6">

    {{-- Animated Payment Icon --}}
    <div class="text-center mb-8">
      <div class="inline-block relative">
        {{-- Pulsing background --}}
        <div class="absolute inset-0 bg-blue-400 rounded-full opacity-20 animate-ping"></div>
        <div class="absolute inset-0 bg-indigo-400 rounded-full opacity-20 animate-ping animation-delay-200"></div>

        {{-- Icon --}}
        <div class="relative bg-gradient-to-br from-blue-500 to-indigo-600 p-8 rounded-full shadow-2xl animate-bounce-slow">
          <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
        </div>
      </div>
    </div>

    {{-- Main Content --}}
    <div class="bg-white rounded-3xl shadow-2xl border border-gray-100 overflow-hidden">
      {{-- Header --}}
      <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-6 sm:px-8 sm:py-8">
        <h1 class="text-2xl sm:text-3xl font-extrabold text-white text-center mb-2">
          Waiting for Payment
        </h1>
        <p class="text-blue-100 text-center text-sm sm:text-base">
          Please complete your payment to process the order
        </p>
      </div>

      {{-- Body --}}
      <div class="p-6 sm:p-8">
        {{-- Order Info --}}
        <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl p-6 mb-6">
          <div class="grid grid-cols-2 gap-4 text-center">
            <div>
              <p class="text-sm text-gray-600 mb-1">Order ID</p>
              <p class="text-lg font-bold text-gray-900">#<span id="orderId">{{ str_pad($orderId, 6, '0', STR_PAD_LEFT) }}</span></p>
            </div>
            <div>
              <p class="text-sm text-gray-600 mb-1">Total Payment</p>
              <p class="text-lg font-bold text-gray-900">{{ $pemesanan->pembayaran->jumlah_bayar_formatted ?? 'Rp 0' }}</p>
            </div>
          </div>
        </div>

        {{-- Payment Method & Details --}}
        @if($pemesanan->pembayaran)
        <div class="bg-white border-2 border-blue-200 rounded-2xl p-6 mb-6">
          <div class="flex items-center gap-3 mb-4">
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
              <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
              </svg>
            </div>
            <div>
              <p class="text-sm text-gray-600">Payment Method</p>
              <p class="font-bold text-gray-900">{{ $pemesanan->pembayaran->metode_pembayaran_display ?? strtoupper($pemesanan->pembayaran->metode_pembayaran) }}</p>
            </div>
          </div>

          {{-- Virtual Account Number --}}
          @if($pemesanan->pembayaran->metode_pembayaran === 'va' && $pemesanan->pembayaran->xendit_account_number)
          <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-5 mb-4">
            <p class="text-sm text-gray-600 mb-2 text-center">Virtual Account Number</p>
            <div class="flex items-center justify-center gap-3">
              <p class="text-2xl font-mono font-bold text-gray-900 tracking-wider" id="vaNumber">
                {{ $pemesanan->pembayaran->xendit_account_number }}
              </p>
              <button onclick="copyVA()" class="p-2 hover:bg-blue-100 rounded-lg transition" title="Copy VA Number">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>
              </button>
            </div>
            @if($pemesanan->pembayaran->channel)
            <p class="text-center text-sm text-gray-600 mt-2">
              Bank: <span class="font-semibold">{{ strtoupper($pemesanan->pembayaran->channel) }}</span>
            </p>
            @endif
          </div>
          @endif

          {{-- E-Wallet / Retail Payment URL --}}
          @if(in_array($pemesanan->pembayaran->metode_pembayaran, ['ewallet', 'retail']) && $pemesanan->pembayaran->xendit_payment_url)
          <div class="mb-4">
            <a href="{{ $pemesanan->pembayaran->xendit_payment_url }}" target="_blank"
               class="w-full inline-flex items-center justify-center gap-2 px-6 py-4 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl hover:from-green-700 hover:to-emerald-700 transition font-bold shadow-lg text-lg">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
              </svg>
              Complete Payment Now
            </a>
            <p class="text-center text-xs text-gray-500 mt-2">Click button above to open payment page</p>
          </div>
          @endif

          {{-- QR Code for QRIS --}}
          @if($pemesanan->pembayaran->xendit_qr_url)
          <div class="text-center mb-4">
            <p class="text-sm text-gray-600 mb-3">Scan QR Code to Pay</p>
            <div class="inline-block p-4 bg-white rounded-xl shadow-md">
              <img src="{{ $pemesanan->pembayaran->xendit_qr_url }}" alt="QR Code" class="w-48 h-48 mx-auto">
            </div>
          </div>
          @endif

          {{-- Payment Expiry --}}
          @if($pemesanan->pembayaran->xendit_expiry_date)
          <div class="flex items-center justify-center gap-2 text-sm text-orange-700 bg-orange-50 rounded-lg py-2 px-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>Expires: {{ \Carbon\Carbon::parse($pemesanan->pembayaran->xendit_expiry_date)->format('d M Y, H:i') }}</span>
          </div>
          @endif
        </div>
        @endif

        {{-- Payment Status --}}
        <div class="text-center mb-6">
          <div id="paymentStatus" class="inline-flex items-center gap-3 px-6 py-3 bg-yellow-100 text-yellow-800 rounded-full">
            <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="font-semibold">Waiting for payment...</span>
          </div>
        </div>

        {{-- Payment Instructions --}}
        <div class="bg-blue-50 border border-blue-200 rounded-2xl p-6 mb-6">
          <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Payment Instructions
          </h3>
          <ul class="space-y-3 text-sm text-gray-700">
            <li class="flex items-start gap-3">
              <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              <span>Complete payment using the payment method you selected</span>
            </li>
            <li class="flex items-start gap-3">
              <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              <span>Payment will be verified automatically</span>
            </li>
            <li class="flex items-start gap-3">
              <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              <span>You will be redirected when payment is confirmed</span>
            </li>
          </ul>
        </div>

        {{-- Action Buttons --}}
        <div class="flex flex-col sm:flex-row gap-3">
          <a href="{{ route('my-orders') }}"
             class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3.5 border-2 border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition font-bold">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            View My Orders
          </a>
          <button onclick="checkPaymentStatus()"
                  class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:from-blue-700 hover:to-indigo-700 transition font-bold shadow-lg">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
            Check Status
          </button>
        </div>
      </div>
    </div>

    {{-- Help Section --}}
    <div class="mt-8 text-center">
      <p class="text-sm text-gray-600 mb-2">Need help?</p>
      <a href="{{ route('contact') }}" class="text-blue-600 font-semibold hover:underline">
        Contact Customer Support
      </a>
    </div>
  </div>
</section>

@push('styles')
<style>
@keyframes bounce-slow {
  0%, 100% {
    transform: translateY(0);
  }
  50% {
    transform: translateY(-10px);
  }
}

.animate-bounce-slow {
  animation: bounce-slow 2s ease-in-out infinite;
}

.animation-delay-200 {
  animation-delay: 0.2s;
}
</style>
@endpush

@push('scripts')
<script>
const orderId = {{ $orderId ?? 0 }};

// Copy VA Number function
function copyVA() {
  const vaNumber = document.getElementById('vaNumber').textContent.trim();
  navigator.clipboard.writeText(vaNumber).then(() => {
    // Show toast notification
    const toast = document.createElement('div');
    toast.className = 'fixed top-4 right-4 bg-green-600 text-white px-6 py-3 rounded-xl shadow-lg z-50 animate-fade-in-up';
    toast.innerHTML = `
      <div class="flex items-center gap-2">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
        </svg>
        <span>VA Number copied!</span>
      </div>
    `;
    document.body.appendChild(toast);
    setTimeout(() => {
      toast.remove();
    }, 3000);
  }).catch(err => {
    alert('Failed to copy: ' + err);
  });
}

// Auto check payment status every 5 seconds
let statusCheckInterval = setInterval(checkPaymentStatus, 5000);

// Check on page load
document.addEventListener('DOMContentLoaded', () => {
  checkPaymentStatus();
});

async function checkPaymentStatus() {
  try {
    const response = await fetch(`/payment/status/${orderId}`, {
      headers: {
        'X-Requested-With': 'XMLHttpRequest'
      }
    });

    const data = await response.json();

    if (data.status === 'paid') {
      // Payment successful
      clearInterval(statusCheckInterval);
      showSuccessStatus();

      // Redirect to success page after 2 seconds
      setTimeout(() => {
        window.location.href = `/payment/success/${orderId}`;
      }, 2000);

    } else if (data.status === 'expired' || data.status === 'failed') {
      // Payment failed
      clearInterval(statusCheckInterval);
      showFailedStatus();

      // Redirect to failed page after 2 seconds
      setTimeout(() => {
        window.location.href = `/payment/failed/${orderId}`;
      }, 2000);
    }
  } catch (error) {
    console.error('Error checking payment status:', error);
  }
}

function showSuccessStatus() {
  const statusEl = document.getElementById('paymentStatus');
  statusEl.className = 'inline-flex items-center gap-3 px-6 py-3 bg-green-100 text-green-800 rounded-full';
  statusEl.innerHTML = `
    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
    </svg>
    <span class="font-semibold">Payment Successful!</span>
  `;
}

function showFailedStatus() {
  const statusEl = document.getElementById('paymentStatus');
  statusEl.className = 'inline-flex items-center gap-3 px-6 py-3 bg-red-100 text-red-800 rounded-full';
  statusEl.innerHTML = `
    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
    </svg>
    <span class="font-semibold">Payment Failed</span>
  `;
}

// Stop checking when user leaves the page
window.addEventListener('beforeunload', () => {
  clearInterval(statusCheckInterval);
});
</script>
@endpush
@endsection

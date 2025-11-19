@extends('layouts_public.app')

@section('title', 'Payment Instructions • SeFashion')

@section('content')
<section class="min-h-screen bg-gradient-to-br from-blue-50 to-white py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6">
        {{-- Instructions Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-blue-200 p-8">
            {{-- Header --}}
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Complete Your Payment</h1>
                <p class="text-gray-600">Please complete your payment before the expiry time</p>
            </div>

            {{-- Payment Details --}}
            <div class="grid md:grid-cols-2 gap-8 mb-8">
                {{-- Payment Information --}}
                <div class="bg-gray-50 rounded-xl p-6">
                    <h3 class="font-semibold text-gray-900 mb-4">Payment Information</h3>
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
                            <span class="text-gray-600">Amount:</span>
                            <span class="font-semibold text-green-600">{{ $pemesanan->pembayaran->jumlah_bayar_formatted }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Expires:</span>
                            <span class="font-semibold text-red-600" id="expiry-timer">
                                {{ $pemesanan->pembayaran->xendit_expiry_date?->format('d M Y H:i:s') }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Virtual Account Details --}}
                <div class="bg-blue-50 rounded-xl p-6 border border-blue-200">
                    <h3 class="font-semibold text-gray-900 mb-4">Virtual Account Details</h3>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Bank</p>
                            <p class="font-semibold text-lg">{{ $pemesanan->pembayaran->channel_display }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Virtual Account Number</p>
                            <p class="font-semibold text-2xl text-gray-900 tracking-wider" id="va-number">
                                {{ $pemesanan->pembayaran->account_number_display }}
                            </p>
                            <button onclick="copyVANumber()" 
                                    class="mt-2 text-sm text-blue-600 hover:text-blue-700 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                                Copy VA Number
                            </button>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Merchant Name</p>
                            <p class="font-semibold">{{ $pemesanan->pembayaran->xendit_merchant_name ?? 'SeFashion Store' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Payment Instructions --}}
            <div class="bg-white border border-gray-200 rounded-xl p-6 mb-8">
                <h3 class="font-semibold text-gray-900 mb-4">How to Pay via Virtual Account</h3>
                <div id="payment-instructions">
                    {{-- Instructions will be loaded via AJAX --}}
                    <div class="animate-pulse space-y-3">
                        <div class="h-4 bg-gray-200 rounded w-3/4"></div>
                        <div class="h-4 bg-gray-200 rounded w-1/2"></div>
                        <div class="h-4 bg-gray-200 rounded w-2/3"></div>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <button onclick="checkPaymentStatus()" 
                        class="px-6 py-3 bg-gray-900 text-white rounded-xl hover:bg-black transition font-semibold flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Check Payment Status
                </button>
                <a href="{{ route('home') }}" 
                   class="px-6 py-3 border border-gray-300 text-gray-900 rounded-xl hover:bg-gray-50 transition font-semibold">
                    Continue Shopping
                </a>
            </div>
        </div>
    </div>
</section>

{{-- Payment Status Modal --}}
<div id="payment-status-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-2xl p-6 max-w-md mx-4">
        <div id="modal-content">
            {{-- Content will be loaded dynamically --}}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let expiryTime = new Date("{{ $pemesanan->pembayaran->xendit_expiry_date }}").getTime();

function updateTimer() {
    const now = new Date().getTime();
    const distance = expiryTime - now;

    if (distance < 0) {
        document.getElementById("expiry-timer").innerHTML = "EXPIRED";
        document.getElementById("expiry-timer").classList.add("text-red-600");
        return;
    }

    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

    document.getElementById("expiry-timer").innerHTML = 
        `${hours}h ${minutes}m ${seconds}s`;
}

// Update timer every second
setInterval(updateTimer, 1000);
updateTimer();

function copyVANumber() {
    const vaNumber = "{{ $pemesanan->pembayaran->xendit_account_number }}";
    navigator.clipboard.writeText(vaNumber).then(function() {
        // Show success message
        const button = event.target;
        const originalText = button.innerHTML;
        button.innerHTML = '✓ Copied!';
        button.classList.add('text-green-600');
        
        setTimeout(() => {
            button.innerHTML = originalText;
            button.classList.remove('text-green-600');
        }, 2000);
    });
}

function checkPaymentStatus() {
    fetch('{{ route("order.status", $pemesanan->id_pemesanan) }}')
        .then(response => response.json())
        .then(data => {
            const modal = document.getElementById('payment-status-modal');
            const modalContent = document.getElementById('modal-content');
            
            if (data.success) {
                if (data.status === 'sudah_bayar') {
                    modalContent.innerHTML = `
                        <div class="text-center">
                            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Payment Successful!</h3>
                            <p class="text-gray-600 mb-4">Your payment has been confirmed.</p>
                            <button onclick="redirectToSuccess()" class="w-full px-4 py-2 bg-gray-900 text-white rounded-xl hover:bg-black transition">
                                View Order Details
                            </button>
                        </div>
                    `;
                } else {
                    modalContent.innerHTML = `
                        <div class="text-center">
                            <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Payment Pending</h3>
                            <p class="text-gray-600 mb-4">Your payment is still being processed.</p>
                            <button onclick="closeModal()" class="w-full px-4 py-2 border border-gray-300 text-gray-900 rounded-xl hover:bg-gray-50 transition">
                                Close
                            </button>
                        </div>
                    `;
                }
            } else {
                modalContent.innerHTML = `
                    <div class="text-center">
                        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Error</h3>
                        <p class="text-gray-600 mb-4">${data.message}</p>
                        <button onclick="closeModal()" class="w-full px-4 py-2 border border-gray-300 text-gray-900 rounded-xl hover:bg-gray-50 transition">
                            Close
                        </button>
                    </div>
                `;
            }
            
            modal.classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

function closeModal() {
    document.getElementById('payment-status-modal').classList.add('hidden');
}

function redirectToSuccess() {
    window.location.href = '{{ route("payment.success", $pemesanan->id_pemesanan) }}';
}

// Load payment instructions
document.addEventListener('DOMContentLoaded', function() {
    fetch('{{ route("order.instructions", $pemesanan->id_pemesanan) }}')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                let instructionsHTML = '';
                for (const [method, steps] of Object.entries(data.instructions)) {
                    instructionsHTML += `
                        <div class="mb-4">
                            <h4 class="font-semibold text-gray-900 mb-2">${method}</h4>
                            <ol class="list-decimal list-inside space-y-1 text-sm text-gray-600">
                                ${steps.map(step => `<li>${step}</li>`).join('')}
                            </ol>
                        </div>
                    `;
                }
                document.getElementById('payment-instructions').innerHTML = instructionsHTML;
            }
        });
});
</script>
@endpush
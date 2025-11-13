@extends('layouts_public.app')

@section('title', 'Checkout • SeFashion')

@section('content')
{{-- HERO/BREADCRUMB SECTION --}}
<section class="relative h-[30vh] min-h-[250px] w-full overflow-hidden bg-gradient-to-br from-gray-50 to-gray-100">
  <div class="absolute inset-0 flex items-center justify-center">
    <div class="text-center animate-fade-in-up">
      <h1 class="text-4xl sm:text-5xl font-extrabold text-gray-900 mb-2">Checkout</h1>
      <p class="text-gray-600">Complete your purchase</p>
    </div>
  </div>
</section>

{{-- CHECKOUT CONTENT --}}
<section class="py-10 sm:py-14 bg-white">
  <div class="max-w-7xl mx-auto px-4 sm:px-6">

    @if(session('error'))
      <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl animate-slide-down">
        <div class="flex items-center">
          <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293z" clip-rule="evenodd"/>
          </svg>
          {{ session('error') }}
        </div>
      </div>
    @endif

    <form id="checkoutForm" class="grid lg:grid-cols-3 gap-8">
      @csrf

      {{-- Left Side - Forms --}}
      <div class="lg:col-span-2 space-y-6">

        {{-- Shipping Information --}}
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden scroll-reveal opacity-0 translate-y-8">
          <div class="p-6 border-b border-gray-200 bg-gray-50">
            <h2 class="text-xl font-bold text-gray-900">Shipping Information</h2>
          </div>
          <div class="p-6 space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Recipient Name *</label>
              <input type="text" name="nama_penerima" value="{{ $customer->nama }}" required
                     class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-gray-900/20 focus:border-gray-900 transition">
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
              <input type="tel" name="no_hp_penerima" value="{{ $customer->no_hp }}" required
                     class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-gray-900/20 focus:border-gray-900 transition">
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Shipping Address *</label>
              <textarea name="alamat_tujuan" rows="4" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-gray-900/20 focus:border-gray-900 transition">{{ $customer->alamat }}</textarea>
            </div>
          </div>
        </div>

        {{-- Shipping Method --}}
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden scroll-reveal opacity-0 translate-y-8">
          <div class="p-6 border-b border-gray-200 bg-gray-50">
            <h2 class="text-xl font-bold text-gray-900">Shipping Method</h2>
          </div>
          <div class="p-6 space-y-4">
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Courier</label>
                <select name="ekspedisi" id="ekspedisi" onchange="updateShippingCost()"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-gray-900/20 focus:border-gray-900 transition">
                  <option value="JNE">JNE</option>
                  <option value="TIKI">TIKI</option>
                  <option value="POS">POS Indonesia</option>
                  <option value="SiCepat">SiCepat</option>
                  <option value="JNT">J&T Express</option>
                </select>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Service</label>
                <select name="layanan" id="layanan" onchange="updateShippingCost()"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-gray-900/20 focus:border-gray-900 transition">
                  <option value="REG">Regular (2-3 days)</option>
                  <option value="EXPRESS">Express (1 day)</option>
                  <option value="YES">Same Day</option>
                </select>
              </div>
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
              <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div class="text-sm text-blue-800">
                  <p class="font-semibold">Shipping Cost: <span id="shipping-cost-display">Rp {{ number_format($ongkir, 0, ',', '.') }}</span></p>
                  <p class="text-xs">Estimated delivery: <span id="shipping-estimation">2-3 days</span></p>
                </div>
              </div>
            </div>
            <input type="hidden" name="biaya_ongkir" id="biaya_ongkir" value="{{ $ongkir }}">
          </div>
        </div>

        {{-- Payment Method --}}
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden scroll-reveal opacity-0 translate-y-8">
          <div class="p-6 border-b border-gray-200 bg-gray-50">
            <h2 class="text-xl font-bold text-gray-900">Payment Method</h2>
          </div>
          <div class="p-6 space-y-3">
            <label class="flex items-center gap-4 p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-gray-900 hover:bg-gray-50 transition group">
              <input type="radio" name="metode_pembayaran" value="transfer" checked class="w-5 h-5 text-gray-900">
              <div class="flex-1">
                <p class="font-semibold text-gray-900">Bank Transfer</p>
                <p class="text-sm text-gray-600">Transfer via BCA, Mandiri, BNI, BRI</p>
              </div>
              <svg class="w-6 h-6 text-gray-400 group-hover:text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
              </svg>
            </label>

            <label class="flex items-center gap-4 p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-gray-900 hover:bg-gray-50 transition group">
              <input type="radio" name="metode_pembayaran" value="ewallet" class="w-5 h-5 text-gray-900">
              <div class="flex-1">
                <p class="font-semibold text-gray-900">E-Wallet</p>
                <p class="text-sm text-gray-600">GoPay, OVO, Dana, ShopeePay</p>
              </div>
              <svg class="w-6 h-6 text-gray-400 group-hover:text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
              </svg>
            </label>

            <label class="flex items-center gap-4 p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-gray-900 hover:bg-gray-50 transition group">
              <input type="radio" name="metode_pembayaran" value="va" class="w-5 h-5 text-gray-900">
              <div class="flex-1">
                <p class="font-semibold text-gray-900">Virtual Account</p>
                <p class="text-sm text-gray-600">VA BCA, Mandiri, BNI, BRI, Permata</p>
              </div>
              <svg class="w-6 h-6 text-gray-400 group-hover:text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
              </svg>
            </label>

            <label class="flex items-center gap-4 p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-gray-900 hover:bg-gray-50 transition group">
              <input type="radio" name="metode_pembayaran" value="qris" class="w-5 h-5 text-gray-900">
              <div class="flex-1">
                <p class="font-semibold text-gray-900">QRIS</p>
                <p class="text-sm text-gray-600">Scan QR code to pay</p>
              </div>
              <svg class="w-6 h-6 text-gray-400 group-hover:text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
              </svg>
            </label>

            <label class="flex items-center gap-4 p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-gray-900 hover:bg-gray-50 transition group">
              <input type="radio" name="metode_pembayaran" value="cod" class="w-5 h-5 text-gray-900">
              <div class="flex-1">
                <p class="font-semibold text-gray-900">Cash on Delivery</p>
                <p class="text-sm text-gray-600">Pay when product arrives</p>
              </div>
              <svg class="w-6 h-6 text-gray-400 group-hover:text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
              </svg>
            </label>
          </div>
        </div>

      </div>

      {{-- Right Side - Order Summary --}}
      <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl border border-gray-200 p-6 sticky top-24 scroll-reveal opacity-0 translate-y-8">
          <h2 class="text-xl font-bold text-gray-900 mb-6">Order Summary</h2>

          {{-- Products --}}
          <div class="space-y-4 mb-6 max-h-64 overflow-y-auto">
            @foreach($cartItems as $item)
              <div class="flex gap-3">
                <div class="w-16 h-16 flex-shrink-0 bg-gray-100 rounded-lg overflow-hidden">
                  <img src="{{ $item->detailUkuran->produk->gambar_url }}"
                       alt="{{ $item->detailUkuran->produk->nama_produk }}"
                       class="w-full h-full object-cover">
                </div>
                <div class="flex-1 min-w-0">
                  <h4 class="font-semibold text-sm text-gray-900 line-clamp-1">
                    {{ $item->detailUkuran->produk->nama_produk }}
                  </h4>
                  <p class="text-xs text-gray-600 mt-1">
                    {{ $item->detailUkuran->detailWarna->nama_warna ?? '-' }} • {{ $item->detailUkuran->ukuran }}
                  </p>
                  <p class="text-xs text-gray-600">Qty: {{ $item->jumlah }}</p>
                  <p class="text-sm font-bold text-gray-900 mt-1">
                    {{ $item->subtotal_formatted }}
                  </p>
                </div>
              </div>
            @endforeach
          </div>

          {{-- Price Breakdown --}}
          <div class="space-y-3 border-t pt-4 mb-6">
            <div class="flex justify-between text-gray-600">
              <span>Subtotal ({{ $totalItems }} items)</span>
              <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-gray-600">
              <span>Shipping</span>
              <span id="shipping-cost-summary">Rp {{ number_format($ongkir, 0, ',', '.') }}</span>
            </div>
            <div class="border-t pt-3 flex justify-between items-center">
              <span class="text-lg font-bold text-gray-900">Total</span>
              <span class="text-2xl font-bold text-gray-900" id="grand-total">
                Rp {{ number_format($grandTotal, 0, ',', '.') }}
              </span>
            </div>
          </div>

          {{-- Submit Button --}}
          <button type="submit" id="submitBtn"
                  class="w-full py-3 px-6 bg-gray-900 text-white text-center rounded-xl hover:bg-black transition-all duration-300 hover:scale-105 hover:shadow-xl font-semibold mb-3">
            <span id="btn-text">Place Order</span>
            <span id="btn-loading" class="hidden">
              <svg class="animate-spin h-5 w-5 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
            </span>
          </button>

          <a href="{{ route('cart.index') }}"
             class="block w-full py-3 px-6 border border-gray-300 text-gray-900 text-center rounded-xl hover:bg-gray-50 transition font-semibold">
            Back to Cart
          </a>

          {{-- Security Badge --}}
          <div class="mt-6 pt-6 border-t border-gray-200">
            <div class="flex items-start gap-3">
              <svg class="w-6 h-6 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
              </svg>
              <div class="text-sm text-gray-600">
                <p class="font-semibold text-gray-900">Secure Checkout</p>
                <p>Your information is protected with SSL encryption</p>
              </div>
            </div>
          </div>
        </div>
      </div>

    </form>

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

@keyframes slideDown {
  from {
    opacity: 0;
    transform: translateY(-20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-fade-in-up {
  animation: fadeInUp 0.8s ease-out forwards;
}

.animate-slide-down {
  animation: slideDown 0.3s ease-out;
}

.scroll-reveal {
  transition: all 0.7s cubic-bezier(0.4, 0, 0.2, 1);
}

.scroll-reveal.revealed {
  opacity: 1 !important;
  transform: translateY(0) !important;
}
</style>
@endpush

@push('scripts')
<script>
// Scroll reveal
const observer = new IntersectionObserver(function(entries) {
  entries.forEach((entry, index) => {
    if (entry.isIntersecting) {
      setTimeout(() => {
        entry.target.classList.add('revealed');
      }, index * 100);
      observer.unobserve(entry.target);
    }
  });
}, {
  threshold: 0.1,
  rootMargin: '0px 0px -50px 0px'
});

document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('.scroll-reveal').forEach(el => observer.observe(el));
});

// Update shipping cost based on courier and service
function updateShippingCost() {
  const ekspedisi = document.getElementById('ekspedisi').value;
  const layanan = document.getElementById('layanan').value;

  fetch('{{ route("checkout.shipping") }}', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    },
    body: JSON.stringify({
      ekspedisi: ekspedisi,
      layanan: layanan
    })
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      document.getElementById('biaya_ongkir').value = data.cost;
      document.getElementById('shipping-cost-display').textContent = data.cost_formatted;
      document.getElementById('shipping-cost-summary').textContent = data.cost_formatted;
      document.getElementById('shipping-estimation').textContent = data.estimation;

      // Update grand total
      const subtotal = {{ $subtotal }};
      const grandTotal = subtotal + data.cost;
      document.getElementById('grand-total').textContent = 'Rp ' + grandTotal.toLocaleString('id-ID');
    }
  })
  .catch(error => {
    console.error('Error:', error);
  });
}

// Handle checkout form submission
document.getElementById('checkoutForm').addEventListener('submit', function(e) {
  e.preventDefault();

  const submitBtn = document.getElementById('submitBtn');
  const btnText = document.getElementById('btn-text');
  const btnLoading = document.getElementById('btn-loading');

  // Disable button and show loading
  submitBtn.disabled = true;
  btnText.classList.add('hidden');
  btnLoading.classList.remove('hidden');

  const formData = new FormData(this);
  const data = Object.fromEntries(formData.entries());

  fetch('{{ route("checkout.process") }}', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    },
    body: JSON.stringify(data)
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      // Redirect to success page or payment URL
      if (data.payment_url) {
        window.location.href = data.payment_url;
      } else {
        window.location.href = data.redirect_url;
      }
    } else {
      alert(data.message);
      // Re-enable button
      submitBtn.disabled = false;
      btnText.classList.remove('hidden');
      btnLoading.classList.add('hidden');
    }
  })
  .catch(error => {
    console.error('Error:', error);
    alert('Failed to process checkout. Please try again.');
    // Re-enable button
    submitBtn.disabled = false;
    btnText.classList.remove('hidden');
    btnLoading.classList.add('hidden');
  });
});
</script>
@endpush
@endsection

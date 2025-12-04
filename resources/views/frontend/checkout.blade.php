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
              <path fill-rule="evenodd"
                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293z"
                clip-rule="evenodd" />
            </svg>
            {{ session('error') }}
          </div>
        </div>
      @endif

      <form id="checkoutForm" class="grid lg:grid-cols-3 gap-8">
        @csrf

        {{-- Hidden inputs for shipping data --}}
        <input type="hidden" name="ongkir" id="hidden_ongkir" value="0">
        <input type="hidden" name="ekspedisi" id="hidden_ekspedisi" value="">

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
                <textarea name="alamat_tujuan" rows="3" required
                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-gray-900/20 focus:border-gray-900 transition">{{ $customer->alamat }}</textarea>
              </div>

              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Province *</label>
                  <select name="province_id" id="province_id" required onchange="loadCities()"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-gray-900/20 focus:border-gray-900 transition">
                    <option value="">Select Province</option>
                    @foreach($provinces as $province)
                      <option value="{{ $province['id'] }}">{{ $province['name'] }}</option>
                    @endforeach
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">City *</label>
                  <select name="destination" id="destination" required onchange="updateShippingCost()" disabled
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-gray-900/20 focus:border-gray-900 transition bg-gray-50">
                    <option value="">Select City</option>
                  </select>
                </div>
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

              </div>

              <div id="shipping-results" class="hidden mt-4">
                <!-- Dynamic shipping options will appear here -->
              </div>

              <!-- Hidden display for selected shipping info (optional, or remove if not needed) -->
              <div class="hidden bg-blue-50 border border-blue-200 rounded-xl p-4 mt-4">
                <div class="flex items-center gap-3">
                  <svg class="w-5 h-5 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  <div class="text-sm text-blue-800">
                    <p class="font-semibold">Shipping Cost: <span id="shipping-cost-display">-</span></p>
                    <p class="text-xs">Estimated delivery: <span id="shipping-estimation">-</span></p>
                  </div>
                </div>
              </div>
            </div>
            <input type="hidden" name="biaya_ongkir" id="biaya_ongkir" value="{{ $ongkir }}">
          </div>
        </div>

        {{-- Payment Method --}}
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden scroll-reveal opacity-0 translate-y-8">
          <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
            <h2 class="text-xl font-bold text-gray-900">Payment Method</h2>
            <p class="text-sm text-gray-600 mt-1">Select your preferred bank for Virtual Account payment</p>
          </div>
          <div class="p-6">
            {{-- Virtual Account Info --}}
            <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-xl">
              <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                  viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="flex-1">
                  <p class="text-sm font-semibold text-blue-900">Virtual Account Payment</p>
                  <p class="text-xs text-blue-700 mt-1">You will receive a unique Virtual Account number after placing
                    your order. Complete the payment within 24 hours.</p>
                </div>
              </div>
            </div>

            {{-- Bank Selection --}}
            <div class="space-y-3">
              <label class="block text-sm font-medium text-gray-700 mb-3">Choose Your Bank</label>

              {{-- BCA --}}
              <label
                class="bank-option flex items-center gap-4 p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition-all group">
                <input type="radio" name="channel" value="BCA" class="w-5 h-5 text-blue-600 focus:ring-blue-500">
                <div class="flex items-center gap-3 flex-1">
                  <div
                    class="w-12 h-12 bg-white rounded-lg flex items-center justify-center border border-gray-200 group-hover:border-blue-300 transition">
                    <span class="text-blue-600 font-bold text-lg">BCA</span>
                  </div>
                  <div>
                    <p class="font-semibold text-gray-900">BCA Virtual Account</p>
                    <p class="text-xs text-gray-600">Bank Central Asia</p>
                  </div>
                </div>
                <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600 transition" fill="none" stroke="currentColor"
                  viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </label>

              {{-- BRI --}}
              <label
                class="bank-option flex items-center gap-4 p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition-all group">
                <input type="radio" name="channel" value="BRI" class="w-5 h-5 text-blue-600 focus:ring-blue-500">
                <div class="flex items-center gap-3 flex-1">
                  <div
                    class="w-12 h-12 bg-white rounded-lg flex items-center justify-center border border-gray-200 group-hover:border-blue-300 transition">
                    <span class="text-blue-700 font-bold text-lg">BRI</span>
                  </div>
                  <div>
                    <p class="font-semibold text-gray-900">BRI Virtual Account</p>
                    <p class="text-xs text-gray-600">Bank Rakyat Indonesia</p>
                  </div>
                </div>
                <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600 transition" fill="none" stroke="currentColor"
                  viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </label>

              {{-- BNI --}}
              <label
                class="bank-option flex items-center gap-4 p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-orange-500 hover:bg-orange-50 transition-all group">
                <input type="radio" name="channel" value="BNI" class="w-5 h-5 text-orange-600 focus:ring-orange-500">
                <div class="flex items-center gap-3 flex-1">
                  <div
                    class="w-12 h-12 bg-white rounded-lg flex items-center justify-center border border-gray-200 group-hover:border-orange-300 transition">
                    <span class="text-orange-600 font-bold text-lg">BNI</span>
                  </div>
                  <div>
                    <p class="font-semibold text-gray-900">BNI Virtual Account</p>
                    <p class="text-xs text-gray-600">Bank Negara Indonesia</p>
                  </div>
                </div>
                <svg class="w-5 h-5 text-gray-400 group-hover:text-orange-600 transition" fill="none"
                  stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </label>

              {{-- Mandiri --}}
              <label
                class="bank-option flex items-center gap-4 p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-yellow-500 hover:bg-yellow-50 transition-all group">
                <input type="radio" name="channel" value="MANDIRI" class="w-5 h-5 text-yellow-600 focus:ring-yellow-500">
                <div class="flex items-center gap-3 flex-1">
                  <div
                    class="w-12 h-12 bg-white rounded-lg flex items-center justify-center border border-gray-200 group-hover:border-yellow-300 transition">
                    <span class="text-yellow-600 font-bold text-sm">MANDIRI</span>
                  </div>
                  <div>
                    <p class="font-semibold text-gray-900">Mandiri Virtual Account</p>
                    <p class="text-xs text-gray-600">Bank Mandiri</p>
                  </div>
                </div>
                <svg class="w-5 h-5 text-gray-400 group-hover:text-yellow-600 transition" fill="none"
                  stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </label>
            </div>
          </div>
        </div>

    </div>

    {{-- Right Side - Order Summary --}}
    <div class="lg:col-span-1">
      <div
        class="bg-white rounded-2xl border border-gray-200 overflow-hidden sticky top-24 scroll-reveal opacity-0 translate-y-8 shadow-sm">

        {{-- Header --}}
        <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
          <h2 class="text-xl font-bold text-gray-900">Order Summary</h2>
          <p class="text-sm text-gray-600 mt-1">Review your order details</p>
        </div>

        {{-- Products List --}}
        <div class="p-6 border-b border-gray-200">
          <h3 class="text-sm font-semibold text-gray-700 mb-4 uppercase tracking-wide">Items ({{ $totalItems }})</h3>
          <div class="space-y-4 max-h-80 overflow-y-auto custom-scrollbar">
            @foreach($cartItems as $item)
              <div class="flex gap-3 p-3 bg-gray-50 rounded-xl hover:bg-gray-100 transition">
                <div class="w-16 h-16 flex-shrink-0 bg-white rounded-lg overflow-hidden border border-gray-200">
                  <img src="{{ $item->detailUkuran->produk->gambar_url }}"
                    alt="{{ $item->detailUkuran->produk->nama_produk }}" class="w-full h-full object-cover">
                </div>
                <div class="flex-1 min-w-0">
                  <h4 class="font-semibold text-sm text-gray-900 line-clamp-2 mb-1">
                    {{ $item->detailUkuran->produk->nama_produk }}
                  </h4>
                  <div class="flex items-center gap-2 text-xs text-gray-600 mb-2">
                    <span class="px-2 py-0.5 bg-white rounded border border-gray-200">
                      {{ $item->detailUkuran->detailWarna->nama_warna ?? '-' }}
                    </span>
                    <span class="px-2 py-0.5 bg-white rounded border border-gray-200">
                      {{ $item->detailUkuran->ukuran }}
                    </span>
                  </div>
                  <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-600">Qty: {{ $item->jumlah }}</span>
                    <span class="text-sm font-bold text-gray-900">
                      {{ $item->subtotal_formatted }}
                    </span>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        </div>

        {{-- Price Breakdown --}}
        <div class="p-6 bg-gray-50">
          <div class="space-y-3">
            <div class="flex justify-between items-center text-sm">
              <span class="text-gray-600">Subtotal</span>
              <span class="font-semibold text-gray-900">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between items-center text-sm">
              <span class="text-gray-600">Shipping Cost</span>
              <span class="font-semibold text-gray-900" id="shipping-cost-summary">Rp
                {{ number_format($ongkir, 0, ',', '.') }}</span>
            </div>
            <div class="border-t border-gray-300 pt-3 mt-3">
              <div class="flex justify-between items-center">
                <span class="text-base font-bold text-gray-900">Total Payment</span>
                <span class="text-2xl font-bold text-gray-900" id="grand-total">
                  Rp {{ number_format($grandTotal, 0, ',', '.') }}
                </span>
              </div>
            </div>
          </div>
        </div>

        {{-- Action Buttons --}}
        <div class="p-6 space-y-3">
          <button type="submit" id="submitBtn"
            class="w-full py-3.5 px-6 bg-gradient-to-r from-gray-900 to-gray-800 text-white text-center rounded-xl hover:from-black hover:to-gray-900 transition-all duration-300 hover:shadow-lg font-semibold flex items-center justify-center gap-2">
            <span id="btn-text">Place Order</span>
            <span id="btn-loading" class="hidden">
              <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor"
                  d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
              </svg>
            </span>
            <svg class="w-5 h-5" id="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
            </svg>
          </button>

          <a href="{{ route('cart.index') }}"
            class="block w-full py-3 px-6 border-2 border-gray-300 text-gray-700 text-center rounded-xl hover:bg-gray-50 hover:border-gray-400 transition font-semibold">
            <span class="flex items-center justify-center gap-2">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
              </svg>
              Back to Cart
            </span>
          </a>

          {{-- Security Badge --}}
          <div class="mt-4 pt-4 border-t border-gray-200">
            <div class="flex items-start gap-3 p-3 bg-green-50 rounded-xl border border-green-200">
              <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
              </svg>
              <div class="flex-1">
                <p class="text-xs font-semibold text-green-900">Secure Checkout</p>
                <p class="text-xs text-green-700 mt-0.5">SSL encrypted payment</p>
              </div>
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

      /* Bank option styles */
      .bank-option {
        position: relative;
      }

      .bank-option input[type="radio"]:checked~* {
        /* Visual feedback when selected */
      }

      .bank-option:has(input[type="radio"]:checked) {
        border-color: #3b82f6 !important;
        background-color: #eff6ff !important;
      }

      .bank-option:has(input[type="radio"]:checked) svg:last-child {
        color: #3b82f6 !important;
      }

      /* BNI specific */
      .bank-option:has(input[value="BNI"]:checked) {
        border-color: #f97316 !important;
        background-color: #fff7ed !important;
      }

      .bank-option:has(input[value="BNI"]:checked) svg:last-child {
        color: #f97316 !important;
      }

      /* Mandiri specific */
      .bank-option:has(input[value="MANDIRI"]:checked) {
        border-color: #eab308 !important;
        background-color: #fefce8 !important;
      }

      .bank-option:has(input[value="MANDIRI"]:checked) svg:last-child {
        color: #eab308 !important;
      }

      /* Custom scrollbar */
      .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
      }

      .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 10px;
      }

      .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
      }

      .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
      }
    </style>
  @endpush

  @push('scripts')
    <script>
      // Scroll reveal
      const observer = new IntersectionObserver(function (entries) {
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

      document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.scroll-reveal').forEach(el => observer.observe(el));
      });



      // Load cities based on province
      function loadCities() {
        const provinceId = document.getElementById('province_id').value;
        const citySelect = document.getElementById('destination');

        if (!provinceId) {
          citySelect.innerHTML = '<option value="">Select City</option>';
          citySelect.disabled = true;
          return;
        }

        fetch(`{{ route("checkout.cities") }}?province_id=${provinceId}`)
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              let options = '<option value="">Select City</option>';
              data.cities.forEach(city => {
                options += `<option value="${city.id}">${city.name}</option>`;
              });
              citySelect.innerHTML = options;
              citySelect.disabled = false;
            }
          });
      }

      // Update shipping cost based on courier and service
      function updateShippingCost() {
        const ekspedisi = document.getElementById('ekspedisi').value;
        const destination = document.getElementById('destination').value;

        // Update hidden ekspedisi input
        document.getElementById('hidden_ekspedisi').value = ekspedisi;

        if (!destination) return;

        // Show loading state
        const resultContainer = document.getElementById('shipping-results');
        if (resultContainer) {
          resultContainer.innerHTML = '<div class="text-center py-4"><svg class="animate-spin h-5 w-5 mx-auto text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg><p class="text-xs text-gray-500 mt-2">Calculating shipping...</p></div>';
          resultContainer.classList.remove('hidden');
        }

        fetch('{{ route("checkout.shipping") }}', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
          },
          body: JSON.stringify({
            ekspedisi: ekspedisi,
            destination: destination,
            weight: 1000 // Default 1kg
          })
        })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              const resultContainer = document.getElementById('shipping-results');
              let html = '<label class="block text-sm font-medium text-gray-700 mb-2">Select Service</label><div class="space-y-2">';

              if (data.costs.length === 0) {
                html += '<p class="text-sm text-red-500">No service available for this route.</p>';
              } else {
                data.costs.forEach((cost, index) => {
                  html += `
                                                <label class="flex items-center justify-between p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition">
                                                    <div class="flex items-center gap-3">
                                                        <input type="radio" name="layanan" value="${cost.service}"
                                                               data-cost="${cost.cost}"
                                                               data-etd="${cost.etd}"
                                                               data-courier="${ekspedisi}"
                                                               onchange="selectShippingService(this)"
                                                               ${index === 0 ? 'checked' : ''}
                                                               class="w-4 h-4 text-gray-900">
                                                        <div>
                                                            <p class="font-semibold text-sm text-gray-900">${cost.service}</p>
                                                            <p class="text-xs text-gray-500">${cost.description} (${cost.etd})</p>
                                                        </div>
                                                    </div>
                                                    <span class="font-bold text-sm text-gray-900">${cost.cost_formatted}</span>
                                                </label>
                                              `;
                });
              }
              html += '</div>';

              resultContainer.innerHTML = html;

              // Auto select first option
              const firstOption = document.querySelector('input[name="layanan"]:checked');
              if (firstOption) {
                selectShippingService(firstOption);
              }
            } else {
              // Handle error with detailed message
              const resultContainer = document.getElementById('shipping-results');
              const errorMessage = data.message || 'Failed to calculate shipping cost. Please check your API subscription.';
              resultContainer.innerHTML = `
                                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                                      <div class="flex items-start gap-3">
                                        <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                          <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293z" clip-rule="evenodd"/>
                                        </svg>
                                        <div class="flex-1">
                                          <p class="text-sm font-semibold text-red-800">Shipping Calculation Error</p>
                                          <p class="text-xs text-red-700 mt-1">${errorMessage}</p>
                                        </div>
                                      </div>
                                    </div>
                                  `;
            }
          })
          .catch(error => {
            console.error('Error:', error);
            const resultContainer = document.getElementById('shipping-results');
            resultContainer.innerHTML = `
                                  <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                                    <div class="flex items-start gap-3">
                                      <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293z" clip-rule="evenodd"/>
                                      </svg>
                                      <div class="flex-1">
                                        <p class="text-sm font-semibold text-red-800">Network Error</p>
                                        <p class="text-xs text-red-700 mt-1">An error occurred while calculating shipping. Please try again.</p>
                                      </div>
                                    </div>
                                  </div>
                                `;
          });
      }

      function selectShippingService(element) {
        const cost = parseInt(element.dataset.cost);
        const etd = element.dataset.etd;
        const courier = element.dataset.courier || document.querySelector('select[name="ekspedisi"]')?.value || '';
        const costFormatted = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(cost);

        document.getElementById('biaya_ongkir').value = cost;

        // Update hidden inputs for form submission
        document.getElementById('hidden_ongkir').value = cost;
        document.getElementById('hidden_ekspedisi').value = courier;

        // Update display if elements exist
        const displayEl = document.getElementById('shipping-cost-display');
        if (displayEl) displayEl.textContent = costFormatted;

        const summaryEl = document.getElementById('shipping-cost-summary');
        if (summaryEl) summaryEl.textContent = costFormatted;

        const estimationEl = document.getElementById('shipping-estimation');
        if (estimationEl) estimationEl.textContent = etd;

        // Update grand total
        const subtotal = {{ $subtotal }};
        const grandTotal = subtotal + cost;
        document.getElementById('grand-total').textContent = 'Rp ' + grandTotal.toLocaleString('id-ID');
      }

      // Handle checkout form submission
      document.getElementById('checkoutForm').addEventListener('submit', function (e) {
        e.preventDefault();

        const submitBtn = document.getElementById('submitBtn');
        const btnText = document.getElementById('btn-text');
        const btnLoading = document.getElementById('btn-loading');



        // Validate shipping service selection
        const layanan = document.querySelector('input[name="layanan"]:checked');
        if (!layanan) {
          alert('Silakan pilih layanan pengiriman terlebih dahulu');
          return;
        }

        // Validate bank selection
        const channel = document.querySelector('input[name="channel"]:checked');
        if (!channel) {
          alert('Silakan pilih bank Virtual Account terlebih dahulu');
          return;
        }

        // Disable button and show loading
        submitBtn.disabled = true;
        btnText.classList.add('hidden');
        btnLoading.classList.remove('hidden');
        const btnIcon = document.getElementById('btn-icon');
        if (btnIcon) btnIcon.classList.add('hidden');

        const formData = new FormData(this);
        const data = Object.fromEntries(formData.entries());

        // Set payment method to Virtual Account
        data.metode_pembayaran = 'va';

        // Add channel (bank) to data
        if (channel) {
          data.channel = channel.value;
        }

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
              const btnIcon = document.getElementById('btn-icon');
              if (btnIcon) btnIcon.classList.remove('hidden');
            }
          })
          .catch(error => {
            console.error('Error:', error);
            alert('Failed to process checkout. Please try again.');
            // Re-enable button
            submitBtn.disabled = false;
            btnText.classList.remove('hidden');
            btnLoading.classList.add('hidden');
            const btnIcon = document.getElementById('btn-icon');
            if (btnIcon) btnIcon.classList.remove('hidden');
          });
      });
    </script>
  @endpush
@endsection
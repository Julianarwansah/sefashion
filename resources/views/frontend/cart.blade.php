@extends('layouts_public.app')

@section('title', 'Shopping Cart • SeFashion')

@section('content')
{{-- HERO/BREADCRUMB SECTION --}}
<section class="relative h-[30vh] min-h-[250px] w-full overflow-hidden bg-gradient-to-br from-gray-50 to-gray-100">
  <div class="absolute inset-0 flex items-center justify-center">
    <div class="text-center animate-fade-in-up">
      <h1 class="text-4xl sm:text-5xl font-extrabold text-gray-900 mb-2">Shopping Cart</h1>
      <p class="text-gray-600">Manage your selected items</p>
    </div>
  </div>
</section>

{{-- CART CONTENT --}}
<section class="py-10 sm:py-14 bg-white">
  <div class="max-w-7xl mx-auto px-4 sm:px-6">

    @if(session('success'))
      <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl animate-slide-down">
        <div class="flex items-center">
          <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
          </svg>
          {{ session('success') }}
        </div>
      </div>
    @endif

    @if($cartItems->count() > 0)
      <div class="grid lg:grid-cols-3 gap-8">

        {{-- Cart Items List --}}
        <div class="lg:col-span-2">
          <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden scroll-reveal opacity-0 translate-y-8">
            <div class="p-6 border-b border-gray-200 bg-gray-50">
              <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-gray-900">Cart Items ({{ $totalItems }})</h2>
                <form action="{{ route('cart.clear') }}" method="POST" onsubmit="return confirm('Clear all items from cart?')">
                  @csrf
                  <button type="submit" class="text-sm text-red-600 hover:text-red-700 hover:underline">
                    Clear Cart
                  </button>
                </form>
              </div>
            </div>

            <div class="divide-y divide-gray-200">
              @foreach($cartItems as $item)
                <div class="p-6 cart-item scroll-reveal opacity-0 translate-x-4 hover:bg-gray-50 transition-colors duration-200" data-cart-id="{{ $item->id_cart }}">
                  <div class="flex gap-4">
                    {{-- Product Image --}}
                    <div class="w-24 h-24 flex-shrink-0 bg-gray-100 rounded-xl overflow-hidden group">
                      <img src="{{ $item->detailUkuran->produk->gambar_url }}"
                           alt="{{ $item->detailUkuran->produk->nama_produk }}"
                           class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    </div>

                    {{-- Product Details --}}
                    <div class="flex-1 min-w-0">
                      <h3 class="font-semibold text-gray-900 line-clamp-2 mb-1">
                        {{ $item->detailUkuran->produk->nama_produk }}
                      </h3>
                      <p class="text-sm text-gray-600 mb-2">
                        Color: {{ $item->detailUkuran->detailWarna->nama_warna ?? '-' }} |
                        Size: {{ $item->detailUkuran->ukuran }}
                      </p>
                      <p class="text-lg font-bold text-gray-900">
                        Rp {{ number_format($item->detailUkuran->harga, 0, ',', '.') }}
                      </p>
                      <p class="text-sm text-gray-500 mt-1">
                        Stock: {{ $item->detailUkuran->stok }} available
                      </p>
                    </div>

                    {{-- Quantity Controls --}}
                    <div class="flex flex-col items-end justify-between">
                      <div class="flex items-center gap-2 bg-gray-100 rounded-lg">
                        <button onclick="updateQuantity({{ $item->id_cart }}, {{ $item->jumlah - 1 }})"
                                class="px-3 py-2 hover:bg-gray-200 rounded-l-lg transition"
                                {{ $item->jumlah <= 1 ? 'disabled' : '' }}>
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                          </svg>
                        </button>
                        <span class="px-4 py-2 font-semibold quantity-display">{{ $item->jumlah }}</span>
                        <button onclick="updateQuantity({{ $item->id_cart }}, {{ $item->jumlah + 1 }})"
                                class="px-3 py-2 hover:bg-gray-200 rounded-r-lg transition"
                                {{ $item->jumlah >= $item->detailUkuran->stok ? 'disabled' : '' }}>
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                          </svg>
                        </button>
                      </div>

                      <div class="text-right mt-2">
                        <p class="text-sm text-gray-500">Subtotal</p>
                        <p class="text-lg font-bold text-gray-900 subtotal-display">
                          Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                        </p>
                      </div>

                      <button onclick="removeItem({{ $item->id_cart }})"
                              class="mt-2 text-red-600 hover:text-red-700 p-2 hover:bg-red-50 rounded-lg transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                      </button>
                    </div>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        </div>

        {{-- Order Summary --}}
        <div class="lg:col-span-1">
          <div class="bg-white rounded-2xl border border-gray-200 p-6 sticky top-24 scroll-reveal opacity-0 translate-y-8">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Order Summary</h2>

            <div class="space-y-3 mb-6">
              <div class="flex justify-between text-gray-600">
                <span>Subtotal ({{ $totalItems }} items)</span>
                <span id="subtotal-price">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
              </div>
              <div class="flex justify-between text-gray-600">
                <span>Shipping</span>
                <span class="text-sm">Calculated at checkout</span>
              </div>
              <div class="border-t pt-3 flex justify-between items-center">
                <span class="text-lg font-bold text-gray-900">Total</span>
                <span class="text-2xl font-bold text-gray-900" id="total-price">
                  Rp {{ number_format($totalPrice, 0, ',', '.') }}
                </span>
              </div>
            </div>

            <a href="{{ route('checkout') }}"
               class="block w-full py-3 px-6 bg-gray-900 text-white text-center rounded-xl hover:bg-black transition-all duration-300 hover:scale-105 hover:shadow-xl font-semibold mb-3">
              Proceed to Checkout
            </a>

            <a href="{{ route('shop') }}"
               class="block w-full py-3 px-6 border border-gray-300 text-gray-900 text-center rounded-xl hover:bg-gray-50 transition font-semibold">
              Continue Shopping
            </a>

            <div class="mt-6 pt-6 border-t border-gray-200">
              <div class="flex items-start gap-3">
                <svg class="w-6 h-6 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div class="text-sm text-gray-600">
                  <p class="font-semibold text-gray-900">Secure Checkout</p>
                  <p>Your payment information is safe with us</p>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
    @else
      {{-- Empty Cart --}}
      <div class="text-center py-16 scroll-reveal opacity-0 translate-y-8">
        <div class="inline-block p-6 bg-gray-50 rounded-3xl mb-6">
          <svg class="w-24 h-24 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
          </svg>
        </div>
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Your Cart is Empty</h2>
        <p class="text-gray-600 mb-8">Start adding some awesome products to your cart!</p>
        <a href="{{ route('shop') }}"
           class="inline-flex items-center gap-2 px-8 py-3 bg-gray-900 text-white rounded-xl hover:bg-black transition-all duration-300 hover:scale-105 font-semibold">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
          </svg>
          Start Shopping
        </a>
      </div>
    @endif

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
  transform: translateY(0) translateX(0) !important;
}

.cart-item:hover {
  transform: translateX(4px);
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

// Update quantity
function updateQuantity(cartId, newQuantity) {
  if (newQuantity < 1) return;

  fetch(`/cart/${cartId}`, {
    method: 'PUT',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    },
    body: JSON.stringify({ jumlah: newQuantity })
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      // Update quantity display
      const cartItem = document.querySelector(`[data-cart-id="${cartId}"]`);
      cartItem.querySelector('.quantity-display').textContent = newQuantity;
      cartItem.querySelector('.subtotal-display').textContent = data.subtotal_formatted;

      // Update totals
      document.getElementById('total-price').textContent = data.total_formatted;
      document.getElementById('subtotal-price').textContent = data.total_formatted;
    } else {
      alert(data.message);
    }
  })
  .catch(error => {
    console.error('Error:', error);
    alert('Failed to update cart');
  });
}

// Remove item
function removeItem(cartId) {
  if (!confirm('Remove this item from cart?')) return;

  fetch(`/cart/${cartId}`, {
    method: 'DELETE',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    }
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      // Remove item with animation
      const cartItem = document.querySelector(`[data-cart-id="${cartId}"]`);
      cartItem.style.opacity = '0';
      cartItem.style.transform = 'translateX(-100%)';

      setTimeout(() => {
        cartItem.remove();

        // Update totals
        document.getElementById('total-price').textContent = data.total_formatted;
        document.getElementById('subtotal-price').textContent = data.total_formatted;

        // Reload page if cart is empty
        if (data.cart_count === 0) {
          location.reload();
        }
      }, 300);
    } else {
      alert(data.message);
    }
  })
  .catch(error => {
    console.error('Error:', error);
    alert('Failed to remove item');
  });
}
</script>
@endpush
@endsection

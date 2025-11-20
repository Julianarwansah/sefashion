{{-- Cart Notification Modal --}}
<div id="cartNotificationModal" class="fixed inset-0 z-[9999] hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
  {{-- Background overlay --}}
  <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity modal-backdrop"></div>

  {{-- Modal container --}}
  <div class="fixed inset-0 z-[9999] overflow-y-auto">
    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
      {{-- Modal panel --}}
      <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md modal-panel">

        {{-- Success Icon --}}
        <div class="bg-white px-6 pt-8 pb-6">
          <div class="flex flex-col items-center">
            {{-- Animated Checkmark Circle --}}
            <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-green-100 mb-4 animate-scale-in">
              <svg class="h-12 w-12 text-green-600 animate-check" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
              </svg>
            </div>

            {{-- Success Message --}}
            <div class="text-center">
              <h3 class="text-2xl font-bold text-gray-900 mb-2 animate-fade-in-up" id="modal-title">
                Added to Cart!
              </h3>
              <p class="text-sm text-gray-600 mb-4 animate-fade-in-up animation-delay-100" id="modalProductName">
                <!-- Product name will be inserted here -->
              </p>
            </div>

            {{-- Product Info --}}
            <div class="w-full bg-gray-50 rounded-xl p-4 mb-4 animate-fade-in-up animation-delay-200" id="modalProductInfo">
              <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-white rounded-lg overflow-hidden flex-shrink-0" id="modalProductImage">
                  <!-- Product image will be inserted here -->
                </div>
                <div class="flex-1 min-w-0">
                  <p class="text-sm text-gray-600 mb-1">
                    <span id="modalProductSize"></span> • <span id="modalProductColor"></span>
                  </p>
                  <p class="text-sm font-semibold text-gray-900">
                    Quantity: <span id="modalProductQty"></span>
                  </p>
                </div>
              </div>
            </div>

            {{-- Cart Count Badge --}}
            <div class="bg-blue-50 border border-blue-200 rounded-xl px-4 py-3 w-full mb-6 animate-fade-in-up animation-delay-300">
              <p class="text-sm text-center text-blue-900">
                <span class="font-bold text-lg" id="modalCartCount">0</span> item(s) in your cart
              </p>
            </div>

            {{-- Action Buttons --}}
            <div class="flex flex-col sm:flex-row gap-3 w-full animate-fade-in-up animation-delay-400">
              <button type="button"
                      onclick="closeCartModal()"
                      class="flex-1 inline-flex justify-center items-center gap-2 rounded-xl border-2 border-gray-300 bg-white px-4 py-3 text-sm font-semibold text-gray-900 hover:bg-gray-50 transition-all duration-200">
                Continue Shopping
              </button>
              <a href="{{ route('cart.index') }}"
                 class="flex-1 inline-flex justify-center items-center gap-2 rounded-xl bg-gray-900 px-4 py-3 text-sm font-semibold text-white hover:bg-black transition-all duration-200 hover:scale-105">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                View Cart
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Styles --}}
<style>
@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes scaleIn {
  0% {
    opacity: 0;
    transform: scale(0.5);
  }
  50% {
    transform: scale(1.1);
  }
  100% {
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
  animation: fadeInUp 0.5s ease-out forwards;
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

.animation-delay-100 {
  animation-delay: 0.1s;
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

/* Modal animations */
#cartNotificationModal:not(.hidden) .modal-backdrop {
  animation: fadeIn 0.3s ease-out;
}

#cartNotificationModal:not(.hidden) .modal-panel {
  animation: slideUp 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
}

@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

@keyframes slideUp {
  from {
    opacity: 0;
    transform: translateY(100px) scale(0.9);
  }
  to {
    opacity: 1;
    transform: translateY(0) scale(1);
  }
}
</style>

{{-- JavaScript --}}
<script>
// Function to show cart notification modal
function showCartNotification(data) {
  const modal = document.getElementById('cartNotificationModal');

  // Update product name
  document.getElementById('modalProductName').textContent = data.product_name || 'Product';

  // Update product details
  document.getElementById('modalProductSize').textContent = `Size: ${data.size || '-'}`;
  document.getElementById('modalProductColor').textContent = `Color: ${data.color || '-'}`;
  document.getElementById('modalProductQty').textContent = data.quantity || 1;

  // Update cart count
  document.getElementById('modalCartCount').textContent = data.cart_count || 0;

  // Update product image if available
  if (data.image) {
    document.getElementById('modalProductImage').innerHTML = `
      <img src="${data.image}" alt="${data.product_name}" class="w-full h-full object-cover">
    `;
  } else {
    document.getElementById('modalProductImage').innerHTML = `
      <div class="w-full h-full flex items-center justify-center bg-gray-100">
        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
      </div>
    `;
  }

  // Show modal
  modal.classList.remove('hidden');
  document.body.style.overflow = 'hidden'; // Prevent scrolling

  // Auto close after 5 seconds
  setTimeout(() => {
    closeCartModal();
  }, 5000);
}

// Function to close modal
function closeCartModal() {
  const modal = document.getElementById('cartNotificationModal');
  modal.classList.add('hidden');
  document.body.style.overflow = ''; // Re-enable scrolling
}

// Close modal when clicking outside
document.addEventListener('DOMContentLoaded', function() {
  const modal = document.getElementById('cartNotificationModal');
  if (modal) {
    modal.addEventListener('click', function(e) {
      if (e.target === this || e.target.classList.contains('modal-backdrop')) {
        closeCartModal();
      }
    });
  }

  // Close modal with Escape key
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
      closeCartModal();
    }
  });
});
</script>

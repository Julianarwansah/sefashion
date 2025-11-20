{{-- Cart Notification Modal --}}
<div id="cartNotificationModal" class="fixed inset-0 z-[9999] hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
  {{-- Background overlay with blur --}}
  <div class="fixed inset-0 bg-black bg-opacity-60 backdrop-blur-sm transition-all duration-300 modal-backdrop"></div>

  {{-- Modal container --}}
  <div class="fixed inset-0 z-[9999] overflow-y-auto">
    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
      {{-- Modal panel --}}
      <div class="relative transform overflow-hidden rounded-3xl bg-gradient-to-br from-white to-gray-50 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg modal-panel border border-gray-100">

        {{-- Close button --}}
        <button onclick="closeCartModal()" class="absolute top-4 right-4 z-10 p-2 rounded-full hover:bg-gray-100 transition-all duration-200 group">
          <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>

        {{-- Success Icon with particles --}}
        <div class="bg-gradient-to-br from-gray-50 to-gray-100 px-6 pt-8 pb-6 relative overflow-hidden">
          {{-- Decorative circles --}}
          <div class="absolute top-0 right-0 w-32 h-32 bg-gray-300 rounded-full opacity-20 blur-3xl animate-pulse"></div>
          <div class="absolute bottom-0 left-0 w-40 h-40 bg-gray-400 rounded-full opacity-20 blur-3xl animate-pulse animation-delay-300"></div>

          <div class="flex flex-col items-center relative z-10">
            {{-- Animated Shopping Bag Icon --}}
            <div class="relative mb-6 animate-bounce-in">
              <div class="mx-auto flex h-24 w-24 items-center justify-center rounded-full bg-gradient-to-br from-gray-700 to-gray-900 shadow-lg animate-scale-in">
                <svg class="h-12 w-12 text-white animate-wiggle" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
              </div>
              {{-- Success checkmark badge --}}
              <div class="absolute -top-1 -right-1 bg-green-500 rounded-full p-1.5 shadow-lg animate-scale-in animation-delay-200">
                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
              </div>
            </div>

            {{-- Success Message --}}
            <div class="text-center">
              <h3 class="text-2xl font-extrabold bg-gradient-to-r from-gray-900 to-gray-700 bg-clip-text text-transparent mb-2 animate-fade-in-up" id="modal-title">
                Added to Cart!
              </h3>
              <p class="text-base text-gray-600 mb-1 animate-fade-in-up animation-delay-100 font-medium" id="modalProductName">
                <!-- Product name will be inserted here -->
              </p>
            </div>

            {{-- Product Info Card --}}
            <div class="w-full bg-white rounded-2xl p-4 mb-4 animate-fade-in-up animation-delay-200 shadow-md border border-gray-100" id="modalProductInfo">
              <div class="flex items-center gap-4">
                <div class="w-20 h-20 bg-gray-100 rounded-xl overflow-hidden flex-shrink-0 shadow-sm" id="modalProductImage">
                  <!-- Product image will be inserted here -->
                </div>
                <div class="flex-1 min-w-0">
                  <div class="flex items-center gap-2 mb-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-200 text-gray-800" id="modalProductSize">
                      <!-- Size -->
                    </span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-200 text-gray-800" id="modalProductColor">
                      <!-- Color -->
                    </span>
                  </div>
                  <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-500">Qty:</span>
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gradient-to-br from-gray-700 to-gray-900 text-white font-bold text-sm shadow-sm" id="modalProductQty">1</span>
                  </div>
                </div>
              </div>
            </div>

            {{-- Cart Summary --}}
            <div class="w-full bg-gradient-to-r from-gray-800 to-gray-900 rounded-2xl px-6 py-4 mb-6 animate-fade-in-up animation-delay-300 shadow-lg">
              <div class="flex items-center justify-center gap-3 text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <div>
                  <p class="text-sm opacity-90">Your Cart</p>
                  <p class="text-2xl font-bold" id="modalCartCount">0</p>
                </div>
              </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex flex-col sm:flex-row gap-3 w-full animate-fade-in-up animation-delay-400">
              <button type="button"
                      onclick="closeCartModal()"
                      class="flex-1 inline-flex justify-center items-center gap-2 rounded-xl border-2 border-gray-300 bg-white px-6 py-3.5 text-sm font-bold text-gray-700 hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 hover:scale-105 shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Continue Shopping
              </button>
              <a href="{{ route('cart.index') }}"
                 class="flex-1 inline-flex justify-center items-center gap-2 rounded-xl bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-3.5 text-sm font-bold text-white hover:from-gray-900 hover:to-black transition-all duration-200 hover:scale-105 shadow-lg hover:shadow-xl">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
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
    transform: scale(0.3) rotate(-10deg);
  }
  50% {
    transform: scale(1.05) rotate(5deg);
  }
  100% {
    opacity: 1;
    transform: scale(1) rotate(0deg);
  }
}

@keyframes bounceIn {
  0% {
    opacity: 0;
    transform: scale(0.3) translateY(-50px);
  }
  50% {
    opacity: 1;
    transform: scale(1.05) translateY(0);
  }
  70% {
    transform: scale(0.9);
  }
  100% {
    transform: scale(1);
  }
}

@keyframes wiggle {
  0%, 100% {
    transform: rotate(0deg);
  }
  25% {
    transform: rotate(-5deg);
  }
  75% {
    transform: rotate(5deg);
  }
}

@keyframes pulse {
  0%, 100% {
    opacity: 0.2;
  }
  50% {
    opacity: 0.4;
  }
}

.animate-fade-in-up {
  animation: fadeInUp 0.5s ease-out forwards;
  opacity: 0;
}

.animate-scale-in {
  animation: scaleIn 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55) forwards;
}

.animate-bounce-in {
  animation: bounceIn 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55) forwards;
}

.animate-wiggle {
  animation: wiggle 0.5s ease-in-out 0.4s 2;
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
  animation: fadeInBackdrop 0.3s ease-out forwards;
}

#cartNotificationModal:not(.hidden) .modal-panel {
  animation: slideUpScale 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55) forwards;
}

@keyframes fadeInBackdrop {
  from {
    opacity: 0;
    backdrop-filter: blur(0px);
  }
  to {
    opacity: 1;
    backdrop-filter: blur(4px);
  }
}

@keyframes slideUpScale {
  from {
    opacity: 0;
    transform: translateY(100px) scale(0.8);
  }
  to {
    opacity: 1;
    transform: translateY(0) scale(1);
  }
}

/* Hover effects */
#cartNotificationModal button:hover,
#cartNotificationModal a:hover {
  transform: translateY(-2px);
}

#cartNotificationModal button:active,
#cartNotificationModal a:active {
  transform: translateY(0);
}
</style>

{{-- JavaScript --}}
<script>
// Function to show cart notification modal
function showCartNotification(data) {
  const modal = document.getElementById('cartNotificationModal');

  // Update product name with ellipsis if too long
  const productName = data.product_name || 'Product';
  document.getElementById('modalProductName').textContent = productName.length > 50
    ? productName.substring(0, 50) + '...'
    : productName;

  // Update product details with badges
  document.getElementById('modalProductSize').innerHTML = `
    <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
    </svg>
    ${data.size || 'N/A'}
  `;

  document.getElementById('modalProductColor').innerHTML = `
    <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
    </svg>
    ${data.color || 'N/A'}
  `;

  document.getElementById('modalProductQty').textContent = data.quantity || 1;

  // Update cart count with animation
  const cartCountEl = document.getElementById('modalCartCount');
  cartCountEl.style.transform = 'scale(1.2)';
  cartCountEl.textContent = `${data.cart_count || 0} ${(data.cart_count || 0) > 1 ? 'items' : 'item'}`;
  setTimeout(() => {
    cartCountEl.style.transform = 'scale(1)';
  }, 200);

  // Update product image if available
  if (data.image) {
    document.getElementById('modalProductImage').innerHTML = `
      <img src="${data.image}"
           alt="${productName}"
           class="w-full h-full object-cover transform hover:scale-110 transition-transform duration-300"
           onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\\'w-full h-full flex items-center justify-center bg-gray-200\\'>
             <svg class=\\'w-10 h-10 text-gray-400\\' fill=\\'none\\' stroke=\\'currentColor\\' viewBox=\\'0 0 24 24\\'>
               <path stroke-linecap=\\'round\\' stroke-linejoin=\\'round\\' stroke-width=\\'2\\' d=\\'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z\\'/></svg></div>';">
    `;
  } else {
    document.getElementById('modalProductImage').innerHTML = `
      <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg">
        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
      </div>
    `;
  }

  // Show modal with smooth transition
  modal.classList.remove('hidden');
  document.body.style.overflow = 'hidden'; // Prevent scrolling

  // Play success sound (optional - commented out by default)
  // const audio = new Audio('/sounds/cart-add.mp3');
  // audio.volume = 0.3;
  // audio.play().catch(() => {});

  // Auto close after 6 seconds
  setTimeout(() => {
    closeCartModal();
  }, 6000);
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

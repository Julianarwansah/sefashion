@extends('layouts_public.app')

@section('title', 'Contact • SeFashion')

@section('content')
{{-- HERO BANNER WITH CONTACT TEXT --}}
<section class="relative h-[50vh] min-h-[400px] w-full">
  {{-- background image --}}
  <div
    class="absolute inset-0 bg-center bg-cover"
    style="background-image:url('{{ asset('images/hero-rack.jpg') }}');">
  </div>

  {{-- dark overlay --}}
  <div class="absolute inset-0 bg-black/40"></div>

  {{-- Centered Contact Text --}}
  <div class="relative z-10 h-full flex items-center justify-center">
    <div class="text-center">
      <h1 class="text-white text-5xl sm:text-6xl font-extrabold fade-in-up">
        Contact
      </h1>
    </div>
  </div>
</section>

{{-- CONTACT SECTION --}}
<section class="py-16 sm:py-24 bg-white">
  <div class="max-w-6xl mx-auto px-4 sm:px-6">
    <div class="grid lg:grid-cols-2 gap-12 lg:gap-16">
      {{-- Contact Info --}}
      <div class="fade-in-left">
        <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mb-6">
          Get In Touch
        </h2>

        <p class="text-gray-600 mb-8 leading-relaxed">
          Kami senang mendengar dari Anda! Jika Anda memiliki pertanyaan, saran, atau ingin berkolaborasi, jangan ragu untuk menghubungi kami.
        </p>

        <div class="space-y-6">
          {{-- Email --}}
          <div class="flex items-start gap-4">
            <div class="flex-shrink-0 w-12 h-12 bg-gray-900 rounded-lg flex items-center justify-center">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
              </svg>
            </div>
            <div>
              <h3 class="font-semibold text-gray-900 mb-1">Email</h3>
              <a href="mailto:hello@sefashion.com" class="text-gray-600 hover:text-gray-900">hello@sefashion.com</a>
            </div>
          </div>

          {{-- Phone --}}
          <div class="flex items-start gap-4">
            <div class="flex-shrink-0 w-12 h-12 bg-gray-900 rounded-lg flex items-center justify-center">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
              </svg>
            </div>
            <div>
              <h3 class="font-semibold text-gray-900 mb-1">Phone</h3>
              <a href="tel:+6281234567890" class="text-gray-600 hover:text-gray-900">+62 812-3456-7890</a>
            </div>
          </div>

          {{-- Address --}}
          <div class="flex items-start gap-4">
            <div class="flex-shrink-0 w-12 h-12 bg-gray-900 rounded-lg flex items-center justify-center">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
              </svg>
            </div>
            <div>
              <h3 class="font-semibold text-gray-900 mb-1">Address</h3>
              <p class="text-gray-600">Jl. Fashion Boulevard No. 123<br>Jakarta, Indonesia 12345</p>
            </div>
          </div>

          {{-- Social Media --}}
          <div class="flex items-start gap-4">
            <div class="flex-shrink-0 w-12 h-12 bg-gray-900 rounded-lg flex items-center justify-center">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
              </svg>
            </div>
            <div>
              <h3 class="font-semibold text-gray-900 mb-2">Social Media</h3>
              <div class="flex gap-3">
                <a href="#" class="text-gray-600 hover:text-gray-900 transition">
                  <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073z"/>
                    <path d="M12 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                  </svg>
                </a>
                <a href="#" class="text-gray-600 hover:text-gray-900 transition">
                  <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                  </svg>
                </a>
                <a href="#" class="text-gray-600 hover:text-gray-900 transition">
                  <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/>
                  </svg>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- Contact Form --}}
      <div class="fade-in-right">
        <div class="bg-gray-50 rounded-2xl p-8">
          <h3 class="text-2xl font-bold text-gray-900 mb-6">Send Us a Message</h3>

          <form class="space-y-6">
            {{-- Name --}}
            <div>
              <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Name</label>
              <input type="text"
                     id="name"
                     name="name"
                     class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-900/10 focus:border-gray-900"
                     placeholder="Your name"
                     required>
            </div>

            {{-- Email --}}
            <div>
              <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
              <input type="email"
                     id="email"
                     name="email"
                     class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-900/10 focus:border-gray-900"
                     placeholder="your@email.com"
                     required>
            </div>

            {{-- Subject --}}
            <div>
              <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
              <input type="text"
                     id="subject"
                     name="subject"
                     class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-900/10 focus:border-gray-900"
                     placeholder="How can we help?"
                     required>
            </div>

            {{-- Message --}}
            <div>
              <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Message</label>
              <textarea id="message"
                        name="message"
                        rows="5"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-900/10 focus:border-gray-900 resize-none"
                        placeholder="Your message..."
                        required></textarea>
            </div>

            {{-- Submit Button --}}
            <button type="submit"
                    class="w-full px-6 py-3 bg-gray-900 text-white rounded-lg hover:bg-black transition font-medium">
              Send Message
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- MAP SECTION (Optional) --}}
<section class="py-16 bg-gray-50">
  <div class="max-w-6xl mx-auto px-4 sm:px-6">
    <div class="bg-gray-200 rounded-2xl overflow-hidden h-96 fade-in-up">
      <iframe
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3796.196408844428!2d106.60548937480912!3d-6.179186560547389!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69fed8e67cb0ad%3A0x2888b42a70065bd2!2sGlobal%20Institute%20%7C%20Institut%20Teknologi%20dan%20Bisnis%20Bina%20Sarana%20Global!5e1!3m2!1sid!2sid!4v1763004922496!5m2!1sid!2sid"
        width="100%"
        height="100%"
        style="border:0;"
        allowfullscreen=""
        loading="lazy"
        referrerpolicy="no-referrer-when-downgrade">
      </iframe>
    </div>
  </div>
</section>

@endsection

@push('scripts')
<style>
/* Fade In Animations */
.fade-in-up {
  opacity: 0;
  transform: translateY(30px);
  transition: opacity 0.8s ease-out, transform 0.8s ease-out;
}

.fade-in-left {
  opacity: 0;
  transform: translateX(-30px);
  transition: opacity 0.8s ease-out, transform 0.8s ease-out;
}

.fade-in-right {
  opacity: 0;
  transform: translateX(30px);
  transition: opacity 0.8s ease-out, transform 0.8s ease-out;
}

.fade-in-up.visible,
.fade-in-left.visible,
.fade-in-right.visible {
  opacity: 1;
  transform: translate(0, 0);
}
</style>

<script>
// Intersection Observer for scroll animations
document.addEventListener('DOMContentLoaded', function() {
  const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
  };

  const observer = new IntersectionObserver(function(entries) {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('visible');
      }
    });
  }, observerOptions);

  // Observe all animated elements
  const animatedElements = document.querySelectorAll('.fade-in-up, .fade-in-left, .fade-in-right');
  animatedElements.forEach(el => observer.observe(el));
});
</script>
@endpush

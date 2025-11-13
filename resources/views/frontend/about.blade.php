@extends('layouts_public.app')

@section('title', 'About • SeFashion')

@section('content')
{{-- HERO BANNER WITH ABOUT TEXT --}}
<section class="relative h-[50vh] min-h-[400px] w-full">
  {{-- background image --}}
  <div
    class="absolute inset-0 bg-center bg-cover"
    style="background-image:url('{{ asset('images/hero-rack.jpg') }}');">
  </div>

  {{-- dark overlay --}}
  <div class="absolute inset-0 bg-black/40"></div>

  {{-- Centered About Text --}}
  <div class="relative z-10 h-full flex items-center justify-center">
    <div class="text-center">
      <h1 class="text-white text-5xl sm:text-6xl font-extrabold fade-in-up">
        About
      </h1>
    </div>
  </div>
</section>

{{-- SIAPA KAMI SECTION --}}
<section class="py-16 sm:py-24 bg-white">
  <div class="max-w-6xl mx-auto px-4 sm:px-6">
    <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
      {{-- Image --}}
      <div class="order-2 lg:order-1 fade-in-left">
        <div class="relative">
          <div class="aspect-[4/5] rounded-2xl overflow-hidden bg-gray-100">
            <img src="{{ asset('images/about-founder.jpg') }}"
                 class="w-full h-full object-cover"
                 alt="Founder"
                 onerror="this.src='https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=800&auto=format&fit=crop'">
          </div>
          {{-- Decorative element --}}
          <div class="absolute -bottom-6 -right-6 w-32 h-32 bg-gray-900 rounded-2xl -z-10"></div>
        </div>
      </div>

      {{-- Content --}}
      <div class="order-1 lg:order-2 fade-in-right">
        <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mb-6">
          Siapa Kami
        </h2>

        <div class="space-y-4 text-gray-700 leading-relaxed">
          <p>
            <strong>SEFashion</strong> adalah destinasi fashion modern yang menghadirkan koleksi pakaian berkualitas tinggi dengan desain yang timeless dan contemporary. Kami percaya bahwa fashion adalah bentuk ekspresi diri yang tidak mengenal batasan.
          </p>

          <p>
            Sejak didirikan, kami berkomitmen untuk menyediakan produk fashion yang tidak hanya mengikuti tren, tetapi juga mencerminkan gaya yang autentik dan personal setiap individu. Setiap piece yang kami tawarkan dipilih dengan cermat untuk menaatikan kualitas dan kenyamanan terbaik.
          </p>

          <p>
            Di <strong>SEFashion</strong>, kami memahami bahwa setiap orang memiliki gaya unik mereka sendiri. Itulah mengapa kami menyediakan beragam pilihan yang capat disesuaikan dengan kepribadian dan lifestyle Anda.
          </p>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- MISI KAMI SECTION --}}
<section class="py-16 sm:py-24 bg-gray-50">
  <div class="max-w-4xl mx-auto px-4 sm:px-6 text-center">
    <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mb-8 fade-in-up">
      Misi Kami
    </h2>

    <div class="text-gray-700 leading-relaxed space-y-4 fade-in-up delay-100">
      <p>
        Misi kami adalah memberdayakan setiap individu untuk mengekspresikan kepribadian mereka melalui fashion yang berkualitas, accessible, dan sustainable. Kami berkomitmen untuk menyediakan pengalaman berbelanja yang menyenangkan sambil mempertahankan standar kualitas tertinggi dalam setiap produk yang kami tawarkan.
      </p>

      <p>
        <strong>SEFashion</strong> hadir untuk membuktikan bahwa gaya yang luar biasa tidak harus mahal, dan setiap orang berhak merasakan kepercayaan diri melalui pakaian yang mereka kenakan.
      </p>
    </div>
  </div>
</section>

{{-- NILAI KAMI SECTION --}}
<section class="py-16 sm:py-24 bg-white">
  <div class="max-w-6xl mx-auto px-4 sm:px-6">
    <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mb-12 text-center fade-in-up">
      Nilai Kami
    </h2>

    <div class="grid md:grid-cols-3 gap-8 lg:gap-12">
      {{-- Value 1: Kualitas Terbaik --}}
      <div class="text-center fade-in-up delay-100">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-900 rounded-2xl mb-6">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
        </div>

        <h3 class="text-xl font-bold text-gray-900 mb-4">
          Kualitas Terbaik
        </h3>

        <p class="text-gray-600 leading-relaxed">
          Kami hanya menjual produk berkualitas tinggi dalam setiap produk yang kami tawarkan. Dari pemilihan bahan hingga proses produksi, semua dilakukan dengan standar tertinggi.
        </p>
      </div>

      {{-- Value 2: Gaya Tanpa Batas --}}
      <div class="text-center fade-in-up delay-200">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-900 rounded-2xl mb-6">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
          </svg>
        </div>

        <h3 class="text-xl font-bold text-gray-900 mb-4">
          Gaya Tanpa Batas
        </h3>

        <p class="text-gray-600 leading-relaxed">
          Fashion adalah bentuk ekspresi yang tak terbatas. Kami menyediakan koleksi yang beragam untuk membantu setiap gaya dan kepribadian unik Anda.
        </p>
      </div>

      {{-- Value 3: Harga Bersahabat --}}
      <div class="text-center fade-in-up delay-300">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-900 rounded-2xl mb-6">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
          </svg>
        </div>

        <h3 class="text-xl font-bold text-gray-900 mb-4">
          Harga Bersahabat
        </h3>

        <p class="text-gray-600 leading-relaxed">
          Gaya yang luar biasa tidak harus mahal. Kami berkomitmen memberikan produk berkualitas tinggi dengan harga yang terjangkau untuk semua kalangan.
        </p>
      </div>
    </div>
  </div>
</section>

{{-- CALL TO ACTION --}}
<section class="py-16 sm:py-20 bg-gray-900">
  <div class="max-w-4xl mx-auto px-4 sm:px-6 text-center">
    <h2 class="text-3xl sm:text-4xl font-extrabold text-white mb-4 fade-in-up">
      Temukan Gaya Anda Bersama Kami
    </h2>

    <p class="text-gray-300 text-lg mb-8 fade-in-up delay-100">
      Jelajahi koleksi terbaru kami dan temukan pieces yang sempurna untuk Anda.
    </p>

    <a href="{{ route('shop') }}"
       class="inline-flex items-center gap-2 px-8 py-4 bg-white text-gray-900 rounded-xl hover:bg-gray-100 transition font-medium fade-in-up delay-200">
      Shop Now
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
      </svg>
    </a>
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

/* Delay classes for staggered animations */
.delay-100 {
  transition-delay: 0.1s;
}

.delay-200 {
  transition-delay: 0.2s;
}

.delay-300 {
  transition-delay: 0.3s;
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

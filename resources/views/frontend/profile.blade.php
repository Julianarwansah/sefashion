@extends('layouts_public.app')

@section('title', 'Profile • SeFashion')

@section('content')
{{-- HERO BANNER WITH PROFILE TEXT --}}
<section class="relative h-[40vh] min-h-[300px] w-full">
  {{-- background image --}}
  <div
    class="absolute inset-0 bg-center bg-cover"
    style="background-image:url('{{ asset('images/hero-rack.jpg') }}');">
  </div>

  {{-- dark overlay --}}
  <div class="absolute inset-0 bg-black/40"></div>

  {{-- Centered Profile Text --}}
  <div class="relative z-10 h-full flex items-center justify-center">
    <div class="text-center">
      <h1 class="text-white text-5xl sm:text-6xl font-extrabold fade-in-up">
        My Profile
      </h1>
      <p class="text-white/80 mt-4 text-lg fade-in-up delay-100">Manage your account information</p>
    </div>
  </div>
</section>

{{-- PROFILE SECTION --}}
<section class="py-16 sm:py-24 bg-white">
  <div class="max-w-4xl mx-auto px-4 sm:px-6">
    <div class="grid lg:grid-cols-3 gap-8 lg:gap-12">
      {{-- Sidebar Navigation --}}
      <div class="lg:col-span-1 fade-in-left">
        <div class="bg-gray-50 rounded-2xl p-6 sticky top-6">
          <div class="text-center mb-6">
            <div class="w-20 h-20 bg-gray-300 rounded-full mx-auto mb-4 flex items-center justify-center">
              <svg class="w-10 h-10 text-gray-500" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 12a5 5 0 1 1 0-10 5 5 0 0 1 0 10zm0-2a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm7 11a1 1 0 0 1-2 0v-2a3 3 0 0 0-3-3H10a3 3 0 0 0-3 3v2a1 1 0 0 1-2 0v-2a5 5 0 0 1 5-5h4a5 5 0 0 1 5 5v2z"/>
              </svg>
            </div>
            <h3 class="font-semibold text-gray-900">{{ Auth::guard('customer')->user()->nama }}</h3>
            <p class="text-gray-600 text-sm">{{ Auth::guard('customer')->user()->email }}</p>
          </div>
          
          <nav class="space-y-2">
            <a href="#" class="flex items-center gap-3 px-4 py-3 text-gray-600 hover:bg-gray-100 rounded-lg transition">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
              </svg>
              Account Overview
            </a>
            <a href="{{ route('profile') }}" class="flex items-center gap-3 px-4 py-3 bg-gray-900 text-white rounded-lg transition">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
              </svg>
              Profile Settings
            </a>
            <a href="#" class="flex items-center gap-3 px-4 py-3 text-gray-600 hover:bg-gray-100 rounded-lg transition">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
              </svg>
              My Orders
            </a>
            <form method="POST" action="{{ route('logout') }}" class="pt-4 border-t border-gray-200">
              @csrf
              <button type="submit" class="flex items-center gap-3 px-4 py-3 text-red-600 hover:bg-red-50 rounded-lg transition w-full">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Logout
              </button>
            </form>
          </nav>
        </div>
      </div>

      {{-- Profile Form --}}
      <div class="lg:col-span-2 fade-in-right">
        <div class="bg-gray-50 rounded-2xl p-8">
          <h3 class="text-2xl font-bold text-gray-900 mb-6">Profile Settings</h3>

          @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
              {{ session('success') }}
            </div>
          @endif

          @if($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
              <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <form method="POST" action="{{ route('profile.update') }}" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Name --}}
            <div>
              <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
              <input type="text"
                     id="nama"
                     name="nama"
                     value="{{ old('nama', Auth::guard('customer')->user()->nama) }}"
                     class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-900/10 focus:border-gray-900"
                     placeholder="Your full name"
                     required>
            </div>

            {{-- Email --}}
            <div>
              <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
              <input type="email"
                     id="email"
                     name="email"
                     value="{{ old('email', Auth::guard('customer')->user()->email) }}"
                     class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-900/10 focus:border-gray-900"
                     placeholder="your@email.com"
                     required>
            </div>

            {{-- Phone Number --}}
            <div>
              <label for="no_hp" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
              <input type="tel"
                     id="no_hp"
                     name="no_hp"
                     value="{{ old('no_hp', Auth::guard('customer')->user()->no_hp) }}"
                     class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-900/10 focus:border-gray-900"
                     placeholder="081234567890">
            </div>

            {{-- Address --}}
            <div>
              <label for="alamat" class="block text-sm font-medium text-gray-700 mb-2">Address</label>
              <textarea id="alamat"
                        name="alamat"
                        rows="4"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-900/10 focus:border-gray-900 resize-none"
                        placeholder="Your complete address">{{ old('alamat', Auth::guard('customer')->user()->alamat) }}</textarea>
            </div>

            {{-- Password Section --}}
            <div class="pt-6 border-t border-gray-200">
              <h4 class="text-lg font-semibold text-gray-900 mb-4">Change Password</h4>
              
              {{-- Current Password --}}
              <div class="mb-4">
                <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                <input type="password"
                       id="current_password"
                       name="current_password"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-900/10 focus:border-gray-900"
                       placeholder="Enter current password">
              </div>

              {{-- New Password --}}
              <div class="mb-4">
                <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                <input type="password"
                       id="new_password"
                       name="new_password"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-900/10 focus:border-gray-900"
                       placeholder="Enter new password">
              </div>

              {{-- Confirm New Password --}}
              <div>
                <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                <input type="password"
                       id="new_password_confirmation"
                       name="new_password_confirmation"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-900/10 focus:border-gray-900"
                       placeholder="Confirm new password">
              </div>
            </div>

            {{-- Submit Button --}}
            <div class="pt-6">
              <button type="submit"
                      class="px-8 py-3 bg-gray-900 text-white rounded-lg hover:bg-black transition font-medium">
                Update Profile
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
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

.delay-100 {
  transition-delay: 0.1s;
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
@endsection
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'purple-custom': '#52307c',
                        'yellow-custom': '#ffd400',
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');
        body { font-family: 'Inter', sans-serif; }
        .glass { backdrop-filter: blur(10px); }
        
        /* Custom styles for sidebar menu */
        .active-menu {
            background: linear-gradient(to right, #ec4899, #eab308);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            transform: scale(1.05);
            color: white !important;
        }
        
        .active-menu svg {
            color: white !important;
        }
        
        .inactive-menu {
            color: rgb(216 180 254);
            transition: all 0.3s ease;
        }
        
        .inactive-menu:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }
        
        .inactive-menu:hover svg {
            color: white;
        }
        /* Tambahkan di bagian style */
.dropdown-enter {
    opacity: 0;
    transform: translateY(-10px);
}

.dropdown-enter-active {
    opacity: 1;
    transform: translateY(0);
    transition: all 0.3s ease;
}

/* Ensure dropdown appears above other content */
.relative {
    position: relative;
}

.z-50 {
    z-index: 50;
}
    </style>
    @stack('styles')
</head>
<body class="bg-gradient-to-br from-purple-50 via-pink-50 to-yellow-50">
    <div class="flex h-screen overflow-hidden">
        @include('layouts.sidebar')
        
        <div class="flex-1 flex flex-col overflow-hidden">
            @include('layouts.navbar')
            
            <main class="flex-1 overflow-y-auto p-4 lg:p-8">
                @yield('content')
            </main>
        </div>
    </div>

    @include('layouts.footer')
    
    <!-- Scripts -->
    <script>
        // Toggle sidebar function
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const navTexts = document.querySelectorAll('.nav-text');
            const logoSection = document.getElementById('logo-section');
            
            if (sidebar.classList.contains('w-72')) {
                sidebar.classList.remove('w-72');
                sidebar.classList.add('w-20');
                navTexts.forEach(el => el.style.display = 'none');
                if (logoSection) logoSection.style.display = 'none';
            } else {
                sidebar.classList.remove('w-20');
                sidebar.classList.add('w-72');
                setTimeout(() => {
                    navTexts.forEach(el => el.style.display = 'block');
                    if (logoSection) logoSection.style.display = 'flex';
                }, 150);
            }
        }

        // Set active menu based on current URL
        function setActiveMenu() {
            const currentPath = window.location.pathname;
            const navItems = document.querySelectorAll('.nav-item');
            
            navItems.forEach(item => {
                // Reset semua item ke state inactive
                item.classList.remove('active-menu');
                item.classList.add('inactive-menu');
                
                // Hapus kelas Tailwind yang mungkin tertinggal
                item.classList.remove('bg-gradient-to-r', 'from-pink-500', 'to-yellow-400', 'shadow-lg', 'transform', 'scale-105', 'text-white');
                item.classList.add('text-purple-200', 'hover:bg-white/10', 'hover:text-white');
                
                // Cek href dari item
                const href = item.getAttribute('href');
                if (href) {
                    // Normalisasi path untuk perbandingan
                    const itemPath = href.replace(/^https?:\/\/[^\/]+/, ''); // Hapus domain jika ada
                    
                    // Untuk route yang lebih spesifik, gunakan exact match
                    if (currentPath === itemPath) {
                        item.classList.remove('inactive-menu');
                        item.classList.add('active-menu');
                    }
                    // Untuk parent routes, gunakan startsWith
                    else if (currentPath.startsWith(itemPath) && itemPath !== '/') {
                        item.classList.remove('inactive-menu');
                        item.classList.add('active-menu');
                    }
                }
            });
        }

        // Initialize when page loads
        document.addEventListener('DOMContentLoaded', function() {
            setActiveMenu();
        });

        // Re-run when navigating (untuk SPA-like behavior)
        document.addEventListener('click', function(e) {
            if (e.target.closest('.nav-item')) {
                setTimeout(setActiveMenu, 100);
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>
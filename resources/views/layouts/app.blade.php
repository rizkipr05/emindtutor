<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'eMindTutor')</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo1.png') }}">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Animasi show/hide */
        #mainHeader {
            will-change: transform, opacity;
        }
        .sticky-show {
            transform: translateY(0);
            opacity: 1;
        }
        .sticky-hide {
            transform: translateY(-100%);
            opacity: 0;
        }
        
        /* Smooth transitions untuk mobile menu */
        #mobileMenu {
            transition: all 0.3s ease-in-out;
        }
        
        /* Ensure proper spacing on mobile */
        @media (max-width: 768px) {
            #mainHeader {
                top: 1rem;
                left: 1rem;
                right: 1rem;
            }
        }
    </style>
</head>
<body class="bg-white">
    {{-- Loading Animation --}}
    <div id="loading-animation" class="fixed inset-0 bg-white/75 flex items-center justify-center z-50">
        <img src="{{ asset('images/coba.gif') }}" alt="Loading..." class="h-40 w-40">
    </div>

    <div id="app" class="backdrop-blur-md">
        <div class="bg-gradient-to-b from-amber-600 via-amber-800 to-[#030F4A] text-white font-sans min-h-screen">
            {{-- Header --}}
            @include('components.guest.header')

            {{-- Konten: padding top akan di-set otomatis via JS sesuai tinggi header --}}
            <main id="pageContent">
                @yield('content')
            </main>
        </div>
        @include('components.guest.footer')
    </div>

    {{-- Script --}}
    <script>
        // Loading kecil biar halus
        window.addEventListener('load', function () {
            setTimeout(function () {
                const loader = document.getElementById('loading-animation');
                if (loader) loader.style.display = 'none';
                document.getElementById('app')?.classList.remove('hidden');
            }, 1000);
        });

        // Smart Sticky Header with Mobile Support
        (function () {
            const header = document.getElementById('mainHeader');
            const pageContent = document.getElementById('pageContent');
            const mobileMenu = document.getElementById('mobileMenu');
            
            if (!header || !pageContent) return;

            // Set padding-top konten = tinggi header + gap
            function adjustContentOffset() {
                const rect = header.getBoundingClientRect();
                const topGap = window.innerWidth < 768 ? 8 : 16; // Smaller gap on mobile
                pageContent.style.paddingTop = (rect.height + topGap) + 'px';
            }
            
            adjustContentOffset();
            window.addEventListener('resize', adjustContentOffset);

            // Scroll behavior
            let lastScroll = window.pageYOffset || 0;
            let ticking = false;
            const threshold = 12;

            // Pastikan awalnya tampil
            header.classList.remove('sticky-hide');
            header.classList.add('sticky-show');

            function updateOnScroll() {
                const y = window.pageYOffset || 0;

                if (Math.abs(y - lastScroll) < threshold) {
                    ticking = false;
                    return;
                }

                // Close mobile menu on scroll
                if (mobileMenu && !mobileMenu.classList.contains('hidden')) {
                    mobileMenu.classList.add('hidden');
                    document.getElementById('menuIcon')?.classList.remove('hidden');
                    document.getElementById('closeIcon')?.classList.add('hidden');
                }

                if (y > lastScroll && y > 80) {
                    // Scroll turun
                    header.classList.remove('sticky-show');
                    header.classList.add('sticky-hide');
                } else {
                    // Scroll naik
                    header.classList.remove('sticky-hide');
                    header.classList.add('sticky-show');
                }

                lastScroll = y;
                ticking = false;
            }

            window.addEventListener('scroll', () => {
                if (!ticking) {
                    window.requestAnimationFrame(updateOnScroll);
                    ticking = true;
                }
            }, { passive: true });

            // Close mobile menu when clicking outside
            document.addEventListener('click', function(event) {
                if (mobileMenu && !mobileMenu.classList.contains('hidden')) {
                    const isClickInsideMenu = header.contains(event.target);
                    if (!isClickInsideMenu) {
                        mobileMenu.classList.add('hidden');
                        document.getElementById('menuIcon')?.classList.remove('hidden');
                        document.getElementById('closeIcon')?.classList.add('hidden');
                    }
                }
            });

            // Keyboard navigation support
            document.addEventListener('keydown', function(e) {
                // Tampilkan header saat menekan tombol navigasi
                if (['Home', 'End', 'PageUp', 'PageDown', 'ArrowUp', 'ArrowDown'].includes(e.key)) {
                    showHeader();
                    if (e.key === 'Home' || e.key === 'ArrowUp') {
                        // Jangan auto-hide jika user navigasi ke atas
                        if (hideTimer) {
                            clearTimeout(hideTimer);
                            hideTimer = null;
                        }
                    }
                }
            });
        })();
    </script>
</body>
</html>
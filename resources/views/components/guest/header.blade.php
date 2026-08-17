@php
    $basePath = rtrim(request()->getBaseUrl(), '/');
    $origin = request()->getSchemeAndHttpHost();
    $loginUrl = $origin . (($basePath ?: '') . '/login');
    $signupUrl = $origin . (($basePath ?: '') . '/daftar');
@endphp

{{-- Navbar --}}
<div
    id="mainHeader"
    class="fixed top-4 left-4 right-4 md:left-20 md:right-20 z-50 transition-transform duration-300 ease-out sticky-show">
    <header class="bg-white/95 backdrop-blur-md text-black shadow-xl rounded-2xl">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            {{-- Main Header --}}
            <div class="flex justify-between items-center py-4">
                {{-- Logo --}}
                <div class="flex items-center space-x-2">
                <img src="{{ asset('images/logo.png') }}" alt="eMindTutor" class="w-12 h-12">
                    <span class="font-bold text-xl sm:text-2xl">eMindTutor</span>
                </div>
                
                {{-- Desktop Navigation --}}
                <nav class="hidden md:flex items-center space-x-6 text-sm font-medium">
                    <a href="{{ route('compro.index') }}"
                        class="px-3 py-1 rounded-full {{ request()->routeIs('compro.index') ? 'bg-amber-600 text-white' : 'hover:text-sky-700' }}">
                        {{ __('compro/header.home') }}
                    </a>
                    <a href="{{ route('compro.about.index') }}" 
                       class="px-3 py-1 rounded-full {{ request()->routeIs('compro.about.index') ? 'bg-amber-600 text-white' : 'hover:text-sky-700' }}">
                       {{ __('compro/header.about') }}
                    </a>
                    <a href="{{ route('compro.pricing.index') }}" 
                       class="px-3 py-1 rounded-full {{ request()->routeIs('compro.pricing.index') ? 'bg-amber-600 text-white' : 'hover:text-sky-700' }}">
                       {{ __('compro/header.pricing') }}
                    </a>
                    <!-- <a href="{{ route('compro.companies.index') }}" 
                       class="px-3 py-1 rounded-full {{ request()->routeIs('compro.companies.*') ? 'bg-amber-600 text-white' : 'hover:text-sky-700' }}">
                       {{ __('compro/header.company') }}
                    </a> -->
                    
                    {{-- Language Switch --}}
                    <div class="flex items-center space-x-2 pl-4 border-l border-gradient-to-b from-cyan-200 to-orange-200">
                        <a href="{{ route('set.language', 'en') }}"
                           class="font-medium px-3 py-1 rounded-full transition-all duration-200 text-xs
                                   {{ app()->getLocale() === 'en' ? 'bg-gradient-to-r from-orange-500 to-orange-600 text-white shadow-md' : 'text-slate-600 hover:text-orange-600 hover:bg-white' }}">
                            EN
                        </a>
                        <span class="text-gray-400">|</span>
                        <a href="{{ route('set.language', 'my') }}"
                           class="font-medium px-3 py-1 rounded-full transition-all duration-200 text-xs
                                   {{ app()->getLocale() === 'my' ? 'bg-gradient-to-r from-orange-500 to-orange-600 text-white shadow-md' : 'text-slate-600 hover:text-orange-600 hover:bg-white' }}">
                            MY
                        </a>
                    </div>
                </nav>
                
                {{-- Desktop Auth Buttons --}}
                <div class="hidden md:flex items-center space-x-3">
                    <form action="{{ $loginUrl }}" method="GET" class="relative z-[70]">
                        <button type="submit"
                           class="cursor-pointer text-sm font-medium text-slate-700 hover:text-cyan-700 transition-all duration-200 px-3 py-2 rounded-full hover:bg-cyan-50">
                           {{ __('compro/header.login') }}
                        </button>
                    </form>
                    <form action="{{ $signupUrl }}" method="GET" class="relative z-[70]">
                        <button type="submit"
                           class="cursor-pointer bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white px-6 py-2 rounded-full text-sm font-semibold transition-all duration-200 shadow-lg hover:shadow-orange-500/25 hover:scale-105 transform">
                            {{ __('compro/header.signup') }}
                        </button>
                    </form>
                </div>
                
                {{-- Mobile Menu Button --}}
                <button id="mobileMenuBtn" class="md:hidden p-2 rounded-md text-gray-700 hover:bg-gray-100 focus:outline-none transition-colors" aria-label="Toggle menu">
                    <svg id="menuIcon" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg id="closeIcon" class="h-6 w-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            {{-- Mobile Menu --}}
            <div id="mobileMenu" class="md:hidden border-t border-gray-200 py-4 hidden">
                {{-- Mobile Navigation --}}
                <nav class="flex flex-col space-y-3 mb-4">
                    <a href="{{ route('compro.index') }}"
                       class="px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('compro.index') ? 'bg-amber-600 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                        {{ __('compro/header.home') }}
                    </a>
                    <a href="{{ route('compro.about.index') }}" 
                       class="px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('compro.about.index') ? 'bg-amber-600 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                       {{ __('compro/header.about') }}
                    </a>
                    <a href="{{ route('compro.pricing.index') }}" 
                       class="px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('compro.pricing.index') ? 'bg-amber-600 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                       {{ __('compro/header.pricing') }}
                    </a>
                    <a href="{{ route('compro.companies.index') }}" 
                       class="px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('compro.companies.*') ? 'bg-amber-600 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                       {{ __('compro/header.company') }}
                    </a>
                </nav>
                
                {{-- Mobile Language Switch --}}
                <div class="flex items-center justify-center space-x-4 mb-4 py-2 border-t border-b border-gray-200">
                    <span class="text-sm text-gray-600">{{ __('compro/header.language') }}</span>
                    <a href="{{ route('set.language', 'en') }}"
                       class="font-medium px-3 py-1 rounded {{ app()->getLocale() === 'en' ? 'bg-sky-600 text-white' : 'text-gray-700 hover:text-sky-700 hover:underline' }}">
                        EN
                    </a>
                    <a href="{{ route('set.language', 'my') }}"
                       class="font-medium px-3 py-1 rounded {{ app()->getLocale() === 'my' ? 'bg-sky-600 text-white' : 'text-gray-700 hover:text-sky-700 hover:underline' }}">
                        MY
                    </a>
                </div>
                
                {{-- Mobile Auth Buttons --}}
                <div class="flex flex-col space-y-3">
                    <form action="{{ $loginUrl }}" method="GET" class="relative z-[70]">
                        <button type="submit"
                           class="w-full cursor-pointer text-center px-4 py-2 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-100 transition">
                            {{ __('compro/header.login') }}
                        </button>
                    </form>
                    <form action="{{ $signupUrl }}" method="GET" class="relative z-[70]">
                        <button type="submit"
                           class="w-full cursor-pointer text-center bg-gradient-to-r from-orange-500 to-orange-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:from-orange-600 hover:to-orange-700">
                            {{ __('compro/header.signup') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const mobileMenu = document.getElementById('mobileMenu');
    const menuIcon = document.getElementById('menuIcon');
    const closeIcon = document.getElementById('closeIcon');
    
    if (mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener('click', function() {
            const isHidden = mobileMenu.classList.contains('hidden');
            
            if (isHidden) {
                // Show menu
                mobileMenu.classList.remove('hidden');
                menuIcon.classList.add('hidden');
                closeIcon.classList.remove('hidden');
            } else {
                // Hide menu
                mobileMenu.classList.add('hidden');
                menuIcon.classList.remove('hidden');
                closeIcon.classList.add('hidden');
            }
        });
        
        // Close menu when clicking on menu links
        const mobileLinks = mobileMenu.querySelectorAll('a');
        mobileLinks.forEach(link => {
            link.addEventListener('click', function() {
                mobileMenu.classList.add('hidden');
                menuIcon.classList.remove('hidden');
                closeIcon.classList.add('hidden');
            });
        });
    }
});
</script>

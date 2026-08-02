<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'EMindTutor')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo1.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-slate-50" x-data="{ sidebarOpen: false }">
    @if(session('error'))
    <div 
        x-data="{ show: true }" 
        x-init="setTimeout(() => show = false, 4000)" 
        x-show="show"
        class="fixed inset-0 flex items-center justify-center z-50"
    >
        <div class="bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded-lg shadow-lg w-[90%] max-w-md text-center">
            <p class="font-semibold">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    <!-- Top Navbar -->
    <header class="bg-gradient-to-r from-teal-500 to-blue-800 text-white px-6 py-4 flex justify-between items-center shadow">
        <div class="flex items-center space-x-4">
            <!-- Sidebar Toggle Button (Mobile) -->
            <button @click="sidebarOpen = !sidebarOpen" class="md:hidden focus:outline-none">
                <!-- Icon Hamburger -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            <a href="{{ route('dashboard.index') }}" class="text-xl font-bold">EMindTutor</a>
        </div>
        
        <div class="flex items-center space-x-4">

            {{-- Language Switch --}}
            <div class="flex items-center space-x-2 pl-4">
                <a href="{{ route('set.language', 'en') }}"
                class="font-medium px-2 py-1 rounded text-white
                        {{ app()->getLocale() === 'en' ? 'bg-sky-600' : 'hover:text-sky-300 hover:underline' }}">
                    EN
                </a>
                <span class="text-gray-400">|</span>
                <a href="{{ route('set.language', 'my') }}"
                class="font-medium px-2 py-1 rounded 
                        {{ app()->getLocale() === 'my' ? 'bg-sky-600' : 'hover:text-sky-300 hover:underline' }}">
                    MY
                </a>
            </div>

            <!-- User Dropdown -->
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="focus:outline-none">
                    <svg  xmlns="http://www.w3.org/2000/svg" width="24" height="24"  
                                    fill="currentColor" viewBox="0 0 24 24" >
                                    <!--Boxicons v3.0 https://boxicons.com | License  https://docs.boxicons.com/free-->
                                    <path d="M12 12c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5m0-8c1.65 0 3 1.35 3 3s-1.35 3-3 3-3-1.35-3-3 1.35-3 3-3M4 22h16c.55 0 1-.45 1-1v-1c0-3.86-3.14-7-7-7h-4c-3.86 0-7 3.14-7 7v1c0 .55.45 1 1 1m6-7h4c2.76 0 5 2.24 5 5H5c0-2.76 2.24-5 5-5"></path>
                                </svg>
                </button>
                

                <!-- Dropdown Menu -->
                <div x-show="open" @click.away="open = false"
                    class="absolute right-0 mt-2 w-48 bg-white text-gray-700 rounded-lg shadow-lg overflow-hidden z-20"
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:leave="transition ease-in duration-75">
                    
                    <a href="{{ route('logout') }}" 
                       class="block px-4 py-4 hover:bg-gray-100 font-medium rounded text-white bg-sky-600 hover:text-sky-600 hover:underline">
                        Logout
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Sidebar + Main -->
    <div class="flex min-h-screen relative">

        <!-- Overlay (muncul di mobile ketika sidebar open) -->
        <div 
            x-show="sidebarOpen" 
            @click="sidebarOpen = false"
            class="fixed inset-0 bg-black/50 bg-opacity-50 z-20 md:hidden"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
        ></div>

        <!-- Sidebar -->
        <aside 
            class="fixed inset-y-0 left-0 w-64 bg-white p-6 shadow-md transform md:relative md:translate-x-0 transition-transform duration-200 ease-in-out z-30"
            :class="{ '-translate-x-full': !sidebarOpen, 'translate-x-0': sidebarOpen }">
            <h2 class="text-teal-600 font-bold text-lg mb-6">{{ __('layouts/superadmin.main_navigation') }}</h2>
            <nav class="space-y-3">
                <a href="{{ route('dashboard.index') }}" class="block p-2 rounded {{ request()->routeIs('dashboard.index') ? 'bg-teal-100 text-teal-700 font-semibold' : 'hover:bg-gray-100' }}">{{ __('layouts/superadmin.dashboard') }}</a>
                <a href="{{ route('companies.index') }}" class="block p-2 rounded {{ request()->routeIs('companies.*') ? 'bg-teal-100 text-teal-700 font-semibold' : 'hover:bg-gray-100' }}">{{ __('layouts/superadmin.companies') }}</a>
                <a href="{{ route('subscription-packages.index') }}" class="block p-2 rounded {{ request()->routeIs('subscription-packages.*') ? 'bg-teal-100 text-teal-700 font-semibold' : 'hover:bg-gray-100' }}">{{ __('layouts/superadmin.subscription_packages') }}</a>
                <a href="{{ route('invoices.index') }}" class="block p-2 rounded {{ request()->routeIs('invoices.*') ? 'bg-teal-100 text-teal-700 font-semibold' : 'hover:bg-gray-100' }}">{{ __('layouts/superadmin.invoices') }}</a>
                <a href="{{ route('superadmin.stats.index') }}" class="block p-2 rounded {{ request()->routeIs('superadmin.stats.index') ? 'bg-teal-100 text-teal-700 font-semibold' : 'hover:bg-gray-100' }}">{{ __('layouts/superadmin.stats') }}</a>
                <a href="{{ route('superadmin.staff.index') }}" class="block p-2 rounded {{ request()->routeIs('superadmin.staff.index') ? 'bg-teal-100 text-teal-700 font-semibold' : 'hover:bg-gray-100' }}">{{ __('layouts/superadmin.staff') }}</a>
                <a href="{{ route('superadmin.settings.index') }}" class="block p-2 rounded {{ request()->routeIs('superadmin.settings.*') ? 'bg-teal-100 text-teal-700 font-semibold' : 'hover:bg-gray-100' }}">{{ __('layouts/superadmin.settings') }}</a>
                <a href="{{ route('superadmin.payment-gateway.edit') }}" class="block p-2 rounded {{ request()->routeIs('superadmin.payment-gateway.*') ? 'bg-teal-100 text-teal-700 font-semibold' : 'hover:bg-gray-100' }}">Payment Gateway</a>
                @php
                    $unreadCount = \DB::table('announcement_user')
                        ->where('user_id', auth()->id())
                        ->where('is_read', false)
                        ->count();
                @endphp

                <a href="{{ route('broadcasts.index') }}" 
                class="block p-2 {{ request()->routeIs('broadcasts.*') ? 'bg-teal-100 text-teal-700 font-semibold' : 'hover:bg-gray-100' }} rounded">
                    {{ __("layouts/superadmin.communications") }}
                    
                    @if($unreadCount > 0)
                        <span class="text-green-600 text-xs ml-2">
                            {{ __("layouts/admin.new") }} ({{ $unreadCount }})
                        </span>
                    @endif
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-4 md:p-8 overflow-x-hidden">
            {{-- Konten dashboard selanjutnya disini --}}
            @yield('content')
        </main>
    </div>
</body>
</html>

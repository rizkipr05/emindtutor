<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo1.png') }}">

    <title>@yield('title', 'EMindTutor')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-slate-50">
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

    <div class="flex items-center justify-center bg-gradient-to-br from-teal-500 to-blue-800">
    @yield('content')
    </div>
</body>
</html>

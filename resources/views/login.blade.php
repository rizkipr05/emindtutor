@extends('layouts.auth')

@section('title', __('compro/auth.login_title'))

@section('content')
    @php
        $basePath = rtrim(request()->getBaseUrl(), '/');
        $signupUrl = ($basePath ?: '') . '/daftar';
    @endphp
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="w-full max-w-2xl bg-white rounded-2xl shadow-lg p-12">

            <!-- Logo -->
            <a href="{{ route('compro.index') }}" class="flex justify-center mb-3">
                <img src="{{ asset('images/logo.png') }}" alt="eMindTutor" class="h-20">
            </a>

            <!-- Header -->
            <h2 class="text-3xl font-bold text-center mb-8 text-blue-900">
                {{ __('compro/auth.login_header') }}
            </h2>

            <!-- Error Handling -->
            @if ($errors->any())
                <div class="mb-6">
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                        <ul class="list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6">
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg text-sm">
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            <!-- Form -->
            <form action="{{ route('login_post') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">
                        {{ __('compro/auth.login_email') }}
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                        class="w-full mt-2 px-4 py-3 border rounded-xl shadow-sm focus:ring-2 focus:ring-blue-900 focus:outline-none @error('email') border-red-500 @enderror"
                        required>
                    @error('email')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">
                        {{ __('compro/auth.login_password') }}
                    </label>
                    <input type="password" id="password" name="password"
                        class="w-full mt-2 px-4 py-3 border rounded-xl shadow-sm focus:ring-2 focus:ring-blue-900 focus:outline-none @error('password') border-red-500 @enderror">
                    @error('password')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit -->
                <button type="submit"
                    class="w-full bg-blue-900 hover:bg-blue-800 text-white font-semibold py-3 rounded-full transition">
                    {{ __('compro/auth.login_submit') }}
                </button>

                <div class="relative flex items-center justify-center my-5 border-t border-gray-300">
                    <span class="bg-white px-2 mt-[-10px] text-gray-500 text-sm">ATAU</span>
                </div>

                <!-- Biometric Login Button -->
                <button type="button" id="btn-login-capjari"
                    class="group relative w-full overflow-hidden rounded-2xl bg-gradient-to-r from-emerald-500 via-teal-500 to-cyan-500 px-5 py-4 text-white shadow-lg shadow-emerald-900/20 transition duration-300 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-emerald-900/30 focus:outline-none focus:ring-4 focus:ring-emerald-200">
                    <span class="absolute inset-0 bg-white/0 transition group-hover:bg-white/10"></span>
                    <span class="relative flex items-center gap-4 text-left">
                        <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-white/20 ring-1 ring-white/30 backdrop-blur-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 11a2 2 0 1 0-2-2" />
                                <path d="M8.5 13.5A4.5 4.5 0 0 1 17 15v1" />
                                <path d="M7 10a5 5 0 1 1 10 0v2.5" />
                                <path d="M6 14.5V15a6 6 0 0 0 12 0v-2" />
                                <path d="M9.5 18A2.5 2.5 0 0 0 12 20a2.5 2.5 0 0 0 2.5-2" />
                            </svg>
                        </span>
                        <span class="flex-1">
                            <span class="block text-base font-semibold leading-tight">Login dengan Biometrik</span>
                            <span class="mt-1 block text-sm text-emerald-50/90">Masuk lebih cepat dengan cap jari pada peranti anda</span>
                        </span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 text-white/80 transition group-hover:translate-x-0.5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3 10a1 1 0 011-1h9.586L11.293 6.707a1 1 0 111.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L13.586 11H4a1 1 0 01-1-1z" clip-rule="evenodd" />
                        </svg>
                    </span>
                </button>
            </form>

            <!-- Register Link -->
            <p class="text-center text-sm text-gray-500 mt-6">
                Don't have an account?
                <a href="{{ $signupUrl }}" class="text-blue-900 font-semibold hover:underline">
                    {{ __('compro/auth.signup_link') }}
                </a>
            </p>
            <p class="text-center text-sm text-gray-500 mt-1">
                Forgot your password?
                <a href="{{ route('password.request') }}" class="text-blue-900 font-semibold hover:underline">
                    Reset Password
                </a>
            </p>
        </div>
    </div>

    @webauthnScripts

    <script>
        document.getElementById('btn-login-capjari').addEventListener('click', function () {
            const emailInput = document.getElementById('email');
            const email = emailInput.value.trim();
            emailInput.value = email;

            const webauthn = new WebAuthn((name, message) => {
                alert('Ralat Peranti: ' + message);
            });

            if (!webauthn.supported()) {
                alert('Browser atau peranti anda tidak menyokong log masuk Biometrik (WebAuthn).');
                return;
            }

            if (window.location.hostname !== 'localhost' && !window.isSecureContext) {
                alert('Log masuk Biometrik hanya dibenarkan melalui sambungan selamat (HTTPS) atau localhost.');
                return;
            }

            fetch('{{ route("webauthn.login.options") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(email ? { email: email } : {})
            })
            .then(res => {
                if (!res.ok) {
                    return res.json().then(err => { throw err; });
                }
                return res.json();
            })
            .then(publicKey => {
                webauthn.sign(publicKey, function (credentials) {
                    fetch('{{ route("webauthn.login") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify(email ? { email: email, data: credentials } : { data: credentials })
                    })
                    .then(res => res.json())
                    .then(response => {
                        if (response.success) {
                            window.location.href = response.redirect;
                        } else {
                            alert(response.error || response.message || 'Log masuk webauthn gagal.');
                        }
                    })
                    .catch(e => alert('Pengesahan log masuk cap jari menghadapi ralat.'));
                });
            })
            .catch(error => {
                console.error(error);
                let msg = error.message || error.error || 'Pelayan gagal dihubungkan. Pastikan database MySQL berjalan.';
                alert('Ralat Biometrik: ' + msg);
            });
        });
    </script>
@endsection

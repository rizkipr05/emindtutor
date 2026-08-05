@extends('layouts.admin')

@section('title', 'Dashboard | EMindTutor')

@section('content')

@if($showReviewPopup)
    @include('admin.reviews.popup')
@endif

<div class="min-h-screen">
    <div class="container mx-auto sm:px-4 lg:px-3 sm:py-8 lg:py-3">
        <!-- Header Section -->
        <div class="text-center mb-8 sm:mb-12">
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent mb-3">
                {{ $company->user->name }}
            </h1>
            <div class="w-16 sm:w-20 lg:w-24 h-1 bg-gradient-to-r from-indigo-500 to-purple-500 mx-auto rounded-full"></div>
        </div>

        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-900 mb-4">{{ __('admin/dashboard.summary_heading') }}</h2>
            <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
                <div class="bg-white rounded-xl shadow p-5">
                    <p class="text-sm text-gray-500">{{ __('admin/dashboard.summary_courses') }}</p>
                    <p class="text-3xl font-bold text-indigo-600">{{ $summary['courses'] }}</p>
                </div>
                <div class="bg-white rounded-xl shadow p-5">
                    <p class="text-sm text-gray-500">{{ __('admin/dashboard.summary_teachers') }}</p>
                    <p class="text-3xl font-bold text-emerald-600">{{ $summary['teachers'] }}</p>
                </div>
                <div class="bg-white rounded-xl shadow p-5">
                    <p class="text-sm text-gray-500">{{ __('admin/dashboard.summary_students') }}</p>
                    <p class="text-3xl font-bold text-amber-600">{{ $summary['students'] }}</p>
                </div>
                <div class="bg-white rounded-xl shadow p-5">
                    <p class="text-sm text-gray-500">{{ __('admin/dashboard.summary_staff') }}</p>
                    <p class="text-3xl font-bold text-violet-600">{{ $summary['staff'] }}</p>
                </div>
                <div class="bg-white rounded-xl shadow p-5">
                    <p class="text-sm text-gray-500">{{ __('admin/dashboard.summary_announcements') }}</p>
                    <p class="text-3xl font-bold text-rose-600">{{ $summary['announcements'] }}</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 lg:gap-8 max-w-6xl mx-auto">

            <!-- Maklumat Syarikat -->
            <div class="group bg-white/80 backdrop-blur-sm rounded-xl sm:rounded-2xl shadow-lg hover:shadow-2xl border border-white/50 p-4 sm:p-6 lg:p-8 transition-all duration-300 hover:scale-[1.02] lg:hover:scale-105">
                <div class="flex items-center mb-4 sm:mb-6">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg sm:rounded-xl flex items-center justify-center mr-3 sm:mr-4 group-hover:rotate-6 transition-transform duration-300 overflow-hidden">

                        @if ($company->logo_syarikat)
                            <img
                                src="{{ asset('uploads/' . $company->logo_syarikat) }}"
                                alt="Company Logo"
                                class="w-full h-full object-cover"
                            >
                        @else
                            <div class="w-full h-full bg-gradient-to-r from-blue-500 to-cyan-500 flex items-center justify-center">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                        @endif

                    </div>
                    <h2 class="text-lg sm:text-xl font-bold text-gray-800">{{ __('admin/dashboard.company_section') }}</h2>
                </div>

                <div class="space-y-3 sm:space-y-4">
                    <div class="border-l-4 border-blue-400 pl-4 py-2">
                        <span class="font-semibold text-gray-600 text-sm sm:text-base block mb-1">{{ __('admin/dashboard.company_name') }}:</span>
                        <span class="text-gray-800 font-medium text-sm sm:text-base break-words">{{ $company->user->name }}</span>
                    </div>
                    <div class="border-l-4 border-cyan-400 pl-4 py-2">
                        <span class="font-semibold text-gray-600 text-sm sm:text-base block mb-1">{{ __('admin/dashboard.company_email') }}:</span>
                        <span class="text-gray-800 font-medium text-sm sm:text-base break-all">{{ $company->user->email }}</span>
                    </div>
                    <div class="border-l-4 border-indigo-400 pl-4 py-2">
                        <span class="font-semibold text-gray-600 text-sm sm:text-base block mb-1">{{ __('admin/dashboard.company_address') }}:</span>
                        <span class="text-gray-800 font-medium text-sm sm:text-base break-words">{{ $company->alamat }}</span>
                    </div>
                    <div class="border-l-4 border-purple-400 pl-4 py-2">
                        <span class="font-semibold text-gray-600 text-sm sm:text-base block mb-1">{{ __('admin/dashboard.company_phone') }}:</span>
                        <span class="text-gray-800 font-medium text-sm sm:text-base">{{ $company->no_telefon }}</span>
                    </div>
                    <a href="{{ route('admin.company.edit', $company->id) }}"
                        class="inline-block bg-gradient-to-r from-teal-500 to-cyan-500 text-white px-4 sm:px-6 py-2 sm:py-3 rounded-lg sm:rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 font-semibold text-sm sm:text-base">
                        {{ __('admin/dashboard.edit_company') }}
                    </a>

                    <a href="{{ route('admin.bank_details.index') }}"
                        class="inline-block bg-gradient-to-r from-teal-500 to-cyan-500 text-white px-4 sm:px-6 py-2 sm:py-3 rounded-lg sm:rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 font-semibold text-sm sm:text-base">
                        Bank Detail
                    </a>
                </div>
            </div>

            <!-- Nama Wakil Syarikat -->
            <div class="group bg-white/80 backdrop-blur-sm rounded-xl sm:rounded-2xl shadow-lg hover:shadow-2xl border border-white/50 p-4 sm:p-6 lg:p-8 transition-all duration-300 hover:scale-[1.02] lg:hover:scale-105">
                <div class="flex items-center mb-4 sm:mb-6">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-r from-emerald-500 to-teal-500 rounded-lg sm:rounded-xl flex items-center justify-center mr-3 sm:mr-4 group-hover:rotate-6 transition-transform duration-300">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h2 class="text-lg sm:text-xl font-bold text-gray-800">{{ __('admin/dashboard.representative_section') }}</h2>
                </div>

                <div class="space-y-3 sm:space-y-4">
                    <div class="border-l-4 border-emerald-400 pl-4 py-2">
                        <span class="font-semibold text-gray-600 text-sm sm:text-base block mb-1">{{ __('admin/dashboard.representative_name') }}:</span>
                        <span class="text-gray-800 font-medium text-sm sm:text-base break-words">{{ $company->nama_wakil_syarikat }}</span>
                    </div>
                    <div class="border-l-4 border-teal-400 pl-4 py-2">
                        <span class="font-semibold text-gray-600 text-sm sm:text-base block mb-1">{{ __('admin/dashboard.representative_phone') }}:</span>
                        <span class="text-gray-800 font-medium text-sm sm:text-base">{{ $company->pic_telefon }}</span>
                    </div>
                </div>
            </div>

            <!-- Langganan -->
            <div class="group bg-white/80 backdrop-blur-sm rounded-xl sm:rounded-2xl shadow-lg hover:shadow-2xl border border-white/50 p-4 sm:p-6 lg:p-8 transition-all duration-300 hover:scale-[1.02] lg:hover:scale-105">

                <div class="flex items-center mb-4 sm:mb-6">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-r from-orange-500 to-amber-500 rounded-lg sm:rounded-xl flex items-center justify-center mr-3 sm:mr-4 group-hover:rotate-6 transition-transform duration-300">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                        </svg>
                    </div>
                    <h2 class="text-lg sm:text-xl font-bold text-gray-800">{{ __('admin/dashboard.subscription_section') }}</h2>
                </div>

                {{-- Cek status company --}}
                @if (!$company->status)
                    <div class="text-center py-6">
                        <p class="text-red-600 font-semibold text-sm sm:text-base">
                            {{ __('admin/dashboard.company_frozen') }}
                        </p>
                    </div>
                @elseif ($subscription && $subscription->package)
                    @php
                        $progressPct = $totalDays > 0 ? round(($remainingDays / $totalDays) * 100) : 0;
                        $progressPct = min(100, max(0, $progressPct));
                        $barColor    = $remainingDays < 7  ? 'bg-red-500'
                                     : ($remainingDays < 30 ? 'bg-yellow-400' : 'bg-emerald-500');
                        $badgeColor  = $remainingDays < 7  ? 'bg-red-100 text-red-700 border-red-300'
                                     : ($remainingDays < 30 ? 'bg-yellow-100 text-yellow-700 border-yellow-300' : 'bg-emerald-100 text-emerald-700 border-emerald-300');
                    @endphp
                    <div class="space-y-3 sm:space-y-4">
                        {{-- Package name --}}
                        <div class="border-l-4 border-orange-400 pl-4 py-2">
                            <span class="font-semibold text-gray-600 text-sm sm:text-base block mb-1">{{ __('admin/dashboard.package') }}:</span>
                            <span class="text-gray-800 font-medium text-sm sm:text-base">{{ $subscription->package->nama_pakej }}</span>
                        </div>

                        {{-- Expiry date --}}
                        <div class="border-l-4 border-rose-400 pl-4 py-2">
                            <span class="font-semibold text-gray-600 text-sm sm:text-base block mb-1">Tarikh Tamat Langganan:</span>
                            <span class="text-gray-800 font-semibold text-sm sm:text-base">
                                {{ $expiryDate ? $expiryDate->translatedFormat('d F Y') : '-' }}
                            </span>
                        </div>

                        {{-- Progress bar + remaining days --}}
                        <div class="border-l-4 border-amber-400 pl-4 py-2">
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-semibold text-gray-600 text-sm sm:text-base">Sisa Masa:</span>
                                <span class="inline-block px-2.5 py-0.5 text-xs font-bold rounded-full border {{ $badgeColor }}">
                                    {{ $remainingDays }} {{ __('admin/dashboard.days') }}
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5 overflow-hidden">
                                <div class="{{ $barColor }} h-2.5 rounded-full transition-all duration-500"
                                     style="width: {{ $progressPct }}%"></div>
                            </div>
                            <p class="mt-1 text-xs text-gray-400">{{ $progressPct }}% masa langganan masih aktif</p>
                        </div>

                        {{-- Status --}}
                        <div class="border-l-4 border-yellow-400 pl-4 py-2">
                            <span class="font-semibold text-gray-600 text-sm sm:text-base block mb-1">{{ __('admin/dashboard.status') }}:</span>
                            <span class="inline-block px-3 py-1 text-xs sm:text-sm rounded-full font-semibold {{ $subscription->status == 'active' ? 'bg-green-100 text-green-700 border border-green-200' : 'bg-red-100 text-red-700 border border-red-200' }}">
                                {{ $subscription->status == 'active' ? __('admin/dashboard.active') : __('admin/dashboard.inactive') }}
                            </span>
                        </div>

                        {{-- Renew button if expiring soon --}}
                        @if ($remainingDays < 30)
                            <div class="pt-1">
                                <a href="{{ route('admin.subscribe.index') }}"
                                   class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-orange-500 to-amber-500 text-white rounded-xl shadow hover:shadow-lg transform hover:scale-105 transition-all duration-300 font-semibold text-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                    Perbaharui Langganan
                                </a>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="text-center py-4">
                        <div class="mb-4">
                            <svg class="w-12 h-12 sm:w-16 sm:h-16 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
                            </svg>
                        </div>
                        <p class="text-gray-500 italic mb-4 text-sm sm:text-base">{{ __('admin/dashboard.no_active_subscription') }}</p>
                        <a href="{{ route('admin.subscribe.index') }}"
                            class="inline-block bg-gradient-to-r from-teal-500 to-cyan-500 text-white px-4 sm:px-6 py-2 sm:py-3 rounded-lg sm:rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 font-semibold text-sm sm:text-base">
                            {{ __('admin/dashboard.subscribe_now') }}
                        </a>
                    </div>
                @endif
            </div>

            <!-- Kata Laluan & Keselamatan -->
            <div class="group bg-white/80 backdrop-blur-sm rounded-xl sm:rounded-2xl shadow-lg hover:shadow-2xl border border-white/50 p-4 sm:p-6 lg:p-8 transition-all duration-300 hover:scale-[1.02] lg:hover:scale-105">
                <div class="flex items-center mb-4 sm:mb-6">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-r from-rose-500 to-pink-500 rounded-lg sm:rounded-xl flex items-center justify-center mr-3 sm:mr-4 group-hover:rotate-6 transition-transform duration-300">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <h2 class="text-lg sm:text-xl font-bold text-gray-800">{{ __('admin/dashboard.password_section') }}</h2>
                </div>

                <div class="space-y-3 sm:space-y-4">
                    <div class="border-l-4 border-rose-400 pl-4 py-2">
                        <span class="font-semibold text-gray-600 text-sm sm:text-base block mb-1">{{ __('admin/dashboard.account_email') }}:</span>
                        <span class="text-gray-800 font-medium text-sm sm:text-base break-all">{{ $user->email }}</span>
                    </div>

                    <div class="pt-2">
                        <a href="{{ route('admin.maklumat.edit') }}" class="inline-flex items-center px-4 sm:px-6 py-2 sm:py-3 bg-gradient-to-r from-rose-500 to-pink-500 text-white rounded-lg sm:rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 font-semibold text-sm sm:text-base group">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 group-hover:rotate-12 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            {{ __('admin/dashboard.change_password') }}
                        </a>
                    </div>
                </div>
            </div>

            <!-- Log Masuk Cap Jari (Biometrik) -->
            <div class="group bg-white/80 backdrop-blur-sm rounded-xl sm:rounded-2xl shadow-lg hover:shadow-2xl border border-white/50 p-4 sm:p-6 lg:p-8 transition-all duration-300 hover:scale-[1.02] lg:hover:scale-105">
                <div class="flex items-center mb-4 sm:mb-6">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-r from-emerald-500 to-teal-500 rounded-lg sm:rounded-xl flex items-center justify-center mr-3 sm:mr-4 group-hover:rotate-6 transition-transform duration-300">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c1.657 0 3-1.343 3-3S13.657 5 12 5 9 6.343 9 8s1.343 3 3 3zm0 2c-2.67 0-8 1.337-8 4v2h16v-2c0-2.663-5.33-4-8-4z"></path>
                        </svg>
                    </div>
                    <h2 class="text-lg sm:text-xl font-bold text-gray-800">Log Masuk Cap Jari (Biometrik)</h2>
                </div>

                <div class="space-y-3 sm:space-y-4">
                    <p class="text-gray-600 text-sm sm:text-base">Daftarkan cap jari anda pada peranti ini untuk di log masuk pada masa akan datang tanpa memasukkan kata laluan.</p>

                    <div class="pt-2">
                        <button type="button" id="btn-register-capjari" class="inline-flex items-center px-4 sm:px-6 py-2 sm:py-3 bg-gradient-to-r from-emerald-500 to-teal-500 text-white rounded-lg sm:rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 font-semibold text-sm sm:text-base group">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 group-hover:rotate-12 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c1.657 0 3-1.343 3-3S13.657 5 12 5 9 6.343 9 8s1.343 3 3 3zm0 2c-2.67 0-8 1.337-8 4v2h16v-2c0-2.663-5.33-4-8-4z"></path>
                            </svg>
                            Daftar Cap Jari
                        </button>
                    </div>
                </div>
            </div>

            <script>
                const biometricUnavailableMessage = 'Chrome/OS gagal membuat passkey. Pastikan screen lock/PIN Chrome aktif, login Google Password Manager normal, atau gunakan device yang punya fingerprint/Windows Hello.';

                async function platformAuthenticatorAvailable() {
                    if (!window.PublicKeyCredential || typeof PublicKeyCredential.isUserVerifyingPlatformAuthenticatorAvailable !== 'function') {
                        return true;
                    }

                    return PublicKeyCredential.isUserVerifyingPlatformAuthenticatorAvailable();
                }

                function prepareWebAuthnCreationOptions(publicKey, preferPlatform = true) {
                    if (!preferPlatform) {
                        delete publicKey.authenticatorSelection;
                        return publicKey;
                    }

                    publicKey.authenticatorSelection = publicKey.authenticatorSelection || {};
                    if (publicKey.authenticatorSelection.residentKey === 'required') {
                        publicKey.authenticatorSelection.residentKey = 'preferred';
                    }

                    publicKey.authenticatorSelection.authenticatorAttachment = 'platform';
                    publicKey.authenticatorSelection.requireResidentKey = false;
                    publicKey.authenticatorSelection.userVerification = 'preferred';

                    return publicKey;
                }

                function webAuthnRegisterErrorMessage(name, message) {
                    if (name === 'UnknownError') {
                        return message + '\n\n' + biometricUnavailableMessage;
                    }

                    return message;
                }

                document.addEventListener('DOMContentLoaded', function() {
                    const btn = document.getElementById('btn-register-capjari');
                    if (btn) {
                        btn.addEventListener('click', async function() {
                            const resetButton = () => {
                                btn.disabled = false;
                                btn.classList.remove('opacity-70', 'cursor-wait');
                            };

                            const webauthn = new WebAuthn((name, message) => {
                                resetButton();
                                alert('Ralat Peranti: ' + webAuthnRegisterErrorMessage(name, message));
                            });

                            if (!webauthn.supported()) {
                                alert('Browser atau peranti anda tidak menyokong pendaftaran Biometrik (WebAuthn).');
                                return;
                            }

                            if (window.location.hostname !== 'localhost' && !window.isSecureContext) {
                                alert('Pendaftaran Biometrik hanya dibenarkan melalui sambungan selamat (HTTPS) atau localhost.');
                                return;
                            }

                            const preferPlatform = await platformAuthenticatorAvailable();

                            btn.disabled = true;
                            btn.classList.add('opacity-70', 'cursor-wait');

                            fetch('{{ route("webauthn.register.options") }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                }
                            })
                            .then(res => {
                                if (!res.ok) {
                                    return res.json().then(err => { throw err; });
                                }
                                return res.json();
                            })
                            .then(publicKey => {
                                return webauthn.register(prepareWebAuthnCreationOptions(publicKey, preferPlatform), function (credentials) {
                                    fetch('{{ route("webauthn.register") }}', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                        },
                                        body: JSON.stringify({ name: 'Cap Jari Admin', data: credentials })
                                    })
                                    .then(res => {
                                        if (!res.ok) {
                                            return res.json().then(err => { throw err; });
                                        }
                                        return res.json();
                                    })
                                    .then(data => {
                                        alert('Pendaftaran Berjaya! Anda kini boleh log masuk menggunakan cap jari.');
                                        window.location.reload();
                                    })
                                    .catch(err => {
                                        resetButton();
                                        console.error(err);
                                        alert('Ralat Pendaftaran: ' + (err.message || 'Sila cuba sebentar lagi.'));
                                    });
                                });
                            })
                            .catch(err => {
                                resetButton();
                                console.error(err);
                                alert('Ralat: ' + (err.message || 'Gagal mengambil data pendaftaran.'));
                            });
                        });
                    }
                });
            </script>

            <!-- Link Perusahaan -->
            <div class="group bg-white/80 backdrop-blur-sm rounded-xl sm:rounded-2xl shadow-lg hover:shadow-2xl border border-white/50 p-4 sm:p-6 lg:p-8 transition-all duration-300 hover:scale-[1.02] lg:hover:scale-105">
                <div class="flex items-center mb-4 sm:mb-6">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-r from-violet-500 to-purple-500 rounded-lg sm:rounded-xl flex items-center justify-center mr-3 sm:mr-4 group-hover:rotate-6 transition-transform duration-300">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                        </svg>
                    </div>
                    <h2 class="text-lg sm:text-xl font-bold text-gray-800">{{ __('admin/dashboard.company_link_section') }}</h2>
                </div>

                <form action="{{ route('admin.maklumat.addLink', $company->id) }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="space-y-3 sm:space-y-4">
                        <div class="border-l-4 border-violet-400 pl-4 py-2">
                            <span class="font-semibold text-gray-600 text-sm sm:text-base block mb-2">{{ __('admin/dashboard.company_link_label') }}:</span>
                            <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                                <input
                                    type="url"
                                    name="link"
                                    value="{{ old('link', $company->link) }}"
                                    placeholder="https://example.com"
                                    class="flex-1 px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 transition-colors duration-300 text-sm sm:text-base"
                                >
                                <button
                                    type="submit"
                                    class="inline-flex items-center justify-center px-4 sm:px-6 py-2 sm:py-3 bg-gradient-to-r from-violet-500 to-purple-500 text-white rounded-lg sm:rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 font-semibold text-sm sm:text-base group whitespace-nowrap"
                                >
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 group-hover:rotate-12 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                    </svg>
                                    {{ __('admin/dashboard.company_link_save') }}
                                </button>
                            </div>
                            @error('link')
                                <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        @if($company->link)
                            <div class="border-l-4 border-purple-400 pl-4 py-2">
                                <span class="font-semibold text-gray-600 text-sm sm:text-base block mb-1">{{ __('admin/dashboard.company_link_active') }}:</span>
                                <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                                    <span class="text-gray-800 font-medium text-sm sm:text-base break-all flex-1">{{ $company->link }}</span>
                                    <a
                                        href="{{ $company->link }}"
                                        target="_blank"
                                        class="inline-flex items-center px-3 py-1 bg-purple-100 hover:bg-purple-200 text-purple-700 rounded-lg transition-colors duration-300 text-xs sm:text-sm font-medium group whitespace-nowrap"
                                    >
                                        <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 group-hover:rotate-12 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                        </svg>
                                        {{ __('admin/dashboard.company_link_visit') }}
                                    </a>
                                </div>
            </div>
                        @endif
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection

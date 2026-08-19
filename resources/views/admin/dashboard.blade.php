@extends('layouts.admin')

@section('title', 'Dashboard | EMindTutor')

@section('content')

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

        <!-- Log Masuk Cap Jari (Biometrik) -->
        <div class="mt-8 max-w-2xl">
            <div class="group bg-white/80 backdrop-blur-sm rounded-xl sm:rounded-2xl shadow-lg hover:shadow-2xl border border-white/50 p-4 sm:p-6 transition-all duration-300">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-gradient-to-r from-emerald-500 to-teal-500 rounded-lg sm:rounded-xl flex items-center justify-center mr-3 group-hover:rotate-6 transition-transform duration-300">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c1.657 0 3-1.343 3-3S13.657 5 12 5 9 6.343 9 8s1.343 3 3 3zm0 2c-2.67 0-8 1.337-8 4v2h16v-2c0-2.663-5.33-4-8-4z"></path>
                        </svg>
                    </div>
                    <h2 class="text-lg font-bold text-gray-800">Log Masuk Cap Jari (Biometrik)</h2>
                </div>

                <div class="space-y-3">
                    <p class="text-gray-600 text-sm">Daftarkan cap jari anda pada peranti ini untuk di log masuk pada masa akan datang tanpa memasukkan kata laluan.</p>

                    <div class="pt-2">
                        <button type="button" id="btn-register-capjari" class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-emerald-500 to-teal-500 text-white rounded-lg sm:rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 font-semibold text-sm group">
                            <svg class="w-4 h-4 mr-2 group-hover:rotate-12 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c1.657 0 3-1.343 3-3S13.657 5 12 5 9 6.343 9 8s1.343 3 3 3zm0 2c-2.67 0-8 1.337-8 4v2h16v-2c0-2.663-5.33-4-8-4z"></path>
                            </svg>
                            Daftar Cap Jari
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            const biometricUnavailableMessage = 'Chrome/OS gagal membuat passkey. Pastikan screen lock/PIN Chrome aktif, login Google Password Manager normal, atau gunakan peranti yang mempunyai fingerprint/Windows Hello.';

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
    </div>
</div>
@endsection

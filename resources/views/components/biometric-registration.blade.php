<section class="bg-white/80 backdrop-blur-sm border border-white/20 p-4 sm:p-6 lg:p-8 rounded-2xl sm:rounded-3xl shadow-lg hover:shadow-xl transition-all duration-300">
    <div class="flex items-center gap-3 mb-4">
        <div class="p-2 bg-emerald-100 rounded-xl">
            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c1.657 0 3-1.343 3-3S13.657 5 12 5 9 6.343 9 8s1.343 3 3 3zm0 2c-2.67 0-8 1.337-8 4v2h16v-2c0-2.663-5.33-4-8-4z"></path>
            </svg>
        </div>
        <h2 class="text-xl sm:text-2xl font-bold text-gray-900">{{ __('webauthn.register_title') }}</h2>
    </div>

    <p class="text-sm sm:text-base text-gray-600 mb-6">
        {{ __('webauthn.register_description') }}
    </p>

    <button type="button" data-biometric-register class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-gradient-to-r from-emerald-500 to-teal-500 text-white font-semibold px-6 py-3 sm:px-8 sm:py-4 rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200 text-sm sm:text-base">
        {{ __('webauthn.register_button') }}
    </button>
</section>

@webauthnScripts

<script>
    (() => {
        const button = document.querySelector('[data-biometric-register]');
        if (!button) return;

        const csrfToken = @json(csrf_token());
        const unavailableMessage = @json(__('webauthn.unavailable_message'));

        const resetButton = () => {
            button.disabled = false;
            button.classList.remove('opacity-70', 'cursor-wait');
        };

        const prepareOptions = (publicKey, preferPlatform) => {
            if (!preferPlatform) return publicKey;

            publicKey.authenticatorSelection = publicKey.authenticatorSelection || {};
            if (publicKey.authenticatorSelection.residentKey === 'required') {
                publicKey.authenticatorSelection.residentKey = 'preferred';
            }
            publicKey.authenticatorSelection.authenticatorAttachment = 'platform';
            publicKey.authenticatorSelection.requireResidentKey = false;
            publicKey.authenticatorSelection.userVerification = 'preferred';

            return publicKey;
        };

        button.addEventListener('click', async () => {
            const webauthn = new WebAuthn((name, message) => {
                resetButton();
                alert(@json(__('webauthn.device_error_prefix')) + `${message || name}\n\n${unavailableMessage}`);
            });

            if (!webauthn.supported()) {
                alert(@json(__('webauthn.not_supported')));
                return;
            }

            if (window.location.hostname !== 'localhost' && !window.isSecureContext) {
                alert(@json(__('webauthn.https_required')));
                return;
            }

            button.disabled = true;
            button.classList.add('opacity-70', 'cursor-wait');

            try {
                const platformAvailable = !window.PublicKeyCredential
                    || typeof PublicKeyCredential.isUserVerifyingPlatformAuthenticatorAvailable !== 'function'
                    || await PublicKeyCredential.isUserVerifyingPlatformAuthenticatorAvailable();

                const optionsResponse = await fetch(@json(route('webauthn.register.options')), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                });
                const publicKey = await optionsResponse.json();
                if (!optionsResponse.ok) throw new Error(publicKey.error || @json(__('webauthn.options_failed')));

                webauthn.register(prepareOptions(publicKey, platformAvailable), async (credentials) => {
                    try {
                        const registrationResponse = await fetch(@json(route('webauthn.register')), {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                            },
                            body: JSON.stringify({ name: 'Cap Jari', data: credentials }),
                        });
                        const result = await registrationResponse.json();
                        if (!registrationResponse.ok || !result.success) {
                            throw new Error(result.error || result.message || @json(__('webauthn.registration_failed')));
                        }

                        alert(@json(__('webauthn.registration_success')));
                    } catch (error) {
                        alert(@json(__('webauthn.registration_error_prefix')) + (error.message || @json(__('webauthn.try_again'))));
                    } finally {
                        resetButton();
                    }
                });
            } catch (error) {
                resetButton();
                console.error(error);
                alert(@json(__('webauthn.registration_error_prefix')) + (error.message || @json(__('webauthn.start_error')));
            }
        });
    })();
</script>

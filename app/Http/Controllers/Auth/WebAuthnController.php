<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Psr\Http\Message\ServerRequestInterface;
use Rawilk\Webauthn\Facades\Webauthn;
use Rawilk\Webauthn\Models\WebauthnKey;
use Webauthn\AuthenticatorAssertionResponse;
use Webauthn\AuthenticatorAssertionResponseValidator;
use Webauthn\PublicKeyCredentialLoader;
use Webauthn\PublicKeyCredentialRequestOptions;
use Webauthn\PublicKeyCredentialRpEntity;

class WebAuthnController extends Controller
{
    private const USERLESS_SESSION_KEY = 'webauthn.userless_public_key_request';

    /**
     * Return WebAuthn login options (assertion challenge)
     */
    public function loginOptions(Request $request)
    {
        $request->validate(['email' => 'nullable|email']);

        if (! $request->filled('email')) {
            return $this->userlessLoginOptions($request);
        }

        $email = $this->normalizeEmail($request->input('email'));
        $user = User::where('email', $email)->first();

        if (! $user) {
            return response()->json([
                'error' => 'Email tidak terdaftar. Sila semak email atau login menggunakan password dahulu.',
            ], 404);
        }

        if (! $user->webAuthnKeys()->exists()) {
            return response()->json([
                'error' => 'Cap jari belum didaftarkan untuk email ini. Login menggunakan password dahulu, kemudian daftar cap jari di dashboard.',
            ], 404);
        }

        return Webauthn::prepareAssertion($user);
    }

    /**
     * Validate WebAuthn assertion (login)
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'nullable|email',
            'data' => 'required|array'
        ]);

        if (! $request->filled('email')) {
            return $this->userlessLogin($request);
        }

        $email = $this->normalizeEmail($request->input('email'));
        $user = User::where('email', $email)->first();

        if (! $user) {
            return response()->json(['error' => 'Email tidak terdaftar.'], 404);
        }

        try {
            $isValid = Webauthn::validateAssertion($user, $request->input('data'));

            if ($isValid) {
                // Login User
                Auth::login($user);
                $request->session()->regenerate();

                // Redirect logic based on AuthController
                $redirect = route('dashboard.index');
                if($user->role == 'admin') {
                    $redirect = route('a.dashboard.index');
                } else if($user->role == 'teacher') {
                    $redirect = route('t.dashboard.index');
                } else if($user->role == 'student') {
                    $redirect = route('s.dashboard.index');
                }

                return response()->json([
                    'success' => true,
                    'redirect' => $redirect
                ]);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }

        return response()->json(['error' => 'WebAuthn failed validation.'], 422);
    }

    private function userlessLoginOptions(Request $request): PublicKeyCredentialRequestOptions
    {
        $publicKey = new PublicKeyCredentialRequestOptions(
            random_bytes((int) config('webauthn.challenge_length', 32)),
            app(PublicKeyCredentialRpEntity::class)->id,
            [],
            'required',
            (int) config('webauthn.timeout', 60000),
        );

        $request->session()->put(self::USERLESS_SESSION_KEY, $publicKey);

        return $publicKey;
    }

    private function userlessLogin(Request $request): JsonResponse
    {
        $publicKey = $request->session()->pull(self::USERLESS_SESSION_KEY);

        if (! $publicKey instanceof PublicKeyCredentialRequestOptions) {
            return response()->json([
                'error' => 'Sesi log masuk biometrik tamat. Sila cuba klik tombol biometrik sekali lagi.',
            ], 419);
        }

        try {
            $publicKeyCredential = app(PublicKeyCredentialLoader::class)
                ->loadArray($request->input('data'));
            $response = $publicKeyCredential->getResponse();

            if (! $response instanceof AuthenticatorAssertionResponse) {
                return response()->json(['error' => 'Data biometrik tidak sah.'], 422);
            }

            $source = app(AuthenticatorAssertionResponseValidator::class)->check(
                $publicKeyCredential->getRawId(),
                $response,
                $publicKey,
                app(ServerRequestInterface::class),
                null,
            );

            $user = User::find($source->getUserHandle());

            if (! $user) {
                $key = WebauthnKey::query()
                    ->where('credential_id', $request->input('data.id'))
                    ->first();

                $user = $key ? User::find($key->user_id) : null;
            }

            if (! $user) {
                return response()->json(['error' => 'Akaun untuk biometrik ini tidak dijumpai.'], 404);
            }

            return $this->loginUser($request, $user);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    private function loginUser(Request $request, User $user): JsonResponse
    {
        Auth::login($user);
        $request->session()->regenerate();

        return response()->json([
            'success' => true,
            'redirect' => $this->redirectUrlFor($user),
        ]);
    }

    private function redirectUrlFor(User $user): string
    {
        $redirect = route('dashboard.index');
        if($user->role == 'admin') {
            $redirect = route('a.dashboard.index');
        } else if($user->role == 'teacher') {
            $redirect = route('t.dashboard.index');
        } else if($user->role == 'student') {
            $redirect = route('s.dashboard.index');
        }

        return $redirect;
    }

    private function normalizeEmail(string $email): string
    {
        return strtolower(trim($email));
    }

    /**
     * Return WebAuthn registration options (attestation challenge)
     */
    public function registerOptions(Request $request)
    {
        $user = Auth::user();
        if (!$user) return response()->json(['error' => 'Unauthenticated'], 401);

        // Some platform/passkey authenticators reject mandatory resident keys
        // during credential creation with a generic browser device error.
        // Registration should stay broad; login can still ask for the email
        // when a non-discoverable credential is created.
        if (config('webauthn.userless') === 'required') {
            config(['webauthn.userless' => null]);
        }

        return Webauthn::prepareAttestation($user);
    }

    /**
     * Validate and create WebAuthn registration
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'data' => 'required|array'
        ]);

        $user = Auth::user();

        try {
            $key = Webauthn::registerAttestation($user, $request->input('data'), $request->name);
            return response()->json(['success' => true, 'message' => 'Biometrik berjaya didaftarkan.']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }
}

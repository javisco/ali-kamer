<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ApiAuthController extends Controller
{
    public function __construct(private AuthService $authService) {}

    // ── INSCRIPTION ACHETEUR (mobile) ─────────────────────────────────

    public function registerBuyer(Request $request)
    {
        $request->validate([
            'name'     => ['required', 'string', 'min:2', 'max:100'],
            'phone'    => ['required', 'string', 'regex:/^6[0-9]{8}$/', 'unique:users,phone'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = $this->authService->registerBuyer($request->all());
        $user->sendEmailVerificationNotification();
        $token = $user->createToken('mobile')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Compte créé. Vérifiez votre email.',
            'token'   => $token,
            'user'    => new UserResource($user),
        ], 201);
    }

    // ── INSCRIPTION VENDEUR (mobile) ──────────────────────────────────

    public function registerSeller(Request $request)
    {
        $request->validate([
            'name'          => ['required', 'string', 'min:2', 'max:100'],
            'phone_momo'    => ['required', 'string', 'regex:/^6[0-9]{8}$/', 'unique:users,phone_momo'],
            'momo_operator' => ['required', 'in:mtn,orange'],
            'phone'         => ['nullable', 'string', 'regex:/^6[0-9]{8}$/', 'unique:users,phone'],
            'email'         => ['required', 'email', 'unique:users,email'],
            'password'      => ['required', 'string', 'min:8', 'confirmed'],
            'shop_name'     => ['required', 'string', 'min:3', 'max:100'],
            'city'          => ['required', 'string'],
            'category'      => ['required', 'string'],
            'description'   => ['nullable', 'string', 'max:500'],
        ]);

        $user = $this->authService->registerSeller($request->all());
        $user->sendEmailVerificationNotification();
        $token = $user->createToken('mobile')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Compte vendeur créé. Vérifiez votre email puis soumettez votre KYC.',
            'token'   => $token,
            'user'    => new UserResource($user),
        ], 201);
    }

    // ── CONNEXION (mobile) ────────────────────────────────────────────

    public function login(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Email ou mot de passe incorrect.',
            ], 401);
        }

        if ($user->isBanned()) {
            return response()->json([
                'success' => false,
                'message' => 'Compte suspendu. Contactez le support.',
            ], 403);
        }

        // Vérification blacklist à la connexion
        try {
            $this->authService->checkBlacklist(
                phone:     $user->phone,
                phoneMomo: $user->phone_momo
            );
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Accès refusé.',
            ], 403);
        }

        $token = $user->createToken('mobile')->plainTextToken;

        return response()->json([
            'success'        => true,
            'token'          => $token,
            'email_verified' => $user->hasVerifiedEmail(),
            'user'           => new UserResource($user),
        ]);
    }

    // ── DÉCONNEXION ───────────────────────────────────────────────────

    public function logout(Request $request)
    {
        // Révoquer uniquement le token courant (pas tous les appareils)
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Déconnecté.',
        ]);
    }

    // ── PROFIL ────────────────────────────────────────────────────────

    public function me(Request $request)
    {
        return response()->json([
            'success' => true,
            'user'    => new UserResource($request->user()),
        ]);
    }

    // ── MODIFIER SON PROFIL ───────────────────────────────────────────

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name'  => ['sometimes', 'string', 'min:2', 'max:100'],
            'phone' => ['sometimes', 'string', 'regex:/^6[0-9]{8}$/', 'unique:users,phone,' . $user->id],
        ]);

        $user->update($request->only(['name', 'phone']));

        return response()->json([
            'success' => true,
            'user'    => new UserResource($user->fresh()),
        ]);
    }

    // ── RENVOYER L'EMAIL DE VÉRIFICATION ─────────────────────────────

    public function resendVerification(Request $request)
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'success' => false,
                'message' => 'Email déjà vérifié.',
            ]);
        }

        $user->sendEmailVerificationNotification();

        return response()->json([
            'success' => true,
            'message' => 'Email de vérification envoyé.',
        ]);
    }

    // ── RESET MOT DE PASSE (mobile) ───────────────────────────────────

    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => ['required', 'email']]);

        // Même message qu'en web — ne révèle pas si l'email existe
        $message = 'Si cette adresse existe, un lien vous a été envoyé.';
        $user    = User::where('email', $request->email)->first();

        if ($user) {
            $plainToken = \Str::random(64);
            \App\Models\PasswordResetToken::updateOrCreate(
                ['email' => $user->email],
                ['token' => \Hash::make($plainToken), 'created_at' => now()]
            );
            $url = route('password.reset', [
                'token' => $plainToken,
                'email' => $user->email,
            ]);
            \Mail::to($user->email)->send(
                new \App\Mail\ResetPasswordMail($user, $url)
            );
        }

        return response()->json(['success' => true, 'message' => $message]);
    }
}
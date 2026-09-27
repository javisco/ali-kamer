<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

// Ce middleware vérifie à chaque requête que le compte n'a pas été
// banni ou suspendu APRÈS la connexion (cas : admin banne un user
// déjà connecté — il sera bloqué à sa prochaine requête)

class CheckAccountStatus
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return $next($request);
        }

        if ($user->isBanned()) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'message' => 'Votre compte a été définitivement banni.',
                    'status'  => 'banned',
                ], 403);
            }

            auth()->logout();
            $request->session()->invalidate();
            return redirect()->route('login')
                ->withErrors(['phone' => 'Votre compte a été banni. Contactez le support.']);
        }

        if ($user->status === 'suspended') {
            // Règle d'or : vérifier si la suspension est échue
            $activeSuspension = $user->getActiveSuspension();
            if (! $activeSuspension || $activeSuspension->isDue()) {
                app(\App\Services\SanctionEngine::class)->expireDueSanctions();
                $user->refresh();

                if (! $user->isSuspended() && ! $user->isBanned()) {
                    return $next($request);
                }
            }

            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'message'  => 'Votre compte est temporairement suspendu.',
                    'status'   => 'suspended',
                    'sanction' => [
                        'type'             => 'SUSPENSION',
                        'starts_at'        => $activeSuspension?->starts_at?->toIso8601String(),
                        'expires_at'       => $activeSuspension?->expires_at?->toIso8601String(),
                        'formatted_expiry' => $activeSuspension?->formattedExpiry(),
                        'reason'           => $activeSuspension?->sanction?->reason ?? 'Non spécifié',
                    ],
                ], 403);
            }

            $reason = $activeSuspension?->sanction?->reason ?? 'Suspension de compte';
            $expiry = $activeSuspension ? $activeSuspension->formattedExpiry() : 'indéterminée';

            auth()->logout();
            $request->session()->invalidate();
            return redirect()->route('login')
                ->withErrors(['phone' => "Votre compte est temporairement suspendu. Motif : {$reason}. Fin de suspension : {$expiry}."]);
        }

        return $next($request);
    }
}

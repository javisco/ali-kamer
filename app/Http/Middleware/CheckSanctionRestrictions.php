<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSanctionRestrictions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$restrictions
     */
    public function handle(Request $request, Closure $next, string ...$restrictions): Response
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

            return redirect()->route('login')
                ->withErrors(['phone' => 'Votre compte a été banni. Contactez le support.']);
        }

        if ($user->isSuspended()) {
            $activeSuspension = $user->getActiveSuspension();
            if ($activeSuspension && ! $activeSuspension->isDue()) {
                if ($request->expectsJson() || $request->is('api/*')) {
                    return response()->json([
                        'message'  => 'Votre compte est temporairement suspendu.',
                        'status'   => 'suspended',
                        'sanction' => [
                            'type'             => 'SUSPENSION',
                            'starts_at'        => $activeSuspension->starts_at?->toIso8601String(),
                            'expires_at'       => $activeSuspension->expires_at?->toIso8601String(),
                            'formatted_expiry' => $activeSuspension->formattedExpiry(),
                            'reason'           => $activeSuspension->sanction?->reason ?? 'Non spécifié',
                        ],
                    ], 403);
                }

                return redirect()->route('login')
                    ->withErrors(['phone' => 'Votre compte est temporairement suspendu. Motif : ' . ($activeSuspension->sanction?->reason ?? '') . '. Fin : ' . $activeSuspension->formattedExpiry()]);
            }
        }

        foreach ($restrictions as $restriction) {
            if ($user->hasRestriction($restriction)) {
                if ($request->expectsJson() || $request->is('api/*')) {
                    return response()->json([
                        'message'             => "Action interdite : vous faites l'objet d'une restriction ({$restriction}).",
                        'restriction'         => $restriction,
                        'active_restrictions' => $user->getActiveRestrictions(),
                    ], 403);
                }

                return back()->with('error', "Cette action vous est temporairement restreinte ({$restriction}). Contactez le support.");
            }
        }

        return $next($request);
    }
}

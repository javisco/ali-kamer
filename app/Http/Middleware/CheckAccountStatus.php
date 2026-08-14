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
            auth()->logout();
            $request->session()->invalidate();
            return redirect()->route('login')
                ->withErrors(['phone' => 'Votre compte a été suspendu. Contactez le support.']);
        }

        if ($user->status === 'suspended') {
            auth()->logout();
            $request->session()->invalidate();
            return redirect()->route('login')
                ->withErrors(['phone' => 'Votre compte est temporairement suspendu.']);
        }

        return $next($request);
    }
}

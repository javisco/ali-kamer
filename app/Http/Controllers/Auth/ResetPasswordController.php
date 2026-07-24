<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PasswordResetToken;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    /**
     * Affiche le formulaire de réinitialisation.
     */
    public function show(Request $request, string $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email')
        ]);
    }

    /**
     * Met à jour le mot de passe.
     */
    public function update(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'token' => ['required'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Recherche du token correspondant à l'email
        |--------------------------------------------------------------------------
        */

        $passwordReset = PasswordResetToken::where(
            'email',
            $request->email
        )->first();

        if (!$passwordReset) {

            return back()->withErrors([
                'email' => 'Lien de réinitialisation invalide.'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Vérification du token
        |--------------------------------------------------------------------------
        */

        if (!Hash::check($request->token, $passwordReset->token)) {

            return back()->withErrors([
                'email' => 'Lien de réinitialisation invalide.'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Vérification de l'expiration
        |--------------------------------------------------------------------------
        */

        if (
            Carbon::parse($passwordReset->created_at)
            ->addMinutes(60)
            ->isPast()
        ) {

            $passwordReset->delete();

            return back()->withErrors([
                'email' => 'Ce lien de réinitialisation a expiré.'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Recherche de l'utilisateur
        |--------------------------------------------------------------------------
        */

        $user = User::where(
            'email',
            $request->email
        )->first();

        if (!$user) {

            return back()->withErrors([
                'email' => 'Utilisateur introuvable.'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Mise à jour du mot de passe
        |--------------------------------------------------------------------------
        */

        $user->update([

            'password' => Hash::make(
                $request->password
            )

        ]);

        /*
        |--------------------------------------------------------------------------
        | Suppression du token
        |--------------------------------------------------------------------------
        */

        $passwordReset->delete();

        /*
        |--------------------------------------------------------------------------
        | Redirection
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('login.show')
            ->with(
                'success',
                'Votre mot de passe a été modifié avec succès. Vous pouvez maintenant vous connecter.'
            );
    }
}

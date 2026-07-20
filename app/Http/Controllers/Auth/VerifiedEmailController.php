<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerifiedEmailController extends Controller
{
    public function verifiedEmail()
    {
        
        if (Auth::user()->hasVerifiedEmail()) {
            return redirect()->route('dashboard');
        }
        return view('auth.verifiedEmail');
    }

    public function verify(EmailVerificationRequest $request)
    {
        // Cette méthode magique de Laravel s'occupe de tout :
        // Elle remplit la colonne `email_verified_at` et déclenche l'événement "Verified".
        $request->fulfill();

        return redirect()->route('dashboard')->with('success', 'Votre adresse e-mail a été validée avec succès !');
    }


    public function resend(Request $request)
    {
        $user = $request->user();

        // Si l'utilisateur est déjà vérifié, inutile de lui renvoyer un mail
        if ($user->hasVerifiedEmail()) {
            return redirect()->route('dashboard');
        }

        // On déclenche l'envoi de la notification de validation
        $user->sendEmailVerificationNotification();

        return back()->with('message', 'Un nouveau lien de vérification vous a été envoyé !');
    }
}

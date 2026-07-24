<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordMail;
use App\Models\PasswordResetToken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    /**
     * Afficher le formulaire
     */
    public function show()
    {
        return view('auth.forgot-password');
    }

    /**
     * Envoyer le lien
     */
    public function send(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email']
        ]);

        /*
        |--------------------------------------------------------
        | On ne révèle jamais si l'email existe ou non
        |--------------------------------------------------------
        */

        $message = 'Si cette adresse e-mail existe, un lien de réinitialisation vous a été envoyé.';

        $user = User::where('email', $request->email)->first();

        if (!$user) {

            return back()->with('status', $message);

        }

        /*
        |--------------------------------------------------------
        | Génération du token
        |--------------------------------------------------------
        */

        $plainToken = Str::random(64);

        /*
        |--------------------------------------------------------
        | Sauvegarde
        |--------------------------------------------------------
        */

        PasswordResetToken::updateOrCreate(

            [
                'email' => $user->email,

            ],

            [
                'token' => Hash::make($plainToken),
                'created_at' => now()
            ]

        );

        /*
        |--------------------------------------------------------
        | Création du lien
        |--------------------------------------------------------
        */

        $url = route('password.reset', [

            'token' => $plainToken,

            'email' => $user->email

        ]);

        /*
        |--------------------------------------------------------
        | Envoi du mail
        |--------------------------------------------------------
        */

        Mail::to($user->email)

            ->send(

                new ResetPasswordMail(

                    $user,

                    $url

                )

            );

        return back()->with(

            'status',

            $message

        );
    }
}
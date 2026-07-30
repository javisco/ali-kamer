<?php

namespace App\Http\Controllers\Auth;


use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\DashboardService;

class VerifiedEmailController extends Controller
{
    public function __construct(protected DashboardService $dashboard_service) {}

    public function verifiedEmail()
    {

        if (Auth::user()->hasVerifiedEmail()) {
            $user = Auth::user();
            return  $this->dashboard_service->dashboard($user);
        }
        return view('auth.verifiedEmail');
    }

    public function verify(EmailVerificationRequest $request)
    {
        // Cette méthode magique de Laravel s'occupe de tout :
        // Elle remplit la colonne `email_verified_at` et déclenche l'événement "Verified".
        $request->fulfill();

        $user = Auth::user();

        return  $this->dashboard_service->dashboard($user);
    }


    public function resend(Request $request)
    {
        $user = $request->user();

        // Si l'utilisateur est déjà vérifié, inutile de lui renvoyer un mail
        if ($user->hasVerifiedEmail()) {
            $user = Auth::user();
            return   $this->dashboard_service->dashboard($user);
        }

        // On déclenche l'envoi de la notification de validation
        $user->sendEmailVerificationNotification();

        return back()->with('message', 'Un nouveau lien de vérification vous a été envoyé !');
    }
}

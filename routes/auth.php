<?php


use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerifiedEmailController;
use Illuminate\Support\Facades\Route;


// ── Guest uniquement ──────────────────────────────────────────────────

Route::middleware('guest')->group(function () {

    // Page de choix du rôle
    Route::get('/inscription', [AuthController::class, 'showRegisterChoice'])
        ->name('register.show');

    // Inscription acheteur
    Route::get('/inscription/acheteur', [AuthController::class, 'showRegisterBuyer'])
        ->name('register.buyer');
    Route::post('/inscription/acheteur', [AuthController::class, 'registerBuyer'])
        ->name('register.buyer.post');

    // Inscription vendeur
    Route::get('/inscription/vendeur', [AuthController::class, 'showRegisterSeller'])
        ->name('register.seller');
    Route::post('/inscription/vendeur', [AuthController::class, 'registerSeller'])
        ->name('register.seller.post');

    // Connexion
    Route::get('/connexion', [AuthController::class, 'showLogin'])
        ->name('login.show');
    Route::post('/connexion', [AuthController::class, 'login'])
        ->name('login');

    // Reset password (déjà en place, garder les routes existantes)
    Route::get('/forgot-password', [ForgotPasswordController::class, 'show'])
        ->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'send'])
        ->name('password.email');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'show'])
        ->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'update'])
        ->name('password.update');
});

// Déconnexion
Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

// Vérification email (déjà en place, garder les routes existantes)
Route::middleware('auth')->group(function () {
    Route::get('/email/email-verify', [VerifiedEmailController::class, 'verifiedEmail'])
        ->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [VerifiedEmailController::class, 'verify'])
        ->middleware('signed')
        ->name('verification.verify');
    Route::post('/email/verification-notification', [VerifiedEmailController::class, 'resend'])
        ->middleware('throttle:6,1')
        ->name('verification.send');
});

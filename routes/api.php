<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\ApiAuthController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {

    // ── Sans authentification ─────────────────────────────────────────
    Route::post('/register/buyer',  [ApiAuthController::class, 'registerBuyer']);
    Route::post('/register/seller', [ApiAuthController::class, 'registerSeller']);
    Route::post('/login',           [ApiAuthController::class, 'login']);
    Route::post('/forgot-password', [ApiAuthController::class, 'forgotPassword']);

    // ── Avec token Sanctum (Bearer) ───────────────────────────────────
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me',               [ApiAuthController::class, 'me']);
        Route::put('/me',               [ApiAuthController::class, 'updateProfile']);
        Route::post('/logout',          [ApiAuthController::class, 'logout']);
        Route::post('/email/resend',    [ApiAuthController::class, 'resendVerification']);
    });
});
<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\VerifiedEmailController;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Route;



Route::get('/', function () {
        return view('welcome');
});


//authentification

Route::get('/register', [AuthController::class, 'showFormRegister'])->name('register.show');
Route::post('/register', [AuthController::class, 'register'])->name('register');


Route::get('/login', [AuthController::class, 'showFormLogin'])->name('login.show');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/', [AuthController::class, 'index'])->name('index');


Route::middleware(['auth',])->group(function () {
        Route::get('/email/email-verify', [VerifiedEmailController::class, 'verifiedEmail'])->name('verification.notice');

        Route::get('/email/verify/{id}/{hash}', [VerifiedEmailController::class, 'verify'])
                ->middleware('signed')
                ->name('verification.verify');

        Route::post('/email/verification-notification', [VerifiedEmailController::class, 'resend'])
                ->middleware('throttle:6,1')
                ->name('verification.send');
});


Route::get('/dashboard',[AuthController::class,'showDashboard'])->middleware(['auth','verified'])->name('dashboard');




















// Route::get('/toto',function(Request $request){
// $ip = $request->ip();
// return $ip;
// });
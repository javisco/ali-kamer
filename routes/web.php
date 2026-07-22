<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\VerifiedEmailController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Seller\KycSellerController;
use App\Http\Controllers\Admin\KycAdmincontroller;
use App\Http\Controllers\Buyer\BuyerController;

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

//verification de l'email
Route::middleware(['auth',])->group(function () {
        Route::get('/email/email-verify', [VerifiedEmailController::class, 'verifiedEmail'])->name('verification.notice');

        Route::get('/email/verify/{id}/{hash}', [VerifiedEmailController::class, 'verify'])
                ->middleware('signed')
                ->name('verification.verify');

        Route::post('/email/verification-notification', [VerifiedEmailController::class, 'resend'])
                ->name('verification.send');
});


Route::get('/dashboard', [AuthController::class, 'showDashboard'])->middleware([
        'auth',
        'role:buyer,seller,secretary,admin'
])->name('dashboard');



// Vendeur — KYC
Route::middleware(['auth', 'role:seller'])->prefix('seller')->group(function () {
        Route::get('/kyc', [KycSellerController::class, 'create'])->name('seller.kyc.create');
        Route::post('/kyc', [KycSellercontroller::class, 'store'])->name('seller.kyc.store');
        Route::get('/kyc/attente', [KycSellerController::class, 'pending'])->name('seller.kyc.pending');
        Route::get('/kyc/rejected', [KycSellerController::class, 'rejected'])->name('seller.kyc.rejected');
        Route::get('/dashboard',[KycSellerController::class,'dashboard'])->name('seller.dashboard');
});

// Admin — KYC
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
        Route::get('/kyc', [KycAdmincontroller::class, 'index'])->name('admin.kyc.index');
        Route::get('/kyc/{kyc}', [KycAdmincontroller::class, 'show'])->name('admin.kyc.show');
        Route::post('/kyc/{kyc}/approuver', [KycAdmincontroller::class, 'approve'])->name('admin.kyc.approve');
        Route::post('/kyc/{kyc}/rejeter', [KycAdmincontroller::class, 'reject'])->name('admin.kyc.reject');
        Route::get('/kyc/fichier', [KycAdmincontroller::class, 'serveFile'])->name('admin.kyc.file');
        Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
});

Route::middleware(['auth', 'role:buyer'])->group(function () {
        Route::get('dashboard', [BuyerController::class, 'index'])->name('buyer.dashboard');
});












// Route::get('/toto',function(Request $request){
// $ip = $request->ip();
// return $ip;
// });
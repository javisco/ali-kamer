<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\VerifiedEmailController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Seller\KycSellerController;
use App\Http\Controllers\Admin\KycAdmincontroller;
use App\Http\Controllers\Buyer\BuyerController;
use App\Http\Controllers\Seller\ProductController;
use App\Http\Controllers\Buyer\CatalogController;
use App\Http\Controllers\Seller\ShopController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

// Route::get('/', function () {
//         return view('welcome');
// });

//authentification
Route::get('/register', [AuthController::class, 'showFormRegister'])->name('register.show');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::get('/login', [AuthController::class, 'showFormLogin'])->name('login.show');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


//verification de l'email
Route::middleware(['auth',])->group(function () {
        Route::get('/email/email-verify', [VerifiedEmailController::class, 'verifiedEmail'])->name('verification.notice');
        Route::get('/email/verify/{id}/{hash}', [VerifiedEmailController::class, 'verify'])
                ->middleware('signed')
                ->name('verification.verify');
        Route::post('/email/verification-notification', [VerifiedEmailController::class, 'resend'])
                ->name('verification.send');
});

// Vendeur — KYC
Route::middleware(['auth', 'role:seller'])->prefix('seller')->group(function () {
        Route::get('/kyc', [KycSellerController::class, 'create'])->name('seller.kyc.create');
        Route::post('/kyc', [KycSellercontroller::class, 'store'])->name('seller.kyc.store');
        Route::get('/kyc/attente', [KycSellerController::class, 'pending'])->name('seller.kyc.pending');
        Route::get('/kyc/rejected', [KycSellerController::class, 'rejected'])->name('seller.kyc.rejected');
});

// Admin — KYC
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
        Route::get('/kyc/file', [KycAdmincontroller::class, 'serveFile'])->name('admin.kyc.file');
        Route::get('/kyc', [KycAdmincontroller::class, 'index'])->name('admin.kyc.index');
        Route::get('/kyc/{kyc}', [KycAdmincontroller::class, 'show'])->name('admin.kyc.show');
        Route::post('/kyc/{kyc}/approuver', [KycAdmincontroller::class, 'approve'])->name('admin.kyc.approve');
        Route::post('/kyc/{kyc}/rejeter', [KycAdmincontroller::class, 'reject'])->name('admin.kyc.reject');
});

// ── Catalogue public ──────────────────────────────────────────────
Route::get('/', [CatalogController::class, 'index'])->name('buyer.home');
Route::get('/produit/{product}', [CatalogController::class, 'show'])->name('product.show');
Route::get('/boutique/{shop}', [CatalogController::class, 'shop'])->name('shop.show');


//boutique -  vendeur
Route::middleware(['auth', 'role:seller'])->prefix('vendeur')->group(function () {
        Route::get('/boutique/creer', [ShopController::class, 'create'])->name('seller.shop.create');
        Route::post('/boutique', [ShopController::class, 'store'])->name('seller.shop.store');
        Route::get('/boutique/modifier', [ShopController::class, 'edit'])->name('seller.shop.edit');
        Route::put('/boutique', [ShopController::class, 'update'])->name('seller.shop.update');
});

// ── Produits vendeur ──────────────────────────────────────────────
// Route::middleware(['auth', 'role:seller'])->prefix('vendeur')->group(function () {});

Route::middleware(['auth', 'role:seller', 'shop.active'])->prefix('vendeur')->group(function () {
        Route::get('/dasboard',[ProductController::class,'dashboard'])->name('seller.dashboard');
        Route::get('/produits', [ProductController::class, 'index'])
                ->name('seller.products.index');
        Route::get('/produits/creer', [ProductController::class, 'create'])
                ->name('seller.products.create');
        Route::post('/produits', [ProductController::class, 'store'])
                ->name('seller.products.store');
        Route::get('/produits/{product}/modifier', [ProductController::class, 'edit'])
                ->name('seller.products.edit');
        Route::put('/produits/{product}', [ProductController::class, 'update'])
                ->name('seller.products.update');
        Route::delete('/produits/{product}', [ProductController::class, 'destroy'])
                ->name('seller.products.destroy');
        Route::post('/produits/{product}/visibilite', [ProductController::class, 'toggleVisibility'])
                ->name('seller.products.toggle');
        Route::delete('/produits/image/{image}', [ProductController::class, 'deleteImage'])
                ->name('seller.products.image.delete');
});






Route::middleware('guest')->group(function () {

        Route::get('/forgot-password', [ForgotPasswordController::class, 'show'])
                ->name('password.request');

        Route::post('/forgot-password', [ForgotPasswordController::class, 'send'])
                ->name('password.email');

        Route::get('/reset-password/{token}', [ResetPasswordController::class, 'show'])
                ->name('password.reset');

        Route::post('/reset-password', [ResetPasswordController::class, 'update'])
                ->name('password.update');
});



// Route::get('/toto',function(Request $request){
// $ip = $request->ip();
// return $ip;
// });
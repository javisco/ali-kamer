<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\VerifiedEmailController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Seller\KycSellerController;
use App\Http\Controllers\Admin\KycAdmincontroller;
use App\Http\Controllers\Seller\ProductController;
use App\Http\Controllers\Buyer\CatalogController;
use App\Http\Controllers\Seller\ShopController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Buyer\OrderController as BuyerOrderController;
use App\Http\Controllers\Seller\OrderController as SellerOrderController;
use App\Http\Controllers\Buyer\DashboardController as BuyerDashboardController;


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

//mot de passe oublier
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

//boutique -  vendeur
Route::middleware(['auth', 'role:seller'])->prefix('vendeur')->group(function () {
        Route::get('/boutique/creer', [ShopController::class, 'create'])->name('seller.shop.create');
        Route::post('/boutique', [ShopController::class, 'store'])->name('seller.shop.store');
        Route::get('/boutique/modifier', [ShopController::class, 'edit'])->name('seller.shop.edit');
        Route::put('/boutique', [ShopController::class, 'update'])->name('seller.shop.update');
});

// ── Catalogue public ──────────────────────────────────────────────
Route::get('/', [CatalogController::class, 'index'])->name('buyer.home');
Route::get('/produit/{product}', [CatalogController::class, 'show'])->name('product.show');
Route::get('/boutique/{shop}', [CatalogController::class, 'shop'])->name('shop.show');

// ── Produits vendeur ──────────────────────────────────────────────
// Route::middleware(['auth', 'role:seller'])->prefix('vendeur')->group(function () {});

Route::middleware(['auth', 'role:seller', 'shop.active'])->prefix('vendeur')->group(function () {
        Route::get('/dasboard', [ProductController::class, 'dashboard'])->name('seller.dashboard');
        Route::get('/produits', [ProductController::class, 'index'])
                ->name('seller.products.index');
        Route::get('/produits/creer', [ProductController::class, 'create'])
                ->name('seller.products.create');
        Route::get('/produit/{product}/see', [ProductController::class, 'view'])
                ->name('seller.products.view');
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

// ── Commandes acheteur ────────────────────────────────────────────
Route::middleware(['auth', 'role:buyer'])->prefix('commandes')->group(function () {
        Route::get('/', [BuyerOrderController::class, 'index'])->name('buyer.orders.index');
        Route::get('/passer/{product}', [BuyerOrderController::class, 'create'])->name('buyer.orders.create');
        Route::post('/', [BuyerOrderController::class, 'store'])->name('buyer.orders.store');
        Route::get('/{order}', [BuyerOrderController::class, 'show'])->name('buyer.orders.show');
        Route::post('/{order}/annuler', [BuyerOrderController::class, 'cancel'])->name('buyer.orders.cancel');
});

// ── Commandes vendeur ─────────────────────────────────────────────
Route::middleware(['auth', 'role:seller'])->prefix('vendeur')->group(function () {
        Route::get('/commandes', [SellerOrderController::class, 'index'])->name('seller.orders.index');
        Route::get('/commandes/{order}', [SellerOrderController::class, 'show'])->name('seller.orders.show');
        Route::post('/commandes/{order}/preparer', [SellerOrderController::class, 'markPreparing'])->name('seller.orders.preparing');
});


use App\Http\Controllers\Buyer\PaymentController;
use App\Http\Controllers\Payment\WebhookController;
use App\Models\Order;

// Webhook Campay — pas de middleware auth (appelé par Campay)
// Protection assurée par la vérification de signature HMAC
Route::post('/webhooks/campay', [WebhookController::class, 'campay'])
        ->name('payment.webhook.campay');

// Pages paiement acheteur
Route::middleware(['auth', 'role:buyer'])->group(function () {
        Route::get('/paiement/{order}', [PaymentController::class, 'show'])
                ->name('buyer.payment.show');
        Route::post('/paiement/{order}/initier', [PaymentController::class, 'initiate'])
                ->name('buyer.payment.initiate');
        Route::get('/paiement/{order}/attente', [PaymentController::class, 'waiting'])
                ->name('buyer.payment.waiting');
});


// Route appelée par le JS de la page d'attente
// Retourne le statut de la commande en JSON

Route::get('/commandes/{order}/statut', function (Order $order) {
        abort_unless($order->buyer_id === auth()->id(), 403);
        return response()->json(['status' => $order->status]);
})->middleware(['auth', 'role:buyer'])->name('buyer.orders.status');


Route::middleware(['auth', 'role:buyer'])->prefix('acheteur')->group(function () {
        Route::get('/dashboard', [BuyerDashboardController::class, 'index'])->name('buyer.dashboard');
});







use App\Http\Controllers\Seller\WalletController;

// Portefeuille vendeur
Route::middleware(['auth', 'role:seller'])->prefix('vendeur')->group(function () {
        Route::get('/portefeuille', [WalletController::class, 'index'])
                ->name('seller.wallet.index');
        Route::get('/portefeuille/retrait', [WalletController::class, 'withdrawForm'])
                ->name('seller.wallet.withdraw');
        Route::post('/portefeuille/retrait', [WalletController::class, 'withdraw'])
                ->name('seller.wallet.withdraw.post');
});


use App\Http\Controllers\Secretary\DashboardController;

// Interface secrétaire agence
Route::middleware(['auth', 'role:secretary'])
        ->prefix('agence')
        ->group(function () {

                // Dashboard principal
                Route::get('/dashboard', [DashboardController::class, 'index'])
                        ->name('secretary.dashboard');

                // Enregistrer un colis au départ (saisie code de dépôt)
                Route::post('/depot', [DashboardController::class, 'registerDeposit'])
                        ->name('secretary.deposit');

                // Valider l'arrivée d'un colis
                Route::post('/arrivee/{order}', [DashboardController::class, 'validateArrival'])
                        ->name('secretary.arrival');

                // Valider l'OTP de remise
                Route::post('/otp/{order}', [DashboardController::class, 'validateOtp'])
                        ->name('secretary.otp');
        });

use Illuminate\Http\Request;

// Recherche commande par référence pour la remise OTP
Route::get('/agence/recherche-commande', function (Request $request) {
        $order = \App\Models\Order::where('reference', $request->ref)
                ->where('status', \App\Models\Order::STATUS_AWAITING_BUYER_CONFIRMATION)
                ->with(['buyer', 'shipment'])
                ->first();

        if (! $order) {
                return response()->json(['found' => false]);
        }

        return response()->json([
                'found'            => true,
                'id'               => $order->id,
                'reference'        => $order->reference,
                'buyer'            => $order->buyer->name,
                'destination_city' => $order->shipment->destination_city,
        ]);
})->middleware(['auth', 'role:secretary'])->name('secretary.search');






use App\Http\Controllers\MessagingController;

Route::middleware(['auth'])->group(function () {

        // Liste des conversations
        Route::get('/messages', [MessagingController::class, 'index'])
                ->name('messaging.index');

        // Détail d'une conversation
        Route::get('/messages/{conversation}', [MessagingController::class, 'show'])
                ->name('messaging.show');

        // Démarrer une conversation depuis une boutique
        Route::post('/messages/boutique/{shop}', [MessagingController::class, 'start'])
                ->name('messaging.start');

        // Envoyer un message texte
        Route::post('/messages/{conversation}/texte', [MessagingController::class, 'sendText'])
                ->name('messaging.send.text');

        // Envoyer une pièce jointe
        Route::post('/messages/{conversation}/fichier', [MessagingController::class, 'sendAttachment'])
                ->name('messaging.send.attachment');

        // Polling nouveaux messages (appelé par JS)
        Route::get('/messages/{conversation}/poll', [MessagingController::class, 'poll'])
                ->name('messaging.poll');
});

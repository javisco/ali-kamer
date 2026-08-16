<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\VerifiedEmailController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Seller\KycSellerController;
use App\Http\Controllers\Admin\KycAdmincontroller;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Seller\ProductController;
use App\Http\Controllers\Buyer\CatalogController;
use App\Http\Controllers\Seller\ShopController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Buyer\OrderController as BuyerOrderController;
use App\Http\Controllers\Seller\OrderController as SellerOrderController;
use App\Http\Controllers\Buyer\DashboardController as BuyerDashboardController;
use App\Http\Controllers\MessagingController;
use App\Http\Controllers\Buyer\PaymentController;
use App\Http\Controllers\Payment\WebhookController;
use App\Http\Controllers\Secretary\DashboardController;
use App\Http\Controllers\Buyer\DisputeController as BuyerDisputeController;
use App\Http\Controllers\Seller\DisputeController as SellerDisputeController;
use App\Http\Controllers\Admin\DisputeController as AdminDisputeController;
use App\Http\Controllers\Buyer\ReviewController as BuyerReviewController;
use App\Http\Controllers\Seller\ReviewController as SellerReviewController;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use App\Http\Controllers\Seller\WalletController;

use App\Http\Controllers\Admin\AgencyController;
use App\Models\Agency;
use App\Models\AgencyCounter;

// Route::get('/', function () {
//         return view('welcome');
// });



//authentification
Route::get('/register', [AuthController::class, 'showFormRegister'])->name('register.show');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::get('/login', [AuthController::class, 'showFormLogin'])->name('login.show');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

//verification de l'email

Route::middleware(['auth'])->group(function () {
        Route::get('/email/email-verify', [VerifiedEmailController::class, 'verifiedEmail'])->name('verification.notice');

        Route::get('/email/verify/{id}/{hash}', [VerifiedEmailController::class, 'verify'])
                ->middleware('signed')
                ->name('verification.verify');
        Route::post('/email/verification-notification', [VerifiedEmailController::class, 'resend'])
                ->middleware('throttle:6,1')->name('verification.send');
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
Route::middleware(['auth', 'verified', 'role:seller'])->prefix('seller')->group(function () {
        Route::get('/kyc', [KycSellerController::class, 'create'])->name('seller.kyc.create');
        Route::post('/kyc', [KycSellercontroller::class, 'store'])->name('seller.kyc.store');
        Route::get('/kyc/attente', [KycSellerController::class, 'pending'])->name('seller.kyc.pending');
        Route::get('/kyc/rejected', [KycSellerController::class, 'rejected'])->name('seller.kyc.rejected');
});

// Admin — KYC
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->group(function () {
        Route::get('/kyc/file', [KycAdmincontroller::class, 'serveFile'])->name('admin.kyc.file');
        Route::get('/kyc', [KycAdmincontroller::class, 'index'])->name('admin.kyc.index');
        Route::get('/kyc/{kyc}', [KycAdmincontroller::class, 'show'])->name('admin.kyc.show');
        Route::post('/kyc/{kyc}/approuver', [KycAdmincontroller::class, 'approve'])->name('admin.kyc.approve');
        Route::post('/kyc/{kyc}/rejeter', [KycAdmincontroller::class, 'reject'])->name('admin.kyc.reject');
});

//boutique -  vendeur
Route::middleware(['auth', 'verified', 'role:seller'])->prefix('vendeur')->group(function () {
        Route::get('/boutique/creer', [ShopController::class, 'create'])->name('seller.shop.create');
        Route::post('/boutique', [ShopController::class, 'store'])->name('seller.shop.store');
        Route::get('/boutique/modifier', [ShopController::class, 'edit'])->name('seller.shop.edit');
        Route::put('/boutique', [ShopController::class, 'update'])->name('seller.shop.update');
});

Route::middleware(['auth', 'verified', 'role:buyer'])->prefix('acheteur')->group(function () {
        Route::get('/dashboard', [BuyerDashboardController::class, 'index'])->name('buyer.dashboard');
});

// ── Catalogue public ──────────────────────────────────────────────
Route::get('/', [CatalogController::class, 'index'])->name('buyer.home');
Route::get('/produit/{product}', [CatalogController::class, 'show'])->name('product.show');
Route::get('/boutique/{shop}', [CatalogController::class, 'shop'])->name('shop.show');

// ── Produits vendeur ──────────────────────────────────────────────
// Route::middleware(['auth', 'role:seller'])->prefix('vendeur')->group(function () {});

Route::middleware(['auth', 'verified', 'role:seller', 'shop.active'])->prefix('vendeur')->group(function () {
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
Route::middleware(['auth', 'verified', 'role:buyer'])->prefix('commandes')->group(function () {
        Route::get('/', [BuyerOrderController::class, 'index'])->name('buyer.orders.index');
        Route::get('/passer/{product}', [BuyerOrderController::class, 'create'])->name('buyer.orders.create');
        Route::post('/', [BuyerOrderController::class, 'store'])->name('buyer.orders.store');
        Route::get('/{order}', [BuyerOrderController::class, 'show'])->name('buyer.orders.show');
        Route::post('/{order}/annuler', [BuyerOrderController::class, 'cancel'])->name('buyer.orders.cancel');
});


// ── Commandes vendeur ─────────────────────────────────────────────
Route::middleware(['auth', 'verified', 'role:seller'])->prefix('vendeur')->group(function () {
        Route::get('/commandes', [SellerOrderController::class, 'index'])->name('seller.orders.index');
        Route::get('/commandes/{order}', [SellerOrderController::class, 'show'])->name('seller.orders.show');
        // Route::post('/commandes/{order}/preparer', [SellerOrderController::class, 'markPreparing'])->name('seller.orders.preparing');
        Route::post('/commandes/{order}/preparer', [SellerOrderController::class, 'prepare'])
                ->name('seller.orders.prepare');
});

//messagerie
Route::middleware(['auth', 'verified'])->group(function () {

        // Liste des conversations
        Route::get('/messages', [MessagingController::class, 'index'])
                ->name('messaging.index');

        // Détail d'une conversation
        Route::get('/messages/{conversation}', [MessagingController::class, 'show'])
                ->name('messaging.show');

        // Démarrer une conversation depuis une boutique
        Route::get('/messages/{product}/boutique/{shop}', [MessagingController::class, 'start'])
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


// Webhook Campay — pas de middleware auth (appelé par Campay)
// Protection assurée par la vérification de signature HMAC
Route::post('/webhooks/campay', [WebhookController::class, 'campay'])
        ->name('payment.webhook.campay');

// Pages paiement acheteur
Route::middleware(['auth', 'verified', 'role:buyer'])->group(function () {
        // Route::get('/paiement/{order}', [PaymentController::class, 'show'])
        //         ->name('buyer.payment.show');
        Route::get('/paiement/{order}/initier', [PaymentController::class, 'initiate'])
                ->name('buyer.payment.initiate');
        Route::get('/paiement/{order}/attente', [PaymentController::class, 'waiting'])
                ->name('buyer.payment.waiting');
        Route::get('/commandes/{order}/statut', [PaymentController::class, 'status'])
                ->name('buyer.orders.status');
});

// Portefeuille vendeur
Route::middleware(['auth', 'verified', 'role:seller'])->prefix('vendeur')->group(function () {
        Route::get('/portefeuille', [WalletController::class, 'index'])
                ->name('seller.wallet.index');
        Route::get('/portefeuille/retrait', [WalletController::class, 'withdrawForm'])
                ->name('seller.wallet.withdraw');
        Route::post('/portefeuille/retrait', [WalletController::class, 'withdraw'])
                ->name('seller.wallet.withdraw.post');
});


Route::middleware(['auth', 'role:secretary'])
        ->prefix('agence')
        ->group(function () {

                // Dashboard principal
                Route::get('/dashboard', [DashboardController::class, 'index'])
                        ->name('secretary.dashboard');

                // ── PAGE 1 : Dépôts (secrétaire départ) ──────────────────────

                // Page dépôts — saisie du deposit_code vendeur
                Route::get('/depot', [DashboardController::class, 'depositPage'])
                        ->name('secretary.deposit.page');

                // Recherche commande par deposit_code
                Route::post('/depot/recherche', [DashboardController::class, 'searchDeposit'])
                        ->name('secretary.deposit.search');

                Route::get('/depot/{order}/found', [DashboardController::class, 'found'])
                        ->name('found');

                // Enregistrer le colis au départ (+ frais transport si exclu)
                Route::post('/depot/{order}/valider', [DashboardController::class, 'registerDeposit'])
                        ->name('secretary.deposit.validate');

                // ── PAGE 2 : Arrivées (secrétaire arrivée) ───────────────────

                // Page arrivées — liste des colis attendus + recherche par référence
                Route::get('/arrivees', [DashboardController::class, 'arrivalsPage'])
                        ->name('secretary.arrivals.page');

                // Recherche commande par référence (pour la liste longue)
                Route::get('/arrivees/recherche', [DashboardController::class, 'searchArrival'])
                        ->name('secretary.arrivals.search');

                // Valider l'arrivée d'un colis
                Route::post('/arrivees/{order}/valider', [DashboardController::class, 'validateArrival'])
                        ->name('secretary.arrival.validate');

                // ── PAGE 3 : Remise OTP ───────────────────────────────────────

                // Page remise — liste des colis arrivés à remettre + saisie OTP
                Route::get('/remises', [DashboardController::class, 'handoverPage'])
                        ->name('secretary.handover.page');

                // Valider l'OTP et remettre le colis
                Route::post('/remises/{order}/otp', [DashboardController::class, 'validateOtp'])
                        ->name('secretary.handover.otp');
        });

// Paiement frais transport acheteur
Route::middleware(['auth', 'role:buyer'])->group(function () {

        // Page de paiement des frais transport
        Route::get('/commandes/{order}/transport', [BuyerOrderController::class, 'transportPayment'])
                ->name('buyer.orders.transport');

        // Initier le paiement Campay pour les frais transport
        Route::post('/commandes/{order}/transport/payer', [BuyerOrderController::class, 'payTransport'])
                ->name('buyer.orders.transport.pay');
});



// Litiges acheteur
Route::middleware(['auth', 'verified', 'role:buyer'])->group(function () {
        Route::get('/commandes/{order}/litige', [BuyerDisputeController::class, 'create'])
                ->name('buyer.disputes.create');
        Route::post('/commandes/{order}/litige', [BuyerDisputeController::class, 'store'])
                ->name('buyer.disputes.store');
        Route::get('/litiges/{dispute}', [BuyerDisputeController::class, 'show'])
                ->name('buyer.disputes.show');
});

// Litiges vendeur
Route::middleware(['auth', 'verified', 'role:seller'])->prefix('vendeur')->group(function () {
        Route::get('/litiges', [SellerDisputeController::class, 'index'])
                ->name('seller.disputes.index');
        Route::post('/litiges/{dispute}/repondre', [SellerDisputeController::class, 'reply'])
                ->name('seller.disputes.reply');
});

// Litiges admin
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->group(function () {
        Route::get('/litiges', [AdminDisputeController::class, 'index'])
                ->name('admin.disputes.index');
        Route::get('/litiges/{dispute}', [AdminDisputeController::class, 'show'])
                ->name('admin.disputes.show');
        Route::post('/litiges/{dispute}/resoudre', [AdminDisputeController::class, 'resolve'])
                ->name('admin.disputes.resolve');
});



// Avis acheteur
Route::middleware(['auth', 'verified', 'role:buyer'])->group(function () {
        Route::get('/commandes/{order}/noter', [BuyerReviewController::class, 'create'])
                ->name('buyer.reviews.create');
        Route::post('/commandes/{order}/noter', [BuyerReviewController::class, 'store'])
                ->name('buyer.reviews.store');
});

// Avis vendeur sur acheteur
Route::middleware(['auth', 'verified', 'role:seller'])->prefix('vendeur')->group(function () {
        Route::post('/commandes/{order}/noter-acheteur', [SellerReviewController::class, 'store'])
                ->name('seller.reviews.store');
});

Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->group(function () {

        // Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
                ->name('admin.dashboard');

        // Utilisateurs
        Route::get('/utilisateurs', [AdminDashboardController::class, 'users'])
                ->name('admin.users.index');
        Route::post('/utilisateurs/{user}/bannir', [AdminDashboardController::class, 'banUser'])
                ->name('admin.users.ban');
        Route::post('/utilisateurs/{user}/reactiver', [AdminDashboardController::class, 'unbanUser'])
                ->name('admin.users.unban');
        // Blacklister un vendeur
        Route::post('/utilisateurs/{user}/blacklister', [AdminDashboardController::class, 'blacklistUser'])
                ->name('admin.users.blacklist');
});



Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {

        // Agences
        Route::get('/agences', [AgencyController::class, 'index'])
                ->name('admin.agencies.index');
        Route::get('/agences/creer', [AgencyController::class, 'create'])
                ->name('admin.agencies.create');
        Route::post('/agences', [AgencyController::class, 'store'])
                ->name('admin.agencies.store');
        Route::get('/agences/{agency}', [AgencyController::class, 'show'])
                ->name('admin.agencies.show');
        Route::put('/agences/{agency}', [AgencyController::class, 'update'])
                ->name('admin.agencies.update');
        Route::post('/agences/{agency}/toggle', [AgencyController::class, 'toggle'])
                ->name('admin.agencies.toggle');

        // Villes desservies
        Route::post('/agences/{agency}/villes', [AgencyController::class, 'addCity'])
                ->name('admin.agencies.cities.add');
        Route::post('/agences/villes/{city}/toggle', [AgencyController::class, 'toggleCity'])
                ->name('admin.agencies.cities.toggle');

        // Comptoirs
        Route::post('/agences/{agency}/comptoirs', [AgencyController::class, 'storeCounter'])
                ->name('admin.agencies.counters.store');
        Route::post('/agences/comptoirs/{counter}/toggle', [AgencyController::class, 'toggleCounter'])
                ->name('admin.agencies.counters.toggle');

        // Secrétaires
        Route::post('/agences/comptoirs/{counter}/secretaire', [AgencyController::class, 'storeSecretary'])
                ->name('admin.agencies.secretary.store');
});


// Dans routes/web.php — pas besoin d'auth car données publiques
Route::get('/api/agences/{agency}/comptoirs', function (Agency $agency, Request $request) {
        $city     = $request->get('city');
        $counters = AgencyCounter::where('agency_id', $agency->id)
                ->where('city', $city)
                ->where('is_active', true)
                ->get(['id', 'city', 'district', 'landmark']);

        return response()->json($counters);
});

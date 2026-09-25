<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Seller\KycSellerController;
use App\Http\Controllers\DiditKycCallbackController;
use App\Http\Controllers\DiditWebhookController;
use App\Http\Controllers\Admin\KycAdminController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Seller\ProductController;
use App\Http\Controllers\Buyer\CatalogController;
use App\Http\Controllers\Seller\ShopController;
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
use App\Http\Controllers\Seller\WalletController;
use App\Http\Controllers\Buyer\WalletController as BuyerWalletcontroller;
use App\Http\Controllers\Admin\AgencyController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\FinancialEngineController;
use App\Http\Controllers\Admin\TreasuryController;
use App\Http\Controllers\Admin\TutorialController as AdminTutorialController;
use App\Http\Controllers\TutorialController;
use App\Http\Controllers\Buyer\CartController;
use App\Http\Controllers\Buyer\ProfileController as BuyerProfileController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;

use App\Http\Controllers\Buyer\WishlistController;
use App\Http\Controllers\Agency\DashboardController as AgencyDashboard;

// Route::get('/', function () {
//         return view('welcome');
// });


require 'auth.php';


// Vendeur — KYC
Route::middleware(['auth', 'check.status', 'verified', 'role:seller'])->prefix('seller')->group(function () {
        Route::get('/kyc', [KycSellerController::class, 'create'])->name('seller.kyc.create');
        Route::post('/kyc/start', [KycSellerController::class, 'start'])->name('seller.kyc.start');
        Route::get('/kyc/attente', [KycSellerController::class, 'pending'])->name('seller.kyc.pending');
        Route::get('/kyc/rejected', [KycSellerController::class, 'rejected'])->name('seller.kyc.rejected');
});

// Callback Didit : public car Didit peut terminer la vérification sur un autre appareil.
Route::get('/didit/kyc/callback', DiditKycCallbackController::class)->name('didit.kyc.callback');
Route::post('/didit/webhook', DiditWebhookController::class)->name('didit.webhook');

// Admin — KYC
Route::middleware(['auth', 'verified', 'role:admin', 'check.status'])->prefix('admin')->group(function () {
        Route::get('/kyc/file', [KycAdminController::class, 'serveFile'])->name('admin.kyc.file');
        Route::get('/kyc', [KycAdminController::class, 'index'])->name('admin.kyc.index');
        Route::get('/kyc/{kyc}', [KycAdminController::class, 'show'])->name('admin.kyc.show');
        Route::post('/kyc/{kyc}/approuver', [KycAdminController::class, 'approve'])->name('admin.kyc.approve');
        Route::post('/kyc/{kyc}/rejeter', [KycAdminController::class, 'reject'])->name('admin.kyc.reject');
});

//boutique -  vendeur
Route::middleware(['auth', 'verified', 'role:seller', 'check.status'])->prefix('vendeur')->group(function () {
        Route::get('/boutique/creer', [ShopController::class, 'create'])->name('seller.shop.create');
        Route::post('/boutique', [ShopController::class, 'store'])->name('seller.shop.store');

        Route::middleware(['shop.active'])->group(function () {
                Route::get('/boutique/modifier', [ShopController::class, 'edit'])->name('seller.shop.edit');
                Route::put('/boutique', [ShopController::class, 'update'])->name('seller.shop.update');
        });
});




Route::middleware(['auth', 'verified', 'role:buyer', 'check.status'])->prefix('acheteur')->group(function () {
        Route::get('/dashboard', [BuyerDashboardController::class, 'index'])->name('buyer.dashboard');
});

// ── Catalogue public ──────────────────────────────────────────────
Route::get('/', [CatalogController::class, 'index'])->name('buyer.home');
Route::get('/produit/{product}', [CatalogController::class, 'show'])->name('product.show');
Route::get('/boutique/{shop}', [CatalogController::class, 'shop'])->name('shop.show');

// ── Produits vendeur ──────────────────────────────────────────────
// Route::middleware(['auth', 'role:seller'])->prefix('vendeur')->group(function () {});

Route::middleware(['auth', 'verified', 'role:seller', 'shop.active', 'check.status'])->prefix('vendeur')->group(function () {
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
        Route::get('/mon-historique', [BuyerWalletcontroller::class, 'history'])
                ->name('seller.wallet.history');
});



// ── Commandes vendeur ─────────────────────────────────────────────
Route::middleware(['auth', 'verified', 'role:seller', 'shop.active', 'check.status'])->prefix('vendeur')->group(function () {
        Route::get('/commandes', [SellerOrderController::class, 'index'])->name('seller.orders.index');
        Route::get('/commandes/{order}', [SellerOrderController::class, 'show'])->name('seller.orders.show');
        // Route::post('/commandes/{order}/preparer', [SellerOrderController::class, 'markPreparing'])->name('seller.orders.preparing');
        Route::post('/commandes/{order}/preparer', [SellerOrderController::class, 'prepare'])
                ->name('seller.orders.prepare');
});


// ── Commandes acheteur ────────────────────────────────────────────
Route::middleware(['auth', 'verified', 'role:buyer', 'check.status'])->prefix('commandes')->group(function () {
        Route::get('/', [BuyerOrderController::class, 'index'])->name('buyer.orders.index');
        Route::get('/passer/{product}', [BuyerOrderController::class, 'create'])->name('buyer.orders.create');
        Route::post('/', [BuyerOrderController::class, 'store'])->name('buyer.orders.store');
        Route::get('/{order}', [BuyerOrderController::class, 'show'])->name('buyer.orders.show');
        Route::post('/{order}/annuler', [BuyerOrderController::class, 'cancel'])->name('buyer.orders.cancel');
});




// Webhook Campay — pas de middleware auth (appelé par Campay)
// Protection assurée par la vérification de signature HMAC
Route::post('/webhooks/elgiopay', [WebhookController::class, 'elgiopay'])
        ->name('payment.webhook.elgiopay');

// Pages paiement acheteur
Route::middleware(['auth', 'verified', 'role:buyer', 'check.status'])->group(function () {

        Route::get('/paiement/{order}/initier', [PaymentController::class, 'initiate'])
                ->name('buyer.payment.initiate');
        Route::get('/paiement/{order}/attente', [PaymentController::class, 'waiting'])
                ->name('buyer.payment.waiting');
        Route::get('/commandes/{order}/statut', [PaymentController::class, 'status'])
                ->name('buyer.orders.status');
});
// Pages paiement acheteur groupper
Route::middleware(['auth', 'verified', 'role:buyer', 'check.status'])->group(function () {

        Route::get('/paiement/groupe/{group}/initier', [PaymentController::class, 'initiateGroup'])
                ->name('buyer.payment.group.initiate');
        Route::get('/paiement/groupe/{group}/attente', [PaymentController::class, 'waitingGroup'])
                ->name('buyer.payment.group.waiting');
        Route::get('/achats/{group}/statut', [PaymentController::class, 'statusGroup'])
                ->name('buyer.payment.group.status');
        Route::get('/achats/{group}', [BuyerOrderController::class, 'showGroup'])
                ->name('buyer.orders.group.show');
});

// Paiement frais transport acheteur
Route::middleware(['auth', 'role:buyer', 'verified', 'check.status'])->group(function () {

        Route::get('/commandes/{order}/transport', [BuyerOrderController::class, 'transportPayment'])
                ->name('buyer.orders.transport');

        Route::post('/commandes/{order}/transport/payer', [BuyerOrderController::class, 'payTransport'])
                ->name('buyer.orders.transport.pay');

        // Page d'attente dédiée au paiement transport
        Route::get('/commandes/{order}/transport/attente', [BuyerOrderController::class, 'transportWaiting'])
                ->name('buyer.orders.transport.waiting');

        // Vérification statut paiement transport (polling JS)
        Route::get('/commandes/{order}/transport/statut', [BuyerOrderController::class, 'transportStatus'])
                ->name('buyer.orders.transport.status');
});



//messagerie
Route::middleware(['auth', 'verified', 'check.status'])->group(function () {

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




// Portefeuille vendeur
Route::middleware(['auth', 'verified', 'role:seller', 'shop.active', 'check.status'])->prefix('vendeur')->group(function () {
        Route::get('/portefeuille', [WalletController::class, 'index'])
                ->name('seller.wallet.index');
        Route::get('/portefeuille/retrait', [WalletController::class, 'withdrawForm'])
                ->name('seller.wallet.withdraw');
        Route::post('/portefeuille/retrait', [WalletController::class, 'withdraw'])
                ->name('seller.wallet.withdraw.post');
});


Route::middleware(['auth', 'role:secretary', 'check.status'])
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





// ── Litiges acheteur ──────────────────────────────────────────────
Route::middleware(['auth', 'role:buyer', 'verified', 'check.status'])->group(function () {
        // Liste des litiges de l'acheteur
        Route::get('/litiges', [BuyerDisputeController::class, 'index'])
                ->name('buyer.disputes.index');
        // Ouvrir un litige
        Route::get('/commandes/{order}/litige', [BuyerDisputeController::class, 'create'])
                ->name('buyer.disputes.create');
        Route::post('/commandes/{order}/litige', [BuyerDisputeController::class, 'store'])
                ->name('buyer.disputes.store');
        // Détail d'un litige
        Route::get('/litiges/{dispute}', [BuyerDisputeController::class, 'show'])
                ->name('buyer.disputes.show');
});

// ── Litiges vendeur ───────────────────────────────────────────────
Route::middleware(['auth', 'role:seller', 'shop.active', 'check.status'])->prefix('vendeur')->group(function () {
        Route::get('/litiges', [SellerDisputeController::class, 'index'])
                ->name('seller.disputes.index');
        Route::get('/litiges/{dispute}', [SellerDisputeController::class, 'show'])
                ->name('seller.disputes.show');
        Route::post('/litiges/{dispute}/repondre', [SellerDisputeController::class, 'reply'])
                ->name('seller.disputes.reply');
});




// ── Litiges admin ─────────────────────────────────────────────────
Route::middleware(['auth', 'role:admin', 'check.status'])->prefix('admin')->group(function () {
        Route::get('/litiges', [AdminDisputeController::class, 'index'])
                ->name('admin.disputes.index');
        Route::get('/litiges/{dispute}', [AdminDisputeController::class, 'show'])
                ->name('admin.disputes.show');
        Route::post('/litiges/{dispute}/resoudre', [AdminDisputeController::class, 'resolve'])
                ->name('admin.disputes.resolve');
});

// Avis acheteur
Route::middleware(['auth', 'verified', 'role:buyer', 'check.status'])->group(function () {
        Route::get('/commandes/{order}/noter', [BuyerReviewController::class, 'create'])
                ->name('buyer.reviews.create');
        Route::post('/commandes/{order}/noter', [BuyerReviewController::class, 'store'])
                ->name('buyer.reviews.store');
        // Dans le groupe buyer

});

// Avis vendeur sur acheteur
Route::middleware(['auth', 'verified', 'role:seller', 'shop.active', 'check.status'])->prefix('vendeur')->group(function () {
        Route::get('/commandes/{order}/noter-acheteur', [SellerReviewController::class, 'create'])
                ->name('seller.reviews.create');
        Route::post('/commandes/{order}/noter-acheteur', [SellerReviewController::class, 'store'])
                ->name('seller.reviews.store');
});



Route::middleware(['auth', 'verified', 'role:admin', 'check.status'])->prefix('admin')->group(function () {

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

        // Admin — historique de n'importe quel user
        Route::get('/users/{user}/history', [AdminDashboardController::class, 'userHistory'])
                ->name('admin.users.history');

        Route::get('/agences/gains', [AdminDashboardController::class, 'agencies'])
                ->name('admin.agencies.earnings');
        Route::get('/agences/{agency}/historique', [AdminDashboardController::class, 'agencyHistory'])
                ->name('admin.agencies.history');



        Route::get('/utilisateurs/notes-basses', [AdminDashboardController::class, 'lowScoreUsers'])
                ->name('admin.users.low-scores');


        //finacialengine
        Route::get('/moteur-financier', [FinancialEngineController::class, 'index'])
                ->name('admin.financial-engine.index');
        Route::post('/moteur-financier', [FinancialEngineController::class, 'update'])
                ->name('admin.financial-engine.update');

        Route::get('/tresorerie', [TreasuryController::class, 'index'])->name('admin.treasury.index');
        Route::post('/tresorerie/retrait', [TreasuryController::class, 'withdraw'])->name('admin.treasury.withdraw');
});



Route::middleware(['auth', 'role:admin', 'check.status'])->prefix('admin')->group(function () {

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


        //manager agency
        Route::post('/agences/{agency}/manager', [AgencyController::class, 'storeManager'])
                ->name('admin.agencies.manager.store');

        // Nouvelle — modifier le manager
        Route::put('/agences/managers/{manager}', [AgencyController::class, 'updateManager'])
                ->name('admin.agencies.manager.update');


        Route::post('/agences/{agency}/momo', [AgencyController::class, 'updateAgencyMomo'])
                ->name('admin.agencies.momo.update');
});



// Tutoriels publics (acheteurs et vendeurs connectés)
Route::middleware('auth', 'check.status')->group(function () {
        Route::get('/tutoriels', [TutorialController::class, 'index'])
                ->name('tutorials.index');
        Route::get('/tutoriels/{tutorial}', [TutorialController::class, 'show'])
                ->name('tutorials.show');
});


// Admin — gestion tutoriels
Route::middleware(['auth', 'role:admin', 'check.status'])->prefix('admin')->group(function () {
        Route::get('/tutoriels', [AdminTutorialController::class, 'index'])
                ->name('admin.tutorials.index');
        Route::get('/tutoriels/creer', [AdminTutorialController::class, 'create'])
                ->name('admin.tutorials.create');
        Route::post('/tutoriels', [AdminTutorialController::class, 'store'])
                ->name('admin.tutorials.store');
        Route::get('/tutoriels/{tutorial}', [AdminTutorialController::class, 'show'])
                ->name('admin.tutorials.show');
        Route::get('/tutoriels/{tutorial}/modifier', [AdminTutorialController::class, 'edit'])
                ->name('admin.tutorials.edit');
        Route::put('/tutoriels/{tutorial}', [AdminTutorialController::class, 'update'])
                ->name('admin.tutorials.update');
        Route::post('/tutoriels/{tutorial}/toggle', [AdminTutorialController::class, 'toggle'])
                ->name('admin.tutorials.toggle');
        Route::delete('/tutoriels/{tutorial}', [AdminTutorialController::class, 'destroy'])
                ->name('admin.tutorials.destroy');
});


// Routes à ajouter si elles n'existent pas déjà.
// Adapte uniquement les noms de contrôleurs si ton projet utilise une autre organisation.

Route::view('/aide', 'pages.help')->name('help');
Route::view('/a-propos', 'pages.about')->name('about');
Route::view('/how-it-work','pages.work')->name('work');

Route::middleware(['auth', 'role:buyer', 'verified', 'check.status'])->group(function () {

        // ── Panier ────────────────────────────────────────────────────────
        Route::get('/panier', [CartController::class, 'index'])
                ->name('buyer.cart.index');
        Route::post('/panier/{product}/ajouter', [CartController::class, 'add'])
                ->name('buyer.cart.add');
        Route::patch('/panier/item/{item}/', [CartController::class, 'update'])
                ->name('buyer.cart.update');
        Route::delete('/panier/item/{item}', [CartController::class, 'remove'])
                ->name('buyer.cart.remove');
        Route::get('/panier/checkout', [CartController::class, 'checkout'])
                ->name('buyer.cart.checkout');
        Route::post('/panier/commander', [CartController::class, 'confirmOrder'])
                ->name('buyer.cart.order');

        // ── Favoris ───────────────────────────────────────────────────────
        Route::get('/favoris', [WishlistController::class, 'index'])
                ->name('buyer.wishlist.index');
        Route::post('/favoris/{product}', [WishlistController::class, 'toggle'])
                ->name('buyer.wishlist.toggle');


        // historique des transaction
        Route::get('/mon-historique', [BuyerWalletcontroller::class, 'history'])
                ->name('buyer.wallet.history');


        Route::get('/mon-profil', [BuyerProfileController::class, 'index'])
                ->name('buyer.profile');
});





Route::middleware(['auth', 'role:agency_manager', 'check.status'])
        ->prefix('agence-manager')
        ->group(function () {

                // Dashboard
                Route::get('/dashboard', [AgencyDashboard::class, 'index'])
                        ->name('agency.dashboard');

                // Comptoirs
                Route::post('/comptoirs', [AgencyDashboard::class, 'storeCounter'])
                        ->name('agency.counters.store');
                Route::post('/comptoirs/{counter}/toggle', [AgencyDashboard::class, 'toggleCounter'])
                        ->name('agency.counters.toggle');

                // Secrétaires
                Route::post('/comptoirs/{counter}/secretaire', [AgencyDashboard::class, 'storeSecretary'])
                        ->name('agency.secretary.store');
                Route::put('/secretaires/{secretary}', [AgencyDashboard::class, 'updateSecretary'])
                        ->name('agency.secretary.update');
                Route::post('/secretaires/{secretary}/toggle', [AgencyDashboard::class, 'toggleSecretary'])
                        ->name('agency.secretary.toggle');
                Route::delete('/secretaires/{secretary}', [AgencyDashboard::class, 'deleteSecretary'])
                        ->name('agency.secretary.delete');

                // Wallet + retrait
                Route::get('/wallet', [AgencyDashboard::class, 'wallet'])
                        ->name('agency.wallet');
                Route::post('/retrait', [AgencyDashboard::class, 'withdraw'])
                        ->name('agency.withdraw');
        });



Route::middleware(['auth', 'check.status'])->prefix('admin')->name('admin.')->group(function () {
        Route::resource('categories', CategoryController::class)->except(['create', 'show', 'edit']);
        Route::patch('categories/{category}/toggle-status', [CategoryController::class, 'toggleStatus'])->name('categories.toggle-status');
});




Route::middleware(['auth', 'verified', 'role:admin', 'check.status'])
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
                /*
    |--------------------------------------------------------------------------
    | PRODUITS
    |--------------------------------------------------------------------------
    */
                Route::get('/produits', [
                        AdminProductController::class,
                        'index'
                ])->name('products.index');

                Route::get('/produits/moderation', [
                        AdminProductController::class,
                        'moderation'
                ])->name('products.moderation');

                Route::get('/produits/{product}', [
                        AdminProductController::class,
                        'show'
                ])->name('products.show');

                Route::post('/produits/{product}/masquer', [
                        AdminProductController::class,
                        'hide'
                ])->name('products.hide');

                Route::post('/produits/{product}/visible', [
                        AdminProductController::class,
                        'unhide'
                ])->name('products.unhide');

                Route::post('/produits/{product}/bannir', [
                        AdminProductController::class,
                        'ban'
                ])->name('products.ban');

                Route::post('/produits/{product}/rehabiliter', [
                        AdminProductController::class,
                        'unban'
                ])->name('products.unban');

                Route::delete('/produits/{product}', [
                        AdminProductController::class,
                        'destroy'
                ])
                        ->withTrashed()
                        ->name('products.destroy');

                Route::post('/produits/{product}/restaurer', [
                        AdminProductController::class,
                        'restore'
                ])
                        ->withTrashed()
                        ->name('products.restore');


                /*
    |--------------------------------------------------------------------------
    | BOUTIQUES
    |--------------------------------------------------------------------------
    */

                Route::post('/boutiques/{shop}/suspendre', [
                        AdminProductController::class,
                        'suspendShop'
                ])->name('products.shop.suspend');

                Route::post('/boutiques/{shop}/activer', [
                        AdminProductController::class,
                        'activateShop'
                ])->name('products.shop.activate');

                Route::post('/boutiques/{shop}/bannir', [
                        AdminProductController::class,
                        'banShop'
                ])->name('products.shop.ban');


                /*
    |--------------------------------------------------------------------------
    | VENDEURS
    |--------------------------------------------------------------------------
    */

                Route::post('/vendeurs/{user}/suspendre', [
                        AdminProductController::class,
                        'suspendSeller'
                ])->name('products.seller.suspend');

                Route::post('/vendeurs/{user}/activer', [
                        AdminProductController::class,
                        'activateSeller'
                ])->name('products.seller.activate');

                Route::post('/vendeurs/{user}/bannir', [
                        AdminProductController::class,
                        'banSeller'
                ])->name('products.seller.ban');
        });










use App\Http\Controllers\Api\NotificationController;
use App\Services\ElgiopayService;

// Accessible à tout utilisateur connecté (acheteur, vendeur, admin) —
// pas de middleware 'role:' spécifique puisque la cloche est commune.
Route::middleware('auth', 'check.status')->prefix('notifications')->name('notifications.')->group(function () {

        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::get('/unread-count', [NotificationController::class, 'unreadCount'])->name('unread-count');
        Route::post('/{id}/read', [NotificationController::class, 'markAsRead'])->name('read');
        Route::post('/read-all', [NotificationController::class, 'markAllAsRead'])->name('read-all');
});

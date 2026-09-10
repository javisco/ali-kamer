<?php

namespace App\Services;

use App\Models\Dispute;
use App\Models\DisputeEvidence;
use App\Models\Order;
use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Notifications\Disputes\DisputeOpenedNotification;
use App\Notifications\Disputes\DisputeResolvedNotification;
use App\Notifications\Disputes\AdminDisputeOpenedNotification;
use Illuminate\Support\Facades\Notification as NotificationFacade;

class DisputeService
{
    public function __construct(
        private ElgiopayService $elgiopay,
        private WalletService $wallet
    ) {}

    // ── OUVRIR UN LITIGE ──────────────────────────────────────────────
    // INCHANGÉ.
    public function open(
        Order $order,
        User $initiator,
        string $type,
        string $description,
        array $files = []
    ): Dispute {

        if (! $order->canBeDisputed()) {
            throw new \Exception('Cette commande ne peut pas faire l\'objet d\'un litige.');
        }

        if ($order->dispute && $order->dispute->isOpen()) {
            throw new \Exception('Un litige est déjà ouvert pour cette commande.');
        }

        return DB::transaction(function () use (
            $order,
            $initiator,
            $type,
            $description,
            $files
        ) {
            $dispute = Dispute::create([
                'order_id'              => $order->id,
                'initiator_id'          => $initiator->id,
                'type'                  => $type,
                'description'           => $description,
                'status'                => 'open',
                'seller_reply_deadline' => now()->addHours(48),
            ]);

            foreach ($files as $file) {
                $this->addEvidence($dispute, $initiator, $file);
            }

            $order->update(['status' => Order::STATUS_DISPUTED]);
          //  $order->shop->user->notify(new DisputeOpenedNotification($dispute));
          //  NotificationFacade::send(                                    // ← AJOUT
          //      User::where('role', 'admin')->get(),                     // ← AJOUT
          //      new AdminDisputeOpenedNotification($dispute)              // ← AJOUT
          //  );

            return $dispute;
        });
    }

    // ── RÉPONDRE AU LITIGE (vendeur) ──────────────────────────────────
    // INCHANGÉ.
    public function reply(
        Dispute $dispute,
        User $seller,
        string $response,
        array $files = []
    ): void {

        DB::transaction(function () use ($dispute, $seller, $response, $files) {

            DisputeEvidence::create([
                'dispute_id'   => $dispute->id,
                'submitted_by' => $seller->id,
                'type'         => 'text',
                'content'      => $response,
                'description'  => 'Réponse du vendeur',
            ]);

            foreach ($files as $file) {
                $this->addEvidence($dispute, $seller, $file);
            }

            $dispute->update(['status' => 'seller_replied']);
        });
    }

    // ── RÉSOUDRE ET APPLIQUER ─────────────────────────────────────────
    // INCHANGÉ.
    public function resolve(
        Dispute $dispute,
        User $admin,
        string $resolution,
        string $note,
        ?int $resolutionAmount = null
    ): void {

        DB::transaction(function () use (
            $dispute,
            $admin,
            $resolution,
            $note,
            $resolutionAmount
        ) {
            $dispute->update([
                'status'            => 'resolved',
                'resolution'        => $resolution,
                'resolution_note'   => $note,
                'resolution_amount' => $resolutionAmount,
                'resolver_id'       => $admin->id,
                'resolved_at'       => now(),
            ]);

            $this->applyResolution($dispute);
          //  $dispute->order->buyer->notify(new DisputeResolvedNotification($dispute, 'buyer'));   // ← AJOUT
         //   $dispute->order->shop->user->notify(new DisputeResolvedNotification($dispute, 'seller')); // ← AJOUT

            $dispute->order->update([
                'status'       => Order::STATUS_COMPLETED,
                'completed_at' => now(),
            ]);
        });
    }

    // ── APPLICATION FINANCIÈRE ────────────────────────────────────────
    // RÈGLE MÉTIER INCHANGÉE :
    // - Acheteur → remboursement DIRECT via Elgiopay (virement MoMo)
    // - Vendeur  → on crédite/débite son WALLET uniquement
    private function applyResolution(Dispute $dispute): void
    {
        $order  = $dispute->order->load(['buyer', 'shop.user', 'payment']);
        $buyer  = $order->buyer;
        $seller = $order->shop->user;

        match ($dispute->resolution) {
            'refund_buyer'     => $this->refundBuyer($buyer, $order->total_amount, $order),
            'pay_seller'       => $this->creditSellerWallet($seller, $order->net_amount, $order),
            'partial_refund'   => $this->applyPartialRefund($dispute),
            'return_required'  => $this->refundBuyer($buyer, $order->total_amount, $order),
            'buyer_bad_faith'  => $this->creditSellerWallet($seller, $order->net_amount, $order),
            default            => Log::warning('Résolution inconnue', [
                'resolution' => $dispute->resolution
            ]),
        };
    }

    // ── REMBOURSEMENT ACHETEUR (virement Elgiopay direct) ──────────────
    // Gross-Up INCHANGÉ. Seul ajout par rapport à Campay : operator +
    // recipientName requis par l'API Elgiopay pour le payout — on utilise
    // l'opérateur enregistré sur le paiement d'origine de la commande
    // ($order->payment->payer_operator), puisque l'acheteur n'a pas de
    // wallet ni de profil "opérateur MoMo" dédié comme le vendeur.
    private function refundBuyer(User $buyer, int $netAmount, Order $order): void
    {
        $phone = '237' . ltrim($order->payment->payer_phone, '0');

        $grossUp     = $this->elgiopay->grossUpPayout($netAmount);
        $grossAmount = $grossUp['gross'];
        $gatewayFee  = $grossUp['fee'];

        try {
            $this->elgiopay->disburse(
                phone: $phone,
                grossAmount: $grossAmount,
                reference: 'REFUND-' . $order->reference . '-' . Str::uuid(),
                description: "Remboursement litige {$order->reference}",
                operator: $order->payment->payer_operator ?? 'mtn',
                recipientName: $buyer->name
            );

            WalletTransaction::create([
                'user_id'       => $buyer->id,
                'type'          => WalletTransaction::TYPE_CREDIT_REFUND,
                'amount'        => $netAmount,
                'balance_after' => 0,
                'ref_type'      => 'order',
                'ref_id'        => $order->id,
                'note'          => sprintf(
                    'Remboursement %s — reçu: %s FCFA, envoyé Elgiopay: %s FCFA (frais plateforme: %s FCFA)',
                    $order->reference,
                    number_format($netAmount, 0, ',', ' '),
                    number_format($grossAmount, 0, ',', ' '),
                    number_format($gatewayFee, 0, ',', ' ')
                ),
            ]);

            $seller = $order->shop->user;
            // Déterminer le montant net à débiter du vendeur (plafonné au net_amount)
            $sellerDeduct = min($order->net_amount, $netAmount);

            if ($sellerDeduct > 0) {
                // Débiter d'abord depuis le séquestre sans provoquer de négatif
                $fromPending = min($seller->wallet_pending, $sellerDeduct);
                if ($fromPending > 0) {
                    $seller->decrement('wallet_pending', $fromPending);
                }

                // Si le reste provient de fonds déjà libérés (disponible)
                $remainingDeduct = $sellerDeduct - $fromPending;
                if ($remainingDeduct > 0) {
                    $fromAvailable = min($seller->wallet_available, $remainingDeduct);
                    if ($fromAvailable > 0) {
                        $seller->decrement('wallet_available', $fromAvailable);
                    }
                }

                $seller->refresh();

                // Enregistrer la transaction pour le vendeur afin que son portefeuille reste cohérent
                WalletTransaction::create([
                    'user_id'       => $seller->id,
                    'type'          => WalletTransaction::TYPE_CREDIT_REFUND,
                    'amount'        => $sellerDeduct,
                    'balance_after' => $seller->wallet_available,
                    'ref_type'      => 'order',
                    'ref_id'        => $order->id,
                    'note'          => "Débit suite à remboursement litige {$order->reference}",
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Buyer refund failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    // ── CRÉDITER LE WALLET VENDEUR (pas de virement direct) ──────────
    // INCHANGÉ.
    private function creditSellerWallet(User $seller, int $amount, Order $order): void
    {
        DB::transaction(function () use ($seller, $amount, $order) {
            $this->wallet->releaseEscrow($seller, $amount, $order);

            Log::info('Seller wallet credited', [
                'order'  => $order->reference,
                'amount' => $amount,
                'seller' => $seller->id,
            ]);
        });
    }

    // ── REMBOURSEMENT PARTIEL ─────────────────────────────────────────
    // INCHANGÉ.
    private function applyPartialRefund(Dispute $dispute): void
    {
        $order        = $dispute->order;
        $refundAmount = $dispute->resolution_amount ?? 0;
        $sellerAmount = max(0, $order->net_amount - $refundAmount);

        if ($refundAmount > 0) {
            $this->refundBuyer($order->buyer, $refundAmount, $order);
        }

        if ($sellerAmount > 0) {
            $this->creditSellerWallet($order->shop->user, $sellerAmount, $order);
        }
    }

    // ── AJOUTER UNE PREUVE ────────────────────────────────────────────
    // INCHANGÉ.
    public function addEvidence(
        Dispute $dispute,
        User $submitter,
        UploadedFile $file
    ): DisputeEvidence {

        $type = str_starts_with($file->getMimeType(), 'image/') ? 'photo' : 'document';
        $path = $file->store("disputes/{$dispute->id}", 'public');

        return DisputeEvidence::create([
            'dispute_id'   => $dispute->id,
            'submitted_by' => $submitter->id,
            'type'         => $type,
            'url'          => $path,
            'description'  => $file->getClientOriginalName(),
        ]);
    }
}

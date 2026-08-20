<?php

namespace App\Services;

use App\Models\Dispute;
use App\Models\DisputeEvidence;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DisputeService
{
    public function __construct(
        private CampayService $campay,
        private WalletService $wallet
    ) {}

    // ── OUVRIR UN LITIGE ──────────────────────────────────────────────

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

        return DB::transaction(function () use ($order, $initiator, $type, $description, $files) {

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

            return $dispute;
        });
    }

    // ── RÉPONDRE AU LITIGE (vendeur) ──────────────────────────────────

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

    // ── RÉSOUDRE ET APPLIQUER AUTOMATIQUEMENT ────────────────────────

    // Dès que l'admin clique "Résoudre", les fonds sont versés immédiatement
    // Pas de validation manuelle — application directe via Campay
    public function resolve(
        Dispute $dispute,
        User $admin,
        string $resolution,
        string $note,
        ?int $resolutionAmount = null
    ): void {

        DB::transaction(function () use (
            $dispute, $admin, $resolution, $note, $resolutionAmount
        ) {
            // 1. Enregistrer la décision
            $dispute->update([
                'status'            => 'resolved',
                'resolution'        => $resolution,
                'resolution_note'   => $note,
                'resolution_amount' => $resolutionAmount,
                'resolver_id'       => $admin->id,
                'resolved_at'       => now(),
            ]);

            // 2. Appliquer financièrement — immédiatement
            $this->applyResolution($dispute);

            // 3. Terminer la commande
            $dispute->order->update([
                'status'       => Order::STATUS_COMPLETED,
                'completed_at' => now(),
            ]);
        });
    }

    // ── APPLICATION FINANCIÈRE DIRECTE ───────────────────────────────

    private function applyResolution(Dispute $dispute): void
    {
        $order  = $dispute->order->load(['buyer', 'shop.user', 'payment']);
        $buyer  = $order->buyer;
        $seller = $order->shop->user;

        match($dispute->resolution) {

            // Remboursement intégral → acheteur reçoit le total payé
            'refund_buyer' => $this->refundBuyer(
                $buyer,
                $order->total_amount,
                $order,
                'Remboursement intégral — litige résolu en faveur acheteur'
            ),

            // Payer le vendeur → vendeur reçoit son montant net
            'pay_seller' => $this->paySeller(
                $seller,
                $order->net_amount,
                $order,
                'Paiement vendeur — litige résolu en faveur vendeur'
            ),

            // Remboursement partiel
            // L'acheteur reçoit resolution_amount
            // Le vendeur reçoit le reste
            'partial_refund' => $this->applyPartialRefund($dispute),

            // Retour requis — on rembourse l'acheteur
            // Le vendeur sera payé après retour confirmé (géré manuellement)
            'return_required' => $this->refundBuyer(
                $buyer,
                $order->total_amount,
                $order,
                'Remboursement — retour produit requis'
            ),

            // Acheteur de mauvaise foi → payer le vendeur
            'buyer_bad_faith' => $this->paySeller(
                $seller,
                $order->net_amount,
                $order,
                'Paiement vendeur — acheteur de mauvaise foi'
            ),

            default => Log::warning('Resolution inconnue', ['resolution' => $dispute->resolution]),
        };
    }

    // ── REMBOURSER L'ACHETEUR VIA CAMPAY ─────────────────────────────

    private function refundBuyer(
        User $buyer,
        int $amount,
        Order $order,
        string $description
    ): void {

        // Numéro MoMo de l'acheteur = numéro utilisé au paiement
        $phone = '237' . ltrim($order->payment->payer_phone, '0');

        try {
            // Virement direct via Campay
            $this->campay->disburse(
                phone: $phone,
                amount: $amount,
                reference: 'REFUND-' . $order->reference . '-' . Str::uuid(),
                description: $description
            );

            // Enregistrer dans wallet_transactions pour audit
            $this->wallet->refund($buyer, $amount, $order);

            Log::info('Buyer refunded', [
                'order'  => $order->reference,
                'amount' => $amount,
                'phone'  => $phone,
            ]);

        } catch (\Exception $e) {
            Log::error('Buyer refund failed', [
                'order' => $order->reference,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    // ── PAYER LE VENDEUR VIA CAMPAY ───────────────────────────────────

    private function paySeller(
        User $seller,
        int $amount,
        Order $order,
        string $description
    ): void {

        if (! $seller->phone_momo) {
            throw new \Exception(
                "Le vendeur n'a pas de numéro MoMo enregistré."
            );
        }

        $phone = '237' . ltrim($seller->phone_momo, '0');

        try {
            // Virement direct via Campay
            $this->campay->disburse(
                phone: $phone,
                amount: $amount,
                reference: 'SELLER-' . $order->reference . '-' . Str::uuid(),
                description: $description
            );

            // Libérer le séquestre dans le wallet
            $this->wallet->releaseEscrow($seller, $amount, $order);

            Log::info('Seller paid', [
                'order'  => $order->reference,
                'amount' => $amount,
                'phone'  => $phone,
            ]);

        } catch (\Exception $e) {
            Log::error('Seller payment failed', [
                'order' => $order->reference,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    // ── REMBOURSEMENT PARTIEL ─────────────────────────────────────────

    private function applyPartialRefund(Dispute $dispute): void
    {
        $order         = $dispute->order;
        $refundAmount  = $dispute->resolution_amount ?? 0;
        $sellerAmount  = max(0, $order->net_amount - $refundAmount);

        // Rembourser l'acheteur partiellement
        if ($refundAmount > 0) {
            $this->refundBuyer(
                $order->buyer,
                $refundAmount,
                $order,
                "Remboursement partiel {$refundAmount} FCFA — litige {$order->reference}"
            );
        }

        // Payer le vendeur le reste
        if ($sellerAmount > 0) {
            $this->paySeller(
                $order->shop->user,
                $sellerAmount,
                $order,
                "Paiement partiel {$sellerAmount} FCFA — litige {$order->reference}"
            );
        }
    }

    // ── AJOUTER UNE PREUVE ────────────────────────────────────────────

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
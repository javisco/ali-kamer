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

            // Enregistrer la réponse comme preuve texte
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
            // Enregistrer la décision
            $dispute->update([
                'status'            => 'resolved',
                'resolution'        => $resolution,
                'resolution_note'   => $note,
                'resolution_amount' => $resolutionAmount,
                'resolver_id'       => $admin->id,
                'resolved_at'       => now(),
            ]);

            // Appliquer immédiatement
            $this->applyResolution($dispute);

            // Clôturer la commande
            $dispute->order->update([
                'status'       => Order::STATUS_COMPLETED,
                'completed_at' => now(),
            ]);
        });
    }

    // ── APPLICATION FINANCIÈRE ────────────────────────────────────────

    // RÈGLE MÉTIER :
    // - Acheteur    → remboursement DIRECT via Campay (virement MoMo)
    // - Vendeur     → on crédite/débite son WALLET uniquement
    //                 (il fait lui-même le retrait quand il veut)
    // Traçabilité   → toujours enregistrée dans wallet_transactions
    private function applyResolution(Dispute $dispute): void
    {
        $order  = $dispute->order->load(['buyer', 'shop.user', 'payment']);
        $buyer  = $order->buyer;
        $seller = $order->shop->user;

        match ($dispute->resolution) {

            // Acheteur avait raison → remboursement MoMo direct + trace BDD
            'refund_buyer' => $this->refundBuyer($buyer, $order->total_amount, $order),

            // Vendeur avait raison → créditer son wallet (pas de virement direct)
            'pay_seller' => $this->creditSellerWallet($seller, $order->net_amount, $order),

            // Remboursement partiel → acheteur via MoMo + vendeur via wallet
            'partial_refund' => $this->applyPartialRefund($dispute),

            // Retour requis → rembourser l'acheteur via MoMo
            'return_required' => $this->refundBuyer($buyer, $order->total_amount, $order),

            // Acheteur mauvaise foi → créditer le wallet vendeur
            'buyer_bad_faith' => $this->creditSellerWallet($seller, $order->net_amount, $order),

            default => Log::warning('Résolution inconnue', [
                'resolution' => $dispute->resolution
            ]),
        };
    }

    // ── REMBOURSEMENT ACHETEUR (virement Campay direct) ──────────────

    // L'acheteur reçoit son argent directement sur son MoMo
    // car il n'a pas de wallet sur la plateforme
    private function refundBuyer(User $buyer, int $amount, Order $order): void
    {
        // Numéro utilisé au moment du paiement initial
        $phone = '237' . ltrim($order->payment->payer_phone, '0');

        try {
            // Virement direct vers le MoMo de l'acheteur
            $this->campay->disburse(
                phone: $phone,
                amount: $amount,
                reference: 'REFUND-' . $order->reference . '-' . Str::uuid(),
                description: "Remboursement litige commande {$order->reference}"
            );

            $this->wallet->refund($buyer, $amount, $order);

            Log::info('Buyer refunded via Campay', [
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

    // ── CRÉDITER LE WALLET VENDEUR (pas de virement direct) ──────────

    // Le vendeur reçoit l'argent dans son wallet plateforme
    // Il fait lui-même le retrait vers son MoMo via la fonction Withdrawal
    private function creditSellerWallet(User $seller, int $amount, Order $order): void
    {
        DB::transaction(function () use ($seller, $amount, $order) {

            // Libérer le séquestre et créditer le disponible
            $this->wallet->releaseEscrow($seller, $amount, $order);

            Log::info('Seller wallet credited', [
                'order'  => $order->reference,
                'amount' => $amount,
                'seller' => $seller->id,
            ]);
        });
    }

    // ── REMBOURSEMENT PARTIEL ─────────────────────────────────────────

    private function applyPartialRefund(Dispute $dispute): void
    {
        $order        = $dispute->order;
        $refundAmount = $dispute->resolution_amount ?? 0;
        $sellerAmount = max(0, $order->net_amount - $refundAmount);

        // Acheteur → virement MoMo direct
        if ($refundAmount > 0) {
            $this->refundBuyer($order->buyer, $refundAmount, $order);
        }

        // Vendeur → wallet plateforme
        if ($sellerAmount > 0) {
            $this->creditSellerWallet($order->shop->user, $sellerAmount, $order);
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

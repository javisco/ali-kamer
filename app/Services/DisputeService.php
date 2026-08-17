<?php

namespace App\Services;

use App\Models\Dispute;
use App\Models\DisputeEvidence;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DisputeService
{
    // ── OUVRIR UN LITIGE ──────────────────────────────────────────────

    public function open(
        Order $order,
        User $initiator,
        string $type,
        string $description,
        array $files = []
    ): Dispute {
 
        // Vérifier que la commande peut faire l'objet d'un litige
        if (! $order->canBeDisputed()) {
            throw new \Exception(
                'Cette commande ne peut pas faire l\'objet d\'un litige.'
            );
        }

        // Vérifier qu'un litige n'est pas déjà ouvert
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
            // Créer le litige
            $dispute = Dispute::create([
                'order_id'             => $order->id,
                'initiator_id'         => $initiator->id,
                'type'                 => $type,
                'description'          => $description,
                'status'               => 'open',

                // Le vendeur a 48h pour répondre
                'seller_reply_deadline' => now()->addHours(48),
            ]);

            // Uploader les preuves initiales
            foreach ($files as $file) {
                $this->addEvidence($dispute, $initiator, $file);
            }

            // Passer la commande en statut litige
            $order->update(['status' => Order::STATUS_DISPUTED]);

            // Notifier l'admin et l'autre partie
            //     app(NotificationService::class)->notifyDisputeOpened($dispute);

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

            // Ajouter la réponse textuelle comme preuve
            DisputeEvidence::create([
                'dispute_id'   => $dispute->id,
                'submitted_by' => $seller->id,
                'type'         => 'text',
                'content'      => $response,
                'description'  => 'Réponse du vendeur',
            ]);

            // Uploader les preuves du vendeur
            foreach ($files as $file) {
                $this->addEvidence($dispute, $seller, $file);
            }

            // Passer en statut "vendeur a répondu"
            $dispute->update(['status' => 'seller_replied']);

            // Notifier l'admin que le dossier est complet
            //  app(NotificationService::class)->notifyDisputeSellerReplied($dispute);
        });
    }

    // ── RÉSOUDRE LE LITIGE (admin) ────────────────────────────────────

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

            // Après resolve() :
            //  app(NotificationService::class)->notifyDisputeResolved($dispute);

            // Appliquer la décision financière
            $this->applyResolution($dispute);
        });
    }

    // ── APPLIQUER LA DÉCISION FINANCIÈRE ─────────────────────────────

    private function applyResolution(Dispute $dispute): void
    {
        $order  = $dispute->order;
        $wallet = app(WalletService::class);

        match ($dispute->resolution) {

            // Remboursement intégral — acheteur avait raison
            'refund_buyer' => $wallet->refund(
                $order->buyer,
                $order->total_amount,
                $order
            ),

            // Payer le vendeur — acheteur avait tort
            'pay_seller' => $wallet->releaseEscrow(
                $order->shop->user,
                $order->net_amount,
                $order
            ),

            // Remboursement partiel
            'partial_refund' => $this->applyPartialRefund($dispute),

            // Retour requis — on attend le retour avant de débloquer
            'return_required' => null,

            // Acheteur mauvaise foi — payer le vendeur
            'buyer_bad_faith' => $wallet->releaseEscrow(
                $order->shop->user,
                $order->net_amount,
                $order
            ),

            default => null,
        };

        // Mettre à jour le statut de la commande
        $order->update(['status' => Order::STATUS_COMPLETED]);
    }

    // Remboursement partiel
    private function applyPartialRefund(Dispute $dispute): void
    {
        $order  = $dispute->order;
        $wallet = app(WalletService::class);

        $refundAmount  = $dispute->resolution_amount ?? 0;
        $sellerAmount  = $order->net_amount - $refundAmount;

        // Rembourser l'acheteur partiellement
        if ($refundAmount > 0) {
            $wallet->refund($order->buyer, $refundAmount, $order);
        }

        // Payer le vendeur le reste
        if ($sellerAmount > 0) {
            $wallet->releaseEscrow($order->shop->user, $sellerAmount, $order);
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
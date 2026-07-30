<?php

namespace App\Services;

use App\Models\AgencyCounter;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ShippingService
{
    // ── DÉPÔT DU COLIS EN AGENCE (secrétaire départ) ─────────────────

    // Le vendeur donne son code de dépôt au secrétaire
    // Le secrétaire saisit ce code — le colis apparaît et il valide
    public function registerByDepositCode(User $secretary, string $depositCode): Order
    {
        // Chercher la commande correspondant au code
        $order = Order::where('deposit_code', $depositCode)
                      ->where('status', Order::STATUS_PREPARING)
                      ->first();

        if (! $order) {
            throw new \Exception(
                'Code invalide ou commande déjà enregistrée. Vérifiez le code et réessayez.'
            );
        }

        // Récupérer le guichet principal du secrétaire
        $counter = $secretary->agencyCounters()
                             ->wherePivot('is_primary', true)
                             ->first();

        if (! $counter) {
            throw new \Exception('Aucun guichet principal assigné à ce compte.');
        }

        DB::transaction(function () use ($order, $secretary, $counter) {

            // Enregistrer le guichet de départ et le secrétaire
            $order->shipment->update([
                'origin_counter_id' => $counter->id,
                'registered_by'     => $secretary->id,
                'registered_at'     => now(),
            ]);

            // Faire avancer le statut de la commande
            $order->update([
                'status'     => Order::STATUS_REGISTERED_ORIGIN,
                'shipped_at' => now(),
            ]);

            // Notifier l'acheteur que son colis a été déposé
            // app(NotificationService::class)->notifyBuyerPackageRegistered($order);

            // Notifier le vendeur confirmation du dépôt
            // app(NotificationService::class)->notifySellerPackageRegistered($order);
        });

        return $order->fresh();
    }

    // ── VALIDATION ARRIVÉE DU COLIS (secrétaire arrivée) ─────────────

    // Le secrétaire de la ville destination valide l'arrivée
    // Déclenche l'OTP et le timer 72h
    public function validateArrival(
        User $secretary,
        Order $order,
        int $transportFee = 0
    ): void {

        // Vérifier que la commande est bien en transit
        if (! in_array($order->status, [
            Order::STATUS_REGISTERED_ORIGIN,
            Order::STATUS_IN_TRANSIT,
        ])) {
            throw new \Exception('Cette commande ne peut pas être validée à l\'arrivée.');
        }

        // Récupérer le guichet du secrétaire d'arrivée
        $counter = $secretary->agencyCounters()
                             ->wherePivot('is_primary', true)
                             ->first();

        if (! $counter) {
            throw new \Exception('Aucun guichet principal assigné à ce compte.');
        }

        DB::transaction(function () use ($order, $secretary, $counter, $transportFee) {

            // Générer un OTP à 6 chiffres pour la remise du colis
            // Seul l'acheteur reçoit cet OTP — preuve irréfutable de présence
            $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

            // Enregistrer le guichet d'arrivée
            $order->shipment->update([
                'destination_counter_id' => $counter->id,
                'validated_by'           => $secretary->id,
                'arrived_at'             => now(),

                // Frais transport saisis par le secrétaire
                // (payés en main propre par le vendeur à l'agence)
                'transport_fee'          => $transportFee,
            ]);

            // Faire avancer le statut
            $order->update([
                'status'         => Order::STATUS_AWAITING_BUYER_CONFIRMATION,
                'arrived_at'     => now(),

                // OTP envoyé uniquement à l'acheteur
                'otp_code'       => $otp,
                'otp_expires_at' => now()->addHours(120), // expire dans 5 jours

                // Timer 72h : si l'acheteur ne vient pas, le vendeur est payé auto
                'timer_deadline' => now()->addHours(72),
            ]);

            // Notifier l'acheteur avec son OTP et l'adresse de l'agence
            // app(NotificationService::class)->notifyBuyerArrival($order, $otp, $counter);

            // Si transport exclu, notifier l'acheteur du montant à payer
            // if ($transportFee > 0) {
            //     app(NotificationService::class)->notifyBuyerTransportFee($order, $transportFee);
            // }
        });
    }

    // ── PAIEMENT DES FRAIS TRANSPORT PAR L'ACHETEUR ──────────────────

    // L'acheteur peut payer les frais transport via la plateforme
    // dès réception du message — même avant l'arrivée physique du colis
    // L'écran du secrétaire affiche "Transport payé" → il remet le colis
    public function markTransportFeePaid(Order $order): void
    {
        if ($order->shipment->transport_fee_paid) {
            throw new \Exception('Les frais transport ont déjà été payés.');
        }

        $order->shipment->update([
            'transport_fee_paid'    => true,
            'transport_fee_paid_at' => now(),
        ]);

        // Rembourser les frais transport au vendeur
        // (il les avait payés en main propre à l'agence)
        // app(WalletService::class)->creditTransportFee($order->shop->user, $order->shipment->transport_fee, $order);
    }

    // ── VALIDATION OTP REMISE COLIS ───────────────────────────────────

    // L'acheteur donne verbalement son OTP au secrétaire
    // Le secrétaire le saisit — c'est la preuve irréfutable de présence
    public function validateOtp(User $secretary, Order $order, string $otp): void
    {
        // Vérifier que l'OTP est correct
        if ($order->otp_code !== $otp) {
            throw new \Exception('Code OTP incorrect. Demandez à l\'acheteur de vérifier son téléphone.');
        }

        // Vérifier que l'OTP n'est pas expiré
        if ($order->otp_expires_at && now()->isAfter($order->otp_expires_at)) {
            throw new \Exception('Code OTP expiré. Contactez le support.');
        }

        // Vérifier que les frais transport sont payés si transport exclu
        if ($order->shipment->transport_fee > 0 && ! $order->shipment->transport_fee_paid) {
            throw new \Exception(
                'Les frais de transport ('
                . number_format($order->shipment->transport_fee, 0, ',', ' ')
                . ' FCFA) doivent être payés avant la remise du colis.'
            );
        }

        DB::transaction(function () use ($order) {

            // Marquer la commande comme terminée
            $order->update([
                'status'       => Order::STATUS_COMPLETED,
                'otp_used_at'  => now(),
                'completed_at' => now(),
            ]);

            // Libérer les fonds du vendeur
            // app(WalletService::class)->releaseEscrow(
            //     $order->shop->user,
            //     $order->net_amount,
            //     $order
            // );
        });
    }
}
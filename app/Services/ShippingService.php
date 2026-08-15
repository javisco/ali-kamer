<?php

namespace App\Services;

use App\Models\AgencyCounter;
use App\Models\Order;
use App\Models\SecretaryCounter;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ShippingService
{
    // ── DÉPÔT DU COLIS EN AGENCE (secrétaire départ) ─────────────────

    // Le vendeur donne son code de dépôt au secrétaire
    // Le secrétaire saisit ce code — le colis apparaît et il valide

    public function registerByDepositCode(User $secretary, string $depositCode): Order
    {
        $order = Order::where('deposit_code', $depositCode)
            ->where('status', Order::STATUS_PREPARING)
            ->first();

        if (! $order) {
            throw new \Exception('Code invalide ou commande déjà enregistrée.');
        }

        // Récupérer le comptoir du secrétaire
        $secretaryCounter = SecretaryCounter::where('user_id', $secretary->id)
            ->where('is_primary', true)
            ->with('counter.agency')
            ->first();

        if (! $secretaryCounter) {
            throw new \Exception('Aucun comptoir assigné à votre compte.');
        }

        $counter = $secretaryCounter->counter;

        // Vérifier que l'agence du colis correspond à l'agence du secrétaire
        // Un secrétaire Finexs ne peut pas traiter un colis General
        if ($order->shipment->agency_id !== $counter->agency_id) {
            throw new \Exception(
                'Ce colis appartient à une autre agence. Vous ne pouvez pas le traiter.'
            );
        }

        DB::transaction(function () use ($order, $secretary, $counter) {

            $order->shipment->update([
                'origin_counter_id' => $counter->id,
                'registered_by'     => $secretary->id,
                'registered_at'     => now(),
            ]);

            $order->update([
                'status'     => Order::STATUS_REGISTERED_ORIGIN,
                'shipped_at' => now(),
            ]);
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

        // Vérifier que la commande peut être validée à l'arrivée
        if (! in_array($order->status, [
            Order::STATUS_REGISTERED_ORIGIN,
            Order::STATUS_IN_TRANSIT,
        ])) {
            throw new \Exception(
                'Cette commande ne peut pas être validée à l\'arrivée.'
            );
        }

        // Récupérer le comptoir principal du secrétaire
        $secretaryCounter = SecretaryCounter::where('user_id', $secretary->id)
            ->where('is_primary', true)
            ->with('counter.agency')
            ->first();

        if (! $secretaryCounter) {
            throw new \Exception(
                'Aucun comptoir principal assigné à ce compte.'
            );
        }

        $counter = $secretaryCounter->counter;

        if (! $counter) {
            throw new \Exception(
                'Aucun comptoir principal assigné à ce compte.'
            );
        }

        // ---------------------------------------------------------
        // 1. Vérifier l'agence
        // ---------------------------------------------------------

        if ($order->shipment->agency_id !== $counter->agency_id) {
            throw new \Exception(
                'Ce colis appartient à une autre agence.'
            );
        }

        // ---------------------------------------------------------
        // 2. Vérifier LE COMPTOIR DE DESTINATION
        // ---------------------------------------------------------

        if ($order->shipment->destination_counter_id !== $counter->id) {
            throw new \Exception(
                'Ce colis est destiné à un autre comptoir de cette agence.'
            );
        }

        // ---------------------------------------------------------
        // 3. Tout est correct : validation de l'arrivée
        // ---------------------------------------------------------

        DB::transaction(function () use (
            $order,
            $secretary,
            $counter,
            $transportFee
        ) {

            // Générer l'OTP de l'acheteur
            $otp = str_pad(
                random_int(0, 999999),
                6,
                '0',
                STR_PAD_LEFT
            );

            // NE PAS MODIFIER destination_counter_id
            // car il a été choisi auparavant pour cette commande.

            $order->shipment->update([
                'validated_by' => $secretary->id,
                'arrived_at'   => now(),

                // Frais de transport déterminés à l'arrivée
                'transport_fee' => $transportFee,
            ]);

            $order->update([
                'status' => Order::STATUS_AWAITING_BUYER_CONFIRMATION,

                'arrived_at' => now(),

                // OTP de retrait de l'acheteur
                'otp_code' => $otp,

                'otp_expires_at' => now()->addHours(120),

                // Délai avant auto-complétion
                'timer_deadline' => now()->addHours(72),
            ]);

            // Notification future
            // app(NotificationService::class)
            //     ->notifyBuyerArrival($order, $otp, $counter);
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
    public function validateOtp(
        User $secretary,
        Order $order,
        string $otp
    ): void {

        // La commande doit être prête pour la remise
        if ($order->status !== Order::STATUS_AWAITING_BUYER_CONFIRMATION) {
            throw new \Exception(
                'Ce colis n\'est pas disponible pour la remise.'
            );
        }

        // Récupérer le comptoir principal de la secrétaire
        $secretaryCounter = SecretaryCounter::where('user_id', $secretary->id)
            ->where('is_primary', true)
            ->with('counter.agency')
            ->first();

        if (! $secretaryCounter) {
            throw new \Exception(
                'Aucun comptoir assigné à votre compte.'
            );
        }

        $counter = $secretaryCounter->counter;

        if (! $counter) {
            throw new \Exception(
                'Aucun guichet principal assigné à ce compte.'
            );
        }

        // Vérifier l'agence
        if ($order->shipment->agency_id !== $counter->agency_id) {
            throw new \Exception(
                'Ce colis appartient à une autre agence.'
            );
        }

        // Vérifier le comptoir où le colis a été enregistré à l'arrivée
        if ($order->shipment->destination_counter_id !== $counter->id) {
            throw new \Exception(
                'Ce colis n\'est pas enregistré dans votre comptoir.'
            );
        }

        // Vérifier l'OTP
        if ($order->otp_code !== $otp) {
            throw new \Exception(
                'Code OTP incorrect. Demandez à l\'acheteur de vérifier son téléphone.'
            );
        }

        // Vérifier expiration OTP
        if (
            $order->otp_expires_at &&
            now()->isAfter($order->otp_expires_at)
        ) {
            throw new \Exception(
                'Code OTP expiré. Contactez le support.'
            );
        }

        // Si le transport n'est pas inclus,
        // il doit être payé AVANT la remise
        if (
            ! $order->shipment->shipping_included &&
            $order->shipment->transport_fee > 0 &&
            ! $order->shipment->transport_fee_paid
        ) {
            throw new \Exception(
                'Les frais de transport ('
                    . number_format(
                        $order->shipment->transport_fee,
                        0,
                        ',',
                        ' '
                    )
                    . ' FCFA) doivent être payés sur la plateforme avant la remise du colis.'
            );
        }

        DB::transaction(function () use ($order) {

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

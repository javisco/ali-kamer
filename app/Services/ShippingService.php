<?php

namespace App\Services;

use App\Models\AgencyCounter;
use App\Models\Order;
use App\Models\SecretaryCounter;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ShippingService
{
    // ── PAGE DÉPÔT — Rechercher commande par deposit_code ─────────────

    // Le vendeur donne son deposit_code au secrétaire départ
    // Le secrétaire le saisit — on retrouve la commande et on l'affiche
    public function findByDepositCode(User $secretary, string $code): Order
    {
        $order = Order::where('deposit_code', strtoupper($code))
            ->where('status', Order::STATUS_PREPARING)
            ->with(['shop', 'shipment', 'buyer', 'items.product'])
            ->first();

        if (! $order) {
            throw new \Exception(
                'Code invalide ou commande non trouvée. ' .
                    'Vérifiez que le vendeur a bien confirmé la préparation.'
            );
        }

        // Vérifier que la commande appartient à l'agence du secrétaire
        $counter = $this->getSecretaryCounter($secretary);
        $this->checkAgencyMatch($order, $counter);

        return $order;
    }

    // ── PAGE DÉPÔT — Enregistrer le colis au départ ───────────────────

    // Après avoir vu la commande, le secrétaire valide le dépôt
    // Si transport exclu : il saisit les frais
    public function registerAtOrigin(
        User $secretary,
        Order $order,
        int $transportFee = 0
    ): void {

        $counter = $this->getSecretaryCounter($secretary);
        $this->checkAgencyMatch($order, $counter);

        // Vérifier que la commande est bien en PREPARING
        if ($order->status !== Order::STATUS_PREPARING) {
            throw new \Exception('Cette commande ne peut plus être enregistrée au départ.');
        }

        DB::transaction(function () use ($order, $secretary, $counter, $transportFee) {

            $order->shipment->update([
                'origin_counter_id' => $counter->id,
                'registered_by'     => $secretary->id,
                'registered_at'     => now(),

                // Frais transport saisis par le secrétaire
                // (payés en main propre par le vendeur à l'agence)
                'transport_fee'     => $transportFee,
            ]);

            $order->update([
                'status'     => Order::STATUS_REGISTERED_ORIGIN,
                'shipped_at' => now(),
            ]);

            // app(NotificationService::class)->notifyPackageRegistered($order);
        });
    }

    // ── PAGE ARRIVÉES — Liste des colis attendus au comptoir ──────────

    // Retourne les commandes qui doivent arriver à ce comptoir
    // (registered_origin ou in_transit, destination = ville du comptoir)
    public function getPendingArrivals(User $secretary)
    {
        $counter = $this->getSecretaryCounter($secretary);

        return Order::whereIn('status', [
            Order::STATUS_REGISTERED_ORIGIN,
            Order::STATUS_IN_TRANSIT,
        ])
            ->whereHas('shipment', function ($q) use ($counter) {
                $q->where('agency_id', $counter->agency_id)
                    ->where('destination_city', $counter->city);
            })
            ->with(['shop', 'shipment', 'buyer', 'items'])
            ->latest()
            ->get();
    }

    // ── PAGE ARRIVÉES — Rechercher par référence ──────────────────────

    public function findByReference(User $secretary, string $reference): Order
    {
        $counter = $this->getSecretaryCounter($secretary);

        $order = Order::where('reference', strtoupper($reference))
            ->whereIn('status', [
                Order::STATUS_REGISTERED_ORIGIN,
                Order::STATUS_IN_TRANSIT,
            ])
            ->whereHas('shipment', function ($q) use ($counter) {
                $q->where('agency_id', $counter->agency_id)
                    ->where('destination_city', $counter->city);
            })
            ->with(['shop', 'shipment', 'buyer', 'items'])
            ->first();

        if (! $order) {
            throw new \Exception(
                'Commande introuvable. Vérifiez la référence ou que le colis est bien destiné à votre comptoir.'
            );
        }

        return $order;
    }

    // ── PAGE ARRIVÉES — Valider l'arrivée d'un colis ─────────────────

    public function validateArrival(User $secretary, Order $order): void
    {
        $counter = $this->getSecretaryCounter($secretary);

        // Vérifier agence
        $this->checkAgencyMatch($order, $counter);

        // Vérifier ville de destination
        if ($order->shipment->destination_city !== $counter->city) {
            throw new \Exception(
                "Ce colis est destiné à {$order->shipment->destination_city}, " .
                    "pas à {$counter->city}."
            );
        }

        if (! in_array($order->status, [
            Order::STATUS_REGISTERED_ORIGIN,
            Order::STATUS_IN_TRANSIT,
        ])) {
            throw new \Exception('Ce colis ne peut pas être validé à l\'arrivée.');
        }

        DB::transaction(function () use ($order, $secretary, $counter) {

            // Générer l'OTP — envoyé à l'acheteur UNIQUEMENT
            $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

            $order->shipment->update([
                'destination_counter_id' => $counter->id,
                'validated_by'           => $secretary->id,
                'arrived_at'             => now(),
            ]);

            $order->update([
                'status'         => Order::STATUS_AWAITING_BUYER_CONFIRMATION,
                'arrived_at'     => now(),
                'timer_deadline' => now()->addHours(72),
            ]);

            // Créditer la commission de l'agence (1%)
            // Appelé quand le colis arrive à destination
            $agency = $counter->agency;
            app(AgencyManagerService::class)->creditCommission($agency, $order);


            // OTP envoyé à l'acheteur seulement si transport inclus OU déjà payé
            // Sinon l'acheteur doit d'abord payer les frais transport
            $transportDue = ! $order->shipment->shipping_included
                && $order->shipment->transport_fee > 0
                && ! $order->shipment->transport_fee_paid;

            if (! $transportDue) {
                // Transport inclus ou déjà payé → envoyer OTP directement
                $order->update([
                    'otp_code'       => $otp,
                    'otp_expires_at' => now()->addHours(120),
                ]);
                // app(NotificationService::class)->notifyBuyerArrivalWithOtp($order, $otp);
            } else {
                // Transport non payé → informer l'acheteur de payer d'abord
                // L'OTP sera généré après le paiement du transport
                // app(NotificationService::class)->notifyBuyerTransportFee($order);
            }
        });
    }

    // ── PAGE REMISE — Liste des colis à remettre ──────────────────────

    // Colis arrivés au comptoir, en attente de remise OTP
    public function getPendingHandovers(User $secretary)
    {
        $counter = $this->getSecretaryCounter($secretary);

        return Order::where('status', Order::STATUS_AWAITING_BUYER_CONFIRMATION)
            ->whereHas('shipment', function ($q) use ($counter) {
                $q->where('destination_counter_id', $counter->id);
            })
            ->with(['shipment', 'buyer', 'items'])
            ->latest('arrived_at')
            ->get();
    }

    // ── PAGE REMISE — Valider l'OTP et remettre le colis ─────────────

    public function validateOtp(User $secretary, Order $order, string $otp): void
    {
        $counter = $this->getSecretaryCounter($secretary);

        // Vérifier que le colis appartient bien à ce comptoir
        if ($order->shipment->destination_counter_id !== $counter->id) {
            throw new \Exception('Ce colis n\'appartient pas à votre comptoir.');
        }

        // Vérifier que les frais transport sont payés si requis
        if (
            ! $order->shipment->shipping_included &&
            $order->shipment->transport_fee > 0 &&
            ! $order->shipment->transport_fee_paid
        ) {
            throw new \Exception(
                'L\'acheteur doit d\'abord payer les frais de transport ' .
                    '(' . number_format($order->shipment->transport_fee, 0, ',', ' ') . ' FCFA) ' .
                    'avant de récupérer son colis.'
            );
        }

        // Vérifier l'OTP
        if ($order->otp_code !== $otp) {
            throw new \Exception('Code OTP incorrect. Demandez à l\'acheteur de vérifier son téléphone.');
        }

        if ($order->otp_expires_at && now()->isAfter($order->otp_expires_at)) {
            throw new \Exception('Code OTP expiré. Contactez le support.');
        }

        DB::transaction(function () use ($order) {

            $order->update([
                'status'       => Order::STATUS_COMPLETED,
                'otp_used_at'  => now(),
                'completed_at' => now(),
            ]);

            // Libérer les fonds du vendeur
            app(WalletService::class)->releaseEscrow($order->shop->user, $order->net_amount, $order);
        });
    }

    // ── HELPERS PRIVÉS ────────────────────────────────────────────────

    // Récupère le comptoir principal du secrétaire
    // Lance une exception si pas de comptoir assigné
    private function getSecretaryCounter(User $secretary): AgencyCounter
    {
        $secretaryCounter = SecretaryCounter::where('user_id', $secretary->id)
            ->where('is_primary', true)
            ->with('counter.agency')
            ->first();

        if (! $secretaryCounter) {
            throw new \Exception(
                'Aucun comptoir assigné à votre compte. Contactez l\'administrateur.'
            );
        }

        return $secretaryCounter->counter;
    }

    // Vérifie que l'agence du colis correspond à l'agence du secrétaire
    private function checkAgencyMatch(Order $order, AgencyCounter $counter): void
    {
        if ($order->shipment->agency_id !== $counter->agency_id) {
            throw new \Exception(
                'Ce colis appartient à une autre agence. Vous ne pouvez pas le traiter.'
            );
        }
    }
    // Version publique pour le controller
    public function getSecretaryCounterPublic(User $secretary): AgencyCounter
    {
        return $this->getSecretaryCounter($secretary);
    }
}

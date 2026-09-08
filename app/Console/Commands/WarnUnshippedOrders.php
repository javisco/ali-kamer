<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Notifications\Shippings\SellerShippingWarningNotification;
use App\Models\PlatformSetting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class WarnUnshippedOrders extends Command
{
    /**
     * Nom et signature de la commande.
     */
    protected $signature = 'orders:warn-unshipped';

    /**
     * Description.
     */
    protected $description = 'Avertit les vendeurs dont les commandes payées ne sont toujours pas expédiées.';

    /**
     * Exécution de la commande.
     */
    public function handle(): int
    {
        $warningHours = (int) PlatformSetting::get(
            'seller_shipping_warning_hours',
            48
        );

        $limitDate = now()->subHours($warningHours);

        $this->info(
            "Recherche des commandes payées depuis {$warningHours} heures sans expédition..."
        );

        /*
         * Commandes concernées :
         * - paiement terminé
         * - délai dépassé
         * - pas encore expédiées
         */
        $orders = Order::query()
            ->where('status', 'paid')
            ->whereNotNull('paid_at')
            ->where('paid_at', '<=', $limitDate)
            ->with('seller')
            ->get();

        if ($orders->isEmpty()) {
            $this->info('Aucune commande à avertir.');

            return self::SUCCESS;
        }

        $sent = 0;
        $skipped = 0;

        foreach ($orders as $order) {

            /*
             * Sécurité : une commande doit avoir un vendeur.
             */
            if (!$order->seller) {
                $this->warn(
                    "Commande {$order->reference} ignorée : vendeur introuvable."
                );

                $skipped++;

                continue;
            }

            /*
             * Évite d'envoyer plusieurs fois le même avertissement.
             */
            $alreadyNotified = $order->seller
                ->notifications()
                ->where(
                    'type',
                    SellerShippingWarningNotification::class
                )
                ->where(
                    'data->url',
                    route('seller.orders.show', $order)
                )
                ->exists();

            if ($alreadyNotified) {
                $this->line(
                    "Déjà averti : {$order->reference}"
                );

                $skipped++;

                continue;
            }

            /*
             * Envoi de la notification :
             * database + SMS + WhatsApp
             *
             * La notification elle-même est Queueable,
             * donc elle sera traitée par la queue.
             */
            $order->seller->notify(
                new SellerShippingWarningNotification($order)
            );

            $this->info(
                "Avertissement envoyé : {$order->reference}"
            );

            $sent++;
        }

        Log::info('Seller shipping warning terminé.', [
            'warning_hours' => $warningHours,
            'orders_found'  => $orders->count(),
            'notifications_sent' => $sent,
            'notifications_skipped' => $skipped,
        ]);

        $this->newLine();

        $this->info("Commandes trouvées : {$orders->count()}");
        $this->info("Notifications envoyées : {$sent}");
        $this->info("Commandes ignorées : {$skipped}");

        return self::SUCCESS;
    }
}

<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Notifications\Orders\LateCancellationOfferNotification;
use App\Services\BusinessSettingsService;
use App\Services\OrderCancellationService;
use Illuminate\Console\Command;

class OfferLateCancellations extends Command
{
    protected $signature = 'orders:offer-late-cancellation';

    protected $description = 'Propose une annulation à l’acheteur lorsque les délais de préparation ou de dépôt sont dépassés.';

    public function handle(
        OrderCancellationService $cancellation,
        BusinessSettingsService $settings
    ): int {
        if (! $settings->enabled('orders.late_cancel_notifications_enabled', true)) {
            $this->info('Notifications d’annulation tardive désactivées.');

            return self::SUCCESS;
        }

        $sent = 0;

        Order::query()
            ->whereIn('status', [Order::STATUS_PAID, Order::STATUS_PREPARING])
            ->whereNull('late_cancel_offered_at')
            ->with('buyer')
            ->each(function (Order $order) use ($cancellation, &$sent) {
                $eligibility = $cancellation->eligibility($order);
                if (! $eligibility['allowed'] || ! in_array($eligibility['kind'], ['unprepared', 'undeposited'], true)) {
                    return;
                }

                $order->update([
                    'late_cancel_offered_at'  => now(),
                    'late_cancel_offer_kind'  => $eligibility['kind'],
                ]);

                $order->buyer?->notify(new LateCancellationOfferNotification($order, $eligibility));
                $sent++;
            });

        $this->info("Propositions envoyées : {$sent}");

        return self::SUCCESS;
    }
}

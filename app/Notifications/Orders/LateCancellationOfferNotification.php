<?php

namespace App\Notifications\Orders;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class LateCancellationOfferNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Order $order,
        public array $eligibility
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $kind = $this->eligibility['kind'] ?? '';
        $title = $kind === 'undeposited'
            ? 'Votre commande n’a pas encore été déposée en agence'
            : 'Votre commande n’est pas encore en préparation';

        return [
            'title'    => $title,
            'message'  => $this->eligibility['reason'] ?? 'Vous pouvez demander l’annulation et un remboursement hors frais de protection.',
            'url'      => route('buyer.orders.show', $this->order),
            'order_id' => $this->order->id,
            'kind'     => $kind,
        ];
    }
}

<?php

namespace App\Notifications\Payments;

use App\Models\Order;
use App\Notifications\Concerns\HasDatabasePayload;
use App\Notifications\Messages\WhatsAppMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\VonageMessage;
use Illuminate\Notifications\Notification;

class BuyerTransportFeeNotification extends Notification implements ShouldQueue
{
    use Queueable, HasDatabasePayload;

    public function __construct(
        public Order $order
    ) {}

    public function via(object $notifiable): array
    {
        return [
            'database',
            'vonage',
            'whatsapp',
        ];
    }

    protected function databasePayload(object $notifiable): array
    {
        return [
            'title' => 'Frais de transport à payer',
            'body' => "Votre commande {$this->order->reference} "
                . "est arrivée à l'agence. "
                . "Veuillez payer les frais de transport avant de pouvoir "
                . "récupérer votre colis.",
            'icon' => '💳',
            'url' => route(
                'buyer.orders.show',
                $this->order
            ),
        ];
    }

    public function toVonage(object $notifiable): VonageMessage
    {
        return (new VonageMessage())
            ->content(
                "Ali-Kamer : votre commande {$this->order->reference} "
                . "est arrivée à l'agence. "
                . "Veuillez payer les frais de transport avant de "
                . "pouvoir récupérer votre colis."
            )
            ->from(config('services.vonage.sms_from'));
    }

    public function toWhatsApp(object $notifiable): WhatsAppMessage
    {
        return WhatsAppMessage::create()->template(
            name: 'buyer_transport_fee',
            params: [
                $this->order->reference,
            ],
        );
    }
}


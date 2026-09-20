<?php

namespace App\Notifications\Shippings;

use App\Models\Order;
use App\Notifications\Concerns\HasDatabasePayload;
use App\Notifications\Messages\WhatsAppMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\VonageMessage;
use Illuminate\Notifications\Notification;

class BuyerArrivalWithOtpNotification extends Notification implements ShouldQueue
{
    use Queueable, HasDatabasePayload;

    public function __construct(
        public Order $order,
        public string $otp
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
            'title' => 'Votre colis est arrivé',
            'body' => "Votre commande {$this->order->reference} "
                . "est arrivée à l'agence. "
                . "Votre code de retrait est {$this->otp}.",
            'icon' => '📦',
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
                . "Votre code de retrait est {$this->otp}. "
                . "Présentez-le à l'agence pour récupérer votre colis."
            )
            ->from(config('services.vonage.sms_from'));
    }

    public function toWhatsApp(object $notifiable): WhatsAppMessage
    {
        return WhatsAppMessage::create()->template(
            name: 'buyer_arrival_otp',
            params: [
                $this->order->reference,
                $this->otp,
            ],
        );
    }
}


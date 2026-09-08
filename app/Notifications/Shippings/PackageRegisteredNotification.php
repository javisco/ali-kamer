<?php

namespace App\Notifications\Shippings;

use App\Models\Order;
use App\Notifications\Concerns\HasDatabasePayload;
use App\Notifications\Messages\WhatsAppMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\VonageMessage;
use Illuminate\Notifications\Notification;

class PackageRegisteredNotification extends Notification implements ShouldQueue
{
    use Queueable, HasDatabasePayload;

    public function __construct(
        public Order $order
    ) {}

    /**
     * Canaux utilisés.
     */
    public function via(object $notifiable): array
    {
        return [
            'database',
            'vonage',
            'whatsapp',
        ];
    }

    /**
     * Notification en base de données.
     */
    protected function databasePayload(object $notifiable): array
    {
        return [
            'title' => 'Colis enregistré',
            'body' => "Votre commande {$this->order->reference} "
                . "a été enregistrée à l'agence et sera bientôt acheminée.",
            'icon' => '📦',
            'url' => route(
                'buyer.orders.show',
                $this->order
            ),
        ];
    }

    /**
     * SMS Vonage.
     */
    public function toVonage(object $notifiable): VonageMessage
    {
        return (new VonageMessage())
            ->content(
                "Ali-Kamer : votre commande {$this->order->reference} "
                . "a été enregistrée à l'agence. "
                . "Elle sera bientôt acheminée vers sa destination."
            )
            ->from(config('services.vonage.sms_from'));
    }

    /**
     * WhatsApp.
     */
    public function toWhatsApp(object $notifiable): WhatsAppMessage
    {
        return WhatsAppMessage::create()->template(
            name: 'package_registered',
            params: [
                $this->order->reference,
            ],
        );
    }
}

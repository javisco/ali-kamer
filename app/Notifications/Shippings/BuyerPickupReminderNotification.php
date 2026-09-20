<?php
namespace App\Notifications\Shippings;

use App\Models\Order;
use App\Notifications\Concerns\HasDatabasePayload;
use App\Notifications\Messages\WhatsAppMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\VonageMessage;
use Illuminate\Notifications\Notification;

class BuyerPickupReminderNotification extends Notification implements ShouldQueue
{
    use Queueable, HasDatabasePayload;

    public function __construct(
        public Order $order,
        public int $hoursRemaining
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
        $urgent = $this->hoursRemaining <= 24;

        return [
            'title' => $urgent
                ? 'Retrait urgent'
                : 'Rappel de retrait',

            'body' => $urgent
                ? "Votre commande {$this->order->reference} "
                    . "attend toujours votre retrait. "
                    . "Il vous reste environ {$this->hoursRemaining} heures."
                : "Votre commande {$this->order->reference} "
                    . "est arrivée à l'agence. "
                    . "Pensez à la retirer dans les délais.",

            'icon' => $urgent ? '🚨' : '📦',

            'url' => route(
                'buyer.orders.show',
                $this->order
            ),
        ];
    }

    /**
     * SMS.
     */
    public function toVonage(object $notifiable): VonageMessage
    {
        $message = $this->hoursRemaining <= 24
            ? "Ali-Kamer : votre commande {$this->order->reference} "
                . "attend toujours votre retrait. "
                . "Il vous reste environ {$this->hoursRemaining}h. "
                . "Retirez-la rapidement."
            : "Ali-Kamer : votre commande {$this->order->reference} "
                . "est arrivée à l'agence. "
                . "Pensez à la retirer dans les délais.";

        return (new VonageMessage())
            ->content($message)
            ->from(config('services.vonage.sms_from'));
    }

    /**
     * WhatsApp.
     */
    public function toWhatsApp(object $notifiable): WhatsAppMessage
    {
        return WhatsAppMessage::create()->template(
            name: 'pickup_reminder',
            params: [
                $this->order->reference,
                (string) $this->hoursRemaining,
            ],
        );
    }
}


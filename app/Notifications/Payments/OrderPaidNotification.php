<?php

namespace App\Notifications\Payments;

use App\Models\Order;
use App\Notifications\Concerns\HasDatabasePayload;
use App\Notifications\Messages\WhatsAppMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

// Envoyée au VENDEUR quand une commande vient d'être payée — action requise
// (préparer/expédier). Importante → database + mail + WhatsApp.
class OrderPaidNotification extends Notification implements ShouldQueue
{
    use Queueable, HasDatabasePayload;

    public function __construct(public Order $order) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail', 'whatsapp'];
    }

    protected function databasePayload(object $notifiable): array
    {
        return [
            'title' => 'Nouvelle commande payée',
            'body'  => "Commande {$this->order->reference} — "
                . number_format($this->order->net_amount, 0, ',', ' ') . ' FCFA à recevoir. Préparez l\'expédition.',
            'icon'  => '🛍️',
            'url'   => route('seller.orders.show', $this->order), // adapte le nom de route si différent
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Nouvelle commande payée — {$this->order->reference}")
            ->greeting("Bonjour {$notifiable->name},")
            ->line("La commande {$this->order->reference} vient d'être payée par l'acheteur.")
            ->line('Montant net à recevoir : ' . number_format($this->order->net_amount, 0, ',', ' ') . ' FCFA')
            ->action('Voir la commande', route('seller.orders.show', $this->order))
            ->line('Merci de préparer l\'expédition rapidement.');
    }

    public function toWhatsApp(object $notifiable): WhatsAppMessage
    {
        // Template à créer/approuver dans Meta Business Manager, ex:
        // "order_paid" avec variables {{1}}=référence {{2}}=montant
        return WhatsAppMessage::create()->template(
            name: 'order_paid',
            params: [$this->order->reference, number_format($this->order->net_amount, 0, ',', ' ') . ' FCFA'],
        );
    }
}

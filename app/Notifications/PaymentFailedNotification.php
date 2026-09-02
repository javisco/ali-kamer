<?php

namespace App\Notifications;

use App\Models\Order;
use App\Notifications\Concerns\HasDatabasePayload;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

// Envoyée à l'ACHETEUR quand son paiement échoue. Importante mais pas
// urgente au point de justifier du SMS/WhatsApp (l'acheteur est déjà sur
// la page d'attente en train de regarder son écran) → database + mail.
class PaymentFailedNotification extends Notification implements ShouldQueue
{
    use Queueable, HasDatabasePayload;

    public function __construct(public Order $order) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    protected function databasePayload(object $notifiable): array
    {
        return [
            'title' => 'Paiement échoué',
            'body'  => "Le paiement de la commande {$this->order->reference} n'a pas abouti. Réessayez.",
            'icon'  => '❌',
            'url'   => route('buyer.orders.show', $this->order),
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Paiement échoué — {$this->order->reference}")
            ->greeting("Bonjour {$notifiable->name},")
            ->line("Le paiement de votre commande {$this->order->reference} n'a pas pu être validé.")
            ->action('Réessayer le paiement', route('buyer.orders.show', $this->order))
            ->line('Si le problème persiste, contactez le support Ali-Kamer.');
    }
}

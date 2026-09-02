<?php

namespace App\Notifications;

use App\Models\Dispute;
use App\Notifications\Concerns\HasDatabasePayload;
use App\Notifications\Messages\WhatsAppMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

// Envoyée au VENDEUR quand un litige est ouvert contre lui — il a 48h
// pour répondre (seller_reply_deadline), donc urgence réelle →
// database + mail + WhatsApp.
class DisputeOpenedNotification extends Notification implements ShouldQueue
{
    use Queueable, HasDatabasePayload;

    public function __construct(public Dispute $dispute) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail', 'whatsapp'];
    }

    protected function databasePayload(object $notifiable): array
    {
        return [
            'title' => 'Litige ouvert',
            'body'  => "Un litige a été ouvert sur la commande {$this->dispute->order->reference}. "
                . 'Vous avez 48h pour répondre.',
            'icon'  => '⚖️',
            'url'   => route('seller.disputes.show', $this->dispute), // adapte le nom de route si différent
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Litige ouvert — {$this->dispute->order->reference}")
            ->greeting("Bonjour {$notifiable->name},")
            ->line("Un litige a été ouvert sur votre commande {$this->dispute->order->reference}.")
            ->line('Vous avez 48h pour y répondre, faute de quoi le dossier sera traité sans votre réponse.')
            ->action('Répondre au litige', route('seller.disputes.show', $this->dispute));
    }

    public function toWhatsApp(object $notifiable): WhatsAppMessage
    {
        // Template à créer/approuver, ex: "dispute_opened" {{1}}=référence commande
        return WhatsAppMessage::create()->template(
            name: 'dispute_opened',
            params: [$this->dispute->order->reference],
        );
    }
}

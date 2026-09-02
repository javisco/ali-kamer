<?php

namespace App\Notifications;

use App\Models\Dispute;
use App\Notifications\Concerns\HasDatabasePayload;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

// Envoyée à TOUS LES ADMINS quand un litige est ouvert, pour qu'ils
// puissent suivre la file d'attente sans avoir à recharger le dashboard.
// database + mail (pas de SMS/WhatsApp — les admins ont le dashboard
// sous les yeux en continu, pas besoin de canal intrusif).
class AdminDisputeOpenedNotification extends Notification implements ShouldQueue
{
    use Queueable, HasDatabasePayload;

    public function __construct(public Dispute $dispute) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    protected function databasePayload(object $notifiable): array
    {
        return [
            'title' => 'Nouveau litige à traiter',
            'body'  => "Litige ouvert sur la commande {$this->dispute->order->reference} "
                . "({$this->dispute->type}).",
            'icon'  => '🚨',
            'url'   => route('admin.disputes.show', $this->dispute),
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Nouveau litige — {$this->dispute->order->reference}")
            ->greeting('Bonjour,')
            ->line("Un nouveau litige a été ouvert sur la commande {$this->dispute->order->reference}.")
            ->line('Type : ' . $this->dispute->type)
            ->action('Voir le litige', route('admin.disputes.show', $this->dispute));
    }
}

<?php

namespace App\Notifications\Kyc;

use App\Notifications\Concerns\HasDatabasePayload;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

// Envoyée au VENDEUR quand son dossier KYC est rejeté. database + mail.
class KycRejectedNotification extends Notification implements ShouldQueue
{
    use Queueable, HasDatabasePayload;

    public function __construct(public string $reason) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    protected function databasePayload(object $notifiable): array
    {
        return [
            'title' => 'Dossier KYC rejeté',
            'body'  => "Motif : {$this->reason}",
            'icon'  => '❌',
            'url'   => route('seller.kyc.form'), // adapte le nom de route si différent
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Votre dossier a été rejeté')
            ->greeting("Bonjour {$notifiable->name},")
            ->line('Votre dossier KYC a été rejeté pour la raison suivante :')
            ->line($this->reason)
            ->action('Soumettre un nouveau dossier', route('seller.kyc.form'));
    }
}

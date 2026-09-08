<?php

namespace App\Notifications\Kyc;

use App\Notifications\Concerns\HasDatabasePayload;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

// Envoyée au VENDEUR quand son dossier KYC est approuvé — sa boutique
// vient d'être activée. database + mail.
class KycApprovedNotification extends Notification implements ShouldQueue
{
    use Queueable, HasDatabasePayload;

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    protected function databasePayload(object $notifiable): array
    {
        return [
            'title' => 'Dossier KYC approuvé',
            'body'  => 'Votre boutique est maintenant active. Vous pouvez publier vos produits.',
            'icon'  => '🎉',
            'url'   => route('seller.shop.index'), // adapte le nom de route si différent
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Votre dossier a été approuvé')
            ->greeting("Bonjour {$notifiable->name},")
            ->line('Bonne nouvelle : votre dossier KYC a été approuvé et votre boutique est maintenant active.')
            ->action('Gérer ma boutique', route('seller.shop.index'))
            ->line('Bienvenue sur Ali-Kamer !');
    }
}

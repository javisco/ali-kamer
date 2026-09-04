<?php

namespace App\Notifications;

use App\Notifications\Concerns\HasDatabasePayload;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\VonageMessage;
use Illuminate\Notifications\Notification;

// Envoyée au VENDEUR quand son retrait échoue (le montant a été recrédité
// automatiquement à son solde). Il faut qu'il le sache vite pour ne pas
// s'inquiéter de voir son solde momentanément débité → database + mail + SMS.
class WithdrawalFailedNotification extends Notification implements ShouldQueue
{
    use Queueable, HasDatabasePayload;

    public function __construct(public int $netAmount, public string $reason) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail', 'vonage'];
    }

    protected function databasePayload(object $notifiable): array
    {
        return [
            'title' => 'Retrait échoué',
            'body'  => 'Le retrait de ' . number_format($this->netAmount, 0, ',', ' ')
                . ' FCFA a échoué. Le montant a été recrédité à votre solde.',
            'icon'  => '⚠️',
            'url'   => route('seller.wallet.index'),
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Retrait échoué — montant recrédité')
            ->greeting("Bonjour {$notifiable->name},")
            ->line('Votre demande de retrait de ' . number_format($this->netAmount, 0, ',', ' ') . ' FCFA a échoué.')
            ->line('Raison : ' . $this->reason)
            ->line('Le montant a été automatiquement recrédité à votre solde disponible.')
            ->action('Voir mon portefeuille', route('seller.wallet.index'));
    }

    public function toVonage(object $notifiable): VonageMessage
    {
        return (new VonageMessage())
            ->content(
                'Ali-Kamer : votre retrait de ' . number_format($this->netAmount, 0, ',', ' ')
                . ' FCFA a échoué. Montant recrédité à votre solde.'
            )
            ->from(config('services.vonage.sms_from'));
    }
}

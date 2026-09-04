<?php

namespace App\Notifications;

use App\Notifications\Concerns\HasDatabasePayload;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\VonageMessage;
use Illuminate\Notifications\Notification;

// Envoyée au VENDEUR quand son retrait MoMo a réussi — confirmation
// financière, doit arriver de façon fiable → database + SMS.
class WithdrawalCompletedNotification extends Notification implements ShouldQueue
{
    use Queueable, HasDatabasePayload;

    public function __construct(public int $netAmount, public string $phone) {}

    public function via(object $notifiable): array
    {
        return ['database', 'vonage'];
    }

    protected function databasePayload(object $notifiable): array
    {
        return [
            'title' => 'Retrait effectué',
            'body'  => number_format($this->netAmount, 0, ',', ' ') . " FCFA envoyés sur {$this->phone}.",
            'icon'  => '💸',
            'url'   => route('seller.wallet.index'),
        ];
    }

    public function toVonage(object $notifiable):VonageMessage
    {
        return (new VonageMessage())
            ->content(
                'Ali-Kamer : votre retrait de ' . number_format($this->netAmount, 0, ',', ' ')
                    . " FCFA a été envoyé sur {$this->phone}."
            )
            ->from(config('services.vonage.sms_from'));
    }
}

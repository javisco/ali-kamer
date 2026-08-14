<?php

namespace App\Jobs;

use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // Maximum 3 tentatives avant d'abandonner
    public int $tries = 3;

    // Délai entre les tentatives (secondes)
    public int $backoff = 60;

    public function __construct(private Notification $notification) {}

    public function handle(NotificationService $service): void
    {
        $notification = $this->notification;
        $user         = $notification->user;

        // Formater le numéro au format Campay/WhatsApp (237XXXXXXXXX)
        $phone = '237' . ltrim($user->phone, '0');

        // Tenter l'envoi WhatsApp
        $sent = $service->sendWhatsapp($phone, $notification->body);

        if ($sent) {
            // Marquer comme envoyé
            $notification->update([
                'status'  => 'sent',
                'sent_at' => now(),
            ]);
        } else {
            // Incrémenter le compteur de tentatives
            $notification->increment('retry_count');

            // Après 3 échecs, marquer comme échoué définitivement
            if ($notification->retry_count >= 3) {
                $notification->update(['status' => 'failed']);
            }

            // Relancer le job (backoff exponentiel)
            $this->release($this->backoff * $notification->retry_count);
        }
    }
}


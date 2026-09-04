<?php

namespace App\Notifications\Channels;

use App\Notifications\Messages\WhatsAppMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppChannel
{
    public function send(object $notifiable, Notification $notification): void
    {
        if (! method_exists($notification, 'toWhatsApp')) {
            return;
        }

        // Numéro du destinataire — via routeNotificationForWhatsApp() sur
        // le modèle User (voir USER_MODEL_CHANGES.txt), sinon via
        // $notification->toWhatsApp($notifiable) qui doit gérer le numéro
        // lui-même si le modèle n'a pas cette méthode.
        $to = method_exists($notifiable, 'routeNotificationForWhatsApp')
            ? $notifiable->routeNotificationForWhatsApp($notification)
            : $notifiable->routeNotificationFor('whatsapp', $notification);

        if (! $to) {
            Log::warning('WhatsApp : aucun numéro pour le destinataire', [
                'notification' => get_class($notification),
            ]);
            return;
        }

        /** @var WhatsAppMessage $message */
        $message = $notification->toWhatsApp($notifiable);

        $phoneNumberId = config('services.whatsapp.phone_number_id');
        $token         = config('services.whatsapp.access_token');
        $apiVersion    = config('services.whatsapp.api_version', 'v21.0');

        $response = Http::withToken($token)
            ->post("https://graph.facebook.com/{$apiVersion}/{$phoneNumberId}/messages", array_merge([
                'messaging_product' => 'whatsapp',
                'to'                => $to,
            ], $message->toArray()));

        if (! $response->successful()) {
            Log::error('WhatsApp envoi échoué', [
                'to'           => $to,
                'notification' => get_class($notification),
                'status'       => $response->status(),
                'body'         => $response->json(),
            ]);
        } else {
            Log::info('WhatsApp envoyé', [
                'to'           => $to,
                'notification' => get_class($notification),
            ]);
        }
    }
}

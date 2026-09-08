<?php

namespace App\Notifications\Payments;

use App\Models\Order;
use App\Notifications\Concerns\HasDatabasePayload;
use App\Notifications\Messages\WhatsAppMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\VonageMessage;
use Illuminate\Notifications\Notification;

// Envoyée à l'ACHETEUR une fois les frais de transport payés — contient
// le code OTP nécessaire pour retirer le colis à l'agence. CRITIQUE :
// l'acheteur doit le recevoir de façon fiable même s'il a quitté la page
// d'attente → database + SMS (Vonage) + WhatsApp. Pas de mail seul ici,
// l'OTP doit arriver sur le téléphone physiquement en main à l'agence.
class TransportFeePaidNotification extends Notification implements ShouldQueue
{
    use Queueable, HasDatabasePayload;

    public function __construct(public Order $order) {}

    public function via(object $notifiable): array
    {
        return ['database', 'vonage', 'whatsapp', 'mail'];
    }

    protected function databasePayload(object $notifiable): array
    {
        return [
            'title' => 'Code de retrait disponible',
            'body'  => "Frais de transport payés pour {$this->order->reference}. Code de retrait : {$this->order->otp_code}",
            'icon'  => '📦',
            'url'   => route('buyer.orders.show', $this->order),
        ];
    }

    public function toVonage(object $notifiable): VonageMessage
    {
        return (new VonageMessage())
            ->content(
                "Ali-Kamer : frais de transport payés pour {$this->order->reference}. "
                . "Votre code de retrait : {$this->order->otp_code}. "
                . 'Présentez-le à l\'agence pour récupérer votre colis.'
            )
            ->from(config('services.vonage.sms_from'));
    }

    public function toWhatsApp(object $notifiable): WhatsAppMessage
    {
        // Template à créer/approuver dans Meta Business Manager, ex:
        // "transport_paid_otp" avec variables {{1}}=référence {{2}}=code OTP
        return WhatsAppMessage::create()->template(
            name: 'transport_paid_otp',
            params: [$this->order->reference, $this->order->otp_code],
        );
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Code de retrait — {$this->order->reference}")
            ->greeting("Bonjour {$notifiable->name},")
            ->line("Les frais de transport de la commande {$this->order->reference} ont été payés.")
            ->line("Votre code de retrait : {$this->order->otp_code}")
            ->line('Présentez ce code à l\'agence pour récupérer votre colis.');
    }
}

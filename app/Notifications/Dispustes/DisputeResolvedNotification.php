<?php

namespace App\Notifications;

use App\Models\Dispute;
use App\Notifications\Concerns\HasDatabasePayload;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

// Envoyée à l'ACHETEUR et/ou au VENDEUR quand un litige est tranché.
// Le message change selon le destinataire ('buyer' | 'seller') — envoie
// une instance différente à chacun depuis DisputeService::resolve().
// database + mail (pas d'urgence horaire une fois la décision prise).
class DisputeResolvedNotification extends Notification implements ShouldQueue
{
    use Queueable, HasDatabasePayload;

    public function __construct(public Dispute $dispute, public string $recipientRole) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    protected function databasePayload(object $notifiable): array
    {
        return [
            'title' => 'Litige résolu',
            'body'  => "Le litige sur la commande {$this->dispute->order->reference} a été tranché : "
                . $this->resolutionLabel(),
            'icon'  => '✅',
            'url'   => route(
                $this->recipientRole === 'seller' ? 'seller.disputes.show' : 'buyer.orders.show',
                $this->recipientRole === 'seller' ? $this->dispute : $this->dispute->order
            ),
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Litige résolu — {$this->dispute->order->reference}")
            ->greeting("Bonjour {$notifiable->name},")
            ->line("Le litige concernant la commande {$this->dispute->order->reference} a été tranché.")
            ->line('Décision : ' . $this->resolutionLabel())
            ->line($this->dispute->resolution_note ?? '');
    }

    private function resolutionLabel(): string
    {
        return match ($this->dispute->resolution) {
            'refund_buyer'    => 'remboursement intégral de l\'acheteur',
            'pay_seller'      => 'paiement confirmé au vendeur',
            'partial_refund'  => 'remboursement partiel',
            'return_required' => 'retour du produit exigé, acheteur remboursé',
            'buyer_bad_faith' => 'plainte rejetée, vendeur payé',
            default           => $this->dispute->resolution,
        };
    }
}

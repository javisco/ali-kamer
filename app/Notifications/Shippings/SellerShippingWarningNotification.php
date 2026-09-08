<?php

namespace App\Notifications\Shippings;

use App\Models\Order;
use App\Notifications\Concerns\HasDatabasePayload;
use App\Notifications\Messages\WhatsAppMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\VonageMessage;
use Illuminate\Notifications\Notification;

// Envoyée au VENDEUR qui n'a pas expédié une commande payée dans le délai
// 'seller_shipping_warning_hours' de platform_settings. Urgent — risque de
// litige/annulation → database + SMS + WhatsApp.
//
// NOTE : correspond au réglage 'seller_shipping_warning_hours' déjà présent
// dans ton PlatformSettingsSeeder — je ne vois pas encore de Job/commande
// planifiée qui déclenche cette notification dans le code que tu m'as
// donné. Il en faudra une (ex: app/Console/Commands/WarnUnshippedOrders.php,
// planifiée toutes les heures) — dis-moi si tu veux que je la génère.
class SellerShippingWarningNotification extends Notification implements ShouldQueue
{
    use Queueable, HasDatabasePayload;

    public function __construct(public Order $order) {}

    public function via(object $notifiable): array
    {
        return ['database', 'vonage', 'whatsapp'];
    }

    protected function databasePayload(object $notifiable): array
    {
        return [
            'title' => 'Expédition en retard',
            'body'  => "La commande {$this->order->reference} n'a toujours pas été expédiée. Agissez rapidement.",
            'icon'  => '⏰',
            'url'   => route('seller.orders.show', $this->order),
        ];
    }

    public function toVonage(object $notifiable): VonageMessage
    {
        return (new VonageMessage())
            ->content(
                "Ali-Kamer : la commande {$this->order->reference} n'est toujours pas expédiée. "
                . 'Expédiez-la rapidement pour éviter un litige.'
            )
            ->from(config('services.vonage.sms_from'));
    }

    public function toWhatsApp(object $notifiable): WhatsAppMessage
    {
        // Template à créer/approuver, ex: "shipping_warning" {{1}}=référence
        return WhatsAppMessage::create()->template(
            name: 'shipping_warning',
            params: [$this->order->reference],
        );
    }
}

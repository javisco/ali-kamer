<?php

namespace App\Notifications\Products;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Notifications\Concerns\HasDatabasePayload;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

// Envoyée au VENDEUR quand le stock disponible d'un produit (ou d'une
// variante) tombe à 0. Pas assez urgent pour du SMS/WhatsApp (ça peut
// attendre qu'il consulte l'app), mais important pour qu'il pense à
// réapprovisionner → database + mail.
class ProductOutOfStockNotification extends Notification implements ShouldQueue
{
    use Queueable, HasDatabasePayload;

    public function __construct(
        public Product $product,
        public ?ProductVariant $variant = null
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    protected function databasePayload(object $notifiable): array
    {
        $label = $this->product->title . ($this->variant ? ' — ' . $this->variant->label() : '');

        return [
            'title' => 'Rupture de stock',
            'body'  => "\"{$label}\" n'a plus de stock disponible.",
            'icon'  => '📉',
            'url'   => route('seller.products.edit', $this->product), // adapte le nom de route si différent
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $label = $this->product->title . ($this->variant ? ' — ' . $this->variant->label() : '');

        return (new MailMessage)
            ->subject('Rupture de stock — ' . $this->product->title)
            ->greeting("Bonjour {$notifiable->name},")
            ->line("Votre produit \"{$label}\" n'a plus de stock disponible.")
            ->line('Il ne sera plus visible aux acheteurs tant que vous ne l\'aurez pas réapprovisionné.')
            ->action('Gérer ce produit', route('seller.products.edit', $this->product));
    }
}

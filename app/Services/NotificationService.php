<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    // Numéro WhatsApp Business de la plateforme
    // Configuré dans .env : WHATSAPP_PHONE_NUMBER_ID
    private string $phoneNumberId;

    // Token d'accès Meta Cloud API
    // Configuré dans .env : WHATSAPP_TOKEN
    private string $token;

    public function __construct()
    {
        $this->phoneNumberId = config('services.whatsapp.phone_number_id');
        $this->token         = config('services.whatsapp.token');
    }

    // ── ÉVÉNEMENTS COMMANDES ──────────────────────────────────────────

    // Commande payée — notifier acheteur et vendeur
    public function notifyOrderPaid(Order $order): void
    {
        // Message acheteur
        $this->send(
            $order->buyer,
            'order.paid',
            "✅ Paiement confirmé !\n\n" .
            "Commande : {$order->reference}\n" .
            "Montant : " . number_format($order->total_amount, 0, ',', ' ') . " FCFA\n\n" .
            "Le vendeur prépare votre colis."
        );

        // Message vendeur
        $this->send(
            $order->shop->user,
            'order.paid.seller',
            "🛒 Nouvelle commande reçue !\n\n" .
            "Référence : {$order->reference}\n" .
            "Acheteur : {$order->buyer->name}\n" .
            "Montant net : " . number_format($order->net_amount, 0, ',', ' ') . " FCFA\n\n" .
            "Préparez le colis et déposez-le en agence.\n" .
            "Code de dépôt : {$order->deposit_code}"
        );
    }

    // Colis déposé en agence départ
    public function notifyPackageRegistered(Order $order): void
    {
        $this->send(
            $order->buyer,
            'order.registered',
            "📦 Votre colis a été déposé !\n\n" .
            "Commande : {$order->reference}\n" .
            "Destination : {$order->shipment->destination_city}\n\n" .
            "Vous serez notifié à l'arrivée."
        );
    }

    // Colis arrivé à destination — envoyer OTP
    public function notifyPackageArrived(Order $order): void
    {
        $this->send(
            $order->buyer,
            'order.arrived',
            "🎉 Votre colis est arrivé !\n\n" .
            "Commande : {$order->reference}\n" .
            "Agence : {$order->shipment->destinationCounter->full_name}\n\n" .
            "Présentez-vous avec votre CNI et ce code OTP :\n\n" .
            "🔑 *{$order->otp_code}*\n\n" .
            "Valable jusqu'au {$order->otp_expires_at->format('d/m/Y à H:i')}"
        );

        // Notifier le vendeur que les fonds sont crédités
        $this->send(
            $order->shop->user,
            'order.arrived.seller',
            "✅ Colis arrivé à destination !\n\n" .
            "Commande : {$order->reference}\n" .
            "Montant crédité sur votre portefeuille : " .
            number_format($order->net_amount, 0, ',', ' ') . " FCFA (en attente)"
        );
    }

    // Relance acheteur H+24 — colis non retiré
    public function notifyPickupReminder(Order $order, int $hoursLeft): void
    {
        $this->send(
            $order->buyer,
            'order.pickup.reminder',
            "⚠ Rappel : votre colis vous attend !\n\n" .
            "Commande : {$order->reference}\n\n" .
            "Vous avez encore {$hoursLeft}h pour récupérer votre colis.\n" .
            "Passé ce délai, le vendeur sera automatiquement payé.\n\n" .
            "Code OTP : *{$order->otp_code}*"
        );
    }

    // Vendeur non-expéditeur — avertissement
    public function notifySellerShippingWarning(Order $order): void
    {
        $this->send(
            $order->shop->user,
            'order.shipping.warning',
            "⚠ URGENT : Commande non expédiée !\n\n" .
            "Référence : {$order->reference}\n\n" .
            "Votre colis n'a pas encore été déposé en agence.\n" .
            "Déposez-le rapidement ou l'acheteur pourra annuler la commande."
        );
    }

    // Litige ouvert
    public function notifyDisputeOpened(
        \App\Models\Dispute $dispute
    ): void {

        // Notifier l'autre partie
        $otherParty = $dispute->initiator->isBuyer()
            ? $dispute->order->shop->user
            : $dispute->order->buyer;

        $this->send(
            $otherParty,
            'dispute.opened',
            "⚠ Un litige a été ouvert !\n\n" .
            "Commande : {$dispute->order->reference}\n" .
            "Motif : {$dispute->typeLabel()}\n\n" .
            "Vous avez 48h pour répondre."
        );
    }

    // Décision litige
    public function notifyDisputeResolved(
        \App\Models\Dispute $dispute
    ): void {

        $message = "✅ Litige résolu !\n\n" .
            "Commande : {$dispute->order->reference}\n" .
            "Décision : {$dispute->resolutionLabel()}\n\n" .
            $dispute->resolution_note;

        $this->send($dispute->order->buyer, 'dispute.resolved', $message);
        $this->send($dispute->order->shop->user, 'dispute.resolved', $message);
    }

    // KYC approuvé
    public function notifyKycApproved(User $seller): void
    {
        $this->send(
            $seller,
            'kyc.approved',
            "🎉 Bienvenue sur Ali-Kamer !\n\n" .
            "Votre dossier de vérification a été approuvé.\n" .
            "Votre boutique est maintenant active.\n" .
            "Publiez vos premiers produits !"
        );
    }

    // KYC rejeté
    public function notifyKycRejected(User $seller, string $reason): void
    {
        $this->send(
            $seller,
            'kyc.rejected',
            "❌ Dossier KYC rejeté\n\n" .
            "Motif : {$reason}\n\n" .
            "Vous pouvez soumettre un nouveau dossier corrigé."
        );
    }

    // ── ENVOI GÉNÉRIQUE ───────────────────────────────────────────────

    // Méthode principale — crée la notification et la met en file d'attente
    public function send(
        User $user,
        string $type,
        string $body,
        array $data = []
    ): void {

        // Créer la notification en base
        $notification = Notification::create([
            'user_id' => $user->id,
            'channel' => 'whatsapp',
            'type'    => $type,
            'body'    => $body,
            'data'    => $data,
            'status'  => 'pending',
        ]);

        // Dispatch le job en file d'attente Redis
        // Le job gère l'envoi réel et les retries
        \App\Jobs\SendNotificationJob::dispatch($notification);
    }

    // ── ENVOI WHATSAPP VIA META CLOUD API ─────────────────────────────

    public function sendWhatsapp(
        string $phone,    // format : 237XXXXXXXXX
        string $message
    ): bool {

        // En mode test, on loggue seulement
        if (config('app.env') === 'local') {
            Log::info("WhatsApp [TEST] → {$phone}: {$message}");
            return true;
        }

        try {
            $response = Http::withToken($this->token)
                ->post(
                    "https://graph.facebook.com/v18.0/{$this->phoneNumberId}/messages",
                    [
                        'messaging_product' => 'whatsapp',
                        'to'                => $phone,
                        'type'              => 'text',
                        'text'              => ['body' => $message],
                    ]
                );

            if (! $response->successful()) {
                Log::error('WhatsApp send failed', [
                    'phone'    => $phone,
                    'response' => $response->json(),
                ]);
                return false;
            }

            return true;

        } catch (\Throwable $e) {
            Log::error('WhatsApp exception', ['error' => $e->getMessage()]);
            return false;
        }
    }

    // ── ENVOI SMS (fallback) ──────────────────────────────────────────

    public function sendSms(string $phone, string $message): bool
    {
        // TODO Phase 2 : intégrer Twilio ou un opérateur local
        Log::info("SMS [TODO] → {$phone}: {$message}");
        return true;
    }
}
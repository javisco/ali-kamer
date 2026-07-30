<?php

namespace App\Services;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MessagingService
{
    // Limites de taille des pièces jointes
    const MAX_IMAGE_SIZE = 2 * 1024 * 1024; // 2 MB en octets
    const MAX_PDF_SIZE   = 5 * 1024 * 1024; // 5 MB en octets

    // ── TROUVER OU CRÉER UNE CONVERSATION ────────────────────────────

    // Une seule conversation par acheteur/boutique
    // Si elle existe déjà on la retourne, sinon on la crée
    public function findOrCreateConversation(
        User $buyer,
        Shop $shop,
        ?int $productId = null
    ): Conversation {
        return Conversation::firstOrCreate(
            [
                'buyer_id' => $buyer->id,
                'shop_id'  => $shop->id,
            ],
            [
                // Produit de départ — enregistré uniquement à la création
                'product_id' => $productId,
            ]
        );
    }

    // ── ENVOYER UN MESSAGE TEXTE ──────────────────────────────────────

    public function sendText(
        Conversation $conversation,
        User $sender,
        string $body
    ): Message {
        return DB::transaction(function () use ($conversation, $sender, $body) {

            $message = Message::create([
                'conversation_id' => $conversation->id,
                'sender_id'       => $sender->id,
                'type'            => 'text',

                // Le contenu est stocké tel quel
                // Aucune modification possible après envoi
                'body'            => $body,

                // sent_at est défini côté serveur via useCurrent()
                // jamais une valeur envoyée par le client
            ]);

            // Mettre à jour la date du dernier message
            // pour trier les conversations par activité
            $conversation->update(['last_message_at' => now()]);

            return $message;
        });
    }

    // ── ENVOYER UNE PIÈCE JOINTE (image ou PDF) ──────────────────────

    public function sendAttachment(
        Conversation $conversation,
        User $sender,
        UploadedFile $file
    ): Message {

        // Déterminer le type selon le mimetype
        $type = str_starts_with($file->getMimeType(), 'image/') ? 'image' : 'pdf';

        // Vérifier la taille selon le type
        $maxSize = $type === 'image' ? self::MAX_IMAGE_SIZE : self::MAX_PDF_SIZE;
        if ($file->getSize() > $maxSize) {
            $maxMb = $maxSize / 1024 / 1024;
            throw new \Exception("Fichier trop lourd. Maximum {$maxMb} MB.");
        }

        return DB::transaction(function () use ($conversation, $sender, $file, $type) {

            // Stocker le fichier dans un dossier par conversation
            $path = $file->store(
                "messages/{$conversation->id}",
                'public'
            );

            $message = Message::create([
                'conversation_id' => $conversation->id,
                'sender_id'       => $sender->id,
                'type'            => $type,
                'attachment_url'  => $path,
                'attachment_size' => $file->getSize(),
            ]);

            $conversation->update(['last_message_at' => now()]);

            return $message;
        });
    }

    // ── MARQUER LES MESSAGES COMME LUS ───────────────────────────────

    // Appelé quand un utilisateur ouvre une conversation
    public function markAsRead(Conversation $conversation, User $reader): void
    {
        Message::where('conversation_id', $conversation->id)
            ->where('sender_id', '!=', $reader->id)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
    }

    // ── POLLING — NOUVEAUX MESSAGES ───────────────────────────────────

    // Retourne les messages après un certain ID
    // Utilisé par le polling JavaScript (toutes les 5-10 secondes)
    public function getNewMessages(Conversation $conversation, int $afterId): array
    {
        return Message::where('conversation_id', $conversation->id)
            ->where('id', '>', $afterId)
            ->with('sender:id,name')
            ->orderBy('sent_at')
            ->get()
            ->toArray();
    }

    // ── TOTAL DES MESSAGES NON LUS ────────────────────────────────────

    // Badge de notifications dans la navbar
    public function totalUnread(User $user): int
    {
        // Récupérer toutes les conversations de l'utilisateur
        $conversationIds = Conversation::where('buyer_id', $user->id)
            ->orWhereHas('shop', fn($q) => $q->where('user_id', $user->id))
            ->pluck('id');

        return Message::whereIn('conversation_id', $conversationIds)
            ->where('sender_id', '!=', $user->id)
            ->where('is_read', false)
            ->count();
    }

    // ── RÉCUPÉRER LES CONVERSATIONS D'UN UTILISATEUR ─────────────────

    public function getConversations(User $user)
    {
        // Acheteur : ses conversations
        if ($user->isBuyer()) {
            return Conversation::where('buyer_id', $user->id)
                ->with(['shop', 'lastMessage', 'product'])
                ->active()
                ->orderByDesc('last_message_at')
                ->paginate(20);
        }

        // Vendeur : conversations de sa boutique
        return Conversation::where('shop_id', $user->shop->id)
            ->with(['buyer', 'lastMessage', 'product'])
            ->active()
            ->orderByDesc('last_message_at')
            ->paginate(20);
    }
}

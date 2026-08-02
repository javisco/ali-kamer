<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Product;
use App\Models\Shop;
use App\Services\MessagingService;
use Illuminate\Http\Request;

class MessagingController extends Controller
{
    public function __construct(private MessagingService $messagingService) {}

    // Liste de toutes les conversations de l'utilisateur
    public function index()
    {
        $user          = auth()->user();
        $conversations = $this->messagingService->getConversations($user);
        $totalUnread   = $this->messagingService->totalUnread($user);

        return view('messaging.index', compact('conversations', 'totalUnread'));
    }

    // Ouvrir ou créer une conversation avec une boutique
    public function show(Conversation $conversation)
    {
        $user = auth()->user();

        // Vérifier que l'utilisateur fait partie de cette conversation
        abort_unless(
            $conversation->buyer_id === $user->id
            || $conversation->shop->user_id === $user->id,
            403
        );

        // Charger les messages et marquer comme lus
        $conversation->load(['messages.sender', 'shop', 'buyer', 'product']);
        $this->messagingService->markAsRead($conversation, $user);

        return view('messaging.show', compact('conversation'));
    }

    // Démarrer une conversation depuis une fiche produit ou boutique
    public function start(Product $product, Shop $shop)
    {
        $user = auth()->user();

        // Un vendeur ne peut pas se contacter lui-même
        abort_if($shop->user_id === $user->id, 403);

        $conversation = $this->messagingService->findOrCreateConversation(
            $user,
            $shop,
            $product->id
        );

        return redirect()->route('messaging.show', $conversation);
    }

    // Envoyer un message texte
    public function sendText(Request $request, Conversation $conversation)
    {
        $request->validate([
            'body' => ['required', 'string', 'max:2000'],
        ]);

        $user = auth()->user();

        abort_unless(
            $conversation->buyer_id === $user->id
            || $conversation->shop->user_id === $user->id,
            403
        );

        $this->messagingService->sendText($conversation, $user, $request->body);

        return back();
    }

    // Envoyer une pièce jointe (image ou PDF)
    public function sendAttachment(Request $request, Conversation $conversation)
    {
        $request->validate([
            'attachment' => ['required', 'file', 'max:5120', 'mimes:jpg,jpeg,png,pdf'],
        ]);

        $user = auth()->user();

        abort_unless(
            $conversation->buyer_id === $user->id
            || $conversation->shop->user_id === $user->id,
            403
        );

        $this->messagingService->sendAttachment(
            $conversation,
            $user,
            $request->file('attachment')
        );

        return back();
    }

    // Polling — retourne les nouveaux messages en JSON
    // Appelé par JavaScript toutes les 5-10 secondes
    public function poll(Request $request, Conversation $conversation)
    {
        $user = auth()->user();

        abort_unless(
            $conversation->buyer_id === $user->id
            || $conversation->shop->user_id === $user->id,
            403
        );

        $messages = $this->messagingService->getNewMessages(
            $conversation,
            $request->after_id ?? 0
        );

        // Marquer les nouveaux messages comme lus
        $this->messagingService->markAsRead($conversation, $user);

        return response()->json($messages);
    }
}
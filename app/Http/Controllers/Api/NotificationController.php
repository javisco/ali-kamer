<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    // Liste des notifications récentes (lues + non lues) pour le dropdown
    // de la cloche. Limité à 20 pour rester léger côté front.
    public function index()
    {
        $user = Auth::user();

        $notifications = $user->notifications()
            ->latest()
            ->limit(20)
            ->get()
            ->map(fn ($n) => [
                'id'         => $n->id,
                'title'      => $n->data['title'] ?? '',
                'body'       => $n->data['body'] ?? '',
                'icon'       => $n->data['icon'] ?? '🔔',
                'url'        => $n->data['url'] ?? null,
                'read'       => $n->read_at !== null,
                'created_at' => $n->created_at->diffForHumans(),
            ]);

        return response()->json([
            'notifications' => $notifications,
            'unread_count'  => $user->unreadNotifications()->count(),
        ]);
    }

    // Juste le compteur — utilisé par le polling léger toutes les X secondes
    // pour rafraîchir le badge sans recharger toute la liste.
    public function unreadCount()
    {
        return response()->json([
            'unread_count' => Auth::user()->unreadNotifications()->count(),
        ]);
    }

    // Marquer une notification comme lue (au clic dessus dans le dropdown)
    public function markAsRead(string $id)
    {
        $notification = Auth::user()->notifications()->where('id', $id)->firstOrFail();
        $notification->markAsRead();

        return response()->json(['status' => 'ok']);
    }

    // Tout marquer comme lu
    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return response()->json(['status' => 'ok']);
    }
}

<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Dispute;
use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Espace Vendeur : Mon profil / Ma réputation.
     */
    public function index(Request $request): View
    {
        $user = Auth::user();

        abort_unless($user && $user->isSeller(), 403, 'Accès réservé aux vendeurs.');

        $shop = $user->shop;

        // Note moyenne et nombre d'avis
        $reviewsQuery = $shop
            ? Review::where('reviewee_type', 'shop')->where('reviewee_id', $shop->id)
            : Review::whereRaw('1 = 0');

        $reviewsCount = $reviewsQuery->count();
        $avgRating = $reviewsCount > 0 ? round((float) $reviewsQuery->avg('rating'), 2) : 0.0;

        // Éléments explicatifs autorisés du TrustScore (Section 14)
        $summary = [
            'completed_orders' => $shop ? Order::where('shop_id', $shop->id)->whereIn('status', ['completed', 'auto_completed'])->count() : 0,
            'cancelled_orders' => $shop ? Order::where('shop_id', $shop->id)->where('status', 'cancelled')->count() : 0,
            'disputes_count'   => $shop ? Dispute::whereHas('order', fn ($q) => $q->where('shop_id', $shop->id))->count() : 0,
            'sanctions_count'  => $user->sanctions()->count(),
            'reviews_count'    => $reviewsCount,
            'avg_rating'       => $avgRating,
        ];

        // Historique des notes reçues par commande (Section 13)
        $reviewsHistory = $shop
            ? Review::where('reviewee_type', 'shop')
                ->where('reviewee_id', $shop->id)
                ->with(['reviewer', 'order.items'])
                ->latest()
                ->paginate(15)
            : collect();

        return view('seller.profile.index', compact('user', 'shop', 'avgRating', 'reviewsCount', 'summary', 'reviewsHistory'));
    }
}

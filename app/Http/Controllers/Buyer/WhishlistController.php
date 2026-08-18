<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Services\WishlistService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function __construct(private WishlistService $wishlistService) {}

    // Liste des favoris
    public function index()
    {
        $wishlist = $this->wishlistService->getWishlist(Auth::user());
        return view('buyer.wishlist.index', compact('wishlist'));
    }

    // Ajouter/retirer des favoris (toggle)
    public function toggle(Request $request, int $productId)
    {
        $added = $this->wishlistService->toggle(Auth::user(), $productId);

        if ($request->wantsJson()) {
            return response()->json([
                'added'   => $added,
                'message' => $added ? 'Ajouté aux favoris' : 'Retiré des favoris',
            ]);
        }

        return back()->with(
            'success',
            $added ? 'Ajouté aux favoris.' : 'Retiré des favoris.'
        );
    }
}
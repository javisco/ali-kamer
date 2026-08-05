<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\ReviewService;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function __construct(private ReviewService $reviewService) {}

    // Vendeur note l'acheteur
    public function store(Request $request, Order $order)
    {
        abort_unless($order->shop->user_id === auth()->id(), 403);

        $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'body'   => ['nullable', 'string', 'max:500'],
        ]);

        $this->reviewService->reviewBySellerForBuyer(
            $order,
            auth()->user(),
            $request->rating,
            $request->body
        );

        return back()->with('success', 'Acheteur noté avec succès.');
    }
}
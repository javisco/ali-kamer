<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\ReviewService;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function __construct(private ReviewService $reviewService) {}

    // Formulaire de notation (acheteur)
    public function create(Order $order)
    {
        abort_unless($order->buyer_id === auth()->id(), 403);
        abort_unless($order->isCompleted(), 403);

        // Vérifier qu'il n'a pas déjà noté
        $alreadyReviewed = $order->reviews()
            ->where('reviewer_id', auth()->id())
            ->exists();

        if ($alreadyReviewed) {
            return redirect()->route('buyer.orders.show', $order)
                ->with('info', 'Vous avez déjà noté cette commande.');
        }

        return view('buyer.reviews.create', compact('order'));
    }

    // Soumettre la note (acheteur)
    public function store(Request $request, Order $order)
    {
        abort_unless($order->buyer_id === auth()->id(), 403);

        $request->validate([
            'product_rating' => ['required', 'integer', 'min:1', 'max:5'],
            'shop_rating'    => ['required', 'integer', 'min:1', 'max:5'],
            'body'           => ['nullable', 'string', 'max:500'],
        ]);

        $this->reviewService->reviewByBuyer(
            $order,
            auth()->user(),
            $request->product_rating,
            $request->shop_rating,
            $request->body
        );

        return redirect()->route('buyer.orders.show', $order)
            ->with('success', 'Merci pour votre avis !');
    }
}
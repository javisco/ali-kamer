<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureShopActive
{
    public function handle(Request $request, Closure $next)
    {
        $shop = $request->user()->shop;

        if (! $shop) {
            return redirect()->route('seller.shop.create');
        }

        if (! $shop->isActive()) {
            return redirect()->route('seller.kyc.pending')
                ->with('info', 'Votre boutique est en attente de validation.');
        }

        return $next($request);
    }
}
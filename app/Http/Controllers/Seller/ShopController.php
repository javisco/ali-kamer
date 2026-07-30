<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\StoreShopRequest;
use App\Services\ShopService;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
    public function __construct(private ShopService $shopService) {}

    public function create()
    {
        if (Auth::user()->shop) {
            return redirect()->route('seller.kyc.create');
        }

        return view('seller.shop.create');
    }

    public function store(StoreShopRequest $request)
    {
        $this->shopService->create(Auth::user(), $request->validated());

        return redirect()->route('seller.kyc.create')
            ->with('success', 'Boutique créée. Envoyez vos documents pour validation.');
    }

    public function edit()
    {
        $shop = Auth::user()->shop;
        abort_unless($shop, 404);

        return view('seller.shop.edit', compact('shop'));
    }

    public function update(StoreShopRequest $request)
    {
        
        $shop =Auth::user()->shop;
        abort_unless($shop, 404);

        $this->shopService->update($shop, $request->validated());

        return back()->with('success', 'Boutique mise à jour.');
    }
}
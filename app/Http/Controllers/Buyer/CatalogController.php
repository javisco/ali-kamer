<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use App\Models\Shop;
use App\Services\ProductVariantService;
use App\Services\WishlistService;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    // Page d'accueil catalogue
    public function index(Request $request)
    {
        $query = Product::visible()->whereHas('shop', function ($q) {
            $q->where('status', \App\Models\Shop::STATUS_ACTIVE);
        })
            ->with([
                'shop',
                'images' => fn($q) => $q->where('is_primary', true),
                'activeVariants',
            ])
            ->latest();

        // Filtres
        if ($request->filled('q')) {
            $query->search($request->q);
        }

        if ($request->filled('category')) {
            $category = Category::where('name', $request->category)->first();
            if ($category) {
                $query->byCategory($category->id);
            }
        }

        if ($request->filled('city')) {
            $query->byCity($request->city);
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->filled('shipping')) {
            $query->where('shipping_included', $request->shipping === 'included');
        }

        // Tri
        match ($request->get('sort', 'recent')) {
            'price_asc'  => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'popular'    => $query->orderBy('orders_count', 'desc'),
            default      => $query->latest(),
        };

        $products   = $query->paginate(20)->withQueryString();
        $categories = Category::active()->parents()->get();

        return view('buyer.catalog.index', compact('products', 'categories'));
    }



    public function show(Product $product)
    {
        abort_unless($product->isVisible(), 404);
        $product->increment('views_count');

        $product->load([
            'shop',
            'category',
            'images',
            'attributes.values',
            'activeVariants.attributeValues.attribute',
        ]);

        // Variantes pour le sélecteur JS
        $variantsData = app(ProductVariantService::class)
            ->getVariantsForDisplay($product);

        // Favori ?
        $isWishlisted = auth()->check()
            ? app(WishlistService::class)->isWishlisted(auth()->user(), $product->id)
            : false;

        // Avis
        $reviews      = Review::forProduct($product->id)->where('is_flagged', false)
            ->with('reviewer:id,name')->latest()->limit(10)->get();
        $productRating = $reviews->avg('rating');
        $shopReviews   = Review::forShop($product->shop_id)->where('is_flagged', false)
            ->with('reviewer:id,name')->latest()->limit(5)->get();
        $shopRating    = Review::forShop($product->shop_id)->avg('rating');

        // Produits liés
        $related = Product::visible()->byCategory($product->category_id)
            ->where('id', '!=', $product->id)
            ->with(['images' => fn($q) => $q->where('is_primary', true)])
            ->limit(6)->get();

        $shopProducts = Product::visible()->where('shop_id', $product->shop_id)
            ->where('id', '!=', $product->id)
            ->with(['images' => fn($q) => $q->where('is_primary', true)])
            ->limit(6)->get();

        return view('buyer.catalog.show', compact(
            'product',
            'variantsData',
            'isWishlisted',
            'reviews',
            'productRating',
            'shopReviews',
            'shopRating',
            'related',
            'shopProducts'
        ));
    }





    //  Fiche produit
    // public function show(Product $product)
    // {
    //     abort_unless($product->isVisible(), 404);

    //     $product->increment('views_count');

    //     $product->load([
    //         'shop',
    //         'category',
    //         'images',
    //     ]);

    //     // Avis vérifiés sur ce produit
    //     $reviews = Review::forProduct($product->id)
    //         ->where('is_flagged', false)
    //         ->with('reviewer:id,name')
    //         ->latest()
    //         ->limit(10)
    //         ->get();

    //     // Note moyenne du produit
    //     $productRating = $reviews->avg('rating');

    //     // Avis sur la boutique
    //     $shopReviews = Review::forShop($product->shop_id)
    //         ->where('is_flagged', false)
    //         ->with('reviewer:id,name')
    //         ->latest()
    //         ->limit(5)
    //         ->get();

    //     // Note moyenne boutique
    //     $shopRating = Review::forShop($product->shop_id)
    //         ->where('is_flagged', false)
    //         ->avg('rating');

    //     // Produits similaires
    //     $related = Product::visible()
    //         ->byCategory($product->category_id)
    //         ->where('id', '!=', $product->id)
    //         ->with(['images' => fn($q) => $q->where('is_primary', true)])
    //         ->limit(6)
    //         ->get();

    //     $shopProducts = Product::visible()
    //         ->where('shop_id', $product->shop_id)
    //         ->where('id', '!=', $product->id)
    //         ->with(['images' => fn($q) => $q->where('is_primary', true)])
    //         ->limit(6)
    //         ->get();

    //     return view('buyer.catalog.show', compact(
    //         'product',
    //         'reviews',
    //         'productRating',
    //         'shopReviews',
    //         'shopRating',
    //         'related',
    //         'shopProducts'
    //     ));
    // }

    // Page boutique vendeur
    public function shop(Shop $shop)
    {
        abort_unless($shop->isActive(), 404);

        $products = Product::visible()
            ->where('shop_id', $shop->id)
            ->with(['images' => fn($q) => $q->where('is_primary', true)])
            ->paginate(20);

        return view('buyer.catalog.shop', compact('shop', 'products'));
    }
}

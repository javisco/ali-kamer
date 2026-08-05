<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    // Page d'accueil catalogue
    public function index(Request $request)
    {
        $query = Product::visible()
            ->with(['shop', 'images' => fn($q) => $q->where('is_primary', true)])
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

    // Fiche produit
    public function show(Product $product)
    {
        abort_unless($product->isVisible(), 404);

        // Incrémenter les vues
        $product->increment('views_count');

        $product->load([
            'shop',
            'category',
            'images',
        ]);

        // Autres produits de la même catégorie
        $related = Product::visible()
            ->byCategory($product->category_id)
            ->where('id', '!=', $product->id)
            ->with(['images' => fn($q) => $q->where('is_primary', true)])
            ->limit(6)
            ->get();

        // Autres produits du même vendeur
        $shopProducts = Product::visible()
            ->where('shop_id', $product->shop_id)
            ->where('id', '!=', $product->id)
            ->with(['images' => fn($q) => $q->where('is_primary', true)])
            ->limit(6)
            ->get();

        return view('buyer.catalog.show', compact('product', 'related', 'shopProducts'));
    }

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

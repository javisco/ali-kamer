<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function __construct(private ProductService $productService) {}

    // Liste des produits du vendeur
    public function index()
    {
    
        $products = Auth::user()->shop
            ->products()
            ->with(['category', 'images'])
            ->latest()
            ->paginate(20);

        return view('seller.products.index', compact('products'));
    }

    // Formulaire création
    public function create()
    {
        $categories = Category::active()->parents()->with('children')->get();
        return view('seller.products.create', compact('categories'));
    }

    // Sauvegarder le produit
    public function store(StoreProductRequest $request)
    {
        $product = $this->productService->create(
            Auth::user()->shop,
            $request->validated()
        );

        return redirect()->route('seller.products.index')
            ->with('success', 'Produit créé. Il sera visible après validation.');
    }

    // Formulaire modification
    public function edit(Product $product)
    {
        $this->authorizeProduct($product);

        $categories = Category::active()->parents()->with('children')->get();
        return view('seller.products.edit', compact('product', 'categories'));
    }

    // Sauvegarder la modification
    public function update(StoreProductRequest $request, Product $product)
    {
        $this->authorizeProduct($product);

        $this->productService->update($product, $request->validated());

        return redirect()->route('seller.products.index')
            ->with('success', 'Produit mis à jour.');
    }

    // Supprimer le produit
    public function destroy(Product $product)
    {
        $this->authorizeProduct($product);

        $this->productService->delete($product);

        return redirect()->route('seller.products.index')
            ->with('success', 'Produit supprimé.');
    }

    // Activer / désactiver la visibilité
    public function toggleVisibility(Product $product)
    {
        $this->authorizeProduct($product);

        $this->productService->toggleVisibility($product);

        return back()->with('success', 'Statut du produit mis à jour.');
    }

    // Supprimer une image
    public function deleteImage(ProductImage $image)
    {
        $this->authorizeProduct($image->product);

        $this->productService->deleteImage($image);

        return back()->with('success', 'Image supprimée.');
    }

    // Vérifier que le produit appartient au vendeur connecté
    private function authorizeProduct(Product $product): void
    {
        abort_unless(
            $product->shop_id === Auth::user()->shop->id,
            403,
            'Ce produit ne vous appartient pas.'
        );
    }
}
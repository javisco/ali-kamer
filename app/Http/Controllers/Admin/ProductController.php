<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminLog;
use App\Models\Product;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Vue normale des produits pour l'administration.
     */
    public function index(Request $request)
    {
        $query = Product::query()
            ->with(['shop.user', 'category', 'images'])
            ->whereHas('shop', function ($q) {
                $q->where('status', Shop::STATUS_ACTIVE);
            });

        if ($request->filled('q')) {
            $search = trim($request->q);

            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $products = $query
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.products.index', compact('products'));
    }

    /**
     * Vue avancée de modération.
     */
    public function moderation(Request $request)
    {
        $query = Product::with([
            'shop.user',
            'category',
            'images',
        ]);

        if ($request->filled('q')) {
            $search = trim($request->q);

            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('product_status')) {
            $query->where('status', $request->product_status);
        }

        if ($request->filled('shop_status')) {
            $query->whereHas('shop', function ($q) use ($request) {
                $q->where('status', $request->shop_status);
            });
        }

        if ($request->filled('seller_status')) {
            $query->whereHas('shop.user', function ($q) use ($request) {
                $q->where('status', $request->seller_status);
            });
        }

        $products = $query
            ->latest()
            ->paginate(25)
            ->withQueryString();

        $stats = [
            'total' => Product::count(),

            'visible' => Product::where('status', Product::STATUS_VISIBLE)
                ->count(),

            'hidden' => Product::where('status', Product::STATUS_HIDDEN)
                ->count(),

            'sold_out' => Product::where('status', Product::STATUS_SOLD_OUT)
                ->count(),

            'banned' => Product::where('status', Product::STATUS_BANNED)
                ->count(),

            'deleted' => Product::onlyTrashed()->count(),
        ];

        return view('admin.products.moderation', compact(
            'products',
            'stats'
        ));
    }

    /**
     * Dossier avancé d'un produit.
     */
    public function show(Product $product)
    {
        $product->load([
            'shop.user',
            'category',
            'images',
            'variants',
            'attributes',
        ]);

        $logs = AdminLog::where(function ($query) use ($product) {
            $query->where('target_type', Product::class)
                ->where('target_id', $product->id);

            if ($product->shop) {
                $query->orWhere(function ($q) use ($product) {
                    $q->where('target_type', Shop::class)
                        ->where('target_id', $product->shop->id);
                });
            }

            if ($product->shop?->user) {
                $query->orWhere(function ($q) use ($product) {
                    $q->where('target_type', User::class)
                        ->where('target_id', $product->shop->user->id);
                });
            }
        })
            ->latest()
            ->get();

        return view('admin.products.show', compact(
            'product',
            'logs'
        ));
    }

    /**
     * Masquer un produit.
     */
    public function hide(Product $product)
    {
        $product->update([
            'status' => Product::STATUS_HIDDEN,
        ]);

        $this->log(
            auth()->user(),
            'product_hidden',
            $product,
            'Produit masqué par l’administration.'
        );

        return back()->with(
            'success',
            'Le produit a été masqué.'
        );
    }

    /**
     * Rendre visible un produit.
     */
    public function unhide(Product $product)
    {
        if ($product->status === Product::STATUS_BANNED) {
            return back()->with(
                'error',
                'Un produit banni doit d’abord être réhabilité.'
            );
        }

        if (!$product->shop || !$product->shop->isActive()) {
            return back()->with(
                'error',
                'Impossible de rendre le produit visible car la boutique n’est pas active.'
            );
        }

        $product->update([
            'status' => Product::STATUS_VISIBLE,
        ]);

        $this->log(
            auth()->user(),
            'product_visible',
            $product,
            'Produit rendu visible par l’administration.'
        );

        return back()->with(
            'success',
            'Le produit est maintenant visible.'
        );
    }

    /**
     * Bannir définitivement un produit.
     */
    public function ban(Request $request, Product $product)
    {
        $validated = $request->validate([
            'reason' => ['required', 'string', 'min:5', 'max:1000'],
        ]);

        $product->update([
            'status' => Product::STATUS_BANNED,
        ]);

        $this->log(
            auth()->user(),
            'product_banned',
            $product,
            $validated['reason']
        );

        return back()->with(
            'success',
            'Le produit a été banni.'
        );
    }

    /**
     * Réhabiliter un produit banni.
     */
    public function unban(Product $product)
    {
        $product->update([
            'status' => Product::STATUS_HIDDEN,
        ]);

        $this->log(
            auth()->user(),
            'product_unbanned',
            $product,
            'Produit réhabilité. Il reste masqué jusqu’à une nouvelle activation.'
        );

        return back()->with(
            'success',
            'Le produit a été réhabilité et reste masqué.'
        );
    }

    /**
     * Suppression logique du produit.
     */
    public function destroy(Request $request, Product $product)
    {
        $validated = $request->validate([
            'reason' => ['required', 'string', 'min:5', 'max:1000'],
        ]);

        $product->delete();

        $this->log(
            auth()->user(),
            'product_deleted',
            $product,
            $validated['reason']
        );

        return redirect()
            ->route('admin.products.moderation')
            ->with(
                'success',
                'Le produit a été supprimé.'
            );
    }

    /**
     * Restaurer un produit supprimé.
     */
    public function restore(Product $product)
    {
        $product->restore();

        $product->update([
            'status' => Product::STATUS_HIDDEN,
        ]);

        $this->log(
            auth()->user(),
            'product_restored',
            $product,
            'Produit restauré. Il reste masqué.'
        );

        return back()->with(
            'success',
            'Le produit a été restauré.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | BOUTIQUE
    |--------------------------------------------------------------------------
    */

    /**
     * Suspendre une boutique.
     */
    public function suspendShop(Request $request, Shop $shop)
    {
        $validated = $request->validate([
            'reason' => ['required', 'string', 'min:5', 'max:1000'],
        ]);

        $shop->update([
            'status' => Shop::STATUS_SUSPENDED,
        ]);

        $shop->products()
            ->where('status', Product::STATUS_VISIBLE)
            ->update([
                'status' => Product::STATUS_HIDDEN,
            ]);

        $this->log(
            auth()->user(),
            'shop_suspended',
            $shop,
            $validated['reason']
        );

        return back()->with(
            'success',
            'La boutique a été suspendue et ses produits visibles ont été masqués.'
        );
    }

    /**
     * Réactiver une boutique.
     */
    public function activateShop(Shop $shop)
    {
        if ($shop->status === 'banned') {
            return back()->with(
                'error',
                'Une boutique bannie doit d’abord être réhabilitée.'
            );
        }

        $shop->update([
            'status' => Shop::STATUS_ACTIVE,
        ]);

        $this->log(
            auth()->user(),
            'shop_activated',
            $shop,
            'Boutique réactivée par l’administration.'
        );

        return back()->with(
            'success',
            'La boutique a été activée.'
        );
    }

    /**
     * Bannir une boutique.
     */
    public function banShop(Request $request, Shop $shop)
    {
        $validated = $request->validate([
            'reason' => ['required', 'string', 'min:5', 'max:1000'],
        ]);

        $shop->update([
            'status' => 'banned',
        ]);

        $shop->products()
            ->update([
                'status' => Product::STATUS_BANNED,
            ]);

        $this->log(
            auth()->user(),
            'shop_banned',
            $shop,
            $validated['reason']
        );

        return back()->with(
            'success',
            'La boutique a été bannie et ses produits ont été bannis.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | VENDEUR
    |--------------------------------------------------------------------------
    */

    /**
     * Suspendre un vendeur.
     */
    public function suspendSeller(Request $request, User $user)
    {
        $validated = $request->validate([
            'reason' => ['required', 'string', 'min:5', 'max:1000'],
        ]);

        if (!$user->isSeller()) {
            return back()->with(
                'error',
                'Cet utilisateur n’est pas un vendeur.'
            );
        }

        $user->update([
            'status' => User::STATUS_SUSPENDED,
        ]);

        $shop = $user->shop;

        if ($shop) {
            $shop->update([
                'status' => Shop::STATUS_SUSPENDED,
            ]);

            $shop->products()
                ->where('status', Product::STATUS_VISIBLE)
                ->update([
                    'status' => Product::STATUS_HIDDEN,
                ]);
        }

        $this->log(
            auth()->user(),
            'seller_suspended',
            $user,
            $validated['reason']
        );

        return back()->with(
            'success',
            'Le vendeur a été suspendu.'
        );
    }

    /**
     * Réactiver un vendeur.
     */
    public function activateSeller(User $user)
    {
        if (!$user->isSeller()) {
            return back()->with(
                'error',
                'Cet utilisateur n’est pas un vendeur.'
            );
        }

        if ($user->status === User::STATUS_BANNED) {
            return back()->with(
                'error',
                'Un vendeur banni doit d’abord être réhabilité.'
            );
        }

        $user->update([
            'status' => User::STATUS_ACTIVE,
        ]);

        if ($user->shop && $user->shop->status !== 'banned') {
            $user->shop->update([
                'status' => Shop::STATUS_ACTIVE,
            ]);
        }

        $this->log(
            auth()->user(),
            'seller_activated',
            $user,
            'Vendeur réactivé par l’administration.'
        );

        return back()->with(
            'success',
            'Le vendeur a été activé.'
        );
    }

    /**
     * Bannir un vendeur.
     */
    public function banSeller(Request $request, User $user)
    {
        $validated = $request->validate([
            'reason' => ['required', 'string', 'min:5', 'max:1000'],
        ]);

        if (!$user->isSeller()) {
            return back()->with(
                'error',
                'Cet utilisateur n’est pas un vendeur.'
            );
        }

        $user->update([
            'status' => User::STATUS_BANNED,
        ]);

        $shop = $user->shop;

        if ($shop) {
            $shop->update([
                'status' => 'banned',
            ]);

            $shop->products()
                ->update([
                    'status' => Product::STATUS_BANNED,
                ]);
        }

        $this->log(
            auth()->user(),
            'seller_banned',
            $user,
            $validated['reason']
        );

        return back()->with(
            'success',
            'Le vendeur a été banni ainsi que sa boutique et ses produits.'
        );
    }

    /**
     * Enregistrer une action administrative.
     */
    private function log(
        User $admin,
        string $action,
        $target,
        string $note
    ): void {
        $targetType = match (true) {
            $target instanceof Product => Product::class,
            $target instanceof Shop => Shop::class,
            $target instanceof User => User::class,
            default => get_class($target),
        };

        AdminLog::record(
            $admin,
            $action,
            $targetType,
            $target->id,
            $note
        );
    }
}


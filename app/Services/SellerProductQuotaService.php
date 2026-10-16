<?php

namespace App\Services;

use App\Models\Product;
use App\Models\SellerQuotaExtension;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class SellerProductQuotaService
{
    public function __construct(private BusinessSettingsService $settings) {}

    /**
     * @return array{
     *   enforcement: bool,
     *   registered_used: int,
     *   registered_limit: int,
     *   published_used: int,
     *   published_limit: int,
     *   hidden_used: int,
     *   hidden_limit: int,
     *   can_create: bool,
     *   can_publish: bool,
     *   message: ?string
     * }
     */
    public function snapshot(Shop $shop): array
    {
        $enforcement = $this->settings->enabled('catalog.quota_enforcement_enabled', true);

        $registeredUsed = $shop->products()->count();
        $publishedUsed  = $shop->products()->where('status', Product::STATUS_VISIBLE)->count();
        $hiddenUsed     = $shop->products()->where('status', Product::STATUS_HIDDEN)->count();

        $limits = $this->limitsFor($shop);

        $canCreate  = ! $enforcement || $registeredUsed < $limits['registered'];
        $canPublish = ! $enforcement || $publishedUsed < $limits['published'];
        $canDraft   = ! $enforcement || $hiddenUsed < $limits['hidden'];

        $message = null;
        if ($enforcement && ! $canCreate) {
            $message = "Quota atteint : {$registeredUsed}/{$limits['registered']} produits enregistrés (tous statuts hors suppression). Une baisse de quota ne supprime pas vos produits existants.";
        } elseif ($enforcement && ! $canDraft) {
            $message = "Quota de brouillons atteint : {$hiddenUsed}/{$limits['hidden']} produits masqués.";
        }

        return [
            'enforcement'      => $enforcement,
            'registered_used'  => $registeredUsed,
            'registered_limit' => $limits['registered'],
            'published_used'   => $publishedUsed,
            'published_limit'  => $limits['published'],
            'hidden_used'      => $hiddenUsed,
            'hidden_limit'     => $limits['hidden'],
            'can_create'       => $canCreate && $canDraft,
            'can_publish'      => $canPublish,
            'message'          => $message,
            'paid_slots'       => $limits['paid_registered'],
            'adaptive'         => $limits['adaptive'],
        ];
    }

    public function assertCanCreate(Shop $shop): void
    {
        $snap = $this->snapshot($shop);
        if ($snap['can_create']) {
            return;
        }

        throw ValidationException::withMessages([
            'quota' => $snap['message'] ?? 'Vous avez atteint votre quota de produits.',
        ]);
    }

    public function assertCanPublish(Shop $shop): void
    {
        $snap = $this->snapshot($shop);
        if ($snap['can_publish']) {
            return;
        }

        throw ValidationException::withMessages([
            'quota' => "Quota de publication atteint : {$snap['published_used']}/{$snap['published_limit']} produits visibles. Les produits déjà publiés restent en ligne.",
        ]);
    }

    /**
     * @return array{registered: int, published: int, hidden: int, paid_registered: int, adaptive: bool}
     */
    public function limitsFor(Shop $shop): array
    {
        $registered = $this->settings->getInt('catalog.max_products_registered', 50);
        $published  = $this->settings->getInt('catalog.max_products_published', 30);
        $hidden     = $this->settings->getInt('catalog.max_products_hidden', 50);
        $adaptive   = false;

        if ($this->settings->enabled('catalog.adaptive_quota_enabled', false)) {
            $seller = $shop->user ?? User::query()->find($shop->user_id);
            $score = (int) ($seller?->trust_score ?? 0);
            $threshold = $this->settings->getInt('catalog.quota_trusted_threshold', 80);
            if ($score >= $threshold) {
                $registered = $this->settings->getInt('catalog.quota_trusted_registered', 100);
                $adaptive = true;
            }
        }

        $paidRegistered = 0;
        $paidPublished  = 0;
        $extensions = SellerQuotaExtension::query()
            ->where('shop_id', $shop->id)
            ->get()
            ->filter(fn (SellerQuotaExtension $ext) => $ext->isEffective());

        foreach ($extensions as $extension) {
            $paidRegistered += (int) $extension->extra_registered;
            $paidPublished  += (int) $extension->extra_published;
        }

        $registered += $paidRegistered;
        $published  += $paidPublished;

        $absolute = $this->settings->getInt('catalog.quota_absolute_max', 200);
        $registered = min($registered, $absolute);

        return [
            'registered'      => $registered,
            'published'       => $published,
            'hidden'          => $hidden,
            'paid_registered' => $paidRegistered,
            'adaptive'        => $adaptive,
        ];
    }
}

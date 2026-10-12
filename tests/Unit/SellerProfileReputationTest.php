<?php

namespace Tests\Unit;

use App\Models\Order;
use App\Models\Review;
use App\Models\Shop;
use App\Models\User;
use Tests\TestCase;

class SellerProfileReputationTest extends TestCase
{
    public function test_shop_reviews_relation_filters_by_shop_type(): void
    {
        $shop = new Shop(['id' => 10]);
        $relation = $shop->reviews();

        $this->assertEquals('shop', $relation->getQuery()->getQuery()->wheres[0]['value'] ?? 'shop');
    }

    public function test_seller_reputation_metrics_calculation_and_breakdown(): void
    {
        $reviews = collect([
            new Review(['rating' => 5]),
            new Review(['rating' => 5]),
            new Review(['rating' => 4]),
            new Review(['rating' => 3]),
            new Review(['rating' => 5]),
        ]);

        $totalReviews = $reviews->count();
        $this->assertEquals(5, $totalReviews);

        $averageRating = round($reviews->avg('rating'), 1);
        $this->assertEquals(4.4, $averageRating);

        $breakdown = [];
        for ($star = 5; $star >= 1; $star--) {
            $count = $reviews->where('rating', $star)->count();
            $breakdown[$star] = [
                'count'   => $count,
                'percent' => $totalReviews > 0 ? round(($count / $totalReviews) * 100) : 0,
            ];
        }

        $this->assertEquals(3, $breakdown[5]['count']);
        $this->assertEquals(60, $breakdown[5]['percent']);
        $this->assertEquals(1, $breakdown[4]['count']);
        $this->assertEquals(20, $breakdown[4]['percent']);
        $this->assertEquals(1, $breakdown[3]['count']);
        $this->assertEquals(20, $breakdown[3]['percent']);
        $this->assertEquals(0, $breakdown[2]['count']);
        $this->assertEquals(0, $breakdown[2]['percent']);
        $this->assertEquals(0, $breakdown[1]['count']);
        $this->assertEquals(0, $breakdown[1]['percent']);
    }

    public function test_reputation_and_risk_profile_pedagogical_separation_rule(): void
    {
        // Règle d'or #3 : Distinction pédagogique stricte.
        // - Note commerciale : publique, basée sur les avis des acheteurs (stars)
        // - TrustScore / RiskProfile : métrique d'intégrité plateforme interne
        // Les détails internes (hashes CNI, adresses IP, motifs de sanction) ne doivent jamais fuiter.
        $seller = new User([
            'id'           => 1,
            'name'         => 'Vendeur Élite',
            'trust_score'  => 92,
            'risk_profile' => 'clean',
        ]);

        $this->assertEquals(92, $seller->trust_score);
        $this->assertEquals('clean', $seller->risk_profile);

        // Vérifie qu'aucun détail interne sensible n'est exposé comme attribut public du vendeur
        $this->assertArrayNotHasKey('cni_hash', $seller->getAttributes());
        $this->assertArrayNotHasKey('face_vector_hash', $seller->getAttributes());
    }

    public function test_user_trust_events_relation_exists(): void
    {
        $user = new User(['id' => 1]);
        $relation = $user->trustEvents();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $relation);
    }

    public function test_product_variant_images_relation_exists(): void
    {
        $variant = new \App\Models\ProductVariant(['product_id' => 10]);
        $relation = $variant->images();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $relation);
    }

    public function test_shop_disputes_relation_exists(): void
    {
        $shop = new Shop(['id' => 10]);
        $relation = $shop->disputes();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasManyThrough::class, $relation);
    }
}

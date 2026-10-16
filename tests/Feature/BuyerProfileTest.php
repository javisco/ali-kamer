<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Review;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

/**
 * Profil acheteur — couverture des 12 exigences du cahier des charges.
 *
 * NB : la suite utilise une transaction par test (rollback) car l'environnement
 * ne dispose pas de base de test dédiée. Aucune donnée n'est conservée.
 */
class BuyerProfileTest extends TestCase
{
    use DatabaseTransactions;

    // ── Fabriques minimales (colonnes NOT NULL respectées) ──────────────

    private function makeUser(string $role, array $attributes = []): User
    {
        $user = new User();
        $user->forceFill(array_merge([
            'name'              => ucfirst($role) . ' Test',
            'email'             => $role . uniqid() . '@example.com',
            'phone'             => '6' . str_pad((string) random_int(0, 99999999), 8, '0', STR_PAD_LEFT),
            'phone_momo'        => null,
            'password'          => 'password',
            'role'              => $role,
            'status'            => 'active',
            'trust_score'       => 100,
            'risk_profile'      => 'clean',
            'email_verified_at' => now(),
        ], $attributes))->save();

        return $user;
    }

    private function makeBuyer(array $attributes = []): User
    {
        return $this->makeUser('buyer', $attributes);
    }

    private function makeShop(User $seller): Shop
    {
        return Shop::create([
            'user_id'     => $seller->id,
            'name'        => 'Boutique Test',
            'slug'        => 'boutique-test-' . uniqid(),
            'city'        => 'Douala',
            'phone'       => '+237600000000',
            'status'      => 'active',
            'verified_at' => now(),
        ]);
    }

    private function makeOrder(User $buyer, Shop $shop, string $status, int $amount = 10000): Order
    {
        return Order::create([
            'reference'            => 'ALK-TEST-' . uniqid(),
            'buyer_id'             => $buyer->id,
            'shop_id'              => $shop->id,
            'status'               => $status,
            'subtotal'             => $amount,
            'shipping_fee'         => 0,
            'protection_fee'       => 0,
            'gateway_fee'          => 0,
            'total_amount'         => $amount,
            'platform_commission'  => 0,
            'agency_commission'    => 0,
            'gateway_payout_fee'   => 0,
            'net_amount'           => $amount,
            'financial_snapshot'   => ['test' => true],
        ]);
    }

    // ────────────────────────────────────────────────────────────────────
    // TEST 1 — Un acheteur connecté peut consulter son profil.
    // ────────────────────────────────────────────────────────────────────
    public function test_1_buyer_can_view_own_profile(): void
    {
        $buyer = $this->makeBuyer(['name' => 'Awa Ngono']);

        $response = $this->actingAs($buyer)->get(route('buyer.profile'));

        $response->assertOk();
        $response->assertSee('Awa Ngono');
        $this->assertSame($buyer->id, $response->viewData('user')->id);
    }

    // ────────────────────────────────────────────────────────────────────
    // TEST 2 — Un acheteur ne peut pas consulter les informations privées
    //          d'un autre acheteur.
    // ────────────────────────────────────────────────────────────────────
    public function test_2_buyer_cannot_view_another_buyer_private_info(): void
    {
        $buyer = $this->makeBuyer();
        $other = $this->makeBuyer(['email' => 'victime' . uniqid() . '@example.com']);

        $response = $this->actingAs($buyer)->get(route('buyer.profile'));

        $response->assertOk();
        // La route ne prend AUCUN paramètre → pas d'IDOR possible.
        $this->assertEmpty(Route::getRoutes()->getByName('buyer.profile')->parameterNames());
        // Les données privées de l'autre acheteur ne fuitent pas.
        $response->assertDontSee($other->email);
        $response->assertDontSee((string) $other->phone);
    }

    // ────────────────────────────────────────────────────────────────────
    // TEST 3 — Un acheteur peut modifier les informations autorisées.
    // ────────────────────────────────────────────────────────────────────
    public function test_3_buyer_can_update_allowed_information(): void
    {
        $buyer = $this->makeBuyer(['name' => 'Ancien Nom']);

        $response = $this->actingAs($buyer)->put(route('buyer.profile.update'), [
            'name'  => 'Nouveau Nom',
            'email' => $buyer->email,
            'phone' => '699112233',
        ]);

        $response->assertRedirect(route('buyer.profile'));

        $buyer->refresh();
        $this->assertSame('Nouveau Nom', $buyer->name);
        $this->assertSame('699112233', $buyer->phone);
    }

    // ────────────────────────────────────────────────────────────────────
    // TEST 4 — Un acheteur ne peut pas modifier son TrustScore.
    // ────────────────────────────────────────────────────────────────────
    public function test_4_buyer_cannot_modify_trust_score(): void
    {
        $buyer = $this->makeBuyer(['trust_score' => 88]);

        $this->actingAs($buyer)->put(route('buyer.profile.update'), [
            'name'        => $buyer->name,
            'email'       => $buyer->email,
            'phone'       => $buyer->phone,
            'trust_score' => 0,
        ]);

        $this->assertSame(88, $buyer->fresh()->trust_score);
    }

    // ────────────────────────────────────────────────────────────────────
    // TEST 5 — Un acheteur ne peut pas modifier son RiskProfile ni son
    //          statut de sanction.
    // ────────────────────────────────────────────────────────────────────
    public function test_5_buyer_cannot_modify_risk_profile_or_sanction_status(): void
    {
        $buyer = $this->makeBuyer(['risk_profile' => 'clean', 'status' => 'active']);

        $this->actingAs($buyer)->put(route('buyer.profile.update'), [
            'name'         => $buyer->name,
            'email'        => $buyer->email,
            'phone'        => $buyer->phone,
            'risk_profile' => 'blocked',
            'status'       => 'banned',
            'purchase_restricted' => true,
        ]);

        $buyer->refresh();
        $this->assertSame('clean', $buyer->risk_profile);
        $this->assertSame('active', $buyer->status);
        $this->assertFalse((bool) $buyer->purchase_restricted);
    }

    // ────────────────────────────────────────────────────────────────────
    // TEST 6 — Les statistiques de commandes correspondent aux données réelles.
    // ────────────────────────────────────────────────────────────────────
    public function test_6_order_statistics_match_real_data(): void
    {
        $buyer  = $this->makeBuyer();
        $seller = $this->makeUser('seller');
        $shop   = $this->makeShop($seller);

        $this->makeOrder($buyer, $shop, Order::STATUS_COMPLETED);
        $this->makeOrder($buyer, $shop, Order::STATUS_COMPLETED);
        $this->makeOrder($buyer, $shop, Order::STATUS_AUTO_COMPLETED);
        $this->makeOrder($buyer, $shop, Order::STATUS_PENDING);
        $this->makeOrder($buyer, $shop, Order::STATUS_CANCELLED);
        $this->makeOrder($buyer, $shop, Order::STATUS_DISPUTED);

        // Une commande d'un autre acheteur ne doit PAS être comptée.
        $otherBuyer = $this->makeBuyer();
        $this->makeOrder($otherBuyer, $shop, Order::STATUS_COMPLETED);

        $response = $this->actingAs($buyer)->get(route('buyer.profile'));
        $stats = $response->viewData('stats');

        $this->assertSame(6, $stats['orders_total']);
        $this->assertSame(3, $stats['orders_completed']);
        $this->assertSame(1, $stats['orders_in_progress']);
        $this->assertSame(1, $stats['orders_cancelled']);
        $this->assertSame(1, $stats['orders_disputed']);
    }

    // ────────────────────────────────────────────────────────────────────
    // TEST 7 — Les avis affichés appartiennent réellement à l'acheteur
    //          (ou le concernent, selon le type d'évaluation).
    // ────────────────────────────────────────────────────────────────────
    public function test_7_displayed_reviews_belong_to_buyer(): void
    {
        $buyer  = $this->makeBuyer();
        $seller = $this->makeUser('seller');
        $shop   = $this->makeShop($seller);

        $orderA = $this->makeOrder($buyer, $shop, Order::STATUS_COMPLETED);
        $reviewForA = Review::create([
            'order_id'      => $orderA->id,
            'reviewer_id'   => $seller->id,
            'reviewee_type' => 'buyer',
            'reviewee_id'   => $buyer->id,
            'rating'        => 5,
            'body'          => 'Excellent acheteur',
        ]);

        $otherBuyer = $this->makeBuyer();
        $orderB = $this->makeOrder($otherBuyer, $shop, Order::STATUS_COMPLETED);
        $reviewForB = Review::create([
            'order_id'      => $orderB->id,
            'reviewer_id'   => $seller->id,
            'reviewee_type' => 'buyer',
            'reviewee_id'   => $otherBuyer->id,
            'rating'        => 1,
            'body'          => 'Mauvais acheteur',
        ]);

        $response = $this->actingAs($buyer)->get(route('buyer.profile'));
        $received = $response->viewData('reviewsReceived');
        $ids = collect($received->items())->pluck('id');

        $this->assertTrue($ids->contains($reviewForA->id));
        $this->assertFalse($ids->contains($reviewForB->id));
        $this->assertSame(1, $response->viewData('stats')['reviews_received']);
    }

    // ────────────────────────────────────────────────────────────────────
    // TEST 8 — Les avis d'un autre acheteur ne peuvent pas être modifiés
    //          par l'utilisateur connecté.
    // ────────────────────────────────────────────────────────────────────
    public function test_8_buyer_cannot_modify_another_buyers_review(): void
    {
        $buyer      = $this->makeBuyer();
        $otherBuyer = $this->makeBuyer();
        $seller     = $this->makeUser('seller');
        $shop       = $this->makeShop($seller);

        $otherOrder = $this->makeOrder($otherBuyer, $shop, Order::STATUS_COMPLETED);

        // Aucune route de modification d'avis n'est exposée à l'acheteur.
        $this->assertNull(Route::getRoutes()->getByName('buyer.reviews.update'));
        $this->assertNull(Route::getRoutes()->getByName('buyer.reviews.destroy'));

        // Noter la commande d'un autre acheteur est interdit (403).
        $this->actingAs($buyer)
            ->post(route('buyer.reviews.store', $otherOrder), [
                'product_rating' => 1,
                'shop_rating'    => 1,
                'body'           => 'tentative',
            ])
            ->assertForbidden();
    }

    // ────────────────────────────────────────────────────────────────────
    // TEST 9 — Les ressources d'un autre acheteur ne peuvent pas être
    //          consultées ni modifiées (isolation / anti-IDOR).
    //
    // NB : le projet ne dispose d'AUCUN module d'adresses (les infos de
    // livraison sont portées par la commande). Le test porte donc sur la
    // ressource « commande », qui est le support réel des données de
    // livraison, et vérifie la même garantie de propriété.
    // ────────────────────────────────────────────────────────────────────
    public function test_9_buyer_cannot_access_another_buyers_resources(): void
    {
        $buyer      = $this->makeBuyer();
        $otherBuyer = $this->makeBuyer();
        $seller     = $this->makeUser('seller');
        $shop       = $this->makeShop($seller);

        $otherOrder = $this->makeOrder($otherBuyer, $shop, Order::STATUS_COMPLETED);

        $this->actingAs($buyer)
            ->get(route('buyer.orders.show', $otherOrder))
            ->assertForbidden();

        // Le propriétaire légitime y accède normalement.
        $this->actingAs($otherBuyer)
            ->get(route('buyer.orders.show', $otherOrder))
            ->assertOk();
    }

    // ────────────────────────────────────────────────────────────────────
    // TEST 10 — Un utilisateur non connecté ne peut pas accéder au profil privé.
    // ────────────────────────────────────────────────────────────────────
    public function test_10_guest_cannot_access_private_profile(): void
    {
        $this->get(route('buyer.profile'))->assertRedirect(route('login'));
    }

    // ────────────────────────────────────────────────────────────────────
    // TEST 11 — Un utilisateur d'un autre rôle ne peut pas accéder aux routes
    //           réservées à l'acheteur.
    // ────────────────────────────────────────────────────────────────────
    public function test_11_non_buyer_cannot_access_buyer_routes(): void
    {
        $seller = $this->makeUser('seller');

        $this->actingAs($seller)
            ->get(route('buyer.profile'))
            ->assertForbidden();

        $this->actingAs($seller)
            ->get(route('buyer.profile.edit'))
            ->assertForbidden();
    }

    // ────────────────────────────────────────────────────────────────────
    // TEST 12 — Les commandes historiques conservent leurs données même si
    //           l'acheteur modifie son profil.
    // ────────────────────────────────────────────────────────────────────
    public function test_12_historical_orders_preserve_data_after_profile_update(): void
    {
        $buyer  = $this->makeBuyer(['name' => 'Nom Initial']);
        $seller = $this->makeUser('seller');
        $shop   = $this->makeShop($seller);

        $order = $this->makeOrder($buyer, $shop, Order::STATUS_COMPLETED, 25000);
        $originalReference = $order->reference;
        $originalSnapshot  = $order->financial_snapshot;

        $this->actingAs($buyer)->put(route('buyer.profile.update'), [
            'name'  => 'Nom Modifié',
            'email' => $buyer->email,
            'phone' => '677889900',
        ])->assertRedirect(route('buyer.profile'));

        $order->refresh();

        $this->assertSame(25000, $order->total_amount);
        $this->assertSame($originalReference, $order->reference);
        $this->assertSame($originalSnapshot, $order->financial_snapshot);
        $this->assertSame($buyer->id, $order->buyer_id);

        // Bonus : changement de mot de passe correctement pris en compte.
        $this->actingAs($buyer)->put(route('buyer.profile.password'), [
            'current_password'      => 'password',
            'password'              => 'nouveau-mdp-123',
            'password_confirmation' => 'nouveau-mdp-123',
        ])->assertRedirect(route('buyer.profile'));

        $this->assertTrue(Hash::check('nouveau-mdp-123', $buyer->fresh()->password));
    }
}

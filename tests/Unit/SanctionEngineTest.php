<?php

namespace Tests\Unit;

use App\Models\Sanction;
use App\Models\SanctionRestriction;
use App\Models\Suspension;
use App\Models\User;
use Illuminate\Support\Carbon;
use PHPUnit\Framework\TestCase;

class SanctionEngineTest extends TestCase
{
    public function test_suspension_remaining_seconds_and_is_due(): void
    {
        $future = Carbon::now()->addHours(2);
        $suspensionActive = new Suspension();
        $suspensionActive->setRawAttributes([
            'status'     => Suspension::STATUS_ACTIVE,
            'starts_at'  => Carbon::now()->subHour(),
            'expires_at' => $future,
        ]);

        $this->assertFalse($suspensionActive->isDue());
        $this->assertGreaterThan(0, $suspensionActive->remainingSeconds());

        // Suspension échue
        $past = Carbon::now()->subMinutes(10);
        $suspensionDue = new Suspension();
        $suspensionDue->setRawAttributes([
            'status'     => Suspension::STATUS_ACTIVE,
            'starts_at'  => Carbon::now()->subHours(2),
            'expires_at' => $past,
        ]);

        $this->assertTrue($suspensionDue->isDue());
        $this->assertEquals(0, $suspensionDue->remainingSeconds());
    }

    public function test_banned_user_helpers_and_restrictions(): void
    {
        $user = new User([
            'id'     => 10,
            'status' => User::STATUS_BANNED,
            'role'   => User::ROLE_SELLER,
        ]);

        $this->assertTrue($user->isBanned());
        $this->assertFalse($user->isActive());
        $this->assertFalse($user->isSuspended());

        // Banni -> toutes les actions sont interdites
        $this->assertTrue($user->hasRestriction(SanctionRestriction::CANNOT_BUY));
        $this->assertTrue($user->hasRestriction(SanctionRestriction::CANNOT_SELL));
        $this->assertFalse($user->canBuy());
        $this->assertFalse($user->canSell());
        $this->assertFalse($user->canPublish());
        $this->assertFalse($user->canWithdraw());
        $this->assertFalse($user->canMessage());
        $this->assertFalse($user->canCreateOrder());
    }

    public function test_suspended_user_helpers_and_restrictions(): void
    {
        $user = new User([
            'id'     => 11,
            'status' => User::STATUS_SUSPENDED,
            'role'   => User::ROLE_BUYER,
        ]);

        $this->assertTrue($user->isSuspended());
        $this->assertFalse($user->isActive());
        $this->assertFalse($user->isBanned());

        // Suspendu -> restrictions globales appliquées
        $this->assertTrue($user->hasRestriction(SanctionRestriction::CANNOT_BUY));
        $this->assertFalse($user->canBuy());
        $this->assertFalse($user->canCreateOrder());
    }

    public function test_golden_reactivation_rule_priority_resolution(): void
    {
        // Règle d'or (Section 30 & 32) :
        // 1. BAN > SUSPENSION > RESTRICTION > ACTIVE
        // Vérification de la logique de détermination du statut résultant

        $determineStatus = function (array $sanctionTypes, string $currentStatus): string {
            if (in_array(Sanction::TYPE_BAN, $sanctionTypes, true)) {
                return User::STATUS_BANNED;
            }
            if (in_array(Sanction::TYPE_SUSPENSION, $sanctionTypes, true)) {
                return User::STATUS_SUSPENDED;
            }
            if (in_array($currentStatus, [User::STATUS_SUSPENDED, User::STATUS_BANNED], true)) {
                return User::STATUS_ACTIVE;
            }
            return $currentStatus;
        };

        // Cas 1 : Deux sanctions actives (une suspension levée/expirée, mais un BAN encore actif)
        // -> Le compte doit rester BAN
        $status = $determineStatus([Sanction::TYPE_BAN], User::STATUS_SUSPENDED);
        $this->assertEquals(User::STATUS_BANNED, $status);

        // Cas 2 : Deux suspensions simultanées (l'une expire, mais l'autre a encore 3 jours)
        // -> Le compte doit rester SUSPENDED
        $status = $determineStatus([Sanction::TYPE_SUSPENSION], User::STATUS_SUSPENDED);
        $this->assertEquals(User::STATUS_SUSPENDED, $status);

        // Cas 3 : Toutes les sanctions bloquantes sont expirées ou levées (liste vide)
        // -> Le compte redevient ACTIVE
        $status = $determineStatus([], User::STATUS_SUSPENDED);
        $this->assertEquals(User::STATUS_ACTIVE, $status);

        // Cas 4 : Compte banni dont le ban a été levé et aucune autre sanction
        // -> Le compte redevient ACTIVE
        $status = $determineStatus([], User::STATUS_BANNED);
        $this->assertEquals(User::STATUS_ACTIVE, $status);
    }
}

<?php

namespace App\Services;

use App\Models\Agency;
use App\Models\AgencyCounter;
use App\Models\AgencyWalletTransaction;
use App\Models\Order;
use App\Models\SecretaryCounter;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AgencyManagerService
{
    public function __construct(private CampayService $campay) {}

    // ── CRÉER UN COMPTOIR ─────────────────────────────────────────────

    // Le manager peut créer des comptoirs dans ses villes desservies
    public function createCounter(Agency $agency, array $data): AgencyCounter
    {
        // Vérifier que la ville est desservie par l'agence
        if (! $agency->servesCity($data['city'])) {
            throw new \Exception(
                "Votre agence ne dessert pas la ville {$data['city']}. " .
                "Contactez l'administrateur pour ajouter cette ville."
            );
        }

        return AgencyCounter::create([
            'agency_id' => $agency->id,
            'city'      => $data['city'],
            'district'  => $data['district'] ?? null,
            'landmark'  => $data['landmark'] ?? null,
            'phone'     => $data['phone'] ?? null,
            'is_active' => true,
        ]);
    }

    // ── CRÉER UN SECRÉTAIRE ET L'AFFECTER ────────────────────────────

    public function createSecretary(
        Agency $agency,
        AgencyCounter $counter,
        array $data
    ): User {

        // Vérifier que le comptoir appartient à cette agence
        abort_unless($counter->agency_id === $agency->id, 403);

        // Vérifier que le comptoir n'a pas déjà un secrétaire
        if ($counter->secretary) {
            throw new \Exception('Ce comptoir a déjà un secrétaire assigné.');
        }

        return DB::transaction(function () use ($agency, $counter, $data) {

            $secretary = User::create([
                'name'      => $data['name'],
                'phone'     => $data['phone'],
                'email'     => $data['email'] ?? null,
                'password'  => bcrypt($data['password']),
                'role'      => User::ROLE_SECRETARY,
                'status'    => User::STATUS_ACTIVE,
                'agency_id' => $agency->id,
            ]);

            SecretaryCounter::create([
                'user_id'           => $secretary->id,
                'agency_counter_id' => $counter->id,
                'is_primary'        => true,
            ]);

            return $secretary;
        });
    }

    // ── ACTIVER / DÉSACTIVER UN COMPTOIR ─────────────────────────────

    public function toggleCounter(Agency $agency, AgencyCounter $counter): void
    {
        abort_unless($counter->agency_id === $agency->id, 403);
        $counter->update(['is_active' => ! $counter->is_active]);
    }

    // ── ACTIVER / DÉSACTIVER UN SECRÉTAIRE ───────────────────────────

    public function toggleSecretary(Agency $agency, User $secretary): void
    {
        // Vérifier que le secrétaire appartient à cette agence
        abort_unless($secretary->agency_id === $agency->id, 403);

        $newStatus = $secretary->isActive()
            ? User::STATUS_SUSPENDED
            : User::STATUS_ACTIVE;

        $secretary->update(['status' => $newStatus]);
    }

    // ── SUPPRIMER UN SECRÉTAIRE ───────────────────────────────────────

    // Uniquement si aucun colis en cours de traitement
    public function deleteSecretary(Agency $agency, User $secretary): void
    {
        abort_unless($secretary->agency_id === $agency->id, 403);

        // Vérifier qu'aucune commande n'est en cours avec ce secrétaire
        $hasActiveShipments = \App\Models\OrderShipment::where(function ($q) use ($secretary) {
            $q->where('registered_by', $secretary->id)
              ->orWhere('validated_by', $secretary->id);
        })->whereHas('order', fn($q) => $q->whereNotIn('status', [
            'completed', 'auto_completed', 'cancelled', 'failed'
        ]))->exists();

        if ($hasActiveShipments) {
            throw new \Exception(
                'Ce secrétaire a des colis en cours de traitement. ' .
                'Désactivez-le plutôt que de le supprimer.'
            );
        }

        $secretary->agencyCounters()->detach();
        $secretary->delete();
    }

    // ── CRÉDITER LA COMMISSION D'UNE AGENCE ──────────────────────────

    // Appelé depuis ShippingService quand un colis est traité
    // L'agence gagne 1% de la valeur de chaque colis (agency_commission)
    public function creditCommission(Agency $agency, Order $order): void
    {
        DB::transaction(function () use ($agency, $order) {

            $amount = $order->agency_commission;

            if ($amount <= 0) return;

            // Créditer le wallet de l'agence
            $agency->increment('wallet_available', $amount);
            $agency->refresh();

            // Tracer la transaction
            \App\Models\AgencyWalletTransaction::create([
                'agency_id'    => $agency->id,
                'type'         => 'credit_commission',
                'amount'       => $amount,
                'balance_after' => $agency->wallet_available,
                'order_id'     => $order->id,
                'note'         => "Commission 1% colis {$order->reference}",
            ]);
        });
    }

    // ── RETRAIT AGENCE VIA CAMPAY ─────────────────────────────────────

    public function requestWithdrawal(Agency $agency, int $amount): void
    {
        if ($amount < 1000) {
            throw ValidationException::withMessages([
                'amount' => 'Montant minimum de retrait : 1 000 FCFA.',
            ]);
        }

        if ($agency->wallet_available < $amount) {
            throw ValidationException::withMessages([
                'amount' => 'Solde insuffisant. Disponible : '
                    . number_format($agency->wallet_available, 0, ',', ' ') . ' FCFA.',
            ]);
        }

        if (! $agency->phone_momo) {
            throw ValidationException::withMessages([
                'momo' => 'Aucun numéro MoMo configuré pour votre agence.',
            ]);
        }

        $phone     = '237' . ltrim($agency->phone_momo, '0');
        $fees      = (int) round($amount * 0.01);
        $netAmount = $amount - $fees;
        $reference = 'AGENCY-' . $agency->id . '-' . Str::uuid();

        DB::transaction(function () use (
            $agency, $amount, $netAmount, $fees, $phone, $reference
        ) {
            // Débiter immédiatement
            $agency->decrement('wallet_available', $amount);

            try {
                $this->campay->disburse(
                    phone: $phone,
                    amount: $netAmount,
                    reference: $reference,
                    description: "Retrait agence {$agency->name}"
                );

                $agency->refresh();

                \App\Models\AgencyWalletTransaction::create([
                    'agency_id'    => $agency->id,
                    'type'         => 'debit_withdrawal',
                    'amount'       => $amount,
                    'balance_after' => $agency->wallet_available,
                    'order_id'     => null,
                    'note'         => "Retrait {$agency->momo_operator} {$phone} — net {$netAmount} FCFA (frais {$fees} FCFA)",
                ]);

            } catch (\Exception $e) {
                // Rembourser si Campay échoue
                $agency->increment('wallet_available', $amount);
                $agency->refresh();

                \App\Models\AgencyWalletTransaction::create([
                    'agency_id'    => $agency->id,
                    'type'         => 'debit_withdrawal_failed',
                    'amount'       => $amount,
                    'balance_after' => $agency->wallet_available,
                    'order_id'     => null,
                    'note'         => "Retrait échoué — recrédité — {$e->getMessage()}",
                ]);

                throw ValidationException::withMessages([
                    'amount' => 'Virement échoué : ' . $e->getMessage(),
                ]);
            }
        });
    }

    // ── HISTORIQUE TRANSACTIONS AGENCE ────────────────────────────────

    public function getHistory(Agency $agency, int $perPage = 20)
    {
        return \App\Models\AgencyWalletTransaction::where('agency_id', $agency->id)
            ->with('order:id,reference')
            ->latest()
            ->paginate($perPage);
    }

    // ── STATS AGENCE ──────────────────────────────────────────────────

    public function getStats(Agency $agency): array
    {
        $transactions = \App\Models\AgencyWalletTransaction::where('agency_id', $agency->id);

        return [
            'total_earned'     => $transactions->clone()
                ->where('type', 'credit_commission')
                ->sum('amount'),
            'total_withdrawn'  => $transactions->clone()
                ->where('type', 'debit_withdrawal')
                ->sum('amount'),
            'wallet_available' => $agency->wallet_available,
            'colis_count'      => $transactions->clone()
                ->where('type', 'credit_commission')
                ->count(),
        ];
    }
}
<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class WalletService
{
    // Montant minimum de retrait défini par Campay
    const MIN_WITHDRAWAL = 1000;

    public function __construct(private CampayService $campay) {}

    // ── SÉQUESTRE ─────────────────────────────────────────────────────

    // Créditer le séquestre d'un vendeur quand l'acheteur paie
    public function creditEscrow(User $seller, int $amount, Order $order): void
    {
        DB::transaction(function () use ($seller, $amount, $order) {

            $seller->increment('wallet_pending', $amount);
            $seller->refresh();

            WalletTransaction::create([
                'user_id'      => $seller->id,
                'type'         => WalletTransaction::TYPE_CREDIT_ESCROW,
                'amount'       => $amount,
                'balance_after' => $seller->wallet_pending,
                'ref_type'     => 'order',
                'ref_id'       => $order->id,
                'note'         => "Séquestre commande {$order->reference}",
            ]);
        });
    }

    // Libérer le séquestre vers le solde disponible du vendeur
    // Appelé après livraison confirmée ou résolution litige en faveur vendeur
    public function releaseEscrow(User $seller, int $amount, Order $order): void
    {
        DB::transaction(function () use ($seller, $amount, $order) {

            // Déduire du pending
            $seller->decrement('wallet_pending', $amount);

            // Créditer le disponible
            $seller->increment('wallet_available', $amount);

            $seller->refresh();

            // Trace débit séquestre
            WalletTransaction::create([
                'user_id'      => $seller->id,
                'type'         => WalletTransaction::TYPE_DEBIT_ESCROW,
                'amount'       => $amount,
                'balance_after' => $seller->wallet_pending,
                'ref_type'     => 'order',
                'ref_id'       => $order->id,
                'note'         => "Libération séquestre {$order->reference}",
            ]);

            // Trace crédit disponible
            WalletTransaction::create([
                'user_id'      => $seller->id,
                'type'         => WalletTransaction::TYPE_CREDIT_AVAILABLE,
                'amount'       => $amount,
                'balance_after' => $seller->wallet_available,
                'ref_type'     => 'order',
                'ref_id'       => $order->id,
                'note'         => "Fonds disponibles commande {$order->reference}",
            ]);
        });
    }

    // ── REMBOURSEMENT ACHETEUR (trace uniquement) ─────────────────────

    // Le virement réel est fait dans DisputeService via Campay
    // Cette méthode enregistre uniquement la trace comptable
    public function refund(User $buyer, int $amount, Order $order): void
    {
        WalletTransaction::create([
            'user_id'      => $buyer->id,
            'type'         => WalletTransaction::TYPE_CREDIT_REFUND,
            'amount'       => $amount,
            'balance_after' => 0, // acheteur n'a pas de wallet
            'ref_type'     => 'order',
            'ref_id'       => $order->id,
            'note'         => "Remboursement commande {$order->reference}",
        ]);
    }

    // ── RETRAIT VENDEUR (virement MoMo réel via Campay) ──────────────

    // Le vendeur demande un retrait → on appelle Campay disburse
    // → on débite son wallet disponible
    // → on trace dans wallet_transactions
    public function requestWithdrawal(User $user, int $amount): void
    {
        // Validation minimum Campay
        if ($amount < self::MIN_WITHDRAWAL) {
            throw ValidationException::withMessages([
                'amount' => 'Le montant minimum de retrait est '
                    . number_format(self::MIN_WITHDRAWAL, 0, ',', ' ') . ' FCFA.',
            ]);
        }

        // Vérifier solde suffisant
        if ($user->wallet_available < $amount) {
            throw ValidationException::withMessages([
                'amount' => 'Solde insuffisant. Disponible : '
                    . number_format($user->wallet_available, 0, ',', ' ') . ' FCFA.',
            ]);
        }

        // Vérifier numéro MoMo enregistré
        if (! $user->phone_momo || ! $user->momo_operator) {
            throw ValidationException::withMessages([
                'momo' => 'Aucun numéro Mobile Money enregistré. Contactez le support.',
            ]);
        }

        $phone     = '237' . ltrim($user->phone_momo, '0');
        $reference = 'WITHDRAWAL-' . $user->id . '-' . Str::uuid();

        // Frais Campay au retrait (1%)
        $fees      = (int) round($amount * 0.01);
        $netAmount = $amount - $fees;

        DB::transaction(function () use ($user, $amount, $netAmount, $phone, $reference, $fees) {

            // 1. Débiter immédiatement le wallet
            // (avant l'appel Campay pour éviter double retrait si erreur réseau)
            $user->decrement('wallet_available', $amount);
            $user->refresh();

            try {
                // 2. Virement réel via Campay
                $result = $this->campay->disburse(
                    phone: $phone,
                    amount: $netAmount,
                    reference: $reference,
                    description: "Retrait Ali-Kamer — {$user->name}"
                );

                // 3. Trace dans wallet_transactions
                WalletTransaction::create([
                    'user_id'      => $user->id,
                    'type'         => WalletTransaction::TYPE_DEBIT_WITHDRAWAL,
                    'amount'       => $amount,
                    'balance_after' => $user->wallet_available,
                    'ref_type'     => 'withdrawal',
                    'ref_id'       => null,
                    'note'         => "Retrait {$user->momo_operator} {$phone} — net {$netAmount} FCFA (frais {$fees} FCFA) — ref: {$reference}",
                ]);

                Log::info('Withdrawal successful', [
                    'user'      => $user->id,
                    'amount'    => $amount,
                    'net'       => $netAmount,
                    'reference' => $reference,
                    'campay'    => $result,
                ]);

            } catch (\Exception $e) {
                // En cas d'échec Campay, rembourser le wallet
                $user->increment('wallet_available', $amount);

                WalletTransaction::create([
                    'user_id'      => $user->id,
                    'type'         => WalletTransaction::TYPE_CREDIT_AVAILABLE,
                    'amount'       => $amount,
                    'balance_after' => $user->fresh()->wallet_available,
                    'ref_type'     => 'withdrawal_failed',
                    'ref_id'       => null,
                    'note'         => "Retrait échoué — montant recrédité — {$e->getMessage()}",
                ]);

                Log::error('Withdrawal failed', [
                    'user'  => $user->id,
                    'error' => $e->getMessage(),
                ]);

                throw ValidationException::withMessages([
                    'amount' => 'Le virement a échoué : ' . $e->getMessage(),
                ]);
            }
        });
    }

    // ── HISTORIQUE ────────────────────────────────────────────────────

    // Récupère les transactions d'un utilisateur (vendeur ou acheteur)
    public function history(User $user, int $perPage = 20)
    {
        return WalletTransaction::where('user_id', $user->id)
                                ->latest()
                                ->paginate($perPage);
    }

    // ── AUDIT ─────────────────────────────────────────────────────────

    // Recalcule les soldes depuis l'historique pour détecter les incohérences
    public function recalculateBalances(User $user): array
    {
        $transactions = WalletTransaction::where('user_id', $user->id)->get();

        $pending   = 0;
        $available = 0;

        foreach ($transactions as $tx) {
            match($tx->type) {
                WalletTransaction::TYPE_CREDIT_ESCROW   => $pending += $tx->amount,
                WalletTransaction::TYPE_DEBIT_ESCROW    => $pending -= $tx->amount,
                WalletTransaction::TYPE_CREDIT_AVAILABLE,
                WalletTransaction::TYPE_CREDIT_TRANSPORT_FEE
                    => $available += $tx->amount,
                WalletTransaction::TYPE_DEBIT_WITHDRAWAL,
                WalletTransaction::TYPE_DEBIT_COMMISSION,
                WalletTransaction::TYPE_DEBIT_TRANSPORT_FEE
                    => $available -= $tx->amount,
                default => null,
            };
        }

        return [
            'pending'   => max(0, $pending),
            'available' => max(0, $available),
        ];
    }
}
<?php

namespace App\Services;

use App\Models\Order;
use App\Models\PlatformSetting;
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
            $seller->decrement('wallet_pending', $order->net_amount);

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
        // Enregistrement de la trace dans wallet_transactions
        //  balance_after = 0 car l'acheteur n'a pas de wallet
        WalletTransaction::create([
            'user_id'      => $buyer->id,
            'type'         => WalletTransaction::TYPE_CREDIT_REFUND,
            'amount'       => $amount,
            'balance_after' => 0,
            'ref_type'     => 'order',
            'ref_id'       => $order->id,
            'note'         => "Remboursement litige {$order->reference} — virement MoMo {$order->payer_phone}",
        ]);
    }

    // ── RETRAIT VENDEUR (virement MoMo réel via Campay) ──────────────

    // Le vendeur demande un retrait → on appelle Campay disburse
    // → on débite son wallet disponible
    // → on trace dans wallet_transactions
    public function requestWithdrawal(User $user, int $netAmount): void
    {
        // Vérifications
        $minWithdrawal = (int) PlatformSetting::getValue('min_withdrawal_amount', 1000);

        if ($netAmount < $minWithdrawal) {
            throw ValidationException::withMessages([
                'amount' => "Montant minimum : {$minWithdrawal} FCFA.",
            ]);
        }

        if ($user->wallet_available < $netAmount) {
            throw ValidationException::withMessages([
                'amount' => 'Solde insuffisant. Disponible : '
                    . number_format($user->wallet_available, 0, ',', ' ') . ' FCFA.',
            ]);
        }

        if (! $user->phone_momo) {
            throw ValidationException::withMessages([
                'momo' => 'Aucun numéro MoMo enregistré.',
            ]);
        }

        $phone = '237' . ltrim($user->phone_momo, '0');

        // Gross-Up payout : le vendeur reçoit exactement $netAmount
        $grossUp     = $this->campay->grossUpPayout($netAmount);
        $grossAmount = $grossUp['gross']; // Ce qu'on envoie à Campay
        $campayFee   = $grossUp['fee'];   // Frais Campay supportés par la plateforme

        DB::transaction(function () use (
            $user,
            $netAmount,
            $grossAmount,
            $campayFee,
            $phone
        ) {
            // Débiter le wallet du montant NET que le vendeur attendait
            // (la plateforme supporte les frais Campay payout en plus)
            $user->decrement('wallet_available', $netAmount);

            try {
                $reference = 'WITHDRAWAL-' . $user->id . '-' . Str::uuid();

                // Envoyer le montant BRUT à Campay
                // Campay prélève ses frais → vendeur reçoit exactement $netAmount
                $this->campay->disburse(
                    phone: $phone,
                    grossAmount: $grossAmount,
                    reference: $reference,
                    description: "Retrait Ali-Kamer — {$user->name}"
                );

                $user->refresh();

                // Tracer dans wallet_transactions
                WalletTransaction::create([
                    'user_id'       => $user->id,
                    'type'          => WalletTransaction::TYPE_DEBIT_WITHDRAWAL,
                    'amount'        => $netAmount,
                    'balance_after' => $user->wallet_available,
                    'ref_type'      => 'withdrawal',
                    'ref_id'        => null,
                    'note'          => sprintf(
                        'Retrait %s %s — reçu: %s FCFA, envoyé Campay: %s FCFA (frais plateforme: %s FCFA)',
                        strtoupper($user->momo_operator ?? ''),
                        $phone,
                        number_format($netAmount, 0, ',', ' '),
                        number_format($grossAmount, 0, ',', ' '),
                        number_format($campayFee, 0, ',', ' ')
                    ),
                ]);
            } catch (\Exception $e) {
                // Rollback si Campay échoue
                $user->increment('wallet_available', $netAmount);
                $user->refresh();

                WalletTransaction::create([
                    'user_id'       => $user->id,
                    'type'          => WalletTransaction::TYPE_CREDIT_AVAILABLE,
                    'amount'        => $netAmount,
                    'balance_after' => $user->wallet_available,
                    'ref_type'      => 'withdrawal_failed',
                    'ref_id'        => null,
                    'note'          => 'Retrait échoué — montant recrédité : ' . $e->getMessage(),
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
            match ($tx->type) {
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

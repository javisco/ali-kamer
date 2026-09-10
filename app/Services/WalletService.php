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
use App\Notifications\WithdrawalCompletedNotification;
use App\Notifications\WithdrawalFailedNotification;

class WalletService
{
    // Montant minimum de retrait — vient de platform_settings
    // ('min_withdrawal_amount'), pas d'une contrainte Elgiopay documentée.
    const MIN_WITHDRAWAL = 1000;

    public function __construct(private ElgiopayService $elgiopay) {}

    // ── SÉQUESTRE ─────────────────────────────────────────────────────
    // INCHANGÉ.
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

    // INCHANGÉ.
    public function releaseEscrow(User $seller, int $amount, Order $order): void
    {
        DB::transaction(function () use ($seller, $amount, $order) {

            $pendingToDecrement = min($seller->wallet_pending, $order->net_amount);
            if ($pendingToDecrement > 0) {
                $seller->decrement('wallet_pending', $pendingToDecrement);
            }
            $seller->increment('wallet_available', $amount);
            $seller->refresh();

            WalletTransaction::create([
                'user_id'       => $seller->id,
                'type'          => WalletTransaction::TYPE_DEBIT_ESCROW,
                'amount'        => $amount,
                'balance_after' => $seller->wallet_pending,
                'ref_type'      => 'order',
                'ref_id'        => $order->id,
                'note'          => "Transfert séquestre vers disponible — {$order->reference}",
            ]);

            WalletTransaction::create([
                'user_id'       => $seller->id,
                'type'          => WalletTransaction::TYPE_CREDIT_AVAILABLE,
                'amount'        => $amount,
                'balance_after' => $seller->wallet_available,
                'ref_type'      => 'order',
                'ref_id'        => $order->id,
                'note'          => "Fonds disponibles commande {$order->reference}",
            ]);
        });
    }

    // ── REMBOURSEMENT ACHETEUR (trace uniquement) ─────────────────────
    // INCHANGÉ — le virement réel est fait dans DisputeService.
    public function refund(User $buyer, int $amount, Order $order): void
    {
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

    // ── RETRAIT VENDEUR (virement MoMo réel via Elgiopay) ──────────────
    // Le vendeur demande un retrait → on appelle Elgiopay disburse
    // → on débite son wallet disponible → on trace dans wallet_transactions.
    // Gross-Up INCHANGÉ (gateway_payout_rate lu depuis platform_settings,
    // à 0 tant qu'Elgiopay ne facture rien sur ce trajet).
    public function requestWithdrawal(User $user, int $netAmount): void
    {
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
        $grossUp     = $this->elgiopay->grossUpPayout($netAmount);
        $grossAmount = $grossUp['gross']; // Ce qu'on envoie à Elgiopay
        $gatewayFee  = $grossUp['fee'];   // Frais Elgiopay supportés par la plateforme

        DB::transaction(function () use (
            $user,
            $netAmount,
            $grossAmount,
            $gatewayFee,
            $phone
        ) {
            $user->decrement('wallet_available', $netAmount);

            try {
                $reference = 'WITHDRAWAL-' . $user->id . '-' . Str::uuid();

                // Envoyer le montant BRUT à Elgiopay — avec gateway_payout_rate
                // à 0, gross === net en pratique, mais on garde le mécanisme
                // Gross-Up générique au cas où ça change côté Elgiopay.
                $this->elgiopay->disburse(
                    phone: $phone,
                    grossAmount: $grossAmount,
                    reference: $reference,
                    description: "Retrait Ali-Kamer — {$user->name}",
                    operator: $user->momo_operator ?? 'mtn',
                    recipientName: $user->name
                );

                $user->refresh();

                WalletTransaction::create([
                    'user_id'       => $user->id,
                    'type'          => WalletTransaction::TYPE_DEBIT_WITHDRAWAL,
                    'amount'        => $netAmount,
                    'balance_after' => $user->wallet_available,
                    'ref_type'      => 'withdrawal',
                    'ref_id'        => null,
                    'note'          => sprintf(
                        'Retrait %s %s — reçu: %s FCFA, envoyé Elgiopay: %s FCFA (frais plateforme: %s FCFA)',
                        strtoupper($user->momo_operator ?? ''),
                        $phone,
                        number_format($netAmount, 0, ',', ' '),
                        number_format($grossAmount, 0, ',', ' '),
                        number_format($gatewayFee, 0, ',', ' ')
                    ),
                ]);
                //     $user->notify(new WithdrawalCompletedNotification($netAmount, $phone));
            } catch (\Exception $e) {
                // Rollback si Elgiopay échoue
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
                //      $user->notify(new WithdrawalFailedNotification($netAmount, $e->getMessage()));
                throw ValidationException::withMessages([
                    'amount' => 'Le virement a échoué : ' . $e->getMessage(),
                ]);
            }
        });
    }

    // ── HISTORIQUE ────────────────────────────────────────────────────
    // INCHANGÉ.
    public function history(User $user, int $perPage = 20)
    {
        return WalletTransaction::where('user_id', $user->id)
            ->latest()
            ->paginate($perPage);
    }

    // ── AUDIT ─────────────────────────────────────────────────────────
    // INCHANGÉ.
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
                WalletTransaction::TYPE_DEBIT_TRANSPORT_FEE,
                WalletTransaction::TYPE_DEBIT_REFUND
                => $available -= $tx->amount,
                default => null,
            };
        }

        return [
            'pending'   => max(0, $pending),
            'available' => max(0, $available),
        ];
    }

    /**
     * Synchronise et garantit la cohérence des soldes du vendeur
     * (séquestre et disponible) entre transactions et statut réel des commandes.
     */
    public function syncSellerBalances(User $seller): void
    {
        $balances = $this->recalculateBalances($seller);

        // Si le vendeur a une boutique, recalculer le montant réel sous séquestre
        $shop = $seller->relationLoaded('shop') ? $seller->shop : $seller->shop()->first();
        if ($shop) {
            $pendingOrdersSum = Order::where('shop_id', $shop->id)
                ->whereIn('status', [
                    Order::STATUS_PAID,
                    Order::STATUS_PREPARING,
                    Order::STATUS_REGISTERED_ORIGIN,
                    Order::STATUS_IN_TRANSIT,
                    Order::STATUS_ARRIVED_DESTINATION,
                    Order::STATUS_AWAITING_BUYER_CONFIRMATION,
                    Order::STATUS_DISPUTED,
                ])
                ->sum('net_amount');

            // Le séquestre correspond au montant net des commandes en cours non finalisées
            if ($pendingOrdersSum > 0 || $balances['pending'] > 0) {
                $balances['pending'] = max(0, (int) $pendingOrdersSum);
            }
        }

        $seller->update([
            'wallet_pending'   => max(0, $balances['pending']),
            'wallet_available' => max(0, $balances['available']),
        ]);
    }
}

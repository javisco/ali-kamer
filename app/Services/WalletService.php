<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class WalletService
{
    // Montant minimum pour effectuer un retrait (en FCFA)
    const MIN_WITHDRAWAL = 1000;

    // Commission secrétaire par action (dépôt ou réception colis)
    // const SECRETARY_COMMISSION = 150;

    // ── SÉQUESTRE ────────────────────────────────────────────────────

    // Créditer le portefeuille "en attente" du vendeur
    // Appelé quand l'acheteur paie — l'argent est séquestré
    public function creditEscrow(User $seller, int $amount, Order $order): void
    {
        DB::transaction(function () use ($seller, $amount, $order) {

            // Incrémenter le solde en attente
            $seller->increment('wallet_pending', $amount);

            // Recharger pour avoir le solde à jour
            $seller->refresh();

            // Enregistrer la transaction pour audit complet
            WalletTransaction::create([
                'user_id'      => $seller->id,
                'type'         => WalletTransaction::TYPE_CREDIT_ESCROW,
                'amount'       => $amount,

                // balance_after = solde APRÈS l'opération
                // Permet de reconstruire l'historique sans recalculer
                'balance_after' => $seller->wallet_pending,

                'ref_type' => 'order',
                'ref_id'   => $order->id,
                'note'     => "Séquestre commande {$order->reference}",
            ]);
        });
    }

    // Libérer le séquestre et créditer le solde disponible
    // Appelé quand la livraison est confirmée (OTP validé ou timer 72h)
    public function releaseEscrow(User $seller, int $amount, Order $order): void
    {
        DB::transaction(function () use ($seller, $amount, $order) {

            // Déduire du séquestre
            $seller->decrement('wallet_pending', $amount);

            // Créditer le disponible
            $seller->increment('wallet_available', $amount);

            $seller->refresh();

            // Enregistrer le débit du séquestre
            WalletTransaction::create([
                'user_id'      => $seller->id,
                'type'         => WalletTransaction::TYPE_DEBIT_ESCROW,
                'amount'       => $amount,
                'balance_after' => $seller->wallet_pending,
                'ref_type'     => 'order',
                'ref_id'       => $order->id,
                'note'         => "Libération séquestre {$order->reference}",
            ]);

            // Enregistrer le crédit disponible
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

    // ── REMBOURSEMENT ─────────────────────────────────────────────────

    // Rembourser l'acheteur (litige résolu en sa faveur ou annulation)
    // Note : en MVP le remboursement est manuel (admin transfère via MoMo)
    // Cette méthode enregistre juste la trace comptable
    public function refund(User $buyer, int $amount, Order $order): void
    {
        DB::transaction(function () use ($buyer, $amount, $order) {

            WalletTransaction::create([
                'user_id'      => $buyer->id,
                'type'         => WalletTransaction::TYPE_CREDIT_REFUND,
                'amount'       => $amount,

                // L'acheteur n'a pas de wallet — balance_after = 0 par convention
                'balance_after' => 0,

                'ref_type' => 'order',
                'ref_id'   => $order->id,
                'note'     => "Remboursement commande {$order->reference}",
            ]);
        });
    }

    // ── COMMISSION SECRÉTAIRE ─────────────────────────────────────────

    // Créditer la commission automatique d'un secrétaire
    // 150 FCFA au dépôt + 150 FCFA à la réception = 300 FCFA par colis traité
    // public function creditSecretary(User $secretary, Order $order, string $action): void
    // {
    //     DB::transaction(function () use ($secretary, $order, $action) {

    //         $amount = self::SECRETARY_COMMISSION;

    //         $secretary->increment('wallet_available', $amount);
    //         $secretary->refresh();

    //         WalletTransaction::create([
    //             'user_id'      => $secretary->id,
    //             'type'         => WalletTransaction::TYPE_CREDIT_SECRETARY,
    //             'amount'       => $amount,
    //             'balance_after' => $secretary->wallet_available,
    //             'ref_type'     => 'order',
    //             'ref_id'       => $order->id,

    //             // $action = 'depot' ou 'reception'
    //             'note' => "Commission {$action} — commande {$order->reference}",
    //         ]);
    //     });
    // }

    // ── RETRAIT ───────────────────────────────────────────────────────

    // Demande de retrait vers MoMo
    // En MVP : l'admin transfère manuellement et valide dans le back-office
    public function requestWithdrawal(User $user, int $amount): void
    {
        // Vérifier le montant minimum
        if ($amount < self::MIN_WITHDRAWAL) {
            throw ValidationException::withMessages([
                'amount' => 'Le montant minimum de retrait est '
                    . number_format(self::MIN_WITHDRAWAL, 0, ',', ' ') . ' FCFA.',
            ]);
        }

        // Vérifier que le solde est suffisant
        if ($user->wallet_available < $amount) {
            throw ValidationException::withMessages([
                'amount' => 'Solde insuffisant. Disponible : '
                    . number_format($user->wallet_available, 0, ',', ' ') . ' FCFA.',
            ]);
        }

        // Vérifier que le numéro MoMo est renseigné
        if (! $user->phone_momo) {
            throw ValidationException::withMessages([
                'momo' => 'Aucun numéro Mobile Money enregistré sur votre compte.',
            ]);
        }

        DB::transaction(function () use ($user, $amount) {

            // Déduire immédiatement du solde disponible
            // pour éviter un double retrait pendant le traitement admin
            $user->decrement('wallet_available', $amount);
            $user->refresh();

            // Enregistrer la demande de retrait
            WalletTransaction::create([
                'user_id'      => $user->id,
                'type'         => WalletTransaction::TYPE_DEBIT_WITHDRAWAL,
                'amount'       => $amount,
                'balance_after' => $user->wallet_available,
                'ref_type'     => 'withdrawal',
                'ref_id'       => null,
                'note'         => "Retrait vers MoMo {$user->momo_operator} — {$user->phone_momo}",
            ]);
        });
    }

    // ── HISTORIQUE ────────────────────────────────────────────────────

    // Récupérer l'historique des transactions d'un utilisateur
    public function history(User $user, int $perPage = 20)
    {
        return WalletTransaction::where('user_id', $user->id)
            ->latest()
            ->paginate($perPage);
    }

    // Recalculer les soldes depuis l'historique (outil d'audit)
    // Utile pour détecter des incohérences comptables
    public function recalculateBalances(User $user): array
    {
        $transactions = WalletTransaction::where('user_id', $user->id)->get();

        $pending   = 0;
        $available = 0;

        foreach ($transactions as $tx) {
            match($tx->type) {
                // Mouvements du solde en attente
                WalletTransaction::TYPE_CREDIT_ESCROW   => $pending += $tx->amount,
                WalletTransaction::TYPE_DEBIT_ESCROW    => $pending -= $tx->amount,

                // Mouvements du solde disponible
                WalletTransaction::TYPE_CREDIT_AVAILABLE,
                WalletTransaction::TYPE_CREDIT_SECRETARY,
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
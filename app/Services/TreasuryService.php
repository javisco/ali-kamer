<?php

namespace App\Services;

use App\Models\Agency;
use App\Models\AgencyWalletTransaction;
use App\Models\Order;
use App\Models\PlatformWalletTransaction;
use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Support\Str;

class TreasuryService
{
    public function __construct(private ElgiopayService $elgiopay) {}

    // ── PHOTO INSTANTANÉE DE LA TRÉSORERIE ────────────────────────────
    public function getSnapshot(): array
    {
        // Argent réellement détenu sur le compte marchand Elgiopay, live.
        try {
            $response     = $this->elgiopay->getBalance();

            $liveBalance  = (int) ($response['balance'] ?? $response['available_balance'] ?? 0);
            $balanceError = null;
        } catch (\Throwable $e) {
            $liveBalance  = null;
            $balanceError = $e->getMessage();
        }
        //reponse de elgiopay
        // "balance" => "153215.98"
        // "currency" => "XAF"
        // "available_balance" => "153215.98"
        // "reserved_balance" => "0.00"

        // Ce que la plateforme doit encore aux vendeurs (séquestre + disponible non retiré).
        $sellersLiability = (int) User::where('role', 'seller')
            ->selectRaw('COALESCE(SUM(wallet_pending), 0) + COALESCE(SUM(wallet_available), 0) as total')
            ->value('total');

        // Ce que la plateforme doit encore aux agences.
        $agenciesLiability = (int) Agency::selectRaw('COALESCE(SUM(wallet_pending), 0) + COALESCE(SUM(wallet_available), 0) as total')
            ->value('total');

        $totalLiabilities = $sellersLiability + $agenciesLiability;

        $withdrawable = $liveBalance !== null
            ? max(0, $liveBalance - $totalLiabilities)
            : null;

        return [
            'live_balance'       => $liveBalance,
            'balance_error'      => $balanceError,
            'sellers_liability'  => $sellersLiability,
            'agencies_liability' => $agenciesLiability,
            'total_liabilities'  => $totalLiabilities,
            'withdrawable'       => $withdrawable,
            'expected_profit'    => $this->calculateExpectedProfit(),
        ];
    }

    // Reconstruction indépendante à partir des commandes réellement payées.
    // Garde-fou de cohérence UNIQUEMENT — jamais la base du calcul de retrait,
    // car elle suppose net≈gross sur les payouts (approximation si le taux
    // gateway_payout_rate devient significatif un jour).
    private function calculateExpectedProfit(): int
    {
        $paidStatuses = [
            'paid',
            'preparing',
            'registered_origin',
            'in_transit',
            'arrived_destination',
            'awaiting_buyer_confirmation',
            'completed',
            'auto_completed',
            'disputed',
        ];

        $ordersTotal = (int) Order::whereIn('status', $paidStatuses)
            ->selectRaw('COALESCE(SUM(subtotal + protection_fee), 0) as total')
            ->value('total');

        $withdrawnSellers = (int) WalletTransaction::where('type', WalletTransaction::TYPE_DEBIT_WITHDRAWAL)
            ->sum('amount');

        $withdrawnAgencies = (int) AgencyWalletTransaction::where('type', 'debit_withdrawal')->sum('amount');

        $adminWithdrawals = (int) PlatformWalletTransaction::where('type', 'debit_withdrawal')->sum('amount');

        return $ordersTotal - $withdrawnSellers - $withdrawnAgencies - $adminWithdrawals;
    }

    // ── RETRAIT ADMIN ──────────────────────────────────────────────────
    public function withdraw(User $admin, int $amount, string $phone, string $operator, ?string $note = null): PlatformWalletTransaction
    {
        $snapshot = $this->getSnapshot();

        if ($snapshot['live_balance'] === null) {
            throw new \Exception(
                'Impossible de vérifier le solde réel Elgiopay en ce moment. '
                    . 'Retrait bloqué par sécurité pour ne pas risquer de toucher aux fonds des vendeurs/agences.'
            );
        }

        if ($amount > $snapshot['withdrawable']) {
            throw new \Exception(
                'Montant supérieur à ce qui est réellement retirable ('
                    . number_format($snapshot['withdrawable'], 0, ',', ' ') . ' FCFA). '
                    . 'Cette limite protège les fonds dus aux vendeurs et aux agences.'
            );
        }

        $phoneFormatted = '237' . ltrim($phone, '0');
        $reference      = 'TREASURY-' . $admin->id . '-' . Str::uuid();

        $this->elgiopay->disburse(
            phone: $phoneFormatted,
            grossAmount: $amount,
            reference: $reference,
            description: $note ?? "Retrait trésorerie Ali-Kamer par {$admin->name}",
            operator: $operator,
            recipientName: 'Ali-Kamer'
        );

        return PlatformWalletTransaction::create([
            'admin_id'  => $admin->id,
            'type'      => 'debit_withdrawal',
            'amount'    => $amount,
            'phone'     => $phoneFormatted,
            'operator'  => $operator,
            'reference' => $reference,
            'note'      => $note,
        ]);
    }
}

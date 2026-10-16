<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderPayment;
use App\Models\OrderRefund;
use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class RefundService
{
    public function __construct(private ElgiopayService $elgiopay) {}

    /**
     * Montant remboursé = montant effectivement payé − frais de protection non remboursables.
     */
    public function refundableAmount(Order $order): array
    {
        $paid = (int) $order->total_amount;
        $protection = (int) $order->protection_fee;
        $refundable = max(0, $paid - $protection);

        return [
            'paid_amount'              => $paid,
            'protection_fee_retained'  => $protection,
            'refund_amount'            => $refundable,
        ];
    }

    public function refundLateCancellation(Order $order, User $buyer): OrderRefund
    {
        $amounts = $this->refundableAmount($order);

        $existing = OrderRefund::query()
            ->where('order_id', $order->id)
            ->where('kind', OrderRefund::KIND_LATE_CANCEL)
            ->first();

        if ($existing?->isSucceeded()) {
            return $existing;
        }

        if ($existing && $existing->status === OrderRefund::STATUS_PENDING) {
            throw ValidationException::withMessages([
                'refund' => 'Un remboursement est déjà en cours pour cette commande.',
            ]);
        }

        $payment = $order->payment;
        $alreadyPaid = $payment && $payment->isSucceeded();

        return DB::transaction(function () use ($order, $buyer, $amounts, $existing, $payment, $alreadyPaid) {
            $refund = $existing ?? OrderRefund::create([
                'order_id'                 => $order->id,
                'kind'                     => OrderRefund::KIND_LATE_CANCEL,
                'paid_amount'              => $amounts['paid_amount'],
                'protection_fee_retained'  => $amounts['protection_fee_retained'],
                'refund_amount'            => $amounts['refund_amount'],
                'status'                   => OrderRefund::STATUS_PENDING,
                'idempotency_key'          => (string) Str::uuid(),
                'initiated_by'             => $buyer->id,
            ]);

            if (! $alreadyPaid || $amounts['refund_amount'] <= 0) {
                $refund->update([
                    'status'       => OrderRefund::STATUS_SUCCEEDED,
                    'confirmed_at' => now(),
                    'error_message'=> $alreadyPaid ? null : 'Aucun paiement réussi — annulation sans virement.',
                ]);

                return $refund->fresh();
            }

            try {
                $phone = '237' . ltrim($payment->payer_phone ?? '', '0');
                $grossUp = $this->elgiopay->grossUpPayout($amounts['refund_amount']);

                $response = $this->elgiopay->disburse(
                    phone: $phone,
                    grossAmount: $grossUp['gross'],
                    reference: 'LCREF-' . $order->reference . '-' . $refund->id,
                    description: "Remboursement annulation retard {$order->reference}",
                    operator: $payment->payer_operator ?? 'mtn',
                    recipientName: $buyer->name
                );

                $refund->update([
                    'status'              => OrderRefund::STATUS_SUCCEEDED,
                    'confirmed_at'        => now(),
                    'provider_reference'  => $response['data']['transaction_id'] ?? ($response['transaction_id'] ?? null),
                    'error_message'       => null,
                ]);

                $payment->update(['status' => 'refunded']);

                $seller = $order->shop?->user;
                if ($seller && $order->net_amount > 0) {
                    $pending = (int) $seller->wallet_pending;
                    $debit = min($pending, (int) $order->net_amount);
                    if ($debit > 0) {
                        $seller->decrement('wallet_pending', $debit);
                    }
                }

                WalletTransaction::create([
                    'user_id'       => $buyer->id,
                    'type'          => WalletTransaction::TYPE_CREDIT_REFUND,
                    'amount'        => $amounts['refund_amount'],
                    'balance_after' => 0,
                    'ref_type'      => 'order',
                    'ref_id'        => $order->id,
                    'note'          => sprintf(
                        'Remboursement annulation retard %s — payé %s FCFA, protection retenue %s FCFA, viré %s FCFA',
                        $order->reference,
                        number_format($amounts['paid_amount'], 0, ',', ' '),
                        number_format($amounts['protection_fee_retained'], 0, ',', ' '),
                        number_format($amounts['refund_amount'], 0, ',', ' ')
                    ),
                ]);
            } catch (\Throwable $e) {
                Log::error('Late-cancel refund failed', [
                    'order' => $order->reference,
                    'error' => $e->getMessage(),
                ]);

                $refund->update([
                    'status'        => OrderRefund::STATUS_FAILED,
                    'error_message' => $e->getMessage(),
                ]);

                throw ValidationException::withMessages([
                    'refund' => 'Le remboursement n’a pas pu être confirmé par la passerelle. La commande n’a pas été annulée. Réessayez plus tard.',
                ]);
            }

            return $refund->fresh();
        });
    }
}

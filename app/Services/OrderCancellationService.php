<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderCancellationService
{
    public function __construct(
        private BusinessSettingsService $settings,
        private RefundService $refunds,
    ) {}

    /**
     * @return array{
     *   allowed: bool,
     *   kind: ?string,
     *   reason: ?string,
     *   refundable: int,
     *   protection_retained: int
     * }
     */
    public function eligibility(Order $order): array
    {
        if (in_array($order->status, [
            Order::STATUS_CANCELLED,
            Order::STATUS_COMPLETED,
            Order::STATUS_AUTO_COMPLETED,
            Order::STATUS_DISPUTED,
            Order::STATUS_FAILED,
        ], true)) {
            return $this->deny('Cette commande ne peut plus être annulée.');
        }

        if (in_array($order->status, [Order::STATUS_PENDING, Order::STATUS_AWAITING_PAYMENT], true)) {
            return [
                'allowed'              => true,
                'kind'                 => 'unpaid',
                'reason'               => 'Annulation avant paiement.',
                'refundable'           => 0,
                'protection_retained'  => 0,
            ];
        }

        $amounts = $this->refunds->refundableAmount($order);

        if ($this->canLateCancelUnprepared($order)) {
            return [
                'allowed'              => true,
                'kind'                 => 'unprepared',
                'reason'               => 'Délai de préparation dépassé — annulation proposée à l’acheteur.',
                'refundable'           => $amounts['refund_amount'],
                'protection_retained'  => $amounts['protection_fee_retained'],
            ];
        }

        if ($this->canLateCancelUndeposited($order)) {
            return [
                'allowed'              => true,
                'kind'                 => 'undeposited',
                'reason'               => 'Délai de dépôt en agence dépassé — annulation proposée à l’acheteur.',
                'refundable'           => $amounts['refund_amount'],
                'protection_retained'  => $amounts['protection_fee_retained'],
            ];
        }

        return $this->deny('L’annulation n’est pas disponible pour le statut actuel de cette commande.');
    }

    public function canCancel(Order $order): bool
    {
        return $this->eligibility($order)['allowed'] === true;
    }

    public function cancelByBuyer(Order $order, User $buyer, ?string $reason = null): void
    {
        if ($order->buyer_id !== $buyer->id) {
            abort(403);
        }

        $eligibility = $this->eligibility($order);
        if (! $eligibility['allowed']) {
            throw ValidationException::withMessages([
                'cancel' => $eligibility['reason'] ?? 'Annulation refusée.',
            ]);
        }

        $kind = $eligibility['kind'];
        $message = $reason ?: match ($kind) {
            'unprepared'  => 'Annulation acheteur — retard de préparation.',
            'undeposited' => 'Annulation acheteur — retard de dépôt en agence.',
            default       => 'Annulé par l’acheteur.',
        };

        if (in_array($kind, ['unprepared', 'undeposited'], true)) {
            $this->assertLateCancelStillValid($order, $kind);
            $this->refunds->refundLateCancellation($order, $buyer);
        }

        DB::transaction(function () use ($order, $message) {
            $order->refresh();
            $order->loadMissing('items.variant', 'items.product');

            foreach ($order->items as $item) {
                $item->product_variant_id
                    ? $item->variant?->decrement('stock_reserved', $item->quantity)
                    : $item->product?->decrement('stock_reserved', $item->quantity);
            }

            $order->update([
                'status'              => Order::STATUS_CANCELLED,
                'cancelled_at'        => now(),
                'cancellation_reason' => $message,
            ]);
        });
    }

    public function canLateCancelUnprepared(Order $order): bool
    {
        if (! $this->settings->enabled('orders.late_cancel_unprepared_enabled', true)) {
            return false;
        }

        if ($order->status !== Order::STATUS_PAID) {
            return false;
        }

        $hours = $this->settings->getInt('orders.late_cancel_unprepared_hours', 72);
        $start = $order->created_at ?? now();

        return $this->delayElapsed($start, $hours);
    }

    public function canLateCancelUndeposited(Order $order): bool
    {
        if (! $this->settings->enabled('orders.late_cancel_undeposited_enabled', true)) {
            return false;
        }

        if ($order->status !== Order::STATUS_PREPARING) {
            return false;
        }

        $depositedStatuses = [
            Order::STATUS_REGISTERED_ORIGIN,
            Order::STATUS_IN_TRANSIT,
            Order::STATUS_ARRIVED_DESTINATION,
            Order::STATUS_AWAITING_BUYER_CONFIRMATION,
        ];

        if (in_array($order->status, $depositedStatuses, true)) {
            return false;
        }

        $hours = $this->settings->getInt('orders.late_cancel_undeposited_hours', 72);
        $start = $order->preparing_at ?? $order->updated_at ?? now();

        return $this->delayElapsed($start, $hours);
    }

    private function delayElapsed($start, int $hours): bool
    {
        if (! $start) {
            return false;
        }

        if (now()->lt($start->copy()->addHours($hours))) {
            return false;
        }

        $window = $this->settings->getInt('orders.late_cancel_window_hours', 0);
        if ($window > 0 && now()->gt($start->copy()->addHours($hours + $window))) {
            return false;
        }

        return true;
    }

    private function assertLateCancelStillValid(Order $order, string $kind): void
    {
        $order->refresh();

        if ($kind === 'unprepared' && $order->status !== Order::STATUS_PAID) {
            throw ValidationException::withMessages([
                'cancel' => 'La commande a changé de statut (préparation ou dépôt). L’annulation tardive n’est plus possible.',
            ]);
        }

        if ($kind === 'undeposited' && $order->status !== Order::STATUS_PREPARING) {
            throw ValidationException::withMessages([
                'cancel' => 'Le colis a été déposé en agence ou le statut a changé. L’annulation tardive n’est plus possible.',
            ]);
        }
    }

    private function deny(string $reason): array
    {
        return [
            'allowed'              => false,
            'kind'                 => null,
            'reason'               => $reason,
            'refundable'           => 0,
            'protection_retained'  => 0,
        ];
    }
}

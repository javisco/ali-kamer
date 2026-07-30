<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {


        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->foreignId('buyer_id')->constrained('users');
            $table->foreignId('shop_id')->constrained('shops');

            $table->enum('status', [
                'pending',
                'awaiting_payment',
                'paid',
                'preparing',
                'registered_origin',
                'in_transit',
                'arrived_destination',
                'awaiting_buyer_confirmation',
                'completed',
                'auto_completed',
                'disputed',
                'cancelled',
                'failed',
            ])->default('pending');

            // Montants en FCFA
            $table->unsignedInteger('subtotal');
            $table->unsignedInteger('shipping_fee')->default(0);
            $table->unsignedInteger('protection_fee');
            $table->unsignedInteger('gateway_fee');
            $table->unsignedInteger('total_amount');
            $table->unsignedInteger('platform_commission');
            $table->unsignedInteger('agency_commission');
            $table->unsignedInteger('gateway_payout_fee');
            $table->unsignedInteger('net_amount');

            // Instantané financier immuable
            $table->json('financial_snapshot');

            // Code unique donné par le vendeur au secrétaire
            $table->string('deposit_code', 10)->unique()->nullable();

            // OTP remise colis
            $table->string('otp_code', 6)->nullable();
            $table->timestamp('otp_expires_at')->nullable();
            $table->timestamp('otp_used_at')->nullable();

            // Timer 72h
            $table->timestamp('timer_deadline')->nullable();

            // Horodatages transitions
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('preparing_at')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('arrived_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();

            $table->text('buyer_note')->nullable();
            $table->text('cancellation_reason')->nullable();

            $table->timestamps();

            $table->index('status');
            $table->index('buyer_id');
            $table->index('shop_id');
            $table->index('timer_deadline');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

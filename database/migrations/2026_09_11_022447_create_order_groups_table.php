<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_groups', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->foreignId('buyer_id')->constrained('users');

            $table->enum('status', [
                'awaiting_payment',
                'paid',
                'failed',
                'cancelled',
            ])->default('awaiting_payment');

            $table->unsignedInteger('subtotal');
            $table->unsignedInteger('protection_fee');
            $table->unsignedInteger('gateway_fee');
            $table->unsignedInteger('total_amount');

            $table->json('financial_snapshot');

            $table->timestamp('paid_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('buyer_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_groups');
    }
};
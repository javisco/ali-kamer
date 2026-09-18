<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_group_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_group_id')->unique()->constrained()->cascadeOnDelete();

            $table->enum('method', ['campay', 'elgiopay', 'notchpay', 'virtuel_card'])->default('elgiopay');
            $table->enum('status', ['pending', 'processing', 'succeeded', 'failed', 'refunded'])->default('pending');

            $table->string('provider_reference')->nullable();
            $table->string('idempotency_key')->unique();
            $table->string('payer_phone');
            $table->enum('payer_operator', ['mtn', 'orange']);

            $table->json('provider_response')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_group_payments');
    }
};
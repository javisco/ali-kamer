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
        Schema::create('order_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->unique()->constrained()->cascadeOnDelete();

            $table->enum('method', ['manual', 'campay', 'cinetpay'])->default('manual');
            $table->enum('status', [
                'pending',
                'processing',
                'succeeded',
                'failed',
                'refunded',
            ])->default('pending');

            $table->string('provider_reference')->nullable();
            $table->string('idempotency_key')->unique();
            $table->string('payer_phone');
            $table->enum('payer_operator', ['mtn', 'orange']);

            // Paiement manuel
            $table->string('manual_transaction_id')->nullable();
            $table->foreignId('manual_validated_by')
                ->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('manual_validated_at')->nullable();

            $table->json('provider_response')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_payments');
    }
};

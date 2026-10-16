<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_refunds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('kind', 40);
            $table->unsignedInteger('paid_amount');
            $table->unsignedInteger('protection_fee_retained');
            $table->unsignedInteger('refund_amount');
            $table->string('status', 30)->default('pending');
            $table->string('idempotency_key')->unique();
            $table->string('provider_reference')->nullable();
            $table->text('error_message')->nullable();
            $table->foreignId('initiated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();

            $table->unique(['order_id', 'kind']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_refunds');
    }
};

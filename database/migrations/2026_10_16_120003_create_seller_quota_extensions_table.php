<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seller_quota_extensions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('extra_registered')->default(0);
            $table->unsignedInteger('extra_published')->default(0);
            $table->unsignedInteger('price')->default(0);
            $table->string('payment_status', 20)->default('pending');
            $table->string('provider_reference')->nullable();
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['shop_id', 'payment_status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seller_quota_extensions');
    }
};

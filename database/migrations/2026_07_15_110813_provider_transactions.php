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
        Schema::create('provider_transactions', function (Blueprint $table) {
            $table->id();
            $table->uuid('reference_uuid')->unique()->index(); // ID d'idempotence généré avant l'appel API
            $table->string('operator'); // MTN, ORANGE, CAMPAY
            $table->string('phone_number');
            $table->unsignedInteger('amount');
            $table->string('status')->default('processing'); // processing, success, failed
            $table->jsonb('raw_provider_response')->nullable();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('provider_transactions');
    }
};

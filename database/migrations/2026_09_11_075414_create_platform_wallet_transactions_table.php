<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('platform_wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->constrained('users');
            $table->enum('type', ['debit_withdrawal'])->default('debit_withdrawal');
            $table->unsignedInteger('amount');
            $table->string('phone');
            $table->enum('operator', ['mtn', 'orange']);
            $table->string('reference')->unique();
            $table->string('note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('platform_wallet_transactions');
    }
};
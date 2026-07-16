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
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['credit', 'debit']);
            $table->unsignedInteger('amount');
            $table->unsignedInteger('balance_after'); // Solde calculé après l'opération (audit trail)
            $table->string('ref_type'); // ex: App\Models\Order ou App\Models\Withdrawal
            $table->unsignedBigInteger('ref_id');
            $table->text('note')->nullable();
            $table->timestamps();

            $table->index(['ref_type', 'ref_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};

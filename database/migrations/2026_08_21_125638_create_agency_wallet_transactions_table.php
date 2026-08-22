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
        // Wallet dédié aux agences partenaires
        // Séparé du wallet vendeur pour une comptabilité claire
        Schema::create('agency_wallet_transactions', function (Blueprint $table) {
            $table->id();

            // L'agence concernée
            $table->foreignId('agency_id')
                ->constrained()
                ->cascadeOnDelete();

            // Type de mouvement
            $table->enum('type', [
                'credit_commission_pending',
                'credit_commission', // 1% reçu pour chaque colis traité
                'debit_withdrawal',  // retrait vers MoMo
                'debit_withdrawal_failed', // retrait échoué — recrédité
            ]);

            // Montant en FCFA
            $table->unsignedInteger('amount');

            // Solde après opération
            $table->unsignedBigInteger('balance_after');

            // Référence à la commande si applicable
            $table->foreignId('order_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('note')->nullable();
            $table->timestamps();

            $table->index('agency_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agency_wallet_transactions');
    }
};

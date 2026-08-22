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

            // L'utilisateur concerné par cette transaction
            // Peut être un vendeur (gains) ou un secrétaire (commissions)
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Type de mouvement financier
            // credit_escrow       : fonds séquestrés quand l'acheteur paie
            // debit_escrow        : libération du séquestre vers disponible
            // credit_available    : fonds disponibles après livraison confirmée
            // debit_withdrawal    : retrait vers MoMo
            // debit_commission    : commission plateforme prélevée
            // credit_refund       : remboursement vers l'acheteur
            // credit_secretary    : commission secrétaire (150 FCFA par action)
            // debit_transport_fee : frais transport payés par acheteur
            // credit_transport_fee: remboursement frais transport au vendeur
            $table->enum('type', [
                'buy',
                'credit_escrow',
                'debit_escrow',
                'credit_available',
                'debit_withdrawal',
                'debit_commission',
                'credit_refund',
                'credit_secretary',
                'debit_transport_fee',
                'credit_transport_fee',
            ]);

            // Montant en FCFA — toujours positif
            $table->unsignedInteger('amount');

            // Solde après opération — permet de reconstruire l'historique complet
            // et de détecter toute anomalie comptable sans recalculer
            $table->unsignedBigInteger('balance_after');

            // Référence polymorphique : à quelle entité est liée cette transaction
            // Ex: ref_type = 'order', ref_id = 42
            $table->string('ref_type')->nullable();
            $table->unsignedBigInteger('ref_id')->nullable();

            // Description lisible pour l'historique
            $table->string('note')->nullable();

            $table->timestamps();

            $table->index('user_id');
            $table->index(['ref_type', 'ref_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};

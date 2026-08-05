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
        Schema::create('disputes', function (Blueprint $table) {
            $table->id();

            // La commande concernée par le litige
            $table->foreignId('order_id')
                ->constrained()
                ->cascadeOnDelete();

            // Qui a ouvert le litige (acheteur ou vendeur)
            $table->foreignId('initiator_id')
                ->constrained('users');

            // Motif du litige
            $table->enum('type', [
                'not_received',       // produit non reçu
                'not_conform',        // produit non conforme à l'annonce
                'damaged',            // produit endommagé
                'incorrect',          // mauvais produit reçu
                'incomplete',         // produit incomplet
                'empty_package',      // colis vide
                'seller_unresponsive', // vendeur ne répond pas
            ]);

            $table->text('description');

            // Statut du dossier litige
            $table->enum('status', [
                'open',           // ouvert, en attente réponse vendeur
                'seller_replied', // vendeur a répondu
                'under_review',   // admin examine le dossier
                'resolved',       // décision prise par l'admin
                'closed',         // clôturé
            ])->default('open');

            // Décision finale de l'admin
            $table->enum('resolution', [
                'refund_buyer',    // remboursement intégral acheteur
                'pay_seller',      // paiement vendeur (acheteur avait tort)
                'partial_refund',  // remboursement partiel
                'return_required', // retour produit obligatoire
                'buyer_bad_faith', // acheteur de mauvaise foi
            ])->nullable();

            // Montant si remboursement partiel
            $table->unsignedInteger('resolution_amount')->nullable();

            // Note de décision de l'admin
            $table->text('resolution_note')->nullable();

            // Admin qui a tranché
            $table->foreignId('resolver_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Délai de réponse du vendeur (48h après ouverture)
            $table->timestamp('seller_reply_deadline')->nullable();

            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('order_id');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('disputes');
    }
};

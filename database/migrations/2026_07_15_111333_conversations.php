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
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();

            // L'acheteur qui a initié la conversation
            $table->foreignId('buyer_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // La boutique concernée
            $table->foreignId('shop_id')
                ->constrained('shops')
                ->cascadeOnDelete();

            // Produit de départ (optionnel)
            // Permet d'accéder directement au catalogue du vendeur depuis la conversation
            $table->foreignId('product_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            // Date du dernier message — pour trier les conversations par activité
            $table->timestamp('last_message_at')->nullable();

            // Archiver une conversation sans la supprimer
            $table->boolean('is_archived')->default(false);

            $table->timestamps();

            // Un acheteur ne peut avoir qu'une seule conversation par boutique
            $table->unique(['buyer_id', 'shop_id']);

            // Index pour accélérer le tri par activité récente
            $table->index('last_message_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};

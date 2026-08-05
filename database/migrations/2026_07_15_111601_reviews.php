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

        Schema::create('reviews', function (Blueprint $table) {
            $table->id();

            // La commande liée — obligatoire, pas d'avis sans achat confirmé
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();

            // Qui note
            $table->foreignId('reviewer_id')->constrained('users');

            // Qui est noté et quel type d'entité
            // reviewee_type : 'product' | 'shop' | 'buyer'
            $table->enum('reviewee_type', ['shop', 'buyer']); // L'acheteur note la boutique ou le vendeur note la fiabilité de l'acheteur
            $table->unsignedBigInteger('reviewee_id');

            // Note de 1 à 5
            $table->unsignedTinyInteger('rating');

            // Commentaire optionnel
            $table->text('body')->nullable();

            // Avis vérifié — toujours true car order_id obligatoire
            $table->boolean('is_verified')->default(true);

            // Avis signalé comme suspect
            $table->boolean('is_flagged')->default(false);

            // Vendeur conteste cet avis
            $table->boolean('is_contested')->default(false);

            $table->timestamps();

            // Un reviewer ne peut noter qu'une fois par commande par type
            $table->unique(['order_id', 'reviewer_id', 'reviewee_type']);
            $table->index(['reviewee_type', 'reviewee_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};

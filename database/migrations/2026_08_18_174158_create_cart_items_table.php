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
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('cart_id')
                ->constrained()
                ->cascadeOnDelete();

            // Produit dans le panier
            $table->foreignId('product_id')
                ->constrained()
                ->cascadeOnDelete();

            // Variante choisie (null si produit sans variante)
            $table->foreignId('product_variant_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            // Quantité
            $table->unsignedInteger('quantity')->default(1);

            // Prix au moment de l'ajout au panier (snapshot)
            $table->unsignedInteger('unit_price');

            $table->timestamps();

            // Un même produit/variante ne peut être en double dans le panier
            $table->unique(['cart_id', 'product_id', 'product_variant_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};

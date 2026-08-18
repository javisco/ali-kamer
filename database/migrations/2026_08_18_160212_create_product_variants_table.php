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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();

            // Le produit parent
            $table->foreignId('product_id')
                ->constrained()
                ->cascadeOnDelete();

            // Prix de cette variante en FCFA
            $table->unsignedInteger('price');

            // Ancien prix barré (optionnel)
            $table->unsignedInteger('old_price')->nullable();

            // Stock de cette variante précise
            $table->unsignedInteger('stock')->default(0);

            // Stock réservé (paniers actifs)
            $table->unsignedInteger('stock_reserved')->default(0);

            // SKU optionnel (référence interne vendeur)
            $table->string('sku')->nullable();

            // Actif ou désactivé
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};

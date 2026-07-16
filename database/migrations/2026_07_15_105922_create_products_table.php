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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('restrict');
            $table->string('title');
            $table->text('description');
            $table->unsignedInteger('price'); // FCFA entier
            $table->unsignedInteger('old_price')->nullable();
            $table->integer('stock')->default(0);
            $table->integer('stock_reserved')->default(0);
            $table->integer('min_quantity')->default(1);
            $table->string('city'); // Impacte les calculs de livraison
            $table->boolean('shipping_included')->default(false);
            $table->integer('shipping_threshold_qty')->nullable();
            $table->enum('status', ['visible', 'hidden'])->default('hidden');
            $table->string('images'); // Tableau d'URLs compressées WebP
            // $table->jsonb('specifications')->nullable(); // Paires clé-valeur (taille, couleur, etc.)
            $table->timestamps();

            // Indexations cruciales pour la recherche et la performance de filtrage
            $table->index(['status', 'city']);
            $table->index('price');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

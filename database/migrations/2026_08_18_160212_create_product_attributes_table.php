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
        Schema::create('product_attributes', function (Blueprint $table) {
            $table->id();

            // Le produit auquel appartient cet attribut
            // Ex: Lenovo T430s → attribut "Processeur"
            $table->foreignId('product_id')
                ->constrained()
                ->cascadeOnDelete();

            // Nom de l'attribut
            // Ex: "Processeur", "RAM", "Couleur", "Taille"
            $table->string('name');

            // Ordre d'affichage
            $table->unsignedSmallInteger('sort_order')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_attributes');
    }
};

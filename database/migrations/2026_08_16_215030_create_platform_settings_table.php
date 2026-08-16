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
        Schema::create('platform_settings', function (Blueprint $table) {
            $table->id();

            // Clé unique — utilisée dans le code pour récupérer la valeur
            $table->string('key')->unique();

            // Valeur stockée en string — castée selon le type
            $table->string('value');

            // Type pour le cast et l'affichage
            $table->enum('type', ['percentage', 'integer', 'boolean', 'text']);

            // Libellé lisible pour l'admin
            $table->string('label');

            // Description détaillée
            $table->text('description')->nullable();

            // Groupe pour organiser l'affichage
            $table->string('group')->default('general');
            // Ex: 'commissions', 'timers', 'limites'

            // Ordre d'affichage dans le groupe
            $table->unsignedSmallInteger('sort_order')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('platform_settings');
    }
};

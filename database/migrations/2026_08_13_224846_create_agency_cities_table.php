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

        Schema::create('agency_cities', function (Blueprint $table) {
            $table->id();

            // L'agence qui dessert cette ville
            $table->foreignId('agency_id')
                ->constrained()
                ->cascadeOnDelete();

            // La ville desservie
            $table->string('city');

            // Désactiver une ville sans supprimer
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            // Une agence ne peut pas avoir la même ville deux fois
            $table->unique(['agency_id', 'city']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agency_cities');
    }
};

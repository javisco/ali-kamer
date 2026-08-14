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
        Schema::create('agency_counters', function (Blueprint $table) {
            $table->id();
            // L'agence à laquelle appartient ce guichet
            $table->foreignId('agency_id')
                ->constrained()
                ->cascadeOnDelete();

            // Localisation du guichet
            // Une même agence peut avoir plusieurs guichets dans plusieurs villes
            $table->string('city');           // Ex: "Douala"
            $table->string('district')->nullable(); // Ex: "Akwa"
            $table->string('landmark')->nullable(); // Ex: "En face du marché central"
            $table->string('phone')->nullable();

            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Index pour accélérer la recherche des guichets par ville
            $table->index(['agency_id', 'city']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agency_counters');
    }
};

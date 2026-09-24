<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // La migration trust précédente crée déjà ces colonnes sur une base
        // neuve. Ce fichier ne sert qu'à documenter le correctif du modèle,
        // aucune nouvelle colonne n'est créée ici.
    }

    public function down(): void
    {
        // Rien à annuler.
    }
};

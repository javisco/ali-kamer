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
        Schema::table('users', function (Blueprint $table) {
            // Modifier l'enum role pour ajouter agency_manager
            // Note : sur MySQL il faut recréer l'enum
            \DB::statement("ALTER TABLE users MODIFY COLUMN role
        ENUM('buyer','seller','secretary','admin','agency_manager')
        DEFAULT 'buyer'");

            // Lier le compte agence à une agence spécifique
            // $table->foreignId('agency_id')
            //     ->nullable()
            //     ->after('role')
            //     ->constrained('agencies')
            //     ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // 1. Supprimer la clé étrangère et la colonne agency_id
            $table->dropForeign(['agency_id']);
            $table->dropColumn('agency_id');
        });

        // 2. Remettre l'enum role à sa valeur d'origine (sans agency_manager)
        \DB::statement("ALTER TABLE users MODIFY COLUMN role
        ENUM('buyer','seller','secretary','admin')
        DEFAULT 'buyer'");
    }
};

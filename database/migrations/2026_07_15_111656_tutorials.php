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
        Schema::create('tutorials', function (Blueprint $table) {
            $table->id();

            // Titre du tutoriel
            $table->string('title');

            // Type de contenu
            $table->enum('type', ['video', 'text']);

            // À qui est destiné ce tutoriel
            $table->enum('role_target', ['buyer', 'seller', 'all']);

            // Catégorie pour organiser les tutoriels
            $table->string('category');
            // Ex: 'comment-acheter', 'comment-vendre', 'paiement', 'livraison', 'litige'

            // URL de la vidéo YouTube ou hébergée
            $table->string('video_url')->nullable();

            // Miniature de la vidéo
            $table->string('thumbnail_url')->nullable();

            // Contenu texte (si type=text)
            $table->longText('content')->nullable();

            // Durée en minutes (pour les vidéos)
            $table->unsignedSmallInteger('duration_minutes')->nullable();

            // Ordre d'affichage dans la catégorie
            $table->unsignedSmallInteger('sort_order')->default(0);

            // Publié ou brouillon
            $table->boolean('is_published')->default(false);

            $table->timestamps();

            $table->index(['role_target', 'is_published', 'category']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tutorials');
    }
};

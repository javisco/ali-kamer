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
            $table->string('title');
            $table->enum('type', ['video', 'text']);
            $table->enum('role_target', ['buyer', 'seller']); // Strictement séparés par rôle
            $table->string('content_url'); // URL du tutoriel ou de la vidéo
            $table->integer('order_index')->default(0); // Pour trier l'ordre d'affichage
            $table->boolean('is_published')->default(true);
            $table->timestamps();

            $table->index(['role_target', 'is_published']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tutorials');
    }
};

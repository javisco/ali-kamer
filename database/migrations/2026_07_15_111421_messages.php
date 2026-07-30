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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();

            // La conversation à laquelle appartient ce message
            $table->foreignId('conversation_id')
                ->constrained()
                ->cascadeOnDelete();

            // L'auteur du message (acheteur ou vendeur)
            $table->foreignId('sender_id')
                ->constrained('users');

            // Type de contenu
            $table->enum('type', ['text', 'image', 'pdf']);

            // Contenu texte (pour type = text)
            $table->text('body')->nullable();

            // URL du fichier (pour type = image ou pdf)
            $table->string('attachment_url')->nullable();

            // Taille du fichier en octets (pour validation affichage)
            $table->unsignedInteger('attachment_size')->nullable();

            // Statut de lecture
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();

            // RÈGLE CRITIQUE : horodatage serveur uniquement
            // Jamais une date envoyée par le client — évite toute falsification
            $table->timestamp('sent_at')->useCurrent();

            // Pas de updated_at — un message est immuable
            // Aucune API de modification ou suppression n'est exposée
            $table->timestamp('created_at')->useCurrent();

            $table->index(['conversation_id', 'sent_at']);
            $table->index(['sender_id', 'is_read']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};

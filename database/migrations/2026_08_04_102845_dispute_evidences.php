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
        Schema::create('dispute_evidences', function (Blueprint $table) {
            $table->id();

            // Le litige auquel appartient cette preuve
            $table->foreignId('dispute_id')
                ->constrained()
                ->cascadeOnDelete();

            // Qui soumet cette preuve (acheteur ou vendeur)
            $table->foreignId('submitted_by')
                ->constrained('users');

            // Type de preuve
            $table->enum('type', ['photo', 'video', 'document', 'text']);

            // URL du fichier (pour photo/video/document)
            $table->string('url')->nullable();

            // Contenu textuel (pour type = text)
            $table->text('content')->nullable();

            // Description de la preuve
            $table->string('description')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};

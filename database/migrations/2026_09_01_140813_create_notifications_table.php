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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();

            // Destinataire de la notification
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Canal d'envoi
            $table->enum('channel', ['whatsapp', 'sms', 'push', 'email']);

            // Type d'événement — ex: 'order.paid', 'dispute.opened'
            $table->string('type');

            $table->string('title')->nullable();
            $table->text('body');

            // Données contextuelles (order_id, dispute_id, etc.)
            $table->json('data')->nullable();

            // Statut d'envoi
            $table->enum('status', [
                'pending',   // en file d'attente
                'sent',      // envoyé
                'delivered', // confirmé délivré
                'failed',    // échec
            ])->default('pending');

            // Nombre de tentatives
            $table->unsignedTinyInteger('retry_count')->default(0);

            // Message d'erreur si échec
            $table->text('error_message')->nullable();

            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};

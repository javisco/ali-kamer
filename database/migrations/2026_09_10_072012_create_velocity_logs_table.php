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
        Schema::create('velocity_logs', function (Blueprint $table) {
    $table->id();

    // null si tentative sans compte (inscription bloquée)
    $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

    $table->string('ip_address', 45);

    // Action tentée : 'register', 'login', 'order_create', 'payment_initiate'...
    $table->string('action', 100);

    // Données contextuelles optionnelles (ex: email tenté, référence commande...)
    $table->json('metadata')->nullable();

    $table->timestamp('created_at')->useCurrent();

    // Pas de updated_at — ces logs sont immuables
    $table->index(['ip_address', 'action', 'created_at']);
    $table->index(['user_id', 'action', 'created_at']);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('velocity_logs');
    }
};

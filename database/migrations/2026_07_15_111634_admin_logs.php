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
        Schema::create('admin_logs', function (Blueprint $table) {
            $table->id();

            // Admin qui a effectué l'action
            $table->foreignId('admin_id')->constrained('users');

            // Action effectuée — ex: 'kyc.approved', 'dispute.resolved'
            $table->string('action');

            // Entité concernée
            $table->string('target_type')->nullable(); // ex: 'user', 'order'
            $table->unsignedBigInteger('target_id')->nullable();

            // Note descriptive
            $table->text('note')->nullable();

            // IP de l'admin au moment de l'action
            $table->string('ip_address')->nullable();

            $table->timestamps();

            $table->index(['target_type', 'target_id']);
            $table->index('admin_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_logs');
    }
};

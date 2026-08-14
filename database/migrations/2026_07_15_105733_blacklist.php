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
        Schema::create('blacklist', function (Blueprint $table) {
            $table->id();

            // CNI hashée en SHA-256 — jamais stockée en clair
            // Permet de bloquer une réinscription sans connaître le numéro CNI
            $table->string('cni_hash')->nullable()->index();

            // Numéro MoMo du fraudeur — bloque les retraits
            $table->string('phone_momo')->nullable()->index();

            // Téléphone du fraudeur — bloque l'inscription
            $table->string('phone_number')->nullable()->index();

            // IP au moment de la fraude
            $table->string('ip_address')->nullable();

            // Motif du blacklistage
            $table->text('reason');

            // Admin qui a blacklisté
            $table->foreignId('created_by')->constrained('users');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blacklist');
    }
};

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
        Schema::create('blacklist_identifiers', function (Blueprint $table) {
    $table->id();
    $table->foreignId('blacklist_id')->constrained('blacklist')->cascadeOnDelete();

    // Type d'identifiant — du plus fort au plus faible
    $table->enum('type', [
        'cni_hash',         // ★★★★★ Impossible à contourner sans nouvelle CNI
        'phone_momo_hash',  // ★★★★☆ Lié à une CNI légalement
        'device_id_hash',   // ★★★☆☆ Contournable par reset usine
        'email_hash',       // ★★☆☆☆ Facile à recréer
        'phone_hash',       // ★☆☆☆☆ 200 FCFA → nouvelle SIM
        'ip_hash',          // ★☆☆☆☆ VPN omniprésent au Cameroun
    ]);

    // SHA-256(normalize(valeur) + BLACKLIST_PEPPER)
    // Le pepper est dans .env — jamais en base
    $table->char('value_hash', 64);

    // Version masquée pour l'admin : "6**1234**", "jean***@gmail.com"
    $table->string('value_masked', 100);

    // Force du signal 0-100 selon le type
    $table->tinyInteger('signal_strength')->unsigned();

    $table->timestamps();

    // Index pour la recherche rapide à chaque connexion/inscription
    $table->index(['type', 'value_hash']);

    // Un identifiant ne peut pas être dupliqué pour la même blacklist
    $table->unique(['blacklist_id', 'type']);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blacklist_identifiers');
    }
};

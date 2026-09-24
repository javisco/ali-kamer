<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Le projet possède déjà des données historiques dans la table `blacklist`.
     * On retire donc uniquement l’ancienne FK de `blacklist_identifiers` au lieu
     * de la remplacer par une FK vers `blacklists_v2` qui pourrait casser des
     * lignes historiques. La relation Eloquent pointe désormais vers V2.
     *
     * Plus tard, lors d’une migration complète de l’ancien module, une FK stricte
     * pourra être réintroduite après nettoyage des données historiques.
     */
    public function up(): void
    {
        Schema::table('blacklist_identifiers', function (Blueprint $table) {
            try {
                $table->dropForeign(['blacklist_id']);
            } catch (\Throwable) {
                // Déjà supprimée/corrigée : rien à faire.
            }
        });
    }

    public function down(): void
    {
        // Nous ne recréons pas l’ancienne FK : cela pourrait à nouveau
        // empêcher TrustService d’écrire dans blacklists_v2.
    }
};

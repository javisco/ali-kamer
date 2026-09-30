<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ajoute uniquement le stockage privé des médias Didit.
     *
     * Nous ne modifions pas la structure KYC existante : didit_result reste
     * le rapport métier et didit_media référence les fichiers téléchargés
     * depuis les URLs pré-signées de Didit.
     */
    public function up(): void
    {
        Schema::table('kyc_documents', function (Blueprint $table) {
            $table->json('didit_media')->nullable()->after('didit_result');
        });
    }

    public function down(): void
    {
        Schema::table('kyc_documents', function (Blueprint $table) {
            if (Schema::hasColumn('kyc_documents', 'didit_media')) {
                $table->dropColumn('didit_media');
            }
        });
    }
};

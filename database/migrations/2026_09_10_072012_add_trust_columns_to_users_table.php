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
        Schema::table('users', function (Blueprint $table) {
    // Profil de risque calculé automatiquement
    // clean | watch | restricted | blocked
    $table->enum('risk_profile', ['clean', 'watch', 'restricted', 'blocked'])
          ->default('clean')
          ->after('trust_score');

    // Date à laquelle le compte est devenu réellement actif
    // (premier achat ou première vente confirmée)
    $table->timestamp('activated_at')->nullable()->after('risk_profile');

    // Nombre d'IPs distinctes utilisées — signal multi-compte
    $table->smallInteger('distinct_ip_count')->unsigned()->default(0)->after('activated_at');

    // Dernière IP connue
    $table->string('last_ip', 45)->nullable()->after('distinct_ip_count');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};

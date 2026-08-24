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
        Schema::table('orders', function (Blueprint $table) {
            // Montant brut envoyé à Campay pour que le vendeur reçoive net_amount
            $table->unsignedInteger('gross_seller_amount')
                ->default(0)
                ->after('net_amount')
                ->comment('Montant Gross-Up envoyé à Campay — le vendeur reçoit net_amount exactement');

            // Montant net que l'agence reçoit sur son téléphone
            $table->unsignedInteger('net_agency_amount')
                ->default(0)
                ->after('gross_seller_amount')
                ->comment('Ce que l\'agence reçoit exactement sur son MoMo');

            // Montant brut envoyé à Campay pour que l'agence reçoive net_agency_amount
            $table->unsignedInteger('gross_agency_amount')
                ->default(0)
                ->after('net_agency_amount')
                ->comment('Montant Gross-Up envoyé à Campay pour l\'agence');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('gross_seller_amount');
            $table->dropColumn('net_agency_amount');
            $table->dropColumn('gross_agency_amount');
        });
    }
};

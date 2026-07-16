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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('buyer_id')->constrained('users');
            $table->foreignId('shop_id')->constrained('shops');

            // Cycle de vie complexe de la commande
            $table->string('status')->default('PENDING'); // PENDING, PAID, IN_TRANSIT, ARRIVED_DESTINATION, COMPLETED, etc.

            // Financier
            $table->unsignedInteger('total_amount'); // Montant payé total par l'acheteur
            $table->unsignedInteger('shipping_fee')->default(0);
            $table->unsignedInteger('transport_fee_buyer')->default(0); // À payer à l'arrivée si transport exclu
            $table->unsignedInteger('gateway_fees');
            $table->unsignedInteger('protection_fees');
            $table->unsignedInteger('platform_commission');
            $table->unsignedInteger('agency_commission');
            $table->unsignedInteger('net_amount'); // Montant net qui sera versé au vendeur
            $table->jsonb('financial_snapshot'); // Instantané immuable lors du checkout

            // Paiement MoMo
            $table->string('payment_method'); // MTN_MOMO, ORANGE_MONEY, MANUAL
            $table->string('payment_ref')->nullable()->unique()->index();

            // Codes secrets de sécurisation (Preuves physiques)
            $table->string('otp_code', 6)->nullable(); // Reçu par l'acheteur pour retirer le colis
            $table->timestamp('otp_used_at')->nullable();
            $table->string('deposit_code', 8)->nullable()->unique(); // Code de dépôt fourni par le vendeur à l'agence

            // Timers & Logistique
            $table->timestamp('timer_deadline')->nullable(); // Délai de 72h pour validation automatique
            $table->timestamp('shipped_at')->nullable();
            $table->string('shipped_via')->nullable(); // Agence ou Nom/Numéro du livreur local

            // Agences physiques
            $table->foreignId('agency_origin_id')->nullable()->constrained('agencies');
            $table->foreignId('agency_dest_id')->nullable()->constrained('agencies');

            $table->timestamps();

            $table->index('status');
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};


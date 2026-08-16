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
        Schema::create('order_shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->unique()->constrained()->cascadeOnDelete();

            $table->enum('type', ['interurban', 'local']);
            // Ajouter cette colonne dans order_shipments
            $table->foreignId('agency_id')
                ->nullable()
                ->constrained('agencies')
                ->nullOnDelete();
            // Le vendeur choisit l'agence — tous les comptoirs
            // de départ et d'arrivée appartiennent à cette même agence
            $table->boolean('shipping_included');
            $table->unsignedInteger('transport_fee')->default(0);
            $table->boolean('transport_fee_paid')->default(false);
            $table->timestamp('transport_fee_paid_at')->nullable();

            // Agences
            $table->foreignId('origin_counter_id')
                ->nullable()->constrained('agency_counters')->nullOnDelete();
            $table->foreignId('destination_counter_id')
                ->nullable()->constrained('agency_counters')->nullOnDelete();

            // Secrétaires
            $table->foreignId('registered_by')
                ->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('validated_by')
                ->nullable()->constrained('users')->nullOnDelete();

            // Livraison locale
            $table->string('local_carrier_name')->nullable();
            $table->string('local_carrier_phone')->nullable();

            // Destinataire
            $table->string('recipient_name');
            $table->string('recipient_phone');
            $table->string('destination_city');

            // Horodatages
            $table->timestamp('registered_at')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('departed_at')->nullable();
            $table->timestamp('arrived_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_shipments');
    }
};

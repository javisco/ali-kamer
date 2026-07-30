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
        Schema::create('secretary_counters', function (Blueprint $table) {
            $table->id();

            // Un secrétaire peut gérer plusieurs guichets
            // Un guichet peut avoir plusieurs secrétaires
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('agency_counter_id')
                ->constrained()
                ->cascadeOnDelete();

            // Guichet principal du secrétaire
            $table->boolean('is_primary')->default(false);

            $table->timestamps();

            // Un secrétaire ne peut pas être affecté deux fois au même guichet
            $table->unique(['user_id', 'agency_counter_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('secretary_counters');
    }
};

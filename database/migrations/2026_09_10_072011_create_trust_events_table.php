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
        Schema::create('trust_events', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();

    // buyer | seller | platform — le rôle dans lequel l'événement s'est produit
    // Un dispute_lost acheteur ≠ un dispute_lost vendeur
    $table->enum('role_context', ['buyer', 'seller', 'platform']);

    // Type d'événement — liste extensible, stockée en string
    // order_completed, dispute_lost, fraud_confirmed, kyc_approved...
    $table->string('type', 60);

    $table->enum('direction', ['increase', 'decrease']);

    // Points bruts définis par les règles (ex: -10)
    $table->unsignedSmallInteger('points_raw');

    // Points réellement appliqués après pondération maturité
    $table->unsignedSmallInteger('points_applied');

    // Score avant et après pour l'audit
    $table->unsignedTinyInteger('trust_before');
    $table->unsignedTinyInteger('trust_after');

    // Pondération appliquée selon l'âge du compte
    // 2.0 pour compte < 7j, 0.5 pour compte > 1 an
    $table->decimal('weight', 4, 2)->default(1.00);

    // Motif court (affiché dans le dashboard admin)
    $table->string('reason');

    // Description détaillée optionnelle
    $table->text('description')->nullable();

    // Objet à l'origine de l'événement
    // Ex: reference_type='Order', reference_id=458
    $table->string('reference_type', 100)->nullable();
    $table->unsignedBigInteger('reference_id')->nullable();

    // Événement critique = action immédiate indépendamment du score
    // Ex: fraud_confirmed → is_critical=true → BLOCK immédiat
    $table->boolean('is_critical')->default(false);

    // null = système automatique, sinon = admin qui a déclenché
    $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();

    $table->timestamps();

    $table->index(['user_id', 'type']);
    $table->index(['user_id', 'created_at']);
    $table->index('is_critical');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trust_events');
    }
};

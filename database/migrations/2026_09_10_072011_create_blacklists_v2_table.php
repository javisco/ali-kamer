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
       // Nouvelle table enrichie — remplace progressivement l'ancienne table 'blacklist'
// On garde l'ancienne pendant la transition
Schema::create('blacklists_v2', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();

    $table->enum('status', ['active', 'lifted', 'expired'])->default('active');

    // soft = temporaire / hard = long terme / permanent = définitif
    $table->enum('severity', ['soft', 'hard', 'permanent']);

    // Code machine du motif (fraud, kyc_issue, multiple_accounts...)
    $table->string('reason_code', 100);

    // Motif lisible pour l'admin
    $table->string('reason');

    $table->text('description')->nullable();

    // Comment la blacklist a été déclenchée
    $table->enum('trigger_type', ['auto_score', 'auto_critical', 'admin', 'system']);

    $table->timestamp('blocked_at')->useCurrent();

    // null = permanent, sinon date d'expiration
    $table->timestamp('expires_at')->nullable();

    $table->timestamp('lifted_at')->nullable();

    // null = système, sinon admin qui a créé
    $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();

    // Admin qui a levé la blacklist
    $table->foreignId('lifted_by')->nullable()->constrained('users')->nullOnDelete();

    $table->text('lift_reason')->nullable();

    $table->timestamps();

    $table->index(['user_id', 'status']);
    $table->index(['status', 'expires_at']);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blacklists_v2');
    }
};

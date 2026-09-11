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
        Schema::create('account_links', function (Blueprint $table) {
    $table->id();

    // Toujours user_a_id < user_b_id pour éviter les doublons inversés
    $table->foreignId('user_a_id')->constrained('users')->cascadeOnDelete();
    $table->foreignId('user_b_id')->constrained('users')->cascadeOnDelete();

    $table->enum('link_type', [
        'same_cni_hash',     // Force maximale
        'same_phone_momo',   // Force très haute
        'same_device_id',    // Force haute
        'same_email',        // Force moyenne
        'same_ip_burst',     // Force faible — IP commune récente
        'same_phone',        // Force faible
    ]);

    // Degré de certitude 0-100
    $table->tinyInteger('confidence')->unsigned();

    $table->timestamp('detected_at')->useCurrent();

    // Review manuelle admin — jamais automatique
    $table->boolean('reviewed')->default(false);
    $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
    $table->text('review_note')->nullable();
    $table->timestamp('reviewed_at')->nullable();

    $table->timestamps();

    // Un couple (A, B, type) ne peut exister qu'une seule fois
    $table->unique(['user_a_id', 'user_b_id', 'link_type']);

    $table->index('user_a_id');
    $table->index('user_b_id');
    $table->index(['link_type', 'confidence']);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_links');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Messages échangés au cours du litige (acheteur, vendeur, admin, système)
        Schema::create('dispute_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dispute_id')->constrained('disputes')->cascadeOnDelete();
            $table->foreignId('sender_id')->nullable()->constrained('users')->nullOnDelete();

            $table->text('message');
            $table->enum('type', ['MESSAGE', 'SYSTEM', 'ADMIN', 'RESOLUTION'])->default('MESSAGE');

            $table->timestamps();

            $table->index(['dispute_id', 'created_at']);
        });

        // Historique détaillé et traçabilité financière de la résolution d'un litige
        Schema::create('dispute_resolutions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dispute_id')->constrained('disputes')->cascadeOnDelete();
            $table->foreignId('resolved_by')->constrained('users');

            $table->string('decision', 80); // FULL_REFUND, PARTIAL_REFUND, NO_REFUND, REPLACEMENT, RETURN_AND_REFUND, SELLER_PAYS, BUYER_PAYS, SHARED_RESPONSIBILITY, ORDER_CANCELLED
            $table->text('reason');

            $table->unsignedInteger('buyer_amount')->default(0);
            $table->unsignedInteger('seller_amount')->default(0);
            $table->unsignedInteger('platform_amount')->default(0);
            $table->unsignedInteger('refund_amount')->default(0);
            $table->unsignedInteger('seller_compensation')->default(0);
            $table->string('shipping_decision', 100)->nullable();

            $table->timestamp('created_at')->useCurrent();

            $table->index('dispute_id');
        });

        // Catégories d'évaluation détaillée
        Schema::create('rating_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('slug', 100)->unique();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        // Scores détaillés par catégorie rattachés à un avis (review)
        Schema::create('rating_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('review_id')->constrained('reviews')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('rating_categories')->cascadeOnDelete();
            $table->unsignedTinyInteger('score'); // 1 à 5
            $table->timestamp('created_at')->useCurrent();

            $table->unique(['review_id', 'category_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rating_scores');
        Schema::dropIfExists('rating_categories');
        Schema::dropIfExists('dispute_resolutions');
        Schema::dropIfExists('dispute_messages');
    }
};

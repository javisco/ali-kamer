<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Table principale des sanctions (restriction, suspension, ban)
        Schema::create('sanctions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->enum('type', ['restriction', 'suspension', 'ban']);
            $table->enum('status', ['scheduled', 'active', 'expired', 'lifted', 'cancelled'])->default('active');

            $table->string('reason_code', 100);
            $table->text('reason');
            $table->enum('severity', ['soft', 'hard', 'permanent'])->default('hard');

            $table->timestamp('starts_at')->useCurrent();
            $table->timestamp('expires_at')->nullable(); // null pour les bans définitifs

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();

            $table->foreignId('lifted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('lifted_at')->nullable();
            $table->text('lift_reason')->nullable();

            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['type', 'status']);
            $table->index('expires_at');
        });

        // Table spécifique pour les suspensions temporaires
        Schema::create('suspensions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sanction_id')->constrained('sanctions')->cascadeOnDelete();

            $table->timestamp('starts_at')->useCurrent();
            $table->timestamp('expires_at');
            $table->enum('status', ['active', 'expired', 'lifted'])->default('active');

            $table->timestamp('lifted_at')->nullable();
            $table->foreignId('lifted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('lift_reason')->nullable();

            $table->timestamps();

            $table->index(['sanction_id', 'status']);
            $table->index(['status', 'expires_at']);
        });

        // Restrictions ciblées rattachées à une sanction
        Schema::create('sanction_restrictions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sanction_id')->constrained('sanctions')->cascadeOnDelete();
            $table->string('restriction_code', 50); // cannot_buy, cannot_sell, cannot_publish, cannot_withdraw, cannot_create_order, cannot_message
            $table->timestamps();

            $table->index(['sanction_id', 'restriction_code']);
        });

        // Règles d'escalade et de sanction automatique
        Schema::create('sanction_rules', function (Blueprint $table) {
            $table->id();
            $table->string('reason_code', 100)->unique();
            $table->enum('type', ['restriction', 'suspension', 'ban']);
            $table->enum('severity', ['soft', 'hard', 'permanent'])->default('hard');
            $table->unsignedInteger('duration_minutes')->default(1440); // 1440 = 24h
            $table->boolean('automatic')->default(false);
            $table->json('conditions')->nullable();
            $table->boolean('enabled')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sanction_rules');
        Schema::dropIfExists('sanction_restrictions');
        Schema::dropIfExists('suspensions');
        Schema::dropIfExists('sanctions');
    }
};

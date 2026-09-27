<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Journal immuable de traçabilité des décisions
        Schema::create('decision_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('kyc_document_id')->nullable()->constrained('kyc_documents')->nullOnDelete();

            $table->enum('decision', ['ALLOW', 'CHALLENGE', 'REVIEW', 'RESTRICT', 'BLOCK']);
            $table->decimal('risk_score', 5, 2)->nullable();
            $table->decimal('trust_score', 5, 2)->nullable();

            $table->string('reason_code', 100)->nullable();
            $table->json('signals')->nullable();
            $table->string('source', 50)->nullable(); // kyc, didit_webhook, dispute, login, admin, review

            $table->timestamp('created_at')->useCurrent();

            $table->index(['user_id', 'created_at']);
            $table->index('decision');
        });

        // File de vérification humaine pour l'administration
        Schema::create('admin_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('kyc_document_id')->nullable()->constrained('kyc_documents')->nullOnDelete();

            $table->enum('status', ['pending', 'in_review', 'approved', 'rejected', 'closed'])->default('pending');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');

            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->string('decision', 50)->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('reviewed_at')->nullable();

            $table->timestamps();

            $table->index(['status', 'priority']);
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_reviews');
        Schema::dropIfExists('decision_logs');
    }
};

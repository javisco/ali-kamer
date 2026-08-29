<?php

use App\Models\Agency;
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

        Schema::create('users', function (Blueprint $table) {
            $table->id();

            // ── Identité ──────────────────────────────────────────────
            $table->string('name');
            $table->string('email')->unique()->nullable();
            $table->string('phone', 20)->nullable();              // identifiant principal
            $table->string('password');
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->foreignId('agency_id')->nullable();
            // ── Rôle & Statut ─────────────────────────────────────────
            $table->enum('role', ['buyer', 'seller', 'secretary', 'admin'])->default('buyer');
            $table->enum('status', ['candidate', 'active', 'suspended', 'banned'])->default('active');
            // Note : un vendeur commence en status=candidate jusqu'à validation KYC
            // Tous les autres rôles commencent en status=active

            // ── Mobile Money ──────────────────────────────────────────
            $table->string('phone_momo', 20)->unique();
            $table->enum('momo_operator', ['mtn', 'orange'])->nullable();

            // ── Wallet ────────────────────────────────────────────────
            // Ne jamais écrire directement ici — toujours via WalletService
            $table->unsignedBigInteger('wallet_pending')->default(0);
            $table->unsignedBigInteger('wallet_available')->default(0);

            // ── Score acheteur ────────────────────────────────────────
            $table->unsignedTinyInteger('trust_score')->default(100);
            $table->unsignedSmallInteger('dispute_count')->default(0);
            $table->unsignedSmallInteger('abuse_count')->default(0);
            $table->boolean('prepayment_required')->default(false);
            $table->boolean('purchase_restricted')->default(false);

            // ── Divers ────────────────────────────────────────────────
            $table->string('referral_code', 10)->unique()->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            $table->index('role');
            $table->index('status');
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};

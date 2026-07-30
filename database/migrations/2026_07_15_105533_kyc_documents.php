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
        Schema::create('kyc_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('cni_front_url'); // URL Cloudflare R2 privée
            $table->string('cni_back_url');
            $table->string('selfie_url');
            $table->string('rccm_url')->nullable(); //document du vendeur qui certifie qu'il est un vendeur
            $table->boolean('momo_name_check')->default(false); // Concordance nom MoMo et CNI
            $table->enum('status', ['pending','approved', 'rejected'])->default('pending');
            $table->foreignId('reviewer_id')->nullable()->constrained('users')->onDelete('set null');
            $table->text('rejection_reason')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kyc_documents');
    }
};

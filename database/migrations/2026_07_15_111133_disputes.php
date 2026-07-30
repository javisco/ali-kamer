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
        Schema::create('disputes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained();
            $table->foreignId('initiator_id')->constrained('users'); // Celui qui ouvre le litige
            $table->string('type'); // non_received, non_conforming, damaged, etc.
            $table->text('description');
            $table->jsonb('evidence'); // Liens d'images/preuves sur Cloudflare R2
            $table->enum('status', ['opened', 'under_investigation', 'resolved_buyer', 'resolved_seller', 'resolved_split', 'closed'])->default('opened');
            $table->text('resolution')->nullable();
            $table->unsignedInteger('resolution_amount')->default(0); // Montant remboursé si split/remboursement
            $table->foreignId('resolver_id')->nullable()->constrained('users'); // Admin ayant arbitré
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('disputes');
    }
};

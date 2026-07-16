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
        Schema::create('blacklist', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('cni_hash', 64)->unique()->index(); // SHA-256 hash
            $table->string('phone_momo')->nullable()->index();
            $table->string('phone_number')->nullable()->index();
            $table->string('ip_address')->nullable();
            $table->text('reason');
            $table->foreignId('created_by')->constrained('users'); // Admin ayant banni
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blacklist');
    }
};

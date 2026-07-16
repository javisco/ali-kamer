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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('channel'); // whatsapp, sms, push, email
            $table->string('type'); // order_placed, otp_sent, kyc_approved...
            $table->string('title');
            $table->text('body');
            $table->jsonb('data')->nullable(); // Variables contextuelles
            $table->timestamp('sent_at')->nullable();
            $table->string('delivery_status')->default('queued'); // queued, sent, failed
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};

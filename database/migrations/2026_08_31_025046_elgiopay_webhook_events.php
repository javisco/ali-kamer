<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('elgiopay_webhook_events', function (Blueprint $table) {
            $table->id();

            // id renvoyé par Elgiopay (evt_...), unique => garde anti-doublon en BDD
            $table->string('event_id')->unique();
            $table->string('event_type');
            $table->timestamp('processed_at');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('elgiopay_webhook_events');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->timestamp('late_cancel_offered_at')->nullable()->after('cancellation_reason');
            $table->string('late_cancel_offer_kind', 30)->nullable()->after('late_cancel_offered_at');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['late_cancel_offered_at', 'late_cancel_offer_kind']);
        });
    }
};

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

        Schema::table('orders', function (Blueprint $table) {
            $table->string('seller_otp_code', 6)->nullable()->after('deposit_code');
            $table->timestamp('seller_otp_expires_at')->nullable();
            $table->timestamp('seller_otp_used_at')->nullable();

            $table->string('buyer_otp_code', 6)->nullable();
            $table->timestamp('buyer_otp_expires_at')->nullable();
            $table->timestamp('buyer_otp_used_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('seller_otp_code');
            $table->dropColumn('seller_otp_expires_at');
            $table->dropColumn('seller_otp_used_at');
            $table->dropColumn('buyer_opt_code');
            $table->dropColumn('buyer_opt_expires_at');
            $table->dropColumn('buyer_opt_used_at');
        });
    }
};

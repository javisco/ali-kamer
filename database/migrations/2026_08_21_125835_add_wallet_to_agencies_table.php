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
        Schema::table('agencies', function (Blueprint $table) {
            // Solde disponible de l'agence (gains des commissions 1%)
            $table->unsignedBigInteger('wallet_available')->default(0)->after('is_active');
            $table->unsignedBigInteger('wallet_pending')->default(0)->after('is_active');
            // Numéro MoMo de l'agence pour les retraits
            $table->string('phone_momo')->nullable()->after('wallet_available');
            $table->enum('momo_operator', ['mtn', 'orange'])->nullable()->after('phone_momo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agencies', function (Blueprint $table) {
            $table->dropColumn('phono_momo');
            $table->dropColumn('wallet_available');
            $table->dropColumn('momo_operator');
        });
    }
};

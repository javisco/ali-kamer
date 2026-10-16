<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('platform_settings', function (Blueprint $table) {
            $table->text('value')->change();
            $table->boolean('is_active')->default(true)->after('sort_order');
            $table->string('unit')->nullable()->after('is_active');
            $table->string('min_value')->nullable()->after('unit');
            $table->string('max_value')->nullable()->after('min_value');
            $table->string('applies_to')->default('new_and_in_progress')->after('max_value');
            $table->boolean('is_sensitive')->default(false)->after('applies_to');
            $table->string('default_value')->nullable()->after('is_sensitive');
            $table->foreignId('updated_by')->nullable()->after('default_value')->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('platform_settings', function (Blueprint $table) {
            $table->dropConstrainedForeignId('updated_by');
            $table->dropColumn([
                'is_active',
                'unit',
                'min_value',
                'max_value',
                'applies_to',
                'is_sensitive',
                'default_value',
            ]);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('platform_setting_changes', function (Blueprint $table) {
            $table->id();
            $table->string('key');
            $table->text('old_value')->nullable();
            $table->text('new_value')->nullable();
            $table->foreignId('admin_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('reason')->nullable();
            $table->string('ip_address')->nullable();
            $table->timestamps();

            $table->index('key');
            $table->index('admin_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('platform_setting_changes');
    }
};

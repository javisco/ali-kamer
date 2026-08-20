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
        Schema::create('product_attribute_values', function (Blueprint $table) {
            $table->id();

            // L'attribut auquel appartient cette valeur
            $table->foreignId('product_attribute_id')
                ->constrained()
                ->cascadeOnDelete();

            // Valeur de l'attribut
            // Ex: "Core i3", "Core i5", "8GB", "Rouge"
            $table->string('value');

            $table->unsignedSmallInteger('sort_order')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_attribute_values');
    }
};

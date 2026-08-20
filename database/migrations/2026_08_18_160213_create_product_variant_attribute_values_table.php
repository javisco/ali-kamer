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
        // Table pivot : une variante = plusieurs valeurs d'attributs
        // Ex: variante "Core i5 + 8GB" → lié à deux product_attribute_values
        Schema::create('product_variant_attribute_values', function (Blueprint $table) {
            $table->id();

            // Nommer explicitement les foreign keys pour éviter le dépassement 64 chars MySQL
            $table->unsignedBigInteger('product_variant_id');
            $table->unsignedBigInteger('product_attribute_value_id');

            $table->foreign('product_variant_id', 'pvav_variant_fk')
                ->references('id')
                ->on('product_variants')
                ->cascadeOnDelete();

            $table->foreign('product_attribute_value_id', 'pvav_attr_value_fk')
                ->references('id')
                ->on('product_attribute_values')
                ->cascadeOnDelete();

            $table->unique(
                ['product_variant_id', 'product_attribute_value_id'],
                'pvav_unique'
            );

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variant_attribute_values');
    }
};

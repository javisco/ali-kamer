<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            if (! Schema::hasColumn('order_items', 'variant_label')) {
                $table->string('variant_label')->nullable()->after('product_title');
            }
            if (! Schema::hasColumn('order_items', 'variant_snapshot')) {
                $table->json('variant_snapshot')->nullable()->after('variant_label');
            }
        });

        Schema::table('product_attribute_values', function (Blueprint $table) {
            if (! Schema::hasColumn('product_attribute_values', 'image_path')) {
                $table->string('image_path')->nullable()->after('value');
            }
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            if (Schema::hasColumn('order_items', 'variant_snapshot')) {
                $table->dropColumn('variant_snapshot');
            }
            if (Schema::hasColumn('order_items', 'variant_label')) {
                $table->dropColumn('variant_label');
            }
        });

        Schema::table('product_attribute_values', function (Blueprint $table) {
            if (Schema::hasColumn('product_attribute_values', 'image_path')) {
                $table->dropColumn('image_path');
            }
        });
    }
};

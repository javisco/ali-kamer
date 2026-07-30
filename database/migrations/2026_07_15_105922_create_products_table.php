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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->restrictOnDelete();
            $table->string('title');
            $table->text('description');
            $table->string('city');
            $table->unsignedInteger('price');
            $table->unsignedInteger('old_price')->nullable();
            $table->unsignedInteger('stock')->default(0);
            $table->unsignedInteger('stock_reserved')->default(0);
            $table->unsignedSmallInteger('min_quantity')->default(1);
            $table->boolean('shipping_included')->default(false);
            $table->unsignedSmallInteger('shipping_threshold_qty')->nullable();
            $table->json('specifications')->nullable();
            $table->enum('status', ['hidden', 'visible', 'sold_out', 'banned'])
                ->default('hidden');
            $table->unsignedInteger('views_count')->default(0);
            $table->unsignedInteger('orders_count')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'city']);
            $table->index(['shop_id', 'status']);
            $table->index(['category_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

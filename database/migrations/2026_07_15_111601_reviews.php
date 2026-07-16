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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('shop_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_id')->constrained()->onDelete('cascade'); // Preuve d'achat requis !
            $table->foreignId('reviewer_id')->constrained('users');
            $table->enum('reviewee_type', ['shop', 'buyer']); // L'acheteur note la boutique ou le vendeur note la fiabilité de l'acheteur
            $table->tinyInteger('rating'); // 1 à 5
            $table->text('body')->nullable();
            $table->boolean('verified')->default(true);
            $table->boolean('flagged')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};

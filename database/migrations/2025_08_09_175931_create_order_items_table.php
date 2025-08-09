<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id('order_items_id');
            $table->unsignedBigInteger('orders_id');
            $table->unsignedBigInteger('dishes_id');

            $table->string('size', 50)->default('normal');
            $table->decimal('size_multiplier', 4, 2)->default(1.00);
            $table->integer('quantity')->default(1);

            $table->json('extra_ingredients')->nullable();
            $table->json('excluded_ingredients')->nullable();

            $table->decimal('ingredient_price', 8, 2)->default(0.00);
            $table->decimal('exclusion_discount', 8, 2)->default(0.00);
            $table->decimal('final_unit_price', 8, 2)->default(0.00);
            $table->decimal('tax_amount', 8, 2)->default(0.00);
            $table->decimal('subtotal', 10, 2)->default(0.00);

            $table->timestamps();

            $table->foreign('orders_id')->references('orders_id')->on('orders')->onDelete('cascade');
            $table->foreign('dishes_id')->references('dishes_id')->on('dishes')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};

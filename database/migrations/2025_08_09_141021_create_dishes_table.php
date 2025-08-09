<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('dishes', function (Blueprint $table) {
            $table->id('dishes_id');
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->string('image', 255)->nullable();
            $table->string('category', 50)->nullable();
            $table->string('type', 50)->nullable();
            $table->integer('calories')->nullable();
            $table->boolean('vegetarian')->default(false);
            $table->decimal('gross_price', 8, 2);
            $table->decimal('tax_percent', 5, 2)->default(27.00);
            $table->decimal('net_price', 8, 2)->storedAs('gross_price / (1 + tax_percent / 100)');
            $table->json('size_options')->nullable();
            $table->json('ingredient_modifiers')->nullable();
            $table->unsignedInteger('stock')->default(0);
            $table->text('allergens')->nullable();
            $table->text('base_ingredients')->nullable();
            $table->text('extra_ingredients')->nullable();
            $table->boolean('on_sale')->default(false);
            $table->decimal('discount_percent', 5, 2)->default(0.00);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dishes');
    }
};

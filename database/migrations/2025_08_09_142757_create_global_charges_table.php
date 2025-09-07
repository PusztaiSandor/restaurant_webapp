<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('global_charges', function (Blueprint $table) {
            $table->id('global_charges_id');
            $table->enum('charge_type', ['delivery_fee', 'service_fee', 'order_discount', 'cutlery']);
            $table->enum('delivery_method', ['delivery', 'dine-in', 'pickup']);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_percentage')->default(true);
            $table->decimal('value', 6, 2);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('global_charges');
    }
};
